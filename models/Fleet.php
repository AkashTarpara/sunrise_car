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
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'images'], 'safe'],
            [['label', 'name', 'passenger'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => ['Active', 'Inactive']],
            [['type'], 'in', 'range' => array_keys(self::typeOptions())],
            [['status'], 'default', 'value' => 'Active'],
            [['images'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif'], 'maxFiles' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Label'),
            'name' => Yii::t('app', 'Name'),
            'passenger' => Yii::t('app', 'Passenger'),
            'laggage' => Yii::t('app', 'Laggage'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
            'images' => Yii::t('app', 'Images'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
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
}
