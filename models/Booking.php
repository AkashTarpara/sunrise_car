<?php

namespace app\models;

use Yii;

class Booking extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'booking';
    }

    public function rules()
    {
        return [
            [['booking_number', 'fleet_id', 'pickup_date', 'pickup_time', 'ride_data', 'quote_data', 'passenger_data'], 'required'],
            [['fleet_id'], 'integer'],
            [['pickup_date'], 'date', 'format' => 'php:Y-m-d'],
            [['pickup_time'], 'time', 'format' => 'php:H:i'],
            [['ride_data', 'quote_data', 'extras', 'passenger_data', 'notes', 'pickup', 'dropoff'], 'safe'],
            [['terms_accepted'], 'boolean'],
            [['base_price', 'km_per_hour_price', 'distance_price', 'total'], 'number', 'min' => 0],
            [['booking_number'], 'string', 'max' => 32],
            [['service', 'pickup_location_type', 'dropoff_location_type'], 'string', 'max' => 100],
            [['promo_code'], 'string', 'max' => 100],
            [['payment_preference', 'payment_status', 'booking_status'], 'string', 'max' => 50],
            [['currency'], 'string', 'length' => 3],
            [['payment_intent_id'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['fleet_id'], 'exist', 'targetClass' => Fleet::class, 'targetAttribute' => ['fleet_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'booking_number' => Yii::t('app', 'Booking Number'),
            'fleet_id' => Yii::t('app', 'Vehicle'),
            'pickup_date' => Yii::t('app', 'Pickup Date'),
            'pickup_time' => Yii::t('app', 'Pickup Time'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'booking_status' => Yii::t('app', 'Booking Status'),
            'total' => Yii::t('app', 'Total'),
        ];
    }

    public function getFleet()
    {
        return $this->hasOne(Fleet::class, ['id' => 'fleet_id']);
    }

    public function beforeValidate()
    {
        if (static::class === self::class && $this->isNewRecord && empty($this->booking_number)) {
            $this->booking_number = 'SUN-' . date('ymdHis') . '-' . strtoupper(substr(uniqid(), -4));
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert && empty($this->booking_number)) {
            $this->booking_number = 'SUN-' . date('ymdHis') . '-' . strtoupper(substr(uniqid(), -4));
        }
        if (!$insert) {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return true;
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Trigger fleet availability recalculation whenever booking_status or payment_status changes
        $statusChanged = isset($changedAttributes['booking_status']) || isset($changedAttributes['payment_status']);
        $isConfirmed   = ($this->booking_status === 'confirmed' && $this->payment_status === 'paid');
        $isCancelled   = ($this->booking_status === 'cancelled');

        if ($statusChanged && ($isConfirmed || $isCancelled)) {
            $fleet = Fleet::findOne(['id' => $this->fleet_id, 'deleted_at' => null]);
            if ($fleet) {
                $fleet->recalculateAvailableAfter();
            }
        }
    }
}
