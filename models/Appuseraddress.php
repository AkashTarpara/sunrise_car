<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appuser_address".
 *
 * @property int $appuser_address_id
 * @property int $appuser_id
 * @property string $name
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $postal_code
 * @property string $mobile_number
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Appuser $appuser
 */
class Appuseraddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appuser_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appuser_id', 'name', 'address_line_1', 'postal_code', 'mobile_number'], 'required'],
            [['appuser_id'], 'integer'],
            [['address_line_1', 'address_line_2', 'is_default'], 'string'],
            [['created_at', 'updated_at', 'city', 'state', 'address_line_2', 'latitude', 'longitude'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['is_default'], 'default', 'value' => 'No'],
            [['city', 'state', 'address_line_2', 'latitude', 'longitude'], 'default', 'value' => ''],
            [['name', 'postal_code', 'mobile_number', 'city', 'state', 'latitude', 'longitude'], 'string', 'max' => 255],
            [['appuser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appuser::class, 'targetAttribute' => ['appuser_id' => 'appuser_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'appuser_address_id' => Yii::t('app', 'Appuser Address ID'),
            'appuser_id' => Yii::t('app', 'Appuser ID'),
            'name' => Yii::t('app', 'Name'),
            'address_line_1' => Yii::t('app', 'Address Line 1'),
            'address_line_2' => Yii::t('app', 'Address Line 2'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'mobile_number' => Yii::t('app', 'Mobile Number'),
            'is_default' => Yii::t('app', 'Is Default'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Appuser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppuser()
    {
        return $this->hasOne(Appuser::class, ['appuser_id' => 'appuser_id']);
    }

    public function beforeSave($insert)
    {
        //echo "<pre>"; print_r($this->fleet_id); exit;
        if (parent::beforeSave($insert)) {

            if (!$this->isNewRecord) {
                $this->updated_at = date('Y-m-d H:i:s');
                //$this->is_publish=($this->is_schedule==true)?'No':'Yes';
            }
            return true;
        } else {
            return false;
        }
    }
}
