<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property int $product_image_id
 * @property int $product_id
 * @property string $file
 * @property string $created_at
 *
 * @property Product $product
 */
class Productimage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $sort_order;
    public static function tableName()
    {
        return 'product_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'type'], 'required'],
            [['product_id'], 'integer'],
            [['type'], 'string'],
            [['created_at', 'sort_order'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [
                'file',
                'required',
                'when' => function ($model) {
                    return $model->type === 'Image' && empty($model->file);
                },
                'whenClient' => "function (attribute, value) {
        var type = $(attribute.input).closest('.house-item2').find('.ischoicedocumenttype').val();
        var existingVal = $(attribute.input).closest('.house-item2').find('img').attr('src'); 
        return type === 'Image' && (!existingVal || existingVal.trim() === '');
    }"
            ],

            [
                'video',
                'required',
                'when' => function ($model) {
                    return $model->type === 'Video' && empty($model->video);
                },
                'whenClient' => "function (attribute, value) {
        var type = $(attribute.input).closest('.house-item2').find('.ischoicedocumenttype').val();
        var existingVal = $(attribute.input).closest('.house-item2').find('video source').attr('src'); 
        return type === 'Video' && (!existingVal || existingVal.trim() === '');
    }"
            ],

            [
                'video_url',
                'required',
                'when' => function ($model) {
                    return $model->type === 'Youtube' && empty($model->video_url);
                },
                'whenClient' => "function (attribute, value) {
        var type = $(attribute.input).closest('.house-item2').find('.ischoicedocumenttype').val();
        var existingVal = $(attribute.input).val();
        return type === 'Youtube' && (!existingVal || existingVal.trim() === '');
    }"
            ],

            // If you check file extensions, make sure you don't trigger Undefined offset
            // ['file', 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
            // ['video', 'file', 'extensions' => 'mp4', 'skipOnEmpty' => true],
            //['video_url', 'url', 'skipOnEmpty' => false],
            [['file', 'video', 'video_url'], 'default', 'value' => ''],
            [['file', 'video', 'video_url'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_image_id' => Yii::t('app', 'Product Image ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'file' => Yii::t('app', 'Image'),
            'video' => Yii::t('app', 'Video'),
            'video_url' => Yii::t('app', 'Youtube URL'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }

    public function upload()
    {
        if (is_object($this->file)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('file')) && !empty($this->getOldAttribute('file'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('file'));
            }

            $path = 'uploads/images/product/media/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->file->saveAs($path . $name . '.' . $this->file->extension);
            $this->file = $path . $name . '.' . $this->file->extension;
        } else {
            $this->file = $this->getOldAttribute("file");
        }
        if (is_object($this->video)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video')) && !empty($this->getOldAttribute('video'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video'));
            }

            $path = 'uploads/images/product/media/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->video->saveAs($path . $name . '.' . $this->video->extension);
            $this->video = $path . $name . '.' . $this->video->extension;
        } else {
            $this->video = $this->getOldAttribute("video");
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
}
