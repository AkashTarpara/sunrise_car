<?php

namespace app\models;

use Yii;

class FleetImage extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'fleet_image';
    }

    public function rules()
    {
        return [
            [['fleet_id', 'image'], 'required'],
            [['fleet_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['fleet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fleet::class, 'targetAttribute' => ['fleet_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fleet_id' => Yii::t('app', 'Fleet ID'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public function getFleet()
    {
        return $this->hasOne(Fleet::class, ['id' => 'fleet_id']);
    }

    public function softDelete()
    {
        $this->deleted_at = date('Y-m-d H:i:s');
        return $this->save(false, ['deleted_at', 'updated_at']);
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
