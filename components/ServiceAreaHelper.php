<?php

namespace app\components;

use Yii;

class ServiceAreaHelper
{
    /**
     * Get all supported service zones, cities, counties, and airports.
     *
     * @return array
     */
    public static function getAllowedAreas()
    {
        return [
            'florida' => [
                'name' => 'Florida / South Florida',
                'states' => ['FL', 'Florida'],
                'cities' => [
                    'Key Largo',
                    'Homestead',
                    'Kendall',
                    'Doral',
                    'Pinecrest',
                    'Coconut Grove',
                    'Coral Gables',
                    'Miami',
                    'Miami Beach',
                    'North Miami',
                    'South Miami',
                    'Sunny Isles',
                    'Sunny Isles Beach',
                    'Hallandale',
                    'Hallandale Beach',
                    'Hollywood',
                    'Fort Lauderdale',
                    'Ft Lauderdale',
                    'Ft. Lauderdale',
                    'Davie',
                    'Davie Beach',
                    'Dania Beach',
                    'Jupiter',
                    'Jupiter Island',
                    'South Ranches',
                    'Southwest Ranches',
                    'Boca Raton',
                    'Boynton Beach',
                    'Boyton Beach',
                    'West Palm Beach',
                    'Palm Beach',
                    'North Palm Beach',
                    'Palm Beach Gardens',
                    'Port Saint Lucie',
                    'Port St Lucie',
                    'Port St. Lucie',
                    'Vero Beach',
                    'Orlando',
                    'Kissimmee',
                    'Tampa',
                    'St. Petersburg',
                    'Clearwater',
                    'Naples',
                    'Marco Island',
                    'Fort Myers',
                    'Ft Myers',
                    'Ft. Myers',
                    'Cape Coral',
                    'Punta Gorda',
                    'Port Charlotte',
                    'Aventura',
                    'Bal Harbour',
                    'Bay Harbor Islands',
                    'Surfside',
                    'Brickell',
                    'Fisher Island',
                    'Hialeah',
                    'Miramar',
                    'Pembroke Pines',
                    'Plantation',
                    'Sunrise',
                    'Coral Springs',
                    'Pompano Beach',
                    'Deerfield Beach',
                    'Delray Beach',
                    'Wellington',
                ],
                'airports' => [
                    ['code' => 'MIA', 'name' => 'Miami International Airport', 'keywords' => ['MIA', 'Miami International Airport', 'Miami Airport']],
                    ['code' => 'FLL', 'name' => 'Fort Lauderdale–Hollywood International Airport', 'keywords' => ['FLL', 'Fort Lauderdale Airport', 'Fort Lauderdale Hollywood', 'Fort Lauderdale–Hollywood', 'Fort Lauderdale-Hollywood']],
                    ['code' => 'PBI', 'name' => 'Palm Beach International Airport', 'keywords' => ['PBI', 'Palm Beach International Airport', 'Palm Beach Airport']],
                    ['code' => 'MCO', 'name' => 'Orlando International Airport', 'keywords' => ['MCO', 'Orlando International Airport', 'Orlando Airport']],
                    ['code' => 'TPA', 'name' => 'Tampa International Airport', 'keywords' => ['TPA', 'Tampa International Airport', 'Tampa Airport']],
                    ['code' => 'RSW', 'name' => 'Southwest Florida International Airport (Fort Myers)', 'keywords' => ['RSW', 'Southwest Florida International Airport', 'Fort Myers Airport']],
                    ['code' => 'OPF', 'name' => 'Miami-Opa Locka Executive Airport', 'keywords' => ['OPF', 'Opa-locka', 'Opa Locka']],
                    ['code' => 'FXE', 'name' => 'Fort Lauderdale Executive Airport', 'keywords' => ['FXE', 'Fort Lauderdale Executive']],
                    ['code' => 'BCT', 'name' => 'Boca Raton Airport', 'keywords' => ['BCT', 'Boca Raton Airport']],
                ],
            ],
            'ny_nj' => [
                'name' => 'New York & New Jersey Metro',
                'states' => ['NY', 'New York', 'NJ', 'New Jersey'],
                'cities' => [
                    'Morristown',
                    'Newark',
                    'Teterboro',
                    'Manhattan',
                    'New York City',
                    'NYC',
                    'Bronx',
                    'The Bronx',
                    'Queens',
                    'Staten Island',
                    'Brooklyn',
                    'Long Island',
                    'Nassau',
                    'Suffolk',
                    'The Hamptons',
                    'Hamptons',
                    'East Hampton',
                    'Southampton',
                    'Montauk',
                    'White Plains',
                    'Westchester',
                    'Jersey City',
                    'Hoboken',
                    'Weehawken',
                    'Secaucus',
                    'Fort Lee',
                    'Englewood',
                    'Paramus',
                    'Hackensack',
                ],
                'airports' => [
                    ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport', 'keywords' => ['JFK', 'John F. Kennedy', 'John F Kennedy', 'Kennedy Airport', 'JFK Airport']],
                    ['code' => 'EWR', 'name' => 'Newark Liberty International Airport', 'keywords' => ['EWR', 'Newark Airport', 'Newark Liberty', 'Newark Liberty International Airport', 'EWR Airport']],
                    ['code' => 'LGA', 'name' => 'LaGuardia Airport', 'keywords' => ['LGA', 'LaGuardia', 'La Guardia', 'LaGuardia Airport', 'LGA Airport']],
                    ['code' => 'TEB', 'name' => 'Teterboro Executive Airport', 'keywords' => ['TEB', 'Teterboro Executive Airport', 'Teterboro Airport', 'TEB Airport']],
                    ['code' => 'HPN', 'name' => 'Westchester County Airport (White Plains)', 'keywords' => ['HPN', 'White Plains Airport', 'Westchester County Airport', 'HPN Airport']],
                    ['code' => 'ISP', 'name' => 'Long Island MacArthur Airport', 'keywords' => ['ISP', 'MacArthur Airport', 'Long Island MacArthur']],
                ],
            ],
        ];
    }

