<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "client_say".
 *
 * @property int $client_say_id
 * @property string $name
 * @property string $designation
 * @property string $description
 * @property string $image
 * @property string $status
 * @property string $created_at
 */
class Clientsay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_say';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'designation', 'image'], 'required', 'on' => 'create'],
            [['name', 'designation'], 'required', 'on' => 'update'],
            [['description', 'status'], 'string'],
            [['display_order'], 'integer'],
            [['created_at', 'video', 'company_name', 'description', 'updated_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['video', 'company_name', 'description'], 'default', 'value' => ''],
            [['image'], 'file', 'extensions' => 'png,jpg,jpeg,svg'],
            [['video'], 'file', 'extensions' => 'mp4, ogg, MP4, OGG'],
            [['name', 'image', 'company_name', 'video'], 'string', 'max' => 255],
            [['designation'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'client_say_id' => Yii::t('app', 'Client Say ID'),
            'name' => Yii::t('app', 'Name'),
            'designation' => Yii::t('app', 'Designation'),
            'description' => Yii::t('app', 'Description'),
            'company_name' => Yii::t('app', 'Company Name'),
            'image' => Yii::t('app', 'Image'),
            'display_order' => Yii::t('app', 'Display Order'),
            'status' => Yii::t('app', 'Status'),
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

            $path = 'uploads/images/clientsay/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
            // $this->image = Yii::$app->MyFunctions->compress_image($this->image->tempName,$path . $name . '.' . $this->image->extension,30);
            // Yii::$app->MyFunctions->CopyToMediaBank($this->image,'Image');
        }
    }

    public function uploadvideo()
    {
        if (is_object($this->video)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video')) && !empty($this->getOldAttribute('video'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video'));
            }

            $path = 'uploads/video/clientsay/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->video->saveAs($path . $name . '.' . $this->video->extension);
            $this->video = $path . $name . '.' . $this->video->extension;
            Yii::$app->MyFunctions->CopyToMediaBank($this->video, 'Video');
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $Portfolio = Clientsay::find()->orderBy('display_order DESC')->all();
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
