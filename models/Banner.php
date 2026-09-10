<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "banner".
 *
 * @property int $banner_id
 * @property int $banner_type_id
 * @property string $title
 * @property string $url
 * @property string $type
 * @property string $image
 * @property string $video
 * @property int $height
 * @property int $width
 * @property int $duration
 * @property string $status
 * @property string $created_at
 *
 * @property BannerType $bannerType
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'url'],
            [['display_order'], 'integer'],
            [['type', 'status', 'subtitle', 'title'], 'string'],
            [['image'], 'file', 'extensions' => 'png,jpg,jpeg'],
            [['video'], 'file', 'extensions' => 'mp4, ogg, MP4, OGG'],
            [['created_at', 'url', 'subtitle', 'title', 'btn_title', 'mobile_video', 'image_url', 'logo_url', 'video_url', 'updated_at'], 'safe'],
            [['url', 'image', 'video', 'subtitle', 'title', 'btn_title', 'mobile_video'], 'default', 'value' => ''],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['url', 'image', 'video', 'btn_title', 'mobile_video'], 'string', 'max' => 255],
            ['video', 'required', 'when' => function ($model) {
                return $model->type == 'Video';
            }, 'enableClientValidation' => false],

            ['image', 'required', 'when' => function ($model) {
                return $model->type == 'Video';
            }, 'enableClientValidation' => false],

            ['image', 'required', 'when' => function ($model) {
                return $model->type == 'Image';
            }, 'enableClientValidation' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'banner_id' => Yii::t('app', 'Banner ID'),
            'banner_type_id' => Yii::t('app', 'Banner Type'),
            'title' => Yii::t('app', 'Title'),
            'subtitle' => Yii::t('app', 'Sub Title'),
            'url' => Yii::t('app', 'Url'),
            'btn_title' => Yii::t('app', 'Button Title'),
            'type' => Yii::t('app', 'Type'),
            'image' => Yii::t('app', 'Image'),
            'video' => Yii::t('app', 'Video'),
            'mobile_video' => Yii::t('app', 'Mobile Video'),
            'height' => Yii::t('app', 'Height'),
            'width' => Yii::t('app', 'Width'),
            'duration' => Yii::t('app', 'Duration'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }


    public function upload()
    {
        if (is_object($this->image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
            }

            $path = 'uploads/images/banner/image/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }
    }

    public function uploadvideo()
    {
        if (is_object($this->video)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video')) && !empty($this->getOldAttribute('video'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video'));
            }

            $path = 'uploads/images/banner/video/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->video->saveAs($path . $name . '.' . $this->video->extension);
            $this->video = $path . $name . '.' . $this->video->extension;
        }

        if (is_object($this->mobile_video)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('mobile_video')) && !empty($this->getOldAttribute('mobile_video'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('mobile_video'));
            }

            $path = 'uploads/images/banner/video/mobilevideo/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->mobile_video->saveAs($path . $name . '.' . $this->mobile_video->extension);
            $this->mobile_video = $path . $name . '.' . $this->mobile_video->extension;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $Portfolio = Banner::find()->orderBy('display_order DESC')->all();
                if (!$Portfolio) {
                    $this->display_order = 1;
                } else {
                    $this->display_order = $Portfolio[0]->display_order + 1;
                }
            } else {
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
}
