<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "content_page_detail".
 *
 * @property int $content_page_detail_id
 * @property int $content_page_id
 * @property string $type
 * @property string $section_title
 * @property string $title
 * @property string $sub_title
 * @property string $image
 * @property string $button_1_title
 * @property string $button_1_url
 * @property string $button_2_title
 * @property string $button_2_url
 * @property string $logo
 * @property string $video
 * @property int $display_order
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property ContentPage $contentPage
 */
class Contentpagedetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public  $sort_order;
    public static function tableName()
    {
        return 'content_page_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content_page_id', 'type'], 'required'],

            [['content_page_id', 'display_order'], 'integer'],

            [['title', 'status', 'description'], 'string'],

            [['created_at', 'updated_at', 'section_title', 'title', 'sub_title', 'image', 'button_1_title', 'button_1_url', 'button_2_title', 'button_2_url', 'logo', 'video', 'sort_order', 'mobile_image', 'url', 'media_type', 'mobile_video', 'description', 'button_3_title', 'button_3_url'], 'safe'],

            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],

            [['section_title', 'title', 'sub_title', 'image', 'button_1_title', 'button_1_url', 'button_2_title', 'button_2_url', 'logo', 'video', 'mobile_image', 'url', 'media_type', 'mobile_video', 'description', 'button_3_title', 'button_3_url'], 'default', 'value' => ''],

            [['type', 'section_title', 'image', 'button_1_title', 'button_1_url', 'button_2_title', 'button_2_url', 'logo', 'video', 'mobile_image', 'url', 'media_type', 'mobile_video', 'button_3_title', 'button_3_url'], 'string', 'max' => 255],

            [['sub_title'], 'string', 'max' => 500],

            [['content_page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contentpage::class, 'targetAttribute' => ['content_page_id' => 'content_page_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'content_page_detail_id' => Yii::t('app', 'Content Page Detail ID'),
            'content_page_id' => Yii::t('app', 'Content Page ID'),
            'type' => Yii::t('app', 'Type'),
            'media_type' => Yii::t('app', 'Media Type'),
            'section_title' => Yii::t('app', 'Section Title'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'image' => Yii::t('app', 'Image'),
            'button_1_title' => Yii::t('app', 'Button 1 Title'),
            'button_1_url' => Yii::t('app', 'Button 1 Url'),
            'button_2_title' => Yii::t('app', 'Button 2 Title'),
            'button_2_url' => Yii::t('app', 'Button 2 Url'),
            'button_3_title' => Yii::t('app', 'Button 3 Title'),
            'button_3_url' => Yii::t('app', 'Button 3 Url'),
            'logo' => Yii::t('app', 'Logo'),
            'video' => Yii::t('app', 'Video'),
            'mobile_image' => Yii::t('app', 'Mobile Image'),
            'url' => Yii::t('app', 'Url'),
            'description' => Yii::t('app', 'Description'),
            'display_order' => Yii::t('app', 'Display Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ContentPage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContentPage()
    {
        return $this->hasOne(Contentpage::class, ['content_page_id' => 'content_page_id']);
    }

    public function upload()
    {
        if (is_object($this->image)) {
            if (!$this->isNewRecord) {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'))  && is_object($this->image) && !empty($this->getOldAttribute('image')) && $this->getOldAttribute('image') != 'uploads/images/contentpage/contentpagedetail/default.png') {
                    unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
                }
                // if ($this->getOldAttribute('image')) {
                //     $url = $this->getOldAttribute('image');
                //     $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
                // }
            }
            $thumbnail_path = 'uploads/images/contentpage/contentpagedetail/image/';
            if (!is_dir($thumbnail_path)) {
                mkdir($thumbnail_path, 0777, true);
                chmod($thumbnail_path, 0777);
            }
            $name = Yii::$app->MyFunctions->random_string(25);
            //$this->image = Yii::$app->MyFunctions->StoreS3Image($this->image, $thumbnail_path);
            $this->image->saveAs($thumbnail_path . $name . '.' . $this->image->extension);
            $this->image = $thumbnail_path . $name . '.' . $this->image->extension;
        } else {
            $this->image = $this->getOldAttribute("image");
        }

        if (is_object($this->logo)) {
            if (!$this->isNewRecord) {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('logo'))  && is_object($this->logo) && !empty($this->getOldAttribute('logo')) && $this->getOldAttribute('logo') != 'uploads/images/contentpage/contentpagedetail/default.png') {
                    unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('logo'));
                }
                // if ($this->getOldAttribute('logo')) {
                //     $url = $this->getOldAttribute('logo');
                //     $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
                // }
            }
            $thumbnail_path = 'uploads/images/contentpage/contentpagedetail/logo/';
            //$this->logo = Yii::$app->MyFunctions->StoreS3Image($this->logo, $thumbnail_path);
            if (!is_dir($thumbnail_path)) {
                mkdir($thumbnail_path, 0777, true);
                chmod($thumbnail_path, 0777);
            }
            $name = Yii::$app->MyFunctions->random_string(25);
            $this->logo->saveAs($thumbnail_path . $name . '.' . $this->logo->extension);
            $this->logo = $thumbnail_path . $name . '.' . $this->logo->extension;
        } else {
            $this->logo = $this->getOldAttribute("logo");
        }

        if (is_object($this->mobile_image)) {
            if (!$this->isNewRecord) {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('mobile_image'))  && is_object($this->mobile_image) && !empty($this->getOldAttribute('mobile_image')) && $this->getOldAttribute('mobile_image') != 'uploads/images/contentpage/contentpagedetail/default.png') {
                    unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('mobile_image'));
                }
                // if ($this->getOldAttribute('mobile_image')) {
                //     $url = $this->getOldAttribute('mobile_image');
                //     $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
                // }
            }
            $thumbnail_path = 'uploads/images/contentpage/contentpagedetail/mobileimage/';
            //$this->mobile_image = Yii::$app->MyFunctions->StoreS3Image($this->mobile_image, $thumbnail_path);
            if (!is_dir($thumbnail_path)) {
                mkdir($thumbnail_path, 0777, true);
                chmod($thumbnail_path, 0777);
            }
            $name = Yii::$app->MyFunctions->random_string(25);
            $this->mobile_image->saveAs($thumbnail_path . $name . '.' . $this->mobile_image->extension);
            $this->mobile_image = $thumbnail_path . $name . '.' . $this->mobile_image->extension;
        } else {
            $this->mobile_image = $this->getOldAttribute("mobile_image");
        }

        if (is_object($this->video)) {
            if (!$this->isNewRecord) {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video'))  && is_object($this->video) && !empty($this->getOldAttribute('video')) && $this->getOldAttribute('video') != 'uploads/images/contentpage/contentpagedetail/default.png') {
                    unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video'));
                }
                // if ($this->getOldAttribute('video')) {
                //     $url = $this->getOldAttribute('video');
                //     $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
                // }
            }
            $thumbnail_path = 'uploads/images/contentpage/contentpagedetail/video/';
            // $this->video = Yii::$app->MyFunctions->StoreS3Image($this->video, $thumbnail_path);
            // if ($this->media_type == 'Video') {
            //     $this->mobile_image = Yii::$app->MyFunctions->GenerateVidoeThumbnail($this->video, 'uploads/images/contentpagedetail/video/thumbnail/');
            // }

            if (!is_dir($thumbnail_path)) {
                mkdir($thumbnail_path, 0777, true);
                chmod($thumbnail_path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(25);
            $this->video->saveAs($thumbnail_path . $name . '.' . $this->video->extension);
            $this->video = $thumbnail_path . $name . '.' . $this->video->extension;
        } else {
            $this->video = $this->getOldAttribute("video");
        }
        if (is_object($this->mobile_video)) {
            if (!$this->isNewRecord) {
                if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('mobile_video'))  && is_object($this->mobile_video) && !empty($this->getOldAttribute('mobile_video')) && $this->getOldAttribute('mobile_video') != 'uploads/images/contentpage/contentpagedetail/default.png') {
                    unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('mobile_video'));
                }
                // if ($this->getOldAttribute('mobile_video')) {
                //     $url = $this->getOldAttribute('mobile_video');
                //     $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
                // }
            }
            $thumbnail_path = 'uploads/images/contentpage/contentpagedetail/mobilevideo/';
            //$this->mobile_video = Yii::$app->MyFunctions->StoreS3Image($this->mobile_video, $thumbnail_path);
            if (!is_dir($thumbnail_path)) {
                mkdir($thumbnail_path, 0777, true);
                chmod($thumbnail_path, 0777);
            }
            $name = Yii::$app->MyFunctions->random_string(25);
            $this->mobile_video->saveAs($thumbnail_path . $name . '.' . $this->mobile_video->extension);
            $this->mobile_video = $thumbnail_path . $name . '.' . $this->mobile_video->extension;
        } else {
            $this->mobile_video = $this->getOldAttribute("mobile_video");
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $Homescreen = Contentpagedetail::find()->where(['content_page_id' => $this->content_page_id])->orderBy('display_order DESC')->all();
                if (!$Homescreen) {
                    $this->display_order = 1;
                } else {
                    $this->display_order = $Homescreen[0]->display_order + 1;
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
