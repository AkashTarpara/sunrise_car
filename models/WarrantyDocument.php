<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "warranty_document".
 *
 * @property int $warranty_document_id
 * @property int $warranty_id
 * @property string $title
 * @property string $file
 * @property string $created_at
 * @property string|null $updated_at
 */
class WarrantyDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warranty_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warranty_id', 'title'], 'required'],
            [['warranty_id'], 'integer'],
            [['created_at', 'updated_at', 'file'], 'safe'],
            [['title', 'file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'warranty_document_id' => Yii::t('app', 'Warranty Document ID'),
            'warranty_id' => Yii::t('app', 'Warranty ID'),
            'title' => Yii::t('app', 'Title'),
            'file' => Yii::t('app', 'File'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function upload()
    {
        if (is_object($this->file)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('file')) && !empty($this->getOldAttribute('file'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('file'));
            }

            $path = 'uploads/files/warrenty/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->file->saveAs($path . $name . '.' . $this->file->extension);
            $this->file = $path . $name . '.' . $this->file->extension;
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
