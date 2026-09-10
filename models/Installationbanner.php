<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "installation_banner".
 *
 * @property int $installation_banner_id
 * @property string $label
 * @property string $title
 * @property string $sub_title
 * @property string $button_title
 * @property string $button_url
 * @property string $image
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 */
class Installationbanner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'installation_banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image'], 'required', 'on' => 'create'],
            [['sub_title', 'status'], 'string'],
            [['created_at', 'updated_at', 'image', 'label', 'title', 'sub_title', 'button_title', 'button_url'], 'safe'],
            [['label', 'title', 'button_title', 'button_url', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'installation_banner_id' => Yii::t('app', 'Installation Banner ID'),
            'label' => Yii::t('app', 'Label'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'button_title' => Yii::t('app', 'Button Title'),
            'button_url' => Yii::t('app', 'Button Url'),
            'image' => Yii::t('app', 'Image'),
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

            $path = 'uploads/images/installationbanner/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }
    }
}
