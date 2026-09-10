<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appuser_devices_info".
 *
 * @property int $appuser_devices_info_id
 * @property int $appuser_id
 * @property string $devices_type
 * @property string $devices_token
 * @property string $devices_name
 * @property string $devices_id
 * @property string $auth_key
 * @property string $app_version
 * @property string $location
 * @property string $latitude
 * @property string $longitude
 * @property string $created_at
 *
 * @property Appuser $appuser
 */
class Appuserdevicesinfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appuser_devices_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['devices_name', 'devices_id', 'auth_key', 'app_version'], 'required'],

            [['appuser_id'], 'integer'],

            [['devices_type', 'devices_token'], 'string'],

            [['latitude', 'longitude', 'location', 'created_at'], 'safe'],

            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],

            [['devices_name', 'devices_id', 'auth_key'], 'string', 'max' => 255],

            [['app_version'], 'string', 'max' => 10],

            [['location'], 'string', 'max' => 500],

            [['latitude', 'longitude'], 'string', 'max' => 100],

            [['appuser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appuser::className(), 'targetAttribute' => ['appuser_id' => 'appuser_id']],

            [['latitude', 'longitude', 'location', 'devices_token'], 'default', 'value' => ''],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'appuser_devices_info_id' => Yii::t('app', 'Appuser Devices Info ID'),
            'appuser_id' => Yii::t('app', 'Appuser ID'),
            'devices_type' => Yii::t('app', 'Devices Type'),
            'devices_token' => Yii::t('app', 'Devices Token'),
            'devices_name' => Yii::t('app', 'Devices Name'),
            'devices_id' => Yii::t('app', 'Devices ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'app_version' => Yii::t('app', 'App Version'),
            'location' => Yii::t('app', 'Location'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppuser()
    {
        return $this->hasOne(Appuser::className(), ['appuser_id' => 'appuser_id']);
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = date('Y-m-d H:i:s');
            return true;
        } else {
            return false;
        }
    }
}
