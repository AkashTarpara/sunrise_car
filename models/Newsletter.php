<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "newsletter".
 *
 * @property int $newsletter_id
 * @property int $news_catagory_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $thumbnail_image
 * @property int $image_height
 * @property int $image_width
 * @property int $thumbnail_height
 * @property int $thumbnail_width
 * @property string $date
 * @property string $slug
 * @property string $status
 * @property string $created_at
 *
 * @property NewsCatagory $newsCatagory
 */
class Newsletter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'newsletter';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true,
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_height', 'image_width', 'thumbnail_height', 'thumbnail_width', 'is_featured'], 'integer'],
            [['title', 'description', 'image', 'thumbnail_image'], 'required', 'on' => 'create'],
            [['title', 'description'], 'required', 'on' => 'update'],
            [['description', 'status', 'type', 'meta_description', 'contact_type', 'btn_type'], 'string'],
            [['date', 'created_at', 'slug', 'publish_date', 'btn_title', 'btn_url', 'sub_title', 'updated_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            ['date', 'default', 'value' => date('Y-m-d')],
            [['meta_title', 'meta_tag', 'meta_description', 'btn_title', 'btn_url', 'sub_title'], 'default', 'value' => ''],
            [['image', 'thumbnail_image'], 'file', 'extensions' => 'png,jpg,jpeg'],
            [['title', 'image', 'thumbnail_image', 'slug', 'meta_title', 'meta_tag', 'sub_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'newsletter_id' => Yii::t('app', 'Newsletter ID'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'thumbnail_image' => Yii::t('app', 'Thumbnail Image'),
            'image_height' => Yii::t('app', 'Image Height'),
            'image_width' => Yii::t('app', 'Image Width'),
            'thumbnail_height' => Yii::t('app', 'Thumbnail Height'),
            'thumbnail_width' => Yii::t('app', 'Thumbnail Width'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_tag' => Yii::t('app', 'Meta Tag'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'type' => Yii::t('app', 'Type'),
            'contact_type' => Yii::t('app', 'Content Type'),
            'btn_title' => Yii::t('app', 'Button Title'),
            'btn_url' => Yii::t('app', 'Button Url'),
            'btn_type' => Yii::t('app', 'Button Type'),
            'date' => Yii::t('app', 'Date'),
            'publish_date' => Yii::t('app', 'Pubsidh Date Time'),
            'is_featured' => Yii::t('app', 'Featured'),
            'slug' => Yii::t('app', 'Slug'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function upload()
    {
        //echo "<pre>"; print_r($this); exit;
        if (is_object($this->image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
            }

            $path = 'uploads/images/newsletter/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;

            $size = getimagesize($this->image);
            $this->image_height = (!empty($size)) ? $size[1] : '';
            $this->image_width = (!empty($size)) ? $size[0] : '';
        }
        if (is_object($this->thumbnail_image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('thumbnail_image')) && !empty($this->getOldAttribute('thumbnail_image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('thumbnail_image'));
            }

            $path = 'uploads/images/newsletter/thumbnailimage/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->thumbnail_image->saveAs($path . $name . '.' . $this->thumbnail_image->extension);
            $this->thumbnail_image = $path . $name . '.' . $this->thumbnail_image->extension;

            $size = getimagesize($this->thumbnail_image);
            $this->thumbnail_height = (!empty($size)) ? $size[1] : '';
            $this->thumbnail_width = (!empty($size)) ? $size[0] : '';
        }
    }

    public function beforeSave($insert)
    {
        //echo "<pre>"; print_r($this->fleet_id); exit;
        if (parent::beforeSave($insert)) {

            if (!$this->isNewRecord) {
                $this->updated_at = date('Y-m-d H:i:s');
                //$this->is_publish=($this->is_schedule==true)?'No':'Yes';
            }
            return true;
        } else {
            return false;
        }
    }
}
