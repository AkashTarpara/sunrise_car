<?php

namespace app\models;

use app\models\Adminuser;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class PasswordForm extends Model {

    public $password;
    public $old_password;
    public $password_repeat;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    /*public function __construct($token, $from, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        
        $this->_user = Appuser::find()->where(['password_reset_token'=>$token])->one();    
        
        if (!$this->_user) {
            //Yii::$app->session->setFlash('error', 'Invalid link please try again.');
            //return $this->render('successpassword');
            //return false;
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }*/

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['password', 'password_repeat','old_password'], 'required', 'on' => 'changepassword'],
            [['password', 'password_repeat'], 'required', 'on' => 'forrgotpassword'],
            [['password'], 'string', 'min' => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
        ];
    }
    public function attributeLabels()
    {
        return [
            'password' => 'Password',
            'password_repeat' => 'Confirm Password',
        ];
    }
    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword() {
        $user = $this->_user;
        $user->password = sha1($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }

}
