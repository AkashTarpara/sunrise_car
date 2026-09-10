<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "warranty_certificate".
 *
 * @property int $warranty_certificate_id
 * @property string $image
 * @property string $created_at
 * @property string|null $updated_at
 */
class Warrantycertificate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warranty_certificate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['image'], 'required'],
            [['created_at', 'updated_at', 'image'], 'safe'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'warranty_certificate_id' => Yii::t('app', 'Warranty Certificate ID'),
            'image' => Yii::t('app', 'Image'),
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

            $path = 'uploads/image/warrentycertificate/';
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
