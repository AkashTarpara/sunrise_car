<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "appuser".
 *
 * @property int $appuser_id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property int $phone_code Country Phone Code
 * @property string|null $phone_number
 * @property string $phone_verify
 * @property string $otp
 * @property string $email
 * @property string $email_verify_code Email Verification Code
 * @property string $email_verify
 * @property string|null $birth_date
 * @property string $image
 * @property string $login_type
 * @property string $password
 * @property string $password_reset_token
 * @property int|null $password_reset_token_time
 * @property int|null $email_verify_time
 * @property string $lang_code
 * @property string $status
 * @property string $is_deleted
 * @property string $gender
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $user_type
 * @property string $updated_at
 * @property string $created_at
 *
 * @property AppuserDevicesInfo[] $appuserDevicesInfos
 */
class Appuser extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public $devices_type, $devices_token, $devices_name, $devices_id, $app_version, $confirm_password, $os, $player_id;
    public $rols = [];

    public static function tableName()
    {
        return 'appuser';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            //[['first_name', 'last_name', 'otp', 'email_verify_code', 'image', 'password_reset_token', 'address', 'latitude', 'longitude','updated_at', 'created_at'], 'required'],


            [['first_name', 'last_name', 'email', 'password', 'postal_code', 'phone_number', 'devices_type', 'devices_name', 'devices_id', 'app_version'], 'required', 'on' => 'usersignup'],

            [['first_name', 'last_name'], 'required', 'on' => 'apiupdateprofile'],

            [['password'], 'required', 'on' => 'resetpassword'],

            [['email', 'login_type', 'email', 'login_type', 'phone_verify'], 'required', 'on' => 'websignup'],

            //[['email'],'required','on'=>'apiupdateprofile'],

            [['email'], 'unique', 'on' => ['usersignup', 'apiupdateprofile', 'userregister', 'normalusersignup', 'createadmin'], 'filter' => ['<>', 'is_deleted', 'Yes']],

            //[['email'], 'unique'],
            //[['phone_number'],'default','value'=>"",'on'=>['apiupdateprofile']],

            [['email'], 'unique', 'on' => ['usersignup', 'apiupdateprofile', 'normalusersignup', 'createadmin'], 'filter' => ['<>', 'is_deleted', 'Yes']],

            [['devices_type', 'devices_name', 'devices_id', 'app_version'], 'required', 'on' => ['autologin']],

            [['email', 'password', 'devices_type', 'devices_name', 'devices_id', 'app_version'], 'required', 'on' => ['login']],

            [['email_verify_code', 'email_verify_token', 'devices_type', 'devices_name', 'devices_id', 'app_version', 'os'], 'required', 'on' => ['otpverify']],

            [['login_type', 'devices_type', 'devices_name', 'devices_id', 'app_version', 'first_name', 'last_name', 'os'], 'required', 'on' => ['sociallogin']],

            [['email'], 'required', 'on' => ['forgotpassword']],

            ['email', 'required', 'when' => function ($model) {
                return $model->login_type == 'Normal';
            }],
            [['password'], 'required', 'when' => function ($model) {
                return $model->login_type == 'Normal';
            }],

            [['phone_code', 'password_reset_token_time', 'email_verify_time', 'city_id', 'country_id'], 'integer'],

            [['phone_verify', 'email_verify', 'login_type', 'status', 'is_deleted', 'gender', 'address', 'user_type', 'signup_type'], 'string'],

            [['birth_date', 'updated_at', 'created_at', 'first_name', 'last_name', 'otp', 'email_verify_code', 'image', 'password_reset_token', 'address', 'latitude', 'longitude', 'role', 'email_token', 'email_verify_token', 'auth_key', 'site_logo', 'player_id', 'rols', 'postal_code'], 'safe'],

            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],

            [['first_name', 'last_name', 'otp', 'email', 'email_verify_code', 'image', 'password_reset_token', 'latitude', 'longitude', 'role', 'email_token', 'email_verify_token', 'auth_key', 'site_logo', 'postal_code'], 'string', 'max' => 255],

            [['birth_date', 'updated_at', 'created_at', 'first_name', 'last_name', 'otp', 'email_verify_code', 'image', 'password_reset_token', 'address', 'latitude', 'longitude', 'role', 'email_token', 'full_name', 'email_verify_token', 'site_logo', 'postal_code'], 'default', 'value' => ''],

            [['full_name'], 'string', 'max' => 150],

            [['phone_number'], 'string', 'max' => 20],

            [['password'], 'string', 'max' => 300],

            [['lang_code'], 'string', 'max' => 100],

            [['email_verify', 'phone_verify', 'is_deleted'], 'default', 'value' => 'No'],

            [['phone_number'], 'default', 'value' => ""],

            [['phone_code'], 'default', 'value' => 0],

            ['auth_key', 'default', 'value' => Yii::$app->MyFunctions->random_string(32)],

            [['status'], 'default', 'value' => 'Active'],

            //[['image'], 'default', 'value' => 'Yes'],

            [['phone_verify'], 'default', 'value' => 'Yes'],

            [['lang_code'], 'default', 'value' => 'en'],

            [['birth_date'], 'date', 'format' => 'php:Y-m-d'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'appuser_id' => Yii::t('app', 'Appuser ID'),
            'country_id' => Yii::t('app', 'Country Name'),
            'city_id' => Yii::t('app', 'City Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'full_name' => Yii::t('app', 'Full Name'),
            'phone_code' => Yii::t('app', 'Phone Code'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'phone_verify' => Yii::t('app', 'Phone Verify'),
            'otp' => Yii::t('app', 'Otp'),
            'email' => Yii::t('app', 'Email'),
            'email_verify_code' => Yii::t('app', 'Email Verify Code'),
            'email_verify' => Yii::t('app', 'Email Verify'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'image' => Yii::t('app', 'Image'),
            'login_type' => Yii::t('app', 'Login Type'),
            'password' => Yii::t('app', 'Password'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'password_reset_token_time' => Yii::t('app', 'Password Reset Token Time'),
            'email_verify_time' => Yii::t('app', 'Email Verify Time'),
            'lang_code' => Yii::t('app', 'Lang Code'),
            'status' => Yii::t('app', 'Status'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'gender' => Yii::t('app', 'Gender'),
            'address' => Yii::t('app', 'Address'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'site_logo' => Yii::t('app', 'Site Logo'),
            'user_type' => Yii::t('app', 'User Type'),
            'rols' => Yii::t('app', 'Rols'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findByPasswordResetToken($token)
    {
        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }

    public function setPassword($password)
    {
        $this->password = sha1($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Security::generateRandomKey();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = "";
    }

    /**
     * Gets query for [[AppuserDevicesInfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppuserDevicesInfos()
    {
        return $this->hasMany(Appuserdevicesinfo::class, ['appuser_id' => 'appuser_id']);
    }

    public function upload()
    {
        if (is_object($this->image)) {
            if ($this->getOldAttribute('image') != "uploads/default/default_user.png") {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                    unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
                }
            }

            $path = 'uploads/images/user/image/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(50);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }

        if (is_object($this->site_logo)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('site_logo')) && !empty($this->getOldAttribute('site_logo'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('site_logo'));
            }

            $path = 'uploads/images/user/sitelogo/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->site_logo->saveAs($path . $name . '.' . $this->site_logo->extension);
            $this->site_logo = $path . $name . '.' . $this->site_logo->extension;
        }
    }

    public function sendPasswordResetLink()
    {

        $this->generatePasswordResetToken();
        $this->save();
        if ($this->password_reset_token != "" || !empty($this->password_reset_token)) {
            try {
                Yii::$app->mailer->htmlLayout = "@app/mail/layouts/htmlnew";
                return Yii::$app->mailer->compose(['html' => 'userforgotpassword'], ['user' => $this, 'from' => 'user'])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['project_display_name']])
                    ->setTo($this->email)
                    ->setSubject('Forgot Password')
                    ->send();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
    public function sendWelcomeMail()
    {

        try {
            Yii::$app->mailer->htmlLayout = "@app/mail/layouts/htmlnew";
            return Yii::$app->mailer->compose(['html' => 'userwelcome'], ['user' => $this, 'from' => 'user'])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['project_display_name']])
                ->setTo($this->email)
                ->setSubject('Welcome')
                ->send();
        } catch (Exception $e) {
            return false;
        }
    }


    public function IsActive()
    {
        if ($this->status == 'Active') {
            $status = "Activated";
            $class = "btn btn-sm btn-success";
            $title = "Click here to deactivated";
        } else {
            $status = "Deactivated";
            $class = "btn btn-sm btn-danger";
            $title = "Click here to activated";
        }
        return Html::a($status, Yii::$app->urlManager->createUrl(['appuser/changestatus', 'id' => Yii::$app->MyFunctions->encode($this->appuser_id)]), ['class' => $class, "data-toggle" => "tooltip", "title" => $title]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {

                $this->password = (!empty($this->password)) ? sha1($this->password) : '';
                //$this->email_token = Yii::$app->security->generateRandomString();
                //$this->phone_verify = 'Yes';
            }
            $this->full_name = (!empty($this->last_name)) ? $this->first_name . ' ' . $this->last_name : $this->first_name;
            $this->updated_at = date('Y-m-d H:i:s');

            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            if ($this->getOldAttribute('image') != "uploads/default/default_user.png") {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->image) && !empty($this->getOldAttribute('image'))) {
                    unlink(Yii::getAlias("@app") . "/" . $this->image);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
