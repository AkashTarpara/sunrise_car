<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin_user".
 *
 * @property string $admin_id
 * @property string $first_name
 * @property string $last_name
 * @property string $user_name
 * @property string $email
 * @property string $password
 * @property string $profile_pic
 * @property string $contact_no
 * @property string $roles
 * @property string $is_status
 * @property string $time
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $created_at
 * @property string $updated_at
 */
class Adminuser extends \yii\db\ActiveRecord  implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'user_name', 'email', 'password'], 'required'],
            [['roles', 'is_status'], 'string'],
            [['time'], 'integer'],
            [['created_at', 'updated_at','auth_key','time','roles','profile_pic','time_zone','site_logo'], 'safe'],
            [['first_name', 'last_name', 'password'], 'string', 'max' => 50],
            [['user_name', 'email'], 'string', 'max' => 100],
            [['profile_pic', 'site_logo'], 'string', 'max' => 255],
            [['contact_no'], 'string', 'max' => 20],
            [['auth_key', 'password_reset_token'], 'string', 'max' => 35],
            [['user_name'], 'unique'],
            [['email'], 'unique'],
            ['is_status','default','value'=>'Active'],
            ['time','default','value'=>0],
            ['roles','default','value'=>'ADMIN'],
            ['auth_key','default','value'=>Yii::$app->MyFunctions->random_string(32)],
            [['updated_at','created_at'],'default','value'=>date('Y-m-d H:i:s')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'user_name' => 'User Name',
            'email' => 'Email',
            'password' => 'Password',
            'profile_pic' => 'Profile Pic',
            'site_logo' => 'Site Logo',
            'contact_no' => 'Contact No',
            'roles' => 'Roles',
            'is_status' => 'Is Status',
            'time' => 'Time',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'time_zone' => 'Time Zone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    /*Upload image*/
    public function upload()
    {
       if(is_object($this->profile_pic)){
            
            if(file_exists(Yii::getAlias("@app")."/".$this->getOldAttribute('profile_pic')) && !empty($this->getOldAttribute('profile_pic'))){
                unlink(Yii::getAlias("@app")."/".$this->getOldAttribute('profile_pic'));    
            }

            $path = 'uploads/images/adminimage/profilepic/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->profile_pic->saveAs($path . $name . '.' . $this->profile_pic->extension);
            $this->profile_pic = $path . $name . '.' . $this->profile_pic->extension;
        }

        if(is_object($this->site_logo)){
            
            if(file_exists(Yii::getAlias("@app")."/".$this->getOldAttribute('site_logo')) && !empty($this->getOldAttribute('site_logo'))){
                unlink(Yii::getAlias("@app")."/".$this->getOldAttribute('site_logo'));    
            }

            $path = 'uploads/images/adminimage/sitelogo/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->site_logo->saveAs($path . $name . '.' . $this->site_logo->extension);
            $this->site_logo = $path . $name . '.' . $this->site_logo->extension;
        }
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
        $this->password_reset_token = null;
    }
}
