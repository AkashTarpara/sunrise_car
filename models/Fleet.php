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
            [['laggage', 'minimum_hours'], 'integer'],
            [['minimum_hours'], 'default', 'value' => 1],
            [['minimum_hours'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'type' => 'number'],
            [['base_price', 'km_per_hour_price', 'hourly_price'], 'number', 'min' => 0],
            [['hourly_price'], 'default', 'value' => 0.00],
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
            'hourly_price'     => Yii::t('app', 'Hourly Price'),
            'minimum_hours'    => Yii::t('app', 'Minimum Hours'),
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

    /**
     * Calculate ride charge for this fleet based on distance or hourly duration.
     *
     * @param string $service 'distance' or 'hourly'
     * @param float|null $miles
     * @param float|null $hours
     * @return array
     */
    public function calculateCharge($service = 'distance', $miles = null, $hours = null)
    {
        $basePrice    = round((float) $this->base_price, 2);
        $pricePerMile = round((float) $this->km_per_hour_price, 4);
        $hourlyRate   = round((float) $this->hourly_price, 2);
        $minHours     = max(1, (int) $this->minimum_hours);

        if ($service === 'hourly' || ($hours !== null && (float)$hours > 0)) {
            $requestedHours = round((float) $hours, 2);
            $billedHours    = max($requestedHours, $minHours);
            $hourlyCharge   = round($billedHours * $hourlyRate, 2);
            $totalCharge    = max($basePrice, $hourlyCharge);

            return [
                'service'         => 'hourly',
                'requested_hours' => $requestedHours,
                'minimum_hours'   => $minHours,
                'billed_hours'    => $billedHours,
                'hourly_price'    => $hourlyRate,
                'hourly_charge'   => $hourlyCharge,
                'base_price'      => $basePrice,
                'total_charge'    => $totalCharge,
                'charge_basis'    => ($basePrice >= $hourlyCharge) ? 'base_price' : 'hourly',
                'meets_min_hours' => ($requestedHours >= $minHours),
            ];
        }

        if ($miles !== null && (float)$miles > 0) {
            $mileCharge  = round((float)$miles * $pricePerMile, 2);
            $totalCharge = max($basePrice, $mileCharge);
            return [
                'service'        => 'distance',
                'miles'          => round((float)$miles, 2),
                'price_per_mile' => $pricePerMile,
                'miles_charge'   => $mileCharge,
                'base_price'     => $basePrice,
                'total_charge'   => $totalCharge,
                'charge_basis'   => ($basePrice >= $mileCharge) ? 'base_price' : 'miles',
            ];
        }

        return [
            'service'      => 'base',
            'base_price'   => $basePrice,
            'total_charge' => $basePrice,
            'charge_basis' => 'base_price',
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
