<?php

namespace app\components;

use Yii;

class GoogleMapsHelper
{
    const DISTANCE_MATRIX_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    const DIRECTIONS_URL      = 'https://maps.googleapis.com/maps/api/directions/json';

    /**
     * Retrieve the Google Maps API key from config params, environment, or getenv.
     *
     * @return string
     */
    public static function getApiKey()
    {
        $key = Yii::$app->params['google_maps_api_key'] ?? '';
        if (!empty($key)) {
            return trim($key);
        }

        $candidates = [
            $_ENV['GOOGLE_MAPS_API_KEY'] ?? null,
            $_ENV['GOOGLE_API_KEY'] ?? null,
            $_SERVER['GOOGLE_MAPS_API_KEY'] ?? null,
            $_SERVER['GOOGLE_API_KEY'] ?? null,
            getenv('GOOGLE_MAPS_API_KEY'),
            getenv('GOOGLE_API_KEY'),
        ];

        foreach ($candidates as $candidate) {
            if (!empty($candidate)) {
                return trim($candidate);
            }
        }

        return '';
    }

    /**
     * Check if a Google Maps API key is configured.
     *
     * @return bool
     */
    public static function hasApiKey()
    {
        return self::getApiKey() !== '';
    }

    /**
     * Calculate driving distance in miles and estimated duration between pickup and dropoff.
     *
     * @param mixed $origin Pickup location (string or array)
     * @param mixed $destination Dropoff location (string or array)
     * @return array
     */
    public static function calculateDistance($origin, $destination)
    {
        $originStr = ServiceAreaHelper::extractLocationString($origin);
        $destStr   = ServiceAreaHelper::extractLocationString($destination);

        if (empty($originStr) || empty($destStr)) {
            return [
                'success' => false,
                'miles'   => null,
                'message' => Yii::t('app', 'Both pickup and dropoff locations are required to calculate distance.'),
            ];
        }

        $apiKey = self::getApiKey();
        if (empty($apiKey)) {
            return [
                'success' => false,
                'miles'   => null,
                'message' => Yii::t('app', 'Google Maps API key is not configured in GOOGLE_MAPS_API_KEY.'),
            ];
        }

        // Cache lookup to minimize external API costs and latency
        $cacheKey = 'gmap_dist_' . md5(strtolower(trim($originStr)) . '->' . strtolower(trim($destStr)));
        if (Yii::$app->has('cache') && Yii::$app->cache !== null) {
            $cached = Yii::$app->cache->get($cacheKey);
            if ($cached !== false && is_array($cached) && !empty($cached['success'])) {
                return $cached;
            }
        }

        // 1. Try Distance Matrix API
        $matrixResult = self::queryDistanceMatrix($originStr, $destStr, $apiKey);
        if ($matrixResult['success']) {
            if (Yii::$app->has('cache') && Yii::$app->cache !== null) {
                Yii::$app->cache->set($cacheKey, $matrixResult, 86400); // cache for 24h
            }
            return $matrixResult;
        }

        // 2. Fallback to Directions API if Distance Matrix didn't resolve
        $directionsResult = self::queryDirections($originStr, $destStr, $apiKey);
        if ($directionsResult['success']) {
            if (Yii::$app->has('cache') && Yii::$app->cache !== null) {
                Yii::$app->cache->set($cacheKey, $directionsResult, 86400);
            }
            return $directionsResult;
        }

        return [
            'success' => false,
            'miles'   => null,
            'message' => $matrixResult['message'] ?? ($directionsResult['message'] ?? Yii::t('app', 'Could not calculate driving distance.')),
            'debug'   => [
                'matrix_error'     => $matrixResult['message'] ?? null,
                'directions_error' => $directionsResult['message'] ?? null,
            ],
        ];
    }

