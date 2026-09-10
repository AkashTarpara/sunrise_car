<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "warranty".
 *
 * @property int $warranty_id
 * @property string $label
 * @property string $title
 * @property string $sub_title
 * @property string $created_at
 * @property string|null $updated_at
 */
class Warranty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warranty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label', 'title', 'sub_title'], 'required'],
            [['created_at', 'updated_at', 'image'], 'safe'],
            [['label', 'title', 'sub_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'warranty_id' => Yii::t('app', 'Warranty ID'),
            'label' => Yii::t('app', 'Label'),
            'title' => Yii::t('app', 'Title'),
            'image' => Yii::t('app', 'Image'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function upload()
    {
        if (is_object($this->image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
            }

            $path = 'uploads/images/installation/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->isNewRecord) {
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
}
