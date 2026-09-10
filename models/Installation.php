<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "installation".
 *
 * @property int $installation_id
 * @property string $label
 * @property string $title
 * @property string $sub_title
 * @property string $video
 * @property string $before_image
 * @property string $after_image
 * @property string $created_at
 * @property string|null $updated_at
 */
class Installation extends \yii\db\ActiveRecord
{
    public $banner;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'installation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['installation_id', 'label', 'title', 'sub_title'], 'required'],
            [['installation_id'], 'integer'],
            [['created_at', 'updated_at', 'video', 'before_image', 'after_image'], 'safe'],
            [['label', 'title', 'sub_title', 'video', 'before_image', 'after_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'installation_id' => Yii::t('app', 'Installation ID'),
            'label' => Yii::t('app', 'Label'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'video' => Yii::t('app', 'Video'),
            'before_image' => Yii::t('app', 'Before Image'),
            'after_image' => Yii::t('app', 'After Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function upload()
    {
        if (is_object($this->before_image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('before_image')) && !empty($this->getOldAttribute('before_image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('before_image'));
            }

            $path = 'uploads/images/installation/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->before_image->saveAs($path . $name . '.' . $this->before_image->extension);
            $this->before_image = $path . $name . '.' . $this->before_image->extension;
        }

        if (is_object($this->after_image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('after_image')) && !empty($this->getOldAttribute('after_image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('after_image'));
            }

            $path = 'uploads/images/installation/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->after_image->saveAs($path . $name . '.' . $this->after_image->extension);
            $this->after_image = $path . $name . '.' . $this->after_image->extension;
        }

        if (is_object($this->video)) {
            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video')) && !empty($this->getOldAttribute('video'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('video'));
            }

            $path = 'uploads/images/installation/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->video->saveAs($path . $name . '.' . $this->video->extension);
            $this->video = $path . $name . '.' . $this->video->extension;
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
