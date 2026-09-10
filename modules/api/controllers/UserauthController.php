<?php

namespace app\modules\api\controllers;

header("Access-Control-Allow-Origin: *");

use Yii;
use yii\db\Query;

use yii\web\Controller;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use Exception;
use yii\db\Expression;

use app\models\Appuser;
use app\models\Generalsetting;
use app\models\Appuserdevicesinfo;
use app\models\Appuseraddress;
use app\models\Usercarts;
use app\models\Appuserfavourite;
use app\models\Userorder;
use app\models\Userorderdetail;

use DateTime;
use DatePeriod;
use DateInterval;

use Stripe\Stripe;
use Stripe\Token;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Error\Card;
use app\models\StripeCardFingerprint;
use Stripe\PaymentIntent;

$allowed_origins = [
  'http://localhost:5173',
  'https://www.luxurylayers.pro/',
  'https://www.luxurylayers.pro',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
  header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, auth_key");
}

class UserauthController extends Controller
{
  public $stripe_secret_key;
  public $stripe_customer_id;
  public $PAYMENT_STRIPE_MODE = 'live';
  public $PAYMENT_STRIPE_LIVE_SECRET_KEY = '';
  public $PAYMENT_STRIPE_TEST_SECRET_KEY = '';
  public function beforeAction($action)
  {


    //echo "<pre>"; print_r('hello'); exit;
    global $user;
    global $dynamicmodule;
    global $application_module_id;
    $this->enableCsrfValidation = false;

    $headers = Yii::$app->request->headers;
    $authorizationHeader = $headers->get('auth_key');


    if (isset($authorizationHeader) && !empty($authorizationHeader)) {

      $user = Yii::$app->MyFunctions->GetUser($authorizationHeader);
      Yii::$app->language = (isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])) ? $_REQUEST['lang'] : ((isset($user->lang_code) && !empty($user->lang_code)) ? $user->lang_code : 'en');
      $ln = array('en', 'es');
      if (!in_array(Yii::$app->language, $ln)) {
        Yii::$app->language = 'en';
      }
    } else {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 3, 'message' => Yii::t('app', 'Unauthorized access')));
    }

    return parent::beforeAction($action);
  }

  // 1
  public function actionAutologin()
  {
    global $user;
    $user->scenario = "autologin";
    $user->load($_REQUEST);
    $user->scenario = "autologin";
    if ($user->validate() && $user->save()) {
      $UserDevice = Yii::$app->MyFunctions->setDeviceinfo($user);
      $data = Yii::$app->MyFunctions->getUserObject($user, $UserDevice);
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
    }
    Yii::$app->MyFunctions->getModelErrors($user, "Y");
  }

  // 2
  public function actionLogout()
  {
    global $user;
    $headers = Yii::$app->request->headers;
    $authorizationHeader = $headers->get('auth_key');
    $UserDevice = Appuserdevicesinfo::find()->andWhere(["auth_key" => $authorizationHeader])->one();
    if ($UserDevice) {
      $UserDevice->delete();
    }
    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'Logout successfully.')));
  }

  //3
  public function actionAddeditappuseraddress()
  {
    global $user;

    $appuser_address_id = (!empty($_REQUEST['appuser_address_id'])) ? $_REQUEST['appuser_address_id'] : '';

    $model = Appuseraddress::find()->where(['appuser_address_id' => $appuser_address_id])->one();

    $message = Yii::t('app', 'Appuser Address Updated successfully.');
    $isNewRecord = 'No';
    if (empty($model)) {
      $model = new Appuseraddress();
      $message = Yii::t('app', 'Appuser Address added successfully.');
      $isNewRecord = 'Yes';
    }

    $model->appuser_id = $user->appuser_id;

    $model->load($_REQUEST);
    $existingCount = Appuseraddress::find()
      ->where(['appuser_id' => $user->appuser_id])
      ->count();

    // If no address exists yet, set is_default = Yes
    if ($existingCount == 0) {
      $model->is_default = 'Yes';
    }
    if ($model->save()) {
      if ($model->is_default == 'Yes') {
        Appuseraddress::updateAll(
          ['is_default' => 'No'],
          [
            'and',
            ['appuser_id' => $user->appuser_id],
            ['!=', 'appuser_address_id', $model->appuser_address_id]
          ]
        );
      }
      $data = Yii::$app->MyFunctions->getAppuseraddressObject($model);
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
    }
    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  //4
  public function actionGetappuseraddress()
  {
    global $user;
    $data = [];

    $query = Appuseraddress::find()->Where(['appuser_id' => $user->appuser_id])->orderBy(['created_at' => SORT_DESC])->all();
    $data = [];
    if (!empty($query)) {
      foreach ($query as $key => $news_catagory) {
        $data[$key] = Yii::$app->MyFunctions->getAppuseraddressObject($news_catagory);
      }
    }

    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
  }

  // 5
  public function actionDeleteappuseraddress()
  {
    global $user;
    $appuser_address_id = (!empty($_REQUEST['appuser_address_id'])) ? $_REQUEST['appuser_address_id'] : '';
    if (empty($appuser_address_id)) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Appuser Address ID is required')));
    } else {
      $model = Appuseraddress::find()->where(['appuser_address_id' => $appuser_address_id, 'appuser_id' => $user->appuser_id])->one();
      if (empty($model)) {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Appuser Address not found')));
      } else {
        $model->delete();
        Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'Appuser Address deleted successfully')));
      }
    }
  }

  //6
  public function actionAddeditusercarts()
  {
    global $user;

    $user_carts_id = (!empty($_REQUEST['user_carts_id'])) ? $_REQUEST['user_carts_id'] : '';

    $model = Usercarts::find()->where(['user_carts_id' => $user_carts_id])->one();

    $message = Yii::t('app', 'Carts Updated successfully.');
    $isNewRecord = 'No';
    if (empty($model)) {
      $model = new Usercarts();
      $message = Yii::t('app', 'Carts added successfully.');
      $isNewRecord = 'Yes';
    }

    $model->appuser_id = $user->appuser_id;

    $model->load($_REQUEST);

    if ($model->save()) {

      $data = Yii::$app->MyFunctions->getUsercartsObject($model);
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
    }
    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  //7
  public function actionGetusercarts()
  {
    global $user;
    $data = [];

    $query = Usercarts::find()->Where(['appuser_id' => $user->appuser_id])->orderBy(['created_at' => SORT_DESC])->all();
    $data = [];
    if (!empty($query)) {
      foreach ($query as $key => $news_catagory) {
        $data[$key] = Yii::$app->MyFunctions->getUsercartsObject($news_catagory);
      }
    }

    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
  }

  // 8
  public function actionDeleteusercarts()
  {
    global $user;
    $user_carts_id = (!empty($_REQUEST['user_carts_id'])) ? $_REQUEST['user_carts_id'] : '';
    if (empty($user_carts_id)) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'User Carts Id is required')));
    } else {
      $model = Usercarts::find()->where(['user_carts_id' => $user_carts_id, 'appuser_id' => $user->appuser_id])->one();
      if (empty($model)) {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'User Carts not found')));
      } else {
        $model->delete();
        Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'User Carts deleted successfully')));
      }
    }
  }

  // 9
  // public function actionCreatepaymentintent()
  // {
  //   global $user;
  //   $amount = (!empty($_REQUEST['amount'])) ? $_REQUEST['amount'] : '';
  //   $currency = (!empty($_REQUEST['currency'])) ? $_REQUEST['currency'] : 'usd';
  //   if (!$amount) {
  //     Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Amount is required')));
  //   }
  //   $payment_stripe_mode = Yii::$app->params['payment_stripe_mode'];
  //   $secret_key = '';
  //   if ($payment_stripe_mode == 'live') {
  //     $secret_key = Yii::$app->params['payment_stripe_live_secret_key'];
  //   } else {
  //     $secret_key = Yii::$app->params['payment_stripe_test_secret_key'];
  //   }
  //   Stripe::setApiKey($secret_key);
  //   try {
  //     $paymentIntent = PaymentIntent::create([
  //       'amount' => round($amount, 2) * 100, // e.g., 5000 = $50.00
  //       'currency' => $currency,
  //       'payment_method_types' => ['card','affirm'],
  //     ]);
  //     $data['clientSecret'] = $paymentIntent->client_secret;
  //     Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
  //   } catch (\Exception $e) {
  //     Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => $e->getMessage()));
  //   }
  // }

  public function actionCreatepaymentintent()
{
    $amount = Yii::$app->request->post(
        'amount',
        Yii::$app->request->get('amount')
    );

    $currency = Yii::$app->request->post(
        'currency',
        Yii::$app->request->get('currency', 'usd')
    );

    if (
        $amount === null ||
        !is_numeric($amount) ||
        (float) $amount <= 0
    ) {
        Yii::$app->MyFunctions->JsonPrint([
            'status' => 0,
            'message' => 'A valid amount is required',
        ]);
        return;
    }

    $paymentStripeMode =
        Yii::$app->params['payment_stripe_mode'];

    $secretKey = $paymentStripeMode === 'live'
        ? Yii::$app->params['payment_stripe_live_secret_key']
        : Yii::$app->params['payment_stripe_test_secret_key'];

    \Stripe\Stripe::setApiKey($secretKey);

    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => (int) round((float) $amount * 100),
            'currency' => strtolower($currency),

            'payment_method_types' => [
                'card',
                'affirm',
            ],

            'shipping' => [
                'name' => 'Test Customer',
                'phone' => '2015550123',
                'address' => [
                    'line1' => '2900 Northwest 112th Avenue',
                    'line2' => 'Unit D10',
                    'city' => 'Doral',
                    'state' => 'FL',
                    'postal_code' => '33172',
                    'country' => 'US',
                ],
            ],
        ]);

        Yii::$app->MyFunctions->JsonPrint([
            'status' => 1,
            'data' => [
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
                'paymentMethodTypes' =>
                    $paymentIntent->payment_method_types,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'stripeMode' => $paymentStripeMode,
                'stripeAccountKeyPrefix' =>
                    substr($secretKey, 0, 7),
            ],
        ]);
    } catch (\Throwable $e) {
        Yii::$app->MyFunctions->JsonPrint([
            'status' => 0,
            'message' => $e->getMessage(),
        ]);
    }
}

  //10
  public function actionCheckout()
  {
    global $user;


    $model = new Userorder();
    $model->scenario = 'apicreate';
    $model->load($_REQUEST);
    $message = Yii::t('app', 'Checkout successfully.');
    $model->appuser_id = $user->appuser_id;


    $model->payment_date = date('Y-m-d H:i:s');
    $model->order_number = gmdate('dmy') . Yii::$app->MyFunctions->GenerateOTP(6);
    $user_carts_id = (!empty($model->user_carts_id)) ? explode(',', $model->user_carts_id) : '';
    if ($model->validate() && $model->save()) {
      if (!empty($user_carts_id)) {
        foreach ($user_carts_id as $carts_id) {
          $carts = Usercarts::find()->where(['user_carts_id' => $carts_id, 'appuser_id' => $user->appuser_id])->one();
          if (!empty($carts)) {
            $orderDetail = new Userorderdetail();
            $orderDetail->user_order_id = $model->user_order_id;
            $orderDetail->product_id = $carts->product_id;
            $orderDetail->price = $carts->price;
            $orderDetail->quantity = $carts->quantity;
            if ($orderDetail->save()) {
              $carts->delete();
            }
          }
        }
      }
      $model->sendOrderEmail();
      $data = Yii::$app->MyFunctions->getUserorderObject($model);
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
    }
    Yii::$app->MyFunctions->getModelErrors($model, "Y");
  }

  //11
  public function actionGetuserorder()
  {
    global $user;
    $data = [];

    $query = Userorder::find()->Where(['appuser_id' => $user->appuser_id])->orderBy(['created_at' => SORT_DESC])->all();
    $data = [];
    if (!empty($query)) {
      foreach ($query as $key => $news_catagory) {
        $data[$key] = Yii::$app->MyFunctions->getUserorderObject($news_catagory);
      }
    }

    Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'data' => $data));
  }

  // 3
  //Update Profile
  public function actionUpdateprofile()
  {
    global $user;
    $user->scenario = 'apiupdateprofile';
    $user->load($_REQUEST);

    if (!empty($_FILES) && is_object(UploadedFile::getInstance($user, 'image'))) {
      $user->image = UploadedFile::getInstance($user, 'image');
      $user->upload();
    }

    if ($user->validate() && $user->save()) {
      $headers = Yii::$app->request->headers;
      $authorizationHeader = $headers->get('auth_key');
      $UserDevice = Yii::$app->MyFunctions->getDeviceinfo($authorizationHeader);
      $data = Yii::$app->MyFunctions->getUserObject($user, $UserDevice);
      $message = Yii::t('app', 'Profile updated successfully.');
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message, 'data' => $data));
    }
    Yii::$app->MyFunctions->getModelErrors($user, "Y");
  }

  //4
  public function actionChangepassword()
  {
    global $user;

    if (sha1($_REQUEST['password']) == $user->password && !empty($_REQUEST['new_password'])) {
      $user->password = sha1($_REQUEST['new_password']);
      //$user->password=$hasher->HashPassword($_REQUEST['new_password']);
      $user->save();
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => Yii::t('app', 'Your password has been changed')));
    } else {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'The old password you have entered is incorrect, Please enter current password')));
    }
  }

  //42
  public function actionDeleteaccount()
  {
    global $user;

    $user->status = 'Inactive';
    $user->is_deleted = 'Yes';

    if ($user->validate() && $user->save()) {

      $message = Yii::t('app', 'Your Account Deleted successfully.');
      Yii::$app->MyFunctions->JsonPrint(array('status' => 1, 'message' => $message));
    }
    Yii::$app->MyFunctions->getModelErrors($user, "Y");
  }
}
