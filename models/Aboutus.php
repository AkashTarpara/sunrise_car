<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "about_us".
 *
 * @property int $about_us_id
 * @property string $banner_title
 * @property string $banner_sub_title
 * @property string $image
 * @property string $title
 * @property string $sub_title
 * @property string $description
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 */
class Aboutus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'about_us';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['banner_title', 'title', 'description'], 'required'],
            [['description', 'status'], 'string'],
            [['created_at', 'updated_at', 'image', 'banner_sub_title', 'sub_title'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['image', 'banner_sub_title', 'sub_title'], 'default', 'value' => ''],
            [['image'], 'file', 'extensions' => 'png,jpg,jpeg'],
            [['banner_title', 'banner_sub_title', 'image', 'title', 'sub_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'about_us_id' => Yii::t('app', 'About Us ID'),
            'banner_title' => Yii::t('app', 'Banner Title'),
            'banner_sub_title' => Yii::t('app', 'Banner Sub Title'),
            'image' => Yii::t('app', 'Banner Image'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'description' => Yii::t('app', 'Description'),
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

            $path = 'uploads/images/aboutus/';
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
