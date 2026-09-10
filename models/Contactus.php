<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_us".
 *
 * @property int $contact_us_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
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
            [['first_name', 'last_name', 'email', 'message'], 'required'],
            [['message', 'address'], 'string'],
            [['created_at', 'number', 'address'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['number', 'address'], 'default', 'value' => ''],
            [['first_name', 'last_name', 'email', 'number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contact_us_id' => Yii::t('app', 'Contact Us ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'number' => Yii::t('app', 'Number'),
            'address' => Yii::t('app', 'Address'),
            'message' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
