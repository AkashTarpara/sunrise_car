<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_us".
 *
 * @property int $contact_us_id
 * @property string $full_name
 * @property string $email
 * @property string $phone_number
 * @property string $subject
 * @property string $message
 * @property string $created_at
 */
class Contactus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact_us';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'phone_number', 'subject', 'message'], 'required'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            ['email', 'email'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['full_name', 'email', 'phone_number', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contact_us_id' => Yii::t('app', 'Contact Us ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
