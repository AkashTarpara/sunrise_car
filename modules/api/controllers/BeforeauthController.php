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
use app\models\Aboutus;
use app\models\Advertisement;
use app\models\Tradepropartner;
use app\models\Fleet;

use app\models\Importcsv;
use Aws\S3\S3Client;
use yii\filters\Cors;
use Stripe\Stripe;
use Stripe\PaymentIntent;
//use yii2tech\filestorage\storage\Storage;
$allowed_origins = [
  'http://localhost:5173',
  'https://www.luxurylayers.pro/',
  'https://www.luxurylayers.pro',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
  header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
}

//require __DIR__ . '../../components/class-phpass.php';

class BeforeauthController extends Controller
{
  public $stripe_secret_key;
  public $stripe_customer_id;
  public $PAYMENT_STRIPE_MODE = 'live';
  public $PAYMENT_STRIPE_LIVE_SECRET_KEY = '';
  public $PAYMENT_STRIPE_TEST_SECRET_KEY = '';
  public function beforeAction($action)
  {

    global $dynamicmodule;
    $this->enableCsrfValidation = false;

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
    $query = Fleet::find()
      ->where(['status' => 'Active', 'deleted_at' => null])
      ->with('fleetImages')
      ->orderBy(['id' => SORT_DESC]);

    $type = Yii::$app->request->get('type', Yii::$app->request->post('type'));
    if (!empty($type)) {
      $query->andWhere(['type' => $type]);
    }

    $fleets = $query->all();
    $data = [];
    foreach ($fleets as $key => $fleet) {
      $data[$key] = Yii::$app->MyFunctions->getFleetObject($fleet);
    }

    Yii::$app->MyFunctions->JsonPrint([
      'status' => 1,
      'message' => Yii::t('app', 'List found'),
      'data' => $data,
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
    $model->load($_REQUEST);
    if ($model->validate() && $model->save()) {
      $message = Yii::t('app', 'Thanks for the message, one of our team will be in touch shortly');
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message));
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
    $model = new Appuser();
    $model->scenario = "usersignup";
    $model->load($_REQUEST);

    $model->role = '3';
    $model->user_type = 'User';
    $model->signup_type = 'Normal';
    //echo "<pre>";print_r($model);exit;
    if ($model->validate() && $model->save()) {

      $UserDevice = Yii::$app->MyFunctions->setDeviceinfo($model);
      $data = Yii::$app->MyFunctions->getUserObject($model, $UserDevice);

      $message = Yii::t('app', 'User Signup is successful.');

      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
    }
    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  // 11 
  public function actionLogin()
  {

    $model = new Appuser();

    $model->scenario = "login";
    //$load['Appuser']=$_REQUEST;
    $model->load($_REQUEST);

    if ($model->validate()) {

      $Model = Appuser::find()
        ->where(['email' => $model->email, "login_type" => "Normal", 'is_deleted' => "No"])
        ->andWhere(['not in', 'role', ['1']])
        ->one();


      if (!$Model) {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Invalid email or password please try again')));
      }

      if (!empty($Model)) {
        if (sha1($model->password) != $Model->password) {
          Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Invalid email or password please try again')));
        }
      }

      if ($Model && $Model->is_deleted == "Yes") {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 3, 'message' => Yii::t('app', "An account with this email address does not exist. Please sign up to create a new account.")));
      }

      if ($Model && $Model->status == "Inactive") {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 4, 'message' => Yii::t('app', "You account is inactive. Please contact admin for more information.")));
      }


      $Model->devices_type = $model->devices_type;
      $Model->devices_token = $model->devices_token;
      $Model->devices_name = $model->devices_name;
      $Model->devices_id = $model->devices_id;
      $Model->app_version = $model->app_version;
      //echo "<pre>";print_r($model->devices_type);exit;
      if ($Model->save()) {
        //$Model->sendEmailverifycodeMail();
        $UserDevice = Yii::$app->MyFunctions->setDeviceinfo($Model);
        $data = Yii::$app->MyFunctions->getUserObject($Model, $UserDevice);
        //$data['token']=$Model->email_verify_token;
        $message = Yii::t('app', 'Login is successfully completed.');


        Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
      }

      Yii::$app->MyFunctions->getModelErrors($Model, "Y");
    } else {
      Yii::$app->MyFunctions->getModelErrors($model, "Y");
    }
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
    $model = new Appuser();
    $model->scenario = "forgotpassword";
    $model->load($_REQUEST);
    if ($model->validate()) {
      $Model = Appuser::find()
        ->where(['email' => $model->email, "login_type" => "Normal", 'role' => '3', 'user_type' => 'User', 'is_deleted' => "No"])
        ->one();
      if ($Model) {
        if ($Model->status == "Inactive") {
          Yii::$app->MyFunctions->JsonPrint(array('status' => 5, 'message' => Yii::t('app', "You are inactive by admin contact admin for more info")));
        }
        try {
          $Model->sendPasswordResetLink();
        } catch (\Exception $exc) {
          Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => $exc->getMessage()));
        }

        Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'Please check your email for further information.')));
      } else {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'You are not register with this email')));
      }
    } else {
      Yii::$app->MyFunctions->getModelErrors($model, "Y");
    }
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