    /**
     * Convert location payload (string or array) into a searchable string.
     *
     * @param mixed $location
     * @return string
     */
    public static function extractLocationString($location)
    {
        if (empty($location)) {
            return '';
        }

        if (is_string($location)) {
            return trim($location);
        }

        if (is_array($location)) {
            $parts = [];
            foreach (['formatted_address', 'address', 'name', 'city', 'state', 'airportCode', 'code'] as $key) {
                if (!empty($location[$key]) && is_string($location[$key])) {
                    $parts[] = trim($location[$key]);
                }
            }
            if (!empty($parts)) {
                return implode(', ', array_unique($parts));
            }
            return json_encode($location);
        }

        return (string) $location;
    }

    /**
     * Check if a location string falls within our allowed service areas.
     *
     * @param mixed $location
     * @return array
     */
    public static function validateLocation($location)
    {
        $rawText = self::extractLocationString($location);
        if ($rawText === '') {
            return [
                'valid' => false,
                'region' => null,
                'region_key' => null,
                'matched' => null,
                'location' => '',
                'message' => 'Location is empty.',
            ];
        }

        $zones = self::getAllowedAreas();

        // 1. Check for specific airport codes and airport names first
        foreach ($zones as $zoneKey => $zone) {
            foreach ($zone['airports'] as $airport) {
                foreach ($airport['keywords'] as $kw) {
                    $pattern = '/\b' . preg_quote($kw, '/') . '\b/i';
                    if (preg_match($pattern, $rawText)) {
                        return [
                            'valid' => true,
                            'region' => $zone['name'],
                            'region_key' => $zoneKey,
                            'matched' => $airport['name'] . ' (' . $airport['code'] . ')',
                            'type' => 'airport',
                            'location' => $rawText,
                            'message' => 'Location is within our service area.',
                        ];
                    }
                }
            }
        }

        // 2. Check for city / area names (strictly from the allowed list)
        foreach ($zones as $zoneKey => $zone) {
            foreach ($zone['cities'] as $city) {
                $pattern = '/\b' . preg_quote($city, '/') . '\b/i';
                if (preg_match($pattern, $rawText)) {
                    return [
                        'valid' => true,
                        'region' => $zone['name'],
                        'region_key' => $zoneKey,
                        'matched' => $city,
                        'type' => 'city',
                        'location' => $rawText,
                        'message' => 'Location is within our service area.',
                    ];
                }
            }
        }

        return [
            'valid' => false,
            'region' => null,
            'region_key' => null,
            'matched' => null,
            'type' => null,
            'location' => $rawText,
            'message' => 'Location is outside our supported service areas.',
        ];
    }

    /**
     * Validates both pickup and dropoff under Option B (Strict):
     * Both Pickup and Dropoff must be inside allowed service areas.
     * Also checks that the trip stays within the same region (Florida or NY/NJ).
     *
     * @param mixed $pickup
     * @param mixed $dropoff
     * @return array
     */
    public static function validateTrip($pickup, $dropoff)
    {
        $pickupStr = self::extractLocationString($pickup);
        $dropoffStr = self::extractLocationString($dropoff);

        if (empty($pickupStr)) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Pickup location is required.'),
                'data' => null,
            ];
        }

        if (empty($dropoffStr)) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Dropoff location is required.'),
                'data' => null,
            ];
        }

        $pickupResult = self::validateLocation($pickup);
        $dropoffResult = self::validateLocation($dropoff);

        if (!$pickupResult['valid']) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Pickup location "{location}" is outside our service area. We currently serve South & Central Florida and the New York / New Jersey Metro area.', [
                    'location' => $pickupStr,
                ]),
                'data' => [
                    'pickup' => $pickupResult,
                    'dropoff' => $dropoffResult,
                ],
            ];
        }

        if (!$dropoffResult['valid']) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Dropoff location "{location}" is outside our service area. We currently serve South & Central Florida and the New York / New Jersey Metro area.', [
                    'location' => $dropoffStr,
                ]),
                'data' => [
                    'pickup' => $pickupResult,
                    'dropoff' => $dropoffResult,
                ],
            ];
        }

        // Both locations are in allowed areas. Check if they belong to the same service zone
        if ($pickupResult['region_key'] !== $dropoffResult['region_key']) {
            return [
                'valid' => false,
                'message' => Yii::t('app', 'Interstate trips between {from} and {to} are not available for online booking. Please contact us directly for long-distance charter reservations.', [
                    'from' => $pickupResult['region'],
                    'to' => $dropoffResult['region'],
                ]),
                'data' => [
                    'pickup' => $pickupResult,
                    'dropoff' => $dropoffResult,
                ],
            ];
        }

        return [
            'valid' => true,
            'message' => Yii::t('app', 'Locations are valid and within our service area.'),
            'data' => [
                'region' => $pickupResult['region'],
                'region_key' => $pickupResult['region_key'],
                'pickup' => $pickupResult,
                'dropoff' => $dropoffResult,
            ],
        ];
    }
}
