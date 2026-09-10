<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;

class Event extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'event';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'category', 'event_date', 'event_time', 'location'], 'required'],
            [['event_date', 'event_time', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['title', 'category', 'location', 'image', 'slug'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => ['Active', 'Inactive']],
            [['image'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif']],
            [['created_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['status'], 'default', 'value' => 'Active'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'event_id' => Yii::t('app', 'Event ID'),
            'event_date' => Yii::t('app', 'Event Date'),
            'event_time' => Yii::t('app', 'Event Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function upload()
    {
        if (!is_object($this->image)) {
            return;
        }

        $oldImage = $this->getOldAttribute('image');
        if ($oldImage && file_exists(Yii::getAlias('@app') . '/' . $oldImage)) {
            @unlink(Yii::getAlias('@app') . '/' . $oldImage);
        }

        $path = 'uploads/images/event/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }

        $name = Yii::$app->MyFunctions->random_string(32);
        $filePath = $path . $name . '.' . $this->image->extension;
        $this->image->saveAs($filePath);
        $this->image = $filePath;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$this->isNewRecord) {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return true;
    }
}
