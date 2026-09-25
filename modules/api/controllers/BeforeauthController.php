<?php

namespace app\modules\api\controllers;

use Yii;
use yii\db\Query;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\base\ErrorException;
//use app\models\EntryForm;

use app\models\Clientsay;
use app\models\Newsletter;
use app\models\Event;
use app\models\Generalsetting;
use app\models\Contactus;
use app\models\Appuser;
use app\models\Appuserdevicesinfo;
use app\models\Aboutus;
use app\models\Advertisement;
use app\models\Tradepropartner;
use app\models\Fleet;
use app\models\Booking;
use app\components\ServiceAreaHelper;
use app\components\GoogleMapsHelper;

use app\models\Importcsv;
use Aws\S3\S3Client;
use yii\filters\Cors;
use Stripe\Stripe;
use Stripe\PaymentIntent;
//use yii2tech\filestorage\storage\Storage;
$allowed_origins = [
  'http://localhost:3000',
  'http://localhost:3001',
  'http://localhost:3002',
  'http://localhost:3003',
  'http://localhost:5173',
  'http://localhost:8080',
  'http://127.0.0.1:3000',
  'http://127.0.0.1:3001',
  'http://127.0.0.1:5173',
  'https://texiweb.netlify.app',
  'https://www.luxurylayers.pro/',
  'https://www.luxurylayers.pro',
  'https://sunriseblackcar.com',
  'https://www.sunriseblackcar.com',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$isAllowed = false;
if ($origin && (in_array($origin, $allowed_origins, true) || preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#i', $origin))) {
  $isAllowed = true;
}

if ($isAllowed) {
  header("Access-Control-Allow-Origin: " . $origin);
  header("Access-Control-Allow-Credentials: true");
} else {
  header("Access-Control-Allow-Origin: *");
}
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
$reqHeaders = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] ?? '';
$allowedHeaders = 'Content-Type, Authorization, auth_key, auth-key, Auth-Key, authkey, X-Requested-With, X-CSRF-Token, X-Idempotency-Key, Accept, Origin';
if (!empty($reqHeaders)) {
  $allowedHeaders .= ', ' . $reqHeaders;
}
header("Access-Control-Allow-Headers: " . $allowedHeaders);
header("Access-Control-Max-Age: 86400");

if (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS') {
  http_response_code(200);
  exit;
}

//require __DIR__ . '../../components/class-phpass.php';

class BeforeauthController extends Controller
{
  public $stripe_secret_key;
  public $stripe_customer_id;
  public $PAYMENT_STRIPE_MODE = 'live';
  public $PAYMENT_STRIPE_LIVE_SECRET_KEY = '';
  public $PAYMENT_STRIPE_TEST_SECRET_KEY = '';
  public function actionCreatebookingpaymentintent()
  {
    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decodedBody = json_decode($rawBody, true);
      $payload = is_array($decodedBody) ? $decodedBody : $_REQUEST;
    }

    $ride = isset($payload['ride']) && is_array($payload['ride']) ? $payload['ride'] : [];
    $passenger = isset($payload['passenger']) && is_array($payload['passenger']) ? $payload['passenger'] : [];
    $quote = isset($payload['quote']) && is_array($payload['quote']) ? $payload['quote'] : [];
    $fleetId = (int) ($payload['vehicleId'] ?? 0);
    $pickupDate = trim((string) ($ride['date'] ?? ''));
    $pickupTime = trim((string) ($ride['time'] ?? ''));

    if (!$fleetId || !$pickupDate || !$pickupTime || empty($passenger['name']) || empty($passenger['email']) || empty($passenger['phone']) || empty($payload['termsAccepted'])) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => 'Vehicle, date, time, passenger details, and termsAccepted are required'], 400);
    }

    $date = \DateTime::createFromFormat('!Y-m-d', $pickupDate);
    $time = \DateTime::createFromFormat('!H:i', $pickupTime);
    if (!$date || $date->format('Y-m-d') !== $pickupDate || !$time || $time->format('H:i') !== $pickupTime) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => 'ride.date must be YYYY-MM-DD and ride.time must be HH:MM'], 400);
    }

    $fleet = Fleet::find()->where(['id' => $fleetId, 'status' => 'Active', 'deleted_at' => null])->one();
    if (!$fleet) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => 'Selected vehicle is not available'], 404);
    }

    // Check vehicle availability for the requested pickup date
    if (!$fleet->isAvailableForDate($pickupDate)) {
      Yii::$app->MyFunctions->JsonPrint([
        'status'  => 0,
        'message' => 'This vehicle is already booked or unavailable for ' . $pickupDate . '. Please choose a different date or vehicle.',
      ], 409);
    }

    // Service type: 'distance' or 'hourly'
    $service = strtolower(trim((string) ($ride['service'] ?? ($payload['service'] ?? 'distance'))));

    // Hours / duration for hourly booking
    $hours = null;
    if (isset($ride['hours']) && is_numeric($ride['hours'])) {
      $hours = round((float) $ride['hours'], 2);
    } elseif (isset($payload['hours']) && is_numeric($payload['hours'])) {
      $hours = round((float) $payload['hours'], 2);
    } elseif (isset($ride['duration_hours']) && is_numeric($ride['duration_hours'])) {
      $hours = round((float) $ride['duration_hours'], 2);
    } elseif (isset($payload['duration_hours']) && is_numeric($payload['duration_hours'])) {
      $hours = round((float) $payload['duration_hours'], 2);
    } elseif (isset($payload['duration']) && is_numeric($payload['duration'])) {
      $hours = round((float) $payload['duration'], 2);
    }

    if ($hours !== null && empty($service)) {
      $service = 'hourly';
    }

    // Miles for distance booking
    $miles = null;
    if (isset($ride['miles']) && is_numeric($ride['miles'])) {
      $miles = round((float) $ride['miles'], 2);
    } elseif (isset($quote['miles']) && is_numeric($quote['miles'])) {
      $miles = round((float) $quote['miles'], 2);
    } elseif (isset($payload['miles']) && is_numeric($payload['miles'])) {
      $miles = round((float) $payload['miles'], 2);
    }

    // Option B: Validate that locations are inside our allowed service areas
    $pickupLocation = $ride['pickup'] ?? ($payload['pickup'] ?? null);
    $dropoffLocation = $ride['dropoff'] ?? ($payload['dropoff'] ?? null);

    if (!empty($pickupLocation) && !empty($dropoffLocation)) {
      $areaValidation = ServiceAreaHelper::validateTrip($pickupLocation, $dropoffLocation);
    } elseif (!empty($pickupLocation)) {
      $areaValidation = ServiceAreaHelper::validateLocation($pickupLocation);
    } else {
      $areaValidation = ['valid' => false, 'message' => Yii::t('app', 'Pickup location is required.')];
    }

    if (!$areaValidation['valid']) {
      Yii::$app->MyFunctions->JsonPrint([
        'status'  => 0,
        'message' => $areaValidation['message'],
        'data'    => $areaValidation['data'] ?? null,
      ], 422);
    }

    // Auto-calculate driving distance via Google Maps if miles was not provided
    if ($service !== 'hourly' && $miles === null && !empty($pickupLocation) && !empty($dropoffLocation)) {
      $distResult = GoogleMapsHelper::calculateDistance($pickupLocation, $dropoffLocation);
      if ($distResult['success']) {
        $miles = $distResult['miles'];
        $ride['distance_details'] = $distResult;
      }
    }

    // Enforce minimum hours requirement for hourly bookings
    $minHours = max(1, (int) $fleet->minimum_hours);
    if ($service === 'hourly' || ($hours !== null && $hours > 0)) {
      if ($hours !== null && $hours < $minHours) {
        Yii::$app->MyFunctions->JsonPrint([
          'status'  => 0,
          'message' => Yii::t('app', 'This vehicle requires a minimum booking of {min} hours (you requested {requested} hours).', [
            'min'       => $minHours,
            'requested' => $hours,
          ]),
          'data'    => [
            'vehicle_id'      => $fleet->id,
            'minimum_hours'   => $minHours,
            'requested_hours' => $hours,
            'hourly_price'    => (float) $fleet->hourly_price,
          ],
        ], 422);
      }
    }

    // Calculate total charge using Fleet model pricing rules
    $chargeBreakdown = $fleet->calculateCharge($service, $miles, $hours);
    $total = $chargeBreakdown['total_charge'];

    $currency = strtolower((string) ($payload['currency'] ?? 'usd'));
    $paymentMode = Yii::$app->params['payment_stripe_mode'];
    $secretKey = $paymentMode === 'live'
      ? Yii::$app->params['payment_stripe_live_secret_key']
      : Yii::$app->params['payment_stripe_test_secret_key'];

    if (!$secretKey) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => 'Payment gateway is not configured'], 500);
    }

    try {
      Stripe::setApiKey($secretKey);
      $paymentIntent = PaymentIntent::create([
        'amount' => (int) round($total * 100),
        'currency' => $currency,
        'automatic_payment_methods' => ['enabled' => true],
        'metadata' => [
          'vehicle_id'    => (string) $fleet->id,
          'service'       => $service,
          'hours'         => $hours !== null ? (string) $hours : '',
          'minimum_hours' => (string) $minHours,
          'miles'         => $miles !== null ? (string) $miles : '',
          'pickup_date'   => $pickupDate,
          'pickup_time'   => $pickupTime,
        ],
      ]);

      // Merge breakdown into quote_data
      if (empty($quote)) {
        $quote = $chargeBreakdown;
      } else {
        $quote['charge_breakdown'] = $chargeBreakdown;
        $quote['total'] = $total;
      }

      $booking = new Booking();
      $booking->fleet_id = $fleet->id;
      $booking->pickup_date = $pickupDate;
      $booking->pickup_time = $pickupTime;
      $booking->service = $service;
      $booking->pickup_location_type = $ride['pickupLocationType'] ?? null;
      $booking->dropoff_location_type = $ride['dropoffLocationType'] ?? null;
      $booking->pickup = $pickupLocation;
      $booking->dropoff = $dropoffLocation;
      $booking->ride_data = json_encode($ride);
      $booking->quote_data = json_encode($quote);
      $booking->extras = json_encode($payload['extras'] ?? []);
      $booking->notes = $payload['notes'] ?? null;
      $booking->passenger_data = json_encode($passenger);
      $booking->promo_code = $payload['promoCode'] ?? null;
      $booking->payment_preference = $payload['paymentPreference'] ?? null;
      $booking->terms_accepted = true;
      $booking->base_price = $fleet->base_price;
      $booking->km_per_hour_price = $fleet->km_per_hour_price;
      $booking->distance_price = (float) ($quote['distance_price'] ?? ($chargeBreakdown['miles_charge'] ?? 0));
      $booking->total = $total;
      $booking->currency = $currency;
      $booking->payment_intent_id = $paymentIntent->id;
      $booking->payment_status = 'pending';
      $booking->booking_status = 'pending_payment';

      if (!$booking->save()) {
        $paymentIntent->cancel();
        Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => 'Booking could not be saved', 'errors' => $booking->getErrors()], 500);
      }

      Yii::$app->MyFunctions->JsonPrint([
        'status' => 1,
        'message' => 'Booking created and payment initialized',
        'data' => [
          'bookingId' => $booking->id,
          'bookingNumber' => $booking->booking_number,
          'clientSecret' => $paymentIntent->client_secret,
          'paymentIntentId' => $paymentIntent->id,
          'total' => $total,
          'currency' => $currency,
          'chargeBreakdown' => $chargeBreakdown,
          'paymentStatus' => $booking->payment_status,
          'bookingStatus' => $booking->booking_status,
        ],
      ]);
    } catch (\Throwable $exception) {
      Yii::error($exception->getMessage(), 'booking.payment');
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $exception->getMessage()], 500);
    }
  }

  public function actionConfirmbookingpayment()
  {
    $bookingId = (int) Yii::$app->request->post('bookingId', Yii::$app->request->get('bookingId'));
    $paymentIntentId = Yii::$app->request->post('paymentIntentId', Yii::$app->request->get('paymentIntentId'));
    $booking = Booking::findOne(['id' => $bookingId, 'payment_intent_id' => $paymentIntentId]);
    if (!$booking) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => 'Booking not found'], 404);
    }

    $paymentMode = Yii::$app->params['payment_stripe_mode'];
    $secretKey = $paymentMode === 'live'
      ? Yii::$app->params['payment_stripe_live_secret_key']
      : Yii::$app->params['payment_stripe_test_secret_key'];

    try {
      Stripe::setApiKey($secretKey);
      $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
      $booking->payment_status = $paymentIntent->status === 'succeeded' ? 'paid' : $paymentIntent->status;
      $booking->booking_status = $paymentIntent->status === 'succeeded' ? 'confirmed' : 'pending_payment';
      $booking->save(false, ['payment_status', 'booking_status', 'updated_at']);

      Yii::$app->MyFunctions->JsonPrint(['status' => 1, 'data' => [
        'bookingId' => $booking->id,
        'bookingNumber' => $booking->booking_number,
        'paymentStatus' => $booking->payment_status,
        'bookingStatus' => $booking->booking_status,
      ]]);
    } catch (\Throwable $exception) {
      Yii::error($exception->getMessage(), 'booking.payment');
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $exception->getMessage()], 500);
    }
  }

  public function beforeAction($action)
  {
    global $dynamicmodule;
    $this->enableCsrfValidation = false;

    if (Yii::$app->request->isOptions) {
      Yii::$app->response->statusCode = 204;
      Yii::$app->end();
    }

    Yii::$app->language = (isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])) ? $_REQUEST['lang'] : 'en';
    $lang = array('en', 'es');
    if (!in_array(Yii::$app->language, $lang)) {
      Yii::$app->language = 'en';
    }
    return parent::beforeAction($action);
  }

  //Test Api
  public function actionTest()
  {
    echo "<pre>";
    print_r('All Done');
    exit;
  }

  /* public function actionCheckappversion()
  {
    $model = Generalsetting::find()->Where(['setting_id' => 1])->one();
    if (!empty($model)) {
      $message = Yii::t('app', $model->version_message);
      if ($_REQUEST['devices_type'] == 'Android' && ($model->android_app_version == $_REQUEST['app_version'] || $model->android_app_version <= $_REQUEST['app_version'])) {
        // if($model->android_app_version==$_REQUEST['app_version'] || $model->android_app_version <= $_REQUEST['app_version']){
        //   $message = Yii::t('app', $model->version_message);
        //   Yii::$app->MyFunctions->JsonPrint(array('status'=>0,'force_update'=>$model->force_update,'message'=>$message));
        //   exit;
        // }
        // else{
        //$message = Yii::t('app', 'Your application version is :'.$_REQUEST['app_version']);
        $message = Yii::t('app', $model->version_message);
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'force_update' => $model->force_update, 'message' => $message));
        exit;
        //}
      } else if ($_REQUEST['devices_type'] == 'Iphone' && ($model->ios_app_version == $_REQUEST['app_version'] || $model->ios_app_version <= $_REQUEST['app_version'])) {
        // if($model->android_app_version==$_REQUEST['app_version']){
        //   $message = Yii::t('app', $model->version_message);
        //   Yii::$app->MyFunctions->JsonPrint(array('status'=>1,'force_update'=>$model->force_update,'message'=>$message));
        //   exit;
        // }
        // else{
        //$message = Yii::t('app', 'Your application version is :'.$_REQUEST['app_version']);
        $message = Yii::t('app', $model->version_message);
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'force_update' => $model->force_update, 'message' => $message));
        exit;
        //}
      }
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'force_update' => $model->force_update, 'message' => $message), JSON_PRETTY_PRINT);
      exit;
    } else {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'List not found.')), JSON_PRETTY_PRINT);
      exit;
    }
  } */



  //10 Appsetting
  public function actionSetting()
  {
    $model = Generalsetting::find()->Where(['setting_id' => 1])->one();
    // $advertisement = Advertisement::find()->all();

    if (!empty($model)) {
      $data['terms_conditions'] = $model->terms_conditions;
      $data['terms_conditions_trade_pro'] = $model->terms_conditions_trade_pro;
      $data['privacy_policy'] = $model->privacy_policy;
      //$data['ticket_policy']=$model->ticket_policy;
      //$data['user_terms_conditions']=$model->user_terms_conditions;
      //$data['user_privacy_policy']=$model->user_privacy_policy;
      //$data['user_terms_service']=$model->user_terms_service;
      //$data['club_terms_service']=$model->club_terms_service;
      //$data['cookie']=$model->cookie;
      $data['about_us'] = $model->about_us;
      //$data['code_of_conduct']=$model->code_of_conduct;
      // $data['matches']=$model->matches;
      // $data['century']=$model->century;
      // $data['half_century']=$model->half_century;
      // $data['wickets']=$model->wickets;
      // $data['trophies']=$model->trophies;
      $data['facebook'] = $model->facebook;
      $data['instagram'] = $model->instagram;
      $data['twitter'] = $model->twitter;
      $data['youtube'] = $model->youtube;
      $data['tiktok'] = $model->tiktok;
      $data['snapchat'] = $model->snapchat;
      $data['linkedin'] = $model->linkedin;
      $data['contact_no'] = $model->contact_no;
      $data['telephone_no'] = $model->telephone_no;
      $data['email'] = $model->email;
      $data['address'] = $model->address;
      $data['map_url'] = $model->map_url;
      $data['sps_flooring_title'] = $model->sps_flooring_title;
      $data['sps_flooring_sub_title'] = $model->sps_flooring_sub_title;
      $data['weekly_bestsellers_title'] = $model->weekly_bestsellers_title;
      $data['weekly_bestsellers_sub_title'] = $model->weekly_bestsellers_sub_title;
      $data['clients_say_title'] = $model->clients_say_title;
      $data['clients_say_sub_title'] = $model->clients_say_sub_title;
      $data['pick_up_delivery'] = $model->pick_up_delivery;
      $data['agree_delivery'] = $model->agree_delivery;
      $data['meta_title'] = $model->meta_title;
      $data['meta_tag'] = $model->meta_tag;
      $data['meta_description'] = $model->meta_description;
      $data['tax'] = $model->tax;
      $data['warehouse_address'] = $model->warehouse_address;
      $data['latitude'] = $model->latitude;
      $data['longitude'] = $model->longitude;
      $data['delivery_charge'] = $model->delivery_charge;
      $data['need_to_display_roomvo'] = $model->need_to_display_roomvo;
      $data['contact_us_image'] = (!empty($model->contact_us_image)) ? Yii::$app->params['ImagePath'] . $model->contact_us_image : '';
      //$data['current_tournament'] = $model->current_tournament;
      //$data['live_match_url'] = $model->live_match_url;
      //$data['show_chat_support_on_app'] = $model->show_chat_support_on_app;
      //$data['download_calendar'] = (!empty($model->download_calendar)) ? Yii::$app->params['ImagePath'] . $model->download_calendar : '';
      //$data['contact_us_image'] = (!empty($model->contact_us_image)) ? Yii::$app->params['ImagePath'] . $model->contact_us_image : '';
      //$data['rbtv_url'] = 'https://www.redbull.com/us-en/event-series/premier-padel';
      //$data['fip_player_ranking_url'] = 'https://www.padelfip.com/ranking-male/';
      //$data['court_booking_contact_no'] = $model->court_booking_contact_no;
      //$data['spotify_link']=$model->spotify_link;

      $data['advertisement'] = array_map(function ($ad) {

        $arr = $ad->toArray();

        $arr['image'] = Yii::$app->params['ImagePath'] . $ad->image;

        return $arr;
      }, Advertisement::find()->andWhere(["type" => "product"])->all());

      $data['advertisement_home'] = array_map(function ($ad) {

        $arr = $ad->toArray();

        $arr['image'] = Yii::$app->params['ImagePath'] . $ad->image;

        return $arr;
      }, Advertisement::find()->andWhere(["type" => "homepage"])->all());

      $message = Yii::t('app', 'List found');
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
    } else {
      echo json_encode(array('status' => 0, 'message' => Yii::t('app', 'List not found.')), JSON_PRETTY_PRINT);
      exit;
    }
  }

  //1
  public function actionGethomescreen()
  {
    $data = [];
    $query = Newsletter::find()->Where(['status' => 'Active'])->orderBy(['date' => SORT_DESC])->limit(4)->all();
    $data['news'] = [];
    if (!empty($query)) {
      foreach ($query as $key => $news_catagory) {
        $data['news'][$key] = Yii::$app->MyFunctions->getNewslatterNewObject($news_catagory);
      }
    }

    $query = Clientsay::find()->Where(['status' => 'Active'])->orderBy(['display_order' => SORT_ASC])->all();
    $data['client_say'] = [];
    if (!empty($query)) {
      foreach ($query as $key => $news_catagory) {
        $data['client_say'][$key] = Yii::$app->MyFunctions->getClientsayObject($news_catagory);
      }
    }
    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
  }

  public function actionGetfleetlist()
  {
    // ── Unified input parsing (GET, POST JSON flat, or POST JSON with nested ride object) ──
    $bodyParams = Yii::$app->request->getBodyParams();
    if (empty($bodyParams)) {
      $rawInput = file_get_contents('php://input');
      $decoded = json_decode($rawInput, true);
      $bodyParams = is_array($decoded) ? $decoded : [];
    }
    $queryParams = Yii::$app->request->get();
    $params = array_merge($queryParams, is_array($bodyParams) ? $bodyParams : []);
    $rideParams = (isset($params['ride']) && is_array($params['ride'])) ? $params['ride'] : [];

    $getParam = function ($keys, $default = null) use ($params, $rideParams) {
      if (!is_array($keys)) {
        $keys = [$keys];
      }
      foreach ($keys as $k) {
        if (isset($params[$k]) && $params[$k] !== '') {
          return $params[$k];
        }
        if (isset($rideParams[$k]) && $rideParams[$k] !== '') {
          return $rideParams[$k];
        }
      }
      return $default;
    };

    $type = trim((string) $getParam('type', ''));

    // Service type: 'distance' (default) or 'hourly'
    $serviceRaw = trim((string) $getParam('service', ''));
    $service    = strtolower($serviceRaw);

    // Hours / duration param for hourly booking (e.g. 2, 3.5, 4)
    $hoursRaw = $getParam(['hours', 'duration', 'duration_hours', 'hourly_duration']);
    $hours    = (is_numeric($hoursRaw) && (float) $hoursRaw > 0) ? round((float) $hoursRaw, 2) : null;

    if (!empty($hours) && empty($service)) {
      $service = 'hourly';
    }
    if (empty($service)) {
      $service = 'distance';
    }

    // Miles param — to calculate distance ride charge
    $milesRaw = $getParam(['miles', 'distance', 'distance_miles']);
    $miles    = (is_numeric($milesRaw) && (float) $milesRaw > 0) ? round((float) $milesRaw, 2) : null;

    // Date param (YYYY-MM-DD) — for availability filtering
    $dateRaw   = trim((string) $getParam(['date', 'pickup_date'], ''));
    $checkDate = (!empty($dateRaw) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateRaw)) ? $dateRaw : null;

    // Passengers param — return only fleets with capacity >= this number
    $passengersRaw = $getParam(['passengers', 'passenger', 'seats', 'guests']);
    $minPassengers = (is_numeric($passengersRaw) && (int) $passengersRaw > 0) ? (int) $passengersRaw : null;

    // Luggage param — return only fleets with luggage capacity >= this number
    $luggageRaw = $getParam(['luggage', 'laggage', 'bags']);
    $minLuggage = (is_numeric($luggageRaw) && (int) $luggageRaw > 0) ? (int) $luggageRaw : null;

    // Filter by minimum hours: whether to exclude vehicles requiring more hours than requested
    $filterMinHoursRaw  = $getParam('filter_min_hours');
    $includeUnavailable = (bool) (int) $getParam('include_unavailable', 0);
    $filterMinHours     = ($filterMinHoursRaw !== null)
      ? (bool) (int) $filterMinHoursRaw
      : ($hours !== null && !$includeUnavailable);

    // Optional location / ZIP validation
    $zip      = trim((string) $getParam(['zip', 'postal_code'], ''));
    $pickup   = $getParam(['pickup', 'pickup_zip', 'pickup_location']);
    $dropoff  = $getParam(['dropoff', 'dropoff_zip', 'dropoff_location']);
    $location = $getParam('location');

    if (!empty($pickup) && !empty($dropoff)) {
      $tripCheck = ServiceAreaHelper::validateTrip($pickup, $dropoff);
      if (!$tripCheck['valid']) {
        Yii::$app->MyFunctions->JsonPrint([
          'status'  => 0,
          'message' => $tripCheck['message'],
          'data'    => [],
        ]);
      }
    } else {
      $singleTarget = !empty($zip) ? $zip : (!empty($location) ? $location : (!empty($pickup) ? $pickup : $dropoff));
      if (!empty($singleTarget)) {
        $locCheck = ServiceAreaHelper::validateLocation($singleTarget);
        if (!$locCheck['valid']) {
          Yii::$app->MyFunctions->JsonPrint([
            'status'  => 0,
            'message' => $locCheck['message'],
            'data'    => [],
          ]);
        }
      }
    }

    // ── Calculate driving miles via Google Maps if pickup & dropoff provided ────
    $tripDetails = null;
    if (!empty($pickup) && !empty($dropoff)) {
      $shouldCalculate = ($miles === null) || (bool) (int) $getParam('recalculate_miles', 0);
      if ($shouldCalculate) {
        $distanceResult = GoogleMapsHelper::calculateDistance($pickup, $dropoff);
        if ($distanceResult['success']) {
          $miles = $distanceResult['miles'];
          $tripDetails = [
            'pickup'              => $pickup,
            'dropoff'             => $dropoff,
            'miles'               => $distanceResult['miles'],
            'distance_text'       => $distanceResult['distance_text'],
            'duration_minutes'    => $distanceResult['duration_minutes'],
            'duration_hours'      => $distanceResult['duration_hours'],
            'duration_text'       => $distanceResult['duration_text'],
            'origin_address'      => $distanceResult['origin_address'],
            'destination_address' => $distanceResult['destination_address'],
            'source'              => $distanceResult['source'] ?? 'google_maps',
          ];
        } else {
          Yii::warning('Google Maps distance calculation: ' . ($distanceResult['message'] ?? ''), 'google_maps');
          $tripDetails = [
            'pickup'  => $pickup,
            'dropoff' => $dropoff,
            'miles'   => null,
            'note'    => $distanceResult['message'] ?? 'Could not calculate driving distance via Google Maps',
          ];
        }
      } else {
        $tripDetails = [
          'pickup'  => $pickup,
          'dropoff' => $dropoff,
          'miles'   => $miles,
          'source'  => 'manual',
        ];
      }
    }

    // ── Build fleet query ─────────────────────────────────────────────────────
    $query = Fleet::find()
      ->where(['status' => 'Active', 'deleted_at' => null])
      ->with('fleetImages')
      ->orderBy(['id' => SORT_DESC]);

    if (!empty($type)) {
      $query->andWhere(['type' => $type]);
    }

    // ── Process each fleet ────────────────────────────────────────────────────
    $data = [];
    foreach ($query->all() as $fleet) {

      // ── Passenger capacity filter ─────────────────────────────────────────
      if ($minPassengers !== null) {
        $fleetCapacity = (int) $fleet->passenger;
        if ($fleetCapacity < $minPassengers) {
          continue; // skip — not enough seats
        }
      }

      // ── Luggage capacity filter ───────────────────────────────────────────
      if ($minLuggage !== null) {
        $fleetLuggage = (int) $fleet->laggage;
        if ($fleetLuggage < $minLuggage) {
          continue; // skip — not enough luggage space
        }
      }

      // ── Minimum hours check for hourly bookings ───────────────────────────
      $fleetMinHours = max(1, (int) $fleet->minimum_hours);
      $meetsMinHours = ($hours === null || $hours >= $fleetMinHours);

      // If filter_min_hours is active, exclude vehicles requiring more hours than requested
      if ($filterMinHours && !$meetsMinHours) {
        continue;
      }

      // ── Date Availability check ───────────────────────────────────────────
      $isDateAvailable = ($checkDate !== null)
        ? $fleet->isAvailableForDate($checkDate)
        : (bool) $fleet->is_available;

      // Overall availability requires date available AND meeting min hours
      $isAvailable = $isDateAvailable && $meetsMinHours;

      // If a date was supplied, skip unavailable vehicles unless include_unavailable is requested
      if ($checkDate !== null && !$isAvailable && !$includeUnavailable) {
        continue;
      }

      // ── Pricing calculation ───────────────────────────────────────────────
      $chargeBreakdown = $fleet->calculateCharge($service, $miles, $hours);
      $totalCharge     = $chargeBreakdown['total_charge'];

      // ── Build response item ───────────────────────────────────────────────
      $item                     = Yii::$app->MyFunctions->getFleetObject($fleet);
      $item['is_available']     = (int) $isAvailable;
      $item['is_date_available']= (int) $isDateAvailable;
      $item['meets_min_hours']  = (bool) $meetsMinHours;
      $item['minimum_hours']    = $fleetMinHours;
      $item['hourly_price']     = (float) $fleet->hourly_price;
      $item['available_after']  = $fleet->available_after;
      $item['service']          = $service;
      $item['charge']           = $totalCharge;
      $item['charge_breakdown'] = $chargeBreakdown;

      $data[] = $item;
    }

    if (empty($data)) {
      $emptyMessage = Yii::t('app', 'No vehicles available matching your search criteria.');
      if ($checkDate !== null) {
        $emptyMessage = Yii::t('app', 'No vehicles available for {date}. Please try a different date or vehicle type.', ['date' => $checkDate]);
      } elseif ($hours !== null) {
        $emptyMessage = Yii::t('app', 'No vehicles available for {hours} hours booking. Please check minimum hours requirement.', ['hours' => $hours]);
      }
      $response = [
        'status'  => 0,
        'message' => $emptyMessage,
        'data'    => [],
      ];
      if ($tripDetails !== null) {
        $response['trip'] = $tripDetails;
      }
      if ($miles !== null) {
        $response['miles'] = $miles;
      }
      Yii::$app->MyFunctions->JsonPrint($response);
    }

    $response = [
      'status'  => 1,
      'message' => Yii::t('app', 'List found'),
      'data'    => array_values($data),
    ];
    if ($tripDetails !== null) {
      $response['trip'] = $tripDetails;
    }
    if ($miles !== null) {
      $response['miles'] = $miles;
    }

    Yii::$app->MyFunctions->JsonPrint($response);
  }

  public function actionGetfleettypes()
  {
    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Fleet types found'),
      'data' => array_values(Fleet::typeOptions()),
    ]);
  }

  public function actionEmpirelocationdetail()
  {
    $params = array_merge(
      Yii::$app->request->get(),
      Yii::$app->request->post()
    );

    unset($params['r'], $params['lang'], $params['wizardKey']);
    $params['wizardKey'] = 'Fwfjv7j2r02qjW5t8Z5Yt1qfgWBQBOSZJFZywHek52U2JLFxvFhgTzxDty8r-NUwNaoHJ_8Kj4RUO_ph9OZT9w';

    $url = 'https://booking.empirecls.com/Webconnect/DefaultV2/Booking/AjaxLocationUniversalSearch?' . http_build_query($params);
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_HTTPHEADER => [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'X-Requested-With: XMLHttpRequest',
        'Cookie: GWWCTISCOKIEON=yes; prod-booking=20c2753a5a21f1252c48a2cbc2abe5d7; prod-bookingCORS=20c2753a5a21f1252c48a2cbc2abe5d7',
      ],
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($response === false) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => $curlError ?: Yii::t('app', 'Unable to fetch location detail'),
      ], 502);
    }

    $decodedResponse = json_decode($response, true);
    $data = (json_last_error() === JSON_ERROR_NONE) ? $decodedResponse : $response;

    Yii::$app->MyFunctions->JsonPrint([
      'status' => ($httpStatusCode >= 200 && $httpStatusCode < 300) ? 1 : 0,
      'message' => ($httpStatusCode >= 200 && $httpStatusCode < 300)
        ? Yii::t('app', 'Location detail fetched successfully')
        : Yii::t('app', 'Unable to fetch location detail'),
      'http_status' => $httpStatusCode,
      'data' => $data,
    ], ($httpStatusCode >= 200 && $httpStatusCode < 300) ? 200 : 502);
  }

  public function actionEmpireairlines()
  {
    $query = Yii::$app->request->get('query', Yii::$app->request->post('query'));

    if ($query === null || $query === '') {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'query is required'),
      ], 400);
    }

    $url = 'https://booking.empirecls.com/Webconnect/DefaultV2/Common/AjaxGetAirlines?' . http_build_query([
      'query' => $query,
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_HTTPHEADER => [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'X-Requested-With: XMLHttpRequest',
        'Cookie: GWWCTISCOKIEON=yes; prod-booking=20c2753a5a21f1252c48a2cbc2abe5d7; prod-bookingCORS=20c2753a5a21f1252c48a2cbc2abe5d7',
      ],
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($response === false) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => $curlError ?: Yii::t('app', 'Unable to fetch airlines'),
      ], 502);
    }

    $decodedResponse = json_decode($response, true);
    $data = (json_last_error() === JSON_ERROR_NONE) ? $decodedResponse : $response;

    Yii::$app->MyFunctions->JsonPrint([
      'status' => ($httpStatusCode >= 200 && $httpStatusCode < 300) ? 1 : 0,
      'message' => ($httpStatusCode >= 200 && $httpStatusCode < 300)
        ? Yii::t('app', 'Airlines fetched successfully')
        : Yii::t('app', 'Unable to fetch airlines'),
      'http_status' => $httpStatusCode,
      'data' => $data,
    ], ($httpStatusCode >= 200 && $httpStatusCode < 300) ? 200 : 502);
  }

  /**
   * Validate pickup and/or dropoff locations against allowed service areas (Option B: Strict).
   * Accepts JSON body or GET/POST params:
   *  - pickup & dropoff (validates both under Option B)
   *  - OR location (validates single location)
   */
  public function actionCheckservicearea()
  {
    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decoded = json_decode($rawBody, true);
      $payload = is_array($decoded) ? $decoded : $_REQUEST;
    }

    $pickup = $payload['pickup'] ?? ($payload['ride']['pickup'] ?? ($payload['pickup_zip'] ?? Yii::$app->request->get('pickup', Yii::$app->request->get('pickup_zip'))));
    $dropoff = $payload['dropoff'] ?? ($payload['ride']['dropoff'] ?? ($payload['dropoff_zip'] ?? Yii::$app->request->get('dropoff', Yii::$app->request->get('dropoff_zip'))));
    $location = $payload['location'] ?? ($payload['zip'] ?? ($payload['postal_code'] ?? Yii::$app->request->get('location', Yii::$app->request->get('zip', Yii::$app->request->get('postal_code')))));

    // Both pickup and dropoff provided -> validate full trip (Option B Strict)
    if (!empty($pickup) && !empty($dropoff)) {
      $result = ServiceAreaHelper::validateTrip($pickup, $dropoff);
      Yii::$app->MyFunctions->JsonPrint([
        'status' => $result['valid'] ? 1 : 0,
        'message' => $result['message'],
        'data' => $result['data'] ?? null,
      ], $result['valid'] ? 200 : 422);
    }

    // Single location validation
    $singleTarget = !empty($location) ? $location : (!empty($pickup) ? $pickup : $dropoff);
    if (!empty($singleTarget)) {
      $result = ServiceAreaHelper::validateLocation($singleTarget);
      Yii::$app->MyFunctions->JsonPrint([
        'status' => $result['valid'] ? 1 : 0,
        'message' => $result['message'],
        'data' => $result,
      ], $result['valid'] ? 200 : 422);
    }

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 0,
      'message' => Yii::t('app', 'pickup and dropoff (or location) parameters are required'),
    ], 400);
  }

  /**
   * Return the list of all supported service zones, cities, and airports.
   */
  public function actionGetserviceareas()
  {
    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Service areas retrieved successfully'),
      'data' => ServiceAreaHelper::getAllowedAreas(),
    ]);
  }

  public function actionGetfleetdetail()
  {
    $id = Yii::$app->request->get('id', Yii::$app->request->post('id'));
    if (empty($id)) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'Fleet ID is required'),
      ]);
    }

    $fleet = Fleet::find()
      ->where(['id' => $id, 'status' => 'Active', 'deleted_at' => null])
      ->with('fleetImages')
      ->one();

    if (empty($fleet)) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'Fleet not found.'),
      ]);
    }

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Fleet found'),
      'data' => Yii::$app->MyFunctions->getFleetObject($fleet),
    ]);
  }

  //6
  public function actionContactus()
  {
    $model = new Contactus();
    $request = Yii::$app->request;
    $payload = is_array($request->bodyParams) ? $request->bodyParams : array();
    $rawBody = trim($request->rawBody);

    if ($rawBody !== '') {
      $jsonPayload = json_decode($rawBody, true);
      if (is_array($jsonPayload)) {
        $payload = array_merge($payload, $jsonPayload);
      }
    }

    $payload = array_merge($request->get(), $payload);
    $getValue = function ($key, $default = '') use ($payload) {
      return isset($payload[$key]) ? $payload[$key] : $default;
    };

    $model->load([
      'full_name' => trim($getValue('full_name', $getValue('name'))),
      'email' => trim($getValue('email')),
      'phone_number' => trim($getValue('phone_number', $getValue('phone'))),
      'subject' => trim($getValue('subject')),
      'message' => trim($getValue('message')),
    ], '');

    if ($model->validate() && $model->save()) {
      try {
        $toEmail = !empty(Yii::$app->params['contactEmail']) ? Yii::$app->params['contactEmail'] : 'info@sunriseblackcar.com';
        $fromEmail = !empty(Yii::$app->params['senderEmail']) ? Yii::$app->params['senderEmail'] : (!empty(Yii::$app->params['supportEmail']) ? Yii::$app->params['supportEmail'] : $toEmail);
        $senderName = !empty(Yii::$app->params['senderName']) ? Yii::$app->params['senderName'] : 'Sunrise Black Car';

        $subjectTitle = !empty($model->subject) ? $model->subject : 'General Inquiry';

        $htmlContent = "<div style='font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6;'>"
          . "<h2 style='color: #111;'>New Contact Us Submission</h2>"
          . "<table style='width: 100%; max-width: 600px; border-collapse: collapse; margin-top: 15px;'>"
          . "<tr><td style='padding: 8px; font-weight: bold; border-bottom: 1px solid #ddd; width: 140px;'>Full Name:</td><td style='padding: 8px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($model->full_name) . "</td></tr>"
          . "<tr><td style='padding: 8px; font-weight: bold; border-bottom: 1px solid #ddd;'>Email:</td><td style='padding: 8px; border-bottom: 1px solid #ddd;'><a href='mailto:" . htmlspecialchars($model->email) . "'>" . htmlspecialchars($model->email) . "</a></td></tr>"
          . "<tr><td style='padding: 8px; font-weight: bold; border-bottom: 1px solid #ddd;'>Phone Number:</td><td style='padding: 8px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($model->phone_number) . "</td></tr>"
          . "<tr><td style='padding: 8px; font-weight: bold; border-bottom: 1px solid #ddd;'>Subject:</td><td style='padding: 8px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($model->subject) . "</td></tr>"
          . "<tr><td style='padding: 8px; font-weight: bold; border-bottom: 1px solid #ddd; vertical-align: top;'>Message:</td><td style='padding: 8px; border-bottom: 1px solid #ddd;'>" . nl2br(htmlspecialchars($model->message)) . "</td></tr>"
          . "</table>"
          . "</div>";

        Yii::$app->mailer->compose()
          ->setFrom([$fromEmail => $senderName])
          ->setTo($toEmail)
          ->setSubject('New Contact Us Inquiry: ' . $subjectTitle)
          ->setHtmlBody($htmlContent)
          ->send();
      } catch (\Throwable $e) {
        Yii::error('Contact Us email sending error: ' . $e->getMessage(), 'contactus');
      }

      $message = Yii::t('app', 'Thanks for the message, one of our team will be in touch shortly');
      Yii::$app->MyFunctions->JsonPrint(array(
        'status' => 1,
        'message' => $message,
        'data' => array(
          'contact_us_id' => $model->contact_us_id,
          'full_name' => $model->full_name,
          'email' => $model->email,
          'phone_number' => $model->phone_number,
          'subject' => $model->subject,
          'message' => $model->message,
          'created_at' => $model->created_at,
        ),
      ));
    }
    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  public function actionUpload()
  {
    $files = $this->getBeforeauthUploadFiles();

    if (empty($files)) {
      Yii::$app->MyFunctions->JsonPrint(array(
        'status' => 0,
        'message' => Yii::t('app', 'Please upload at least one file.'),
      ));
    }

    $urls = array();
    foreach ($files as $file) {
      $savedPath = $this->saveBeforeauthUploadFile($file);
      if ($savedPath === false) {
        Yii::$app->MyFunctions->JsonPrint(array(
          'status' => 0,
          'message' => Yii::t('app', 'File upload failed.'),
        ));
      }

      $urls[] = Yii::$app->params['ImagePath'] . $savedPath;
    }

    Yii::$app->MyFunctions->JsonPrint(array(
      'status' => 1,
      'message' => Yii::t('app', 'File uploaded successfully.'),
      'data' => $urls,
    ));
  }

  private function getEmailList($emails)
  {
    if (empty($emails)) {
      return array();
    }

    $emailList = array_map('trim', explode(',', $emails));
    return array_filter($emailList);
  }

  private function getBeforeauthUploadFiles()
  {
    $files = array_merge(
      UploadedFile::getInstancesByName('files'),
      UploadedFile::getInstancesByName('file'),
      UploadedFile::getInstancesByName('upload')
    );

    return array_filter($files, function ($file) {
      return $file instanceof UploadedFile && !$file->hasError;
    });
  }

  private function saveBeforeauthUploadFile($file)
  {
    $allowedExtensions = array(
      'jpg',
      'jpeg',
      'png',
      'webp',
      'gif',
      'pdf',
      'doc',
      'docx',
      'xls',
      'xlsx',
      'csv',
      'txt',
      'mp4',
      'mov',
      'webm',
    );
    $extension = strtolower($file->extension);

    if (!in_array($extension, $allowedExtensions)) {
      return false;
    }

    $path = 'uploads/files/beforeauth/';
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
    }

    $name = Yii::$app->security->generateRandomString(32);
    $filePath = $path . $name . '.' . $extension;

    if ($file->saveAs($filePath)) {
      return $filePath;
    }

    return false;
  }

  public function actionTradepropartner()
  {
    $model = new Tradepropartner();
    $data = Yii::$app->request->post();

    if (empty($data)) {
      $rawBody = Yii::$app->request->getRawBody();
      $jsonData = json_decode($rawBody, true);
      if (is_array($jsonData)) {
        $data = $jsonData;
      }
    }

    if (isset($data['Tradepropartner']) && is_array($data['Tradepropartner'])) {
      $data = $data['Tradepropartner'];
    }

    // "Title" field in the signature block may arrive as `title` from the frontend.
    if (!isset($data['signature_title']) && isset($data['title'])) {
      $data['signature_title'] = $data['title'];
    }

    $booleanFields = array(
      'interest_flooring',
      'interest_wall_panels',
      'interest_decking',
      'interest_installation_services',
      'interest_other',
      'terms_accepted',
    );

    foreach ($booleanFields as $field) {
      if (isset($data[$field])) {
        $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
      }
    }

    unset($data['signature_image']);

    $model->load($data, '');
    $model->status = Tradepropartner::STATUS_PENDING;

    $signatureImage = UploadedFile::getInstanceByName('signature_image');
    if (!$signatureImage) {
      $signatureImage = UploadedFile::getInstance($model, 'signature_image');
    }

    if ($signatureImage) {
      $savedFile = $this->saveTradeProPartnerSignature($signatureImage);
      if ($savedFile === false) {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Invalid signature image. Only PNG, JPG, JPEG up to 2MB are allowed.')));
      }
      $model->signature_image = $savedFile;
    }

    if ($model->validate() && $model->save(false)) {
      Yii::$app->MyFunctions->JsonPrint(array(
        'status' => 1,
        'message' => Yii::t('app', 'Trade Pro application submitted successfully.'),
        'data' => array('id' => $model->id),
      ));
    }

    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  private function saveTradeProPartnerSignature($file)
  {
    $allowedExtensions = array('png', 'jpg', 'jpeg');
    $extension = strtolower($file->extension);

    if (!in_array($extension, $allowedExtensions)) {
      return false;
    }

    if ($file->size > 2 * 1024 * 1024) {
      return false;
    }

    $path = 'uploads/files/trade_pro_partner/';
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
    }

    $name = Yii::$app->security->generateRandomString(32);
    $filePath = $path . $name . '.' . $extension;

    if ($file->saveAs($filePath)) {
      return $filePath;
    }

    return false;
  }

  //7
  public function actionGetevents()
  {
    $page = (isset($_REQUEST['page']) && $_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
    $pagesize = (isset($_REQUEST['pagesize']) && $_REQUEST['pagesize']) ? (int) $_REQUEST['pagesize'] : 20;

    $provider = new ActiveDataProvider([
      'query' => Event::find()->where(['status' => 'Active'])->orderBy(['event_date' => SORT_ASC, 'event_time' => SORT_ASC]),
      'pagination' => ['pageSize' => $pagesize, 'page' => max(0, $page - 1)],
    ]);

    $data = [];
    foreach ($provider->getModels() as $event) {
      $data[] = Yii::$app->MyFunctions->getEventObject($event);
    }

    $currentPage = $provider->pagination->page + 1;
    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'total_page' => $provider->pagination->pageCount,
      'current_page' => $currentPage,
      'is_next_page' => $currentPage < $provider->pagination->pageCount ? 'Y' : 'N',
      'data' => $data,
    ]);
  }

  public function actionGeteventdetails()
  {
    $slug = !empty($_REQUEST['slug']) ? $_REQUEST['slug'] : '-';
    $event = Event::find()->where(['status' => 'Active', 'slug' => $slug])->one();

    if ($event === null) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => Yii::t('app', 'Event not found')]);
      return;
    }

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Event found'),
      'data' => Yii::$app->MyFunctions->getEventObject($event),
    ]);
  }

  //8
  public function actionGetnewsletter()
  {

    $page = (isset($_REQUEST['page']) && $_REQUEST['page']) ? $_REQUEST['page'] : 1;

    $pagesize = (isset($_REQUEST['pagesize']) && $_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;

    $data = [];


    $query = Newsletter::find()
      ->Where(['status' => 'Active'])
      ->orderBy(['date' => SORT_DESC]);

    $provider_data = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => $pagesize,
        'page' => $page - 1,
      ],
    ]);
    $ModelData = $provider_data->getModels();
    $totalPage = $provider_data->pagination->pageCount;
    $currentPage = $provider_data->pagination->page + 1;
    if ($currentPage < $totalPage) {
      $is_nextpage = "Y";
    } else {
      $is_nextpage = "N";
    }
    $data = [];
    foreach ($ModelData as $key => $news_catagory) {
      $data[$key] = Yii::$app->MyFunctions->getNewslatterNewObject($news_catagory);
    }

    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'total_page' => $totalPage, 'current_page' => $currentPage, 'is_next_page' => $is_nextpage, 'data' => $data));
  }

  //8
  public function actionGetnewsdetails()
  {
    $slug = (isset($_REQUEST['slug']) && !empty($_REQUEST['slug'])) ? $_REQUEST['slug'] : '-';

    $query = Newsletter::find()
      ->Where(['status' => 'Active', 'slug' => $slug])
      ->one();
    if (!empty($query)) {
      $data = Yii::$app->MyFunctions->getNewslatterObject($query);
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'List found'), 'data' => $data));
    } else {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Invalid parameter value')));
    }
  }

  //9
  public function actionGetcommon()
  {
    $data = [];

    $query = Clientsay::find()->Where(['status' => 'Active'])->orderBy(['display_order' => SORT_ASC])->all();
    $data['client_say'] = [];
    if (!empty($query)) {
      foreach ($query as $key => $news_catagory) {
        $data['client_say'][$key] = Yii::$app->MyFunctions->getClientsayObject($news_catagory);
      }
    }
    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
  }

  // 10
  public function actionUsersignup()
  {
    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decoded = json_decode($rawBody, true);
      $payload = is_array($decoded) ? $decoded : $_REQUEST;
    } else {
      $payload = array_merge($_REQUEST, $payload);
    }

    $data = isset($payload['Appuser']) ? $payload['Appuser'] : $payload;

    // Handle phone number aliases (phone_number, phone_no, phone, mobile)
    $phone = $data['phone_number'] ?? ($data['phone_no'] ?? ($data['phone'] ?? ($data['mobile'] ?? ($data['contact_number'] ?? ''))));

    // Handle full name split if first_name is not provided separately
    if (empty($data['first_name']) && !empty($data['name'])) {
      $nameParts = explode(' ', trim($data['name']), 2);
      $data['first_name'] = $nameParts[0];
      if (empty($data['last_name']) && isset($nameParts[1])) {
        $data['last_name'] = $nameParts[1];
      }
    }

    $model = new Appuser();
    $model->scenario = "usersignup";
    $model->load($data, '');

    if (!empty($phone)) {
      $model->phone_number = trim((string)$phone);
    }

    // Explicit check for phone number
    if (empty($model->phone_number)) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'Phone number is required.'),
      ], 400);
    }

    // Default device metadata for web / API clients if not supplied
    if (empty($model->devices_type)) {
      $model->devices_type = 'Web';
    }
    if (empty($model->devices_name)) {
      $model->devices_name = $_SERVER['HTTP_USER_AGENT'] ?? 'Web Browser';
    }
    if (empty($model->devices_id)) {
      $model->devices_id = 'web_' . md5(($model->email ?? '') . microtime());
    }
    if (empty($model->app_version)) {
      $model->app_version = '1.0';
    }

    $model->role = '3';
    $model->user_type = 'User';
    $model->signup_type = 'Normal';

    if ($model->validate() && $model->save()) {
      $userDevice = Yii::$app->MyFunctions->setDeviceinfo($model);
      $userData = Yii::$app->MyFunctions->getUserObject($model, $userDevice);

      try {
        $model->sendWelcomeMail();
      } catch (\Throwable $e) {
        Yii::error('Welcome email sending error: ' . $e->getMessage(), 'welcome');
      }

      $message = Yii::t('app', 'User Signup is successful.');
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 1,
        'message' => $message,
        'data' => $userData,
      ]);
    }

    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  // 11 
  public function actionLogin()
  {
    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decoded = json_decode($rawBody, true);
      $payload = is_array($decoded) ? $decoded : $_REQUEST;
    } else {
      $payload = array_merge($_REQUEST, $payload);
    }

    $data = isset($payload['Appuser']) ? $payload['Appuser'] : $payload;

    // Apply web defaults if device fields are not supplied
    if (empty($data['devices_type'])) {
      $data['devices_type'] = 'Web';
    }
    if (empty($data['devices_name'])) {
      $data['devices_name'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Web Browser';
    }
    if (empty($data['devices_id'])) {
      $data['devices_id'] = 'web_' . md5(($data['email'] ?? '') . microtime());
    }
    if (empty($data['app_version'])) {
      $data['app_version'] = '1.0';
    }

    $model = new Appuser();
    $model->scenario = "login";
    $model->load($data, '');

    if (!$model->validate()) {
      Yii::$app->MyFunctions->getModelErrors($model, "Y");
    }

    $cleanEmail = strtolower(trim((string)$model->email));
    $user = Appuser::find()
      ->where(['LOWER(email)' => $cleanEmail, 'login_type' => 'Normal', 'is_deleted' => 'No'])
      ->andWhere(['not in', 'role', ['1']])
      ->one();

    if (!$user || sha1($model->password) !== $user->password) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'Invalid email or password please try again'),
      ], 401);
    }

    if ($user->status === 'Inactive') {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 4,
        'message' => Yii::t('app', 'Your account is inactive. Please contact admin for more information.'),
      ], 403);
    }

    $user->devices_type = $model->devices_type;
    $user->devices_token = $model->devices_token;
    $user->devices_name = $model->devices_name;
    $user->devices_id = $model->devices_id;
    $user->app_version = $model->app_version;
    $user->save(false);

    $userDevice = Yii::$app->MyFunctions->setDeviceinfo($user);
    $userData = Yii::$app->MyFunctions->getUserObject($user, $userDevice);

    $message = Yii::t('app', 'Login is successfully completed.');
    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => $message,
      'data' => $userData,
    ]);
  }

  //8
  public function actionGetaboutus()
  {
    $query = Aboutus::find()
      ->Where(['status' => 'Active'])
      ->one();
    if (!empty($query)) {
      $data = Yii::$app->MyFunctions->getAboutusObject($query);
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'List found'), 'data' => $data));
    } else {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Invalid parameter value')));
    }
  }

  // 9
  public function actionForgotpassword()
  {
    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decoded = json_decode($rawBody, true);
      $payload = is_array($decoded) ? $decoded : $_REQUEST;
    } else {
      $payload = array_merge($_REQUEST, $payload);
    }

    $data = isset($payload['Appuser']) ? $payload['Appuser'] : $payload;

    $model = new Appuser();
    $model->scenario = "forgotpassword";
    $model->load($data, '');

    if (!$model->validate()) {
      Yii::$app->MyFunctions->getModelErrors($model, "Y");
    }

    $cleanEmail = strtolower(trim((string)$model->email));
    $user = Appuser::find()
      ->where(['LOWER(email)' => $cleanEmail, 'login_type' => 'Normal', 'is_deleted' => 'No'])
      ->andWhere(['not in', 'role', ['1']])
      ->one();

    if (!$user) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'You are not registered with this email address.'),
      ], 404);
    }

    if ($user->status === 'Inactive') {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 5,
        'message' => Yii::t('app', 'Your account is inactive. Please contact admin for more information.'),
      ], 403);
    }

    try {
      $user->sendPasswordResetLink();
    } catch (\Throwable $exc) {
      Yii::error('Forgot password error: ' . $exc->getMessage(), 'forgotpassword');
    }

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Please check your email for password reset instructions.'),
    ]);
  }

  // 10 - Reset Password API
  public function actionResetpassword()
  {
    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decoded = json_decode($rawBody, true);
      $payload = is_array($decoded) ? $decoded : $_REQUEST;
    } else {
      $payload = array_merge($_REQUEST, $payload);
    }

    $token = trim((string)($payload['token'] ?? ''));
    $password = (string)($payload['password'] ?? '');

    if (empty($token) || empty($password)) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'Token and new password are required.'),
      ], 400);
    }

    $user = Appuser::find()
      ->where(['password_reset_token' => $token, 'is_deleted' => 'No'])
      ->one();

    if (!$user) {
      Yii::$app->MyFunctions->JsonPrint([
        'status' => 0,
        'message' => Yii::t('app', 'Invalid or expired password reset token.'),
      ], 400);
    }

    $user->password = sha1($password);
    $user->password_reset_token = '';
    $user->save(false);

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Password has been reset successfully. You can now log in with your new password.'),
    ]);
  }

  // 11 - Logout API
  public function actionLogout()
  {
    $headers = Yii::$app->request->headers;
    $authKey = $headers->get('auth_key') ?: ($headers->get('auth-key') ?: $headers->get('authKey'));
    if (empty($authKey)) {
      $authHeader = $headers->get('Authorization') ?: $headers->get('authorization');
      if (!empty($authHeader)) {
        if (preg_match('/^Bearer\s+(.*)$/i', $authHeader, $matches)) {
          $authKey = trim($matches[1]);
        } else {
          $authKey = trim($authHeader);
        }
      }
    }

    $payload = Yii::$app->request->getBodyParams();
    if (empty($payload)) {
      $rawBody = file_get_contents('php://input');
      $decoded = json_decode($rawBody, true);
      $payload = is_array($decoded) ? $decoded : $_REQUEST;
    } else {
      $payload = array_merge($_REQUEST, $payload);
    }

    if (empty($authKey)) {
      $authKey = $payload['auth_key'] ?? ($payload['authKey'] ?? null);
    }
    $devicesId = $payload['devices_id'] ?? ($payload['deviceId'] ?? ($payload['devicesId'] ?? null));

    if (!empty($authKey)) {
      Appuserdevicesinfo::deleteAll(['auth_key' => $authKey]);
    } elseif (!empty($devicesId)) {
      Appuserdevicesinfo::deleteAll(['devices_id' => $devicesId]);
    }

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'Logout successfully.'),
    ]);
  }

  public function actionCreatepaymentintent()
  {
    global $user;
    $amount = (!empty($_REQUEST['amount'])) ? $_REQUEST['amount'] : '';
    $currency = (!empty($_REQUEST['currency'])) ? $_REQUEST['currency'] : 'usd';
    if (!$amount) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Amount is required')));
    }
    $payment_stripe_mode = Yii::$app->params['payment_stripe_mode'];
    $secret_key = '';
    if ($payment_stripe_mode == 'live') {
      $secret_key = Yii::$app->params['payment_stripe_live_secret_key'];
    } else {
      $secret_key = Yii::$app->params['payment_stripe_test_secret_key'];
    }
    Stripe::setApiKey($secret_key);
    try {
      $paymentIntent = PaymentIntent::create([
        'amount' => round($amount, 2) * 100, // e.g., 5000 = $50.00
        'currency' => $currency,
        'payment_method_types' => ['card'],
      ]);
      $data['clientSecret'] = $paymentIntent->client_secret;
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
    } catch (\Exception $e) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => $e->getMessage()));
    }
  }

}
