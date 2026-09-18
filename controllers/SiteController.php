<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\models\LoginForm;
use app\models\PasswordForm;
use app\models\Appuser;
use app\models\LoginFormUser;
use app\models\Appuserissignup;
use DateTime;
use DatePeriod;
use DateInterval;
use yii\web\UploadedFile;
use yii\db\Query;
use Aws\S3\S3Client;
use yii\helpers\Json;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        /*echo "hello";
        exit;*/

        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'error', 'test', 'resetpassword', 'userlogin', 'userdelete', 'mobiledetect', 'mobileapp'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ],*/
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    //Error Page Display
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if (Yii::$app->user->isGuest) {
            $this->layout = "blank";
        }

        if ($exception !== null) {
            $this->layout = "blank";
            return $this->render('error', ['exception' => $exception]);
        }
    }


    public function actionTest()
    {
        $this->layout = "login";
        return $this->render('login');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEditorimage()
    {
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["image_param"]["name"]);
        $extension = end($temp);

        // Validate the file extension.
        if (!in_array($extension, $allowedExts)) {
            Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => "Invalid file extension."));
            return;
        }

        // Get MIME type.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["image_param"]["tmp_name"]);
        finfo_close($finfo);

        // Validate the MIME type.
        $allowedMimeTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png");
        if (!in_array($mime, $allowedMimeTypes)) {
            Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => "Invalid MIME type."));
            return;
        }

        // Generate new random name.
        $name = sha1(microtime()) . "." . $extension;
        $path = 'uploads/editorimage/';

        // Create the uploads/editorimage directory if it doesn't exist.
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }

        // Save file in the uploads/editorimage folder.
        if (move_uploaded_file($_FILES["image_param"]["tmp_name"], $path . $name)) {
            // Generate response.
            $response = (object)array();
            $response->link = Yii::$app->params['ImagePath'] . $path . $name;
            echo stripslashes(json_encode($response));
        } else {
            Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => "Failed to upload the image."));
        }
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = "login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        //echo "<pre>"; print_r(Yii::$app->request->post()); exit;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->set('plat_form_type', 'Web');
            return $this->redirect(['index']);
            //return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSetplatformtype()
    {
        $type = (isset($_REQUEST['type']) && $_REQUEST['type']) ? $_REQUEST['type'] : '';
        Yii::$app->session->set('plat_form_type', $type);
        return $this->redirect(['index']);
    }
    //Reset Password
    public function actionResetpassword()
    {
        $this->layout = "login";
        $model = new PasswordForm();
        $model->scenario = "forrgotpassword";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user = Appuser::find()->where(['password_reset_token' => $_REQUEST['token']])->one();
            if ($user != null) {
                $user->password = sha1($model['password']);
                $user->password_reset_token = "";
                $user->save();
                Yii::$app->session->setFlash('success', 'You have successfully reset your password.');
                return $this->redirect(Yii::$app->params['web_site_url']);
                //return $this->render('successpassword');  
            }
            return $this->render('resetPassword', ['model' => $model, 'modeluser' => $user]);
        }


        if (!empty($_REQUEST['token'])) {
            $user = Appuser::find()->where(['password_reset_token' => $_REQUEST['token']])->one();
            if ($user != null) {
                return $this->render('resetPassword', ['model' => $model, 'modeluser' => $user]);
            } else {
                Yii::$app->session->setFlash('error', 'Your reset password url is expired.');
                return $this->redirect(Yii::$app->params['web_site_url']);
                //return $this->render('successpassword');
                exit;
            }
        }

        return $this->render('resetPassword', ['model' => $model, 'modeluser' => $user]);
    }

    public function actionUserlogin()
    {
        $this->layout = "login";


        $model = new LoginFormUser();
        if ($model->load(Yii::$app->request->post())) {
            $Model = Appuser::find()
                ->where(['email' => $model->email, "login_type" => "Normal", 'role' => '3'])
                ->orderBy(['appuser_id' => SORT_DESC])
                ->one();

            if (!empty($Model)) {
                if (sha1($model->password) != $Model->password) {
                    $model->password = '';
                    Yii::$app->session->setFlash('error', 'Invalid email or password please try again');
                    return $this->render('userlogin', [
                        'model' => $model,
                    ]);
                }
                return $this->render('userdelete', [
                    'model' => $Model,
                ]);
            }

            Yii::$app->session->setFlash('error', 'Invalid email or password please try again');
            $model->password = '';
            return $this->render('userlogin', [
                'model' => $model,
            ]);
            //return $this->redirect(['userdelete']);
            //return $this->goBack();
        }
        $model->password = '';
        return $this->render('userlogin', [
            'model' => $model,
        ]);
    }

    public function actionUserdelete()
    {
        $this->layout = "login";
        $Model = Appuser::find()->where(['appuser_id' => $_REQUEST['id']])->one();
        if (!empty($Model)) {
            $Model->status = 'Inactive';
            $Model->is_deleted = 'Yes';

            if ($Model->validate() && $Model->save()) {
                return $this->render('userdelete', [
                    'model' => $Model,
                ]);
            }
        }
        return $this->redirect(['userlogin']);
    }

    public function actionMobiledetect()
    {
        $this->layout = "login";
        return $this->render('mobiledetect');
    }

    public function actionMobileapp()
    {
        //echo "hello"; exit;
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");
        if ($iPod || $iPhone) {

            return $this->redirect('https://apps.apple.com/app/premier-padel-official-app/id6504236153');
        } else if ($iPad) { ?>
            <div style="height: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <h2 style="font-family: sans-serif;">DOWNLOAD THE APP</h2>
                <div class="appBtns fadeInDown" data-wow-delay="0.5s">

                    <a href="https://apps.apple.com/app/premier-padel-official-app/id6504236153" onclick="fbq('track', 'Lead');" class="download-btn-call" target="_blank" title="Download app" style="padding-right: 5px;"><img src="<?= Yii::$app->params['domain'] ?>uploads/default/stock_app_store.svg?>"></a>

                    <a href="https://play.google.com/store/apps/details?id=com.premierpadel" onclick="fbq('track', 'Lead');" class="download-btn-call" target="_blank" style="padding-left: 5px;"><img src="<?= Yii::$app->params['domain'] ?>uploads/default/stock_play_store.svg?>" title="Download app"></a>
                </div>
            </div>
        <?php
        } else if ($Android) {

            return $this->redirect('https://play.google.com/store/apps/details?id=com.premierpadel');
        } else if ($webOS) { ?>
            <div style="height: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <h2 style="font-family: sans-serif;">DOWNLOAD THE APP</h2>
                <div class="appBtns fadeInDown" data-wow-delay="0.5s">

                    <a href="https://apps.apple.com/app/premier-padel-official-app/id6504236153" onclick="fbq('track', 'Lead');" class="download-btn-call" target="_blank" title="Download app" style="padding-right: 5px;"><img src="<?= Yii::$app->params['domain'] ?>uploads/default/stock_app_store.svg?>"></a>

                    <a href="https://play.google.com/store/apps/details?id=com.premierpadel" onclick="fbq('track', 'Lead');" class="download-btn-call" target="_blank" style="padding-left: 5px;"><img src="<?= Yii::$app->params['domain'] ?>uploads/default/stock_play_store.svg?>" title="Download app"></a>
                </div>
            </div>
        <?php
        } else {   ?>
            <div style="height: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <h2 style="font-family: sans-serif;">DOWNLOAD THE APP</h2>
                <div class="appBtns fadeInDown" data-wow-delay="0.5s">

                    <a href="https://apps.apple.com/app/premier-padel-official-app/id6504236153" onclick="fbq('track', 'Lead');" class="download-btn-call" target="_blank" title="Download app" style="padding-right: 5px;"><img src="<?= Yii::$app->params['domain'] ?>uploads/default/stock_app_store.svg?>"></a>

                    <a href="https://play.google.com/store/apps/details?id=com.premierpadel" onclick="fbq('track', 'Lead');" class="download-btn-call" target="_blank" style="padding-left: 5px;"><img src="<?= Yii::$app->params['domain'] ?>uploads/default/stock_play_store.svg?>" title="Download app"></a>

                </div>
            </div>
<?php }
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
