<?php

namespace app\models;

use Yii;

class Fleet extends \yii\db\ActiveRecord
{
    public $images;

    public static function tableName()
    {
        return 'fleet';
    }

    public static function typeOptions()
    {
        return [
            'SEDANS' => 'SEDANS',
            'SUVS' => 'SUVS',
            'LIMOUSINES' => 'LIMOUSINES',
            'SPRINTERS' => 'SPRINTERS',
            'VANS' => 'VANS',
            'BUSES' => 'BUSES',
            'MOTORCYCLES' => 'MOTORCYCLES',
            'TRUCKS' => 'TRUCKS',
        ];
    }

    public function rules()
    {
        return [
            [['label', 'name', 'type'], 'required'],
            [['laggage'], 'integer'],
            [['base_price', 'km_per_hour_price'], 'number', 'min' => 0],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'images'], 'safe'],
            [['label', 'name', 'passenger'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => ['Active', 'Inactive']],
            [['type'], 'in', 'range' => array_keys(self::typeOptions())],
            [['status'], 'default', 'value' => 'Active'],
            [['base_price'], 'default', 'value' => 100],
            [['km_per_hour_price'], 'default', 'value' => 24],
            [['images'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif'], 'maxFiles' => 20],
            // Availability
            [['is_available'], 'boolean'],
            [['is_available'], 'default', 'value' => 1],
            [['available_after'], 'date', 'format' => 'php:Y-m-d'],
            [['available_after'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('app', 'ID'),
            'label'            => Yii::t('app', 'Label'),
            'name'             => Yii::t('app', 'Name'),
            'passenger'        => Yii::t('app', 'Passenger'),
            'laggage'          => Yii::t('app', 'Laggage'),
            'base_price'       => Yii::t('app', 'Base Price'),
            'km_per_hour_price'=> Yii::t('app', 'Km Per Hour Price'),
            'description'      => Yii::t('app', 'Description'),
            'status'           => Yii::t('app', 'Status'),
            'type'             => Yii::t('app', 'Type'),
            'images'           => Yii::t('app', 'Images'),
            'is_available'     => Yii::t('app', 'Is Available'),
            'available_after'  => Yii::t('app', 'Available After'),
            'created_at'       => Yii::t('app', 'Created At'),
            'updated_at'       => Yii::t('app', 'Updated At'),
            'deleted_at'       => Yii::t('app', 'Deleted At'),
        ];
    }

    public function getFleetImages()
    {
        return $this->hasMany(FleetImage::class, ['fleet_id' => 'id'])
            ->andWhere(['deleted_at' => null]);
    }

    public function uploadImages()
    {
        if (empty($this->images) || !is_array($this->images)) {
            return true;
        }

        $path = 'uploads/images/fleet/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }

        foreach ($this->images as $image) {
            if (!$image) {
                continue;
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $filePath = $path . $name . '.' . $image->extension;
            if ($image->saveAs($filePath)) {
                $fleetImage = new FleetImage();
                $fleetImage->fleet_id = $this->id;
                $fleetImage->image = $filePath;
                $fleetImage->save(false);
            }
        }

        return true;
    }

    public function softDelete()
    {
        $this->deleted_at = date('Y-m-d H:i:s');
        if ($this->save(false, ['deleted_at', 'updated_at'])) {
            FleetImage::updateAll(
                ['deleted_at' => date('Y-m-d H:i:s')],
                ['fleet_id' => $this->id, 'deleted_at' => null]
            );
            return true;
        }

        return false;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert && empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }

        if (!$insert) {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return true;
    }

    /**
     * Recalculates available_after based on the latest confirmed+paid booking
     * for this fleet. Called automatically after a booking is confirmed/cancelled.
     *
     * Logic:
     *  - Find the latest pickup_date among all confirmed (booking_status=confirmed,
     *    payment_status=paid) bookings that have not been deleted.
     *  - If found, set available_after = that pickup_date.
     *  - If no active confirmed bookings exist, clear available_after (null).
     */
    public function recalculateAvailableAfter()
    {
        $latestDate = (new \yii\db\Query())
            ->select(['MAX(pickup_date) AS max_date'])
            ->from('booking')
            ->where([
                'fleet_id'       => $this->id,
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'deleted_at'     => null,
            ])
            ->scalar();

        $this->available_after = !empty($latestDate) ? $latestDate : null;
        $this->save(false, ['available_after', 'updated_at']);
    }

    /**
     * Check whether this fleet is available for a given date (Y-m-d).
     * Checks:
     *  1. Admin manual toggle (is_available = 1)
     *  2. available_after is null OR the requested date is AFTER available_after
     *  3. No active confirmed booking exists for that exact date
     */
    public function isAvailableForDate($date)
    {
        // 1. Admin has manually disabled this vehicle
        if (!$this->is_available) {
            return false;
        }

        // 2. Fleet is still within its last booked date window
        if (!empty($this->available_after) && $date <= $this->available_after) {
            return false;
        }

        // 3. Live check: does a confirmed booking already exist for this exact date?
        $exists = (new \yii\db\Query())
            ->from('booking')
            ->where([
                'fleet_id'       => $this->id,
                'pickup_date'    => $date,
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'deleted_at'     => null,
            ])
            ->exists();

        return !$exists;
    }
}
