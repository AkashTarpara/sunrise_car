<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shopping_by_brands".
 *
 * @property int $shopping_by_brands_id
 * @property string $title
 * @property string $image
 * @property string $logo
 * @property int $display_order
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 */
class Shoppingbybrands extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shopping_by_brands';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'image', 'logo'], 'required', 'on' => 'create'],
            [['title'], 'required', 'on' => 'update'],
            [['display_order'], 'integer'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['image', 'logo'], 'file', 'extensions' => 'png,jpg,jpeg,webp,gif'],
            [['title', 'image', 'logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shopping_by_brands_id' => Yii::t('app', 'Shopping By Brands ID'),
            'title' => Yii::t('app', 'Title'),
            'image' => Yii::t('app', 'Image'),
            'logo' => Yii::t('app', 'Logo'),
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

            $path = 'uploads/images/shoppingbybrands/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }
        if (is_object($this->logo)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('logo')) && !empty($this->getOldAttribute('logo'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('logo'));
            }

            $path = 'uploads/images/shoppingbybrands/logo/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->logo->saveAs($path . $name . '.' . $this->logo->extension);
            $this->logo = $path . $name . '.' . $this->logo->extension;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $Portfolio = Shoppingbybrands::find()->orderBy('display_order DESC')->all();
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