    /**
     * Query Google Distance Matrix API
     */
    private static function queryDistanceMatrix($origin, $destination, $apiKey)
    {
        $params = [
            'origins'      => $origin,
            'destinations' => $destination,
            'units'        => 'imperial',
            'key'          => $apiKey,
        ];

        $url = self::DISTANCE_MATRIX_URL . '?' . http_build_query($params);
        $response = self::executeGet($url);

        if (!$response['success']) {
            return [
                'success' => false,
                'miles'   => null,
                'message' => $response['message'],
            ];
        }

        $data = $response['data'];
        $status = $data['status'] ?? 'UNKNOWN';

        if ($status !== 'OK') {
            $err = $data['error_message'] ?? "Google Distance Matrix returned status: {$status}";
            return [
                'success' => false,
                'miles'   => null,
                'message' => $err,
            ];
        }

        $row = $data['rows'][0] ?? null;
        $element = $row['elements'][0] ?? null;

        if (!$element || ($element['status'] ?? '') !== 'OK') {
            $elemStatus = $element['status'] ?? 'NO_ELEMENT';
            return [
                'success' => false,
                'miles'   => null,
                'message' => "Google Distance Matrix element status: {$elemStatus}",
            ];
        }

        $distanceMeters  = (int) ($element['distance']['value'] ?? 0);
        $durationSeconds = (int) ($element['duration']['value'] ?? 0);

        // Convert meters to miles (1 meter = 0.000621371 miles)
        $miles = round($distanceMeters * 0.000621371, 2);
        $durationMinutes = (int) round($durationSeconds / 60);
        $durationHours   = round($durationMinutes / 60, 2);

        return [
            'success'             => true,
            'source'              => 'google_distance_matrix',
            'miles'               => $miles,
            'distance_meters'     => $distanceMeters,
            'distance_text'       => $element['distance']['text'] ?? ($miles . ' mi'),
            'duration_seconds'    => $durationSeconds,
            'duration_minutes'    => $durationMinutes,
            'duration_hours'      => $durationHours,
            'duration_text'       => $element['duration']['text'] ?? ($durationMinutes . ' mins'),
            'origin_address'      => $data['origin_addresses'][0] ?? $origin,
            'destination_address' => $data['destination_addresses'][0] ?? $destination,
        ];
    }

    /**
     * Query Google Directions API (fallback)
     */
    private static function queryDirections($origin, $destination, $apiKey)
    {
        $params = [
            'origin'      => $origin,
            'destination' => $destination,
            'units'       => 'imperial',
            'key'         => $apiKey,
        ];

        $url = self::DIRECTIONS_URL . '?' . http_build_query($params);
        $response = self::executeGet($url);

        if (!$response['success']) {
            return [
                'success' => false,
                'miles'   => null,
                'message' => $response['message'],
            ];
        }

        $data = $response['data'];
        $status = $data['status'] ?? 'UNKNOWN';

        if ($status !== 'OK' || empty($data['routes'][0]['legs'][0])) {
            $err = $data['error_message'] ?? "Google Directions returned status: {$status}";
            return [
                'success' => false,
                'miles'   => null,
                'message' => $err,
            ];
        }

        $leg = $data['routes'][0]['legs'][0];
        $distanceMeters  = (int) ($leg['distance']['value'] ?? 0);
        $durationSeconds = (int) ($leg['duration']['value'] ?? 0);

        $miles = round($distanceMeters * 0.000621371, 2);
        $durationMinutes = (int) round($durationSeconds / 60);
        $durationHours   = round($durationMinutes / 60, 2);
        $polyline        = $data['routes'][0]['overview_polyline']['points'] ?? null;

        return [
            'success'             => true,
            'source'              => 'google_directions',
            'miles'               => $miles,
            'distance_meters'     => $distanceMeters,
            'distance_text'       => $leg['distance']['text'] ?? ($miles . ' mi'),
            'duration_seconds'    => $durationSeconds,
            'duration_minutes'    => $durationMinutes,
            'duration_hours'      => $durationHours,
            'duration_text'       => $leg['duration']['text'] ?? ($durationMinutes . ' mins'),
            'origin_address'      => $leg['start_address'] ?? $origin,
            'destination_address' => $leg['end_address'] ?? $destination,
            'polyline'            => $polyline,
        ];
    }

    /**
     * HTTP GET request helper using curl
     */
    private static function executeGet($url)
    {
        if (!function_exists('curl_init')) {
            $raw = @file_get_contents($url);
            if ($raw === false) {
                return ['success' => false, 'message' => 'file_get_contents failed to fetch Google API'];
            }
            $json = json_decode($raw, true);
            return is_array($json)
                ? ['success' => true, 'data' => $json]
                : ['success' => false, 'message' => 'Invalid JSON from Google API'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || !empty($error)) {
            return [
                'success' => false,
                'message' => "cURL error communicating with Google Maps: {$error}",
            ];
        }

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'message' => "Google Maps HTTP response code: {$httpCode}",
            ];
        }

        $json = json_decode($response, true);
        if (!is_array($json)) {
            return [
                'success' => false,
                'message' => 'Invalid JSON response from Google Maps API',
            ];
        }

        return [
            'success' => true,
            'data'    => $json,
        ];
    }
}
