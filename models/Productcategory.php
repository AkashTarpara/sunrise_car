<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_category".
 *
 * @property int $product_category_id
 * @property string $title
 * @property string $image
 * @property int $display_order
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Product[] $products
 */
class Productcategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'image'], 'required', 'on' => 'create'],
            [['title'], 'required', 'on' => 'update'],
            [['display_order'], 'integer'],
            [['status', 'description'], 'string'],
            [['created_at', 'updated_at', 'description', 'banner_image', 'sample_image', 'price', 'sample_price', 'meta_title', 'meta_tag', 'meta_description'], 'safe'],
            [['material_per_step', 'installation_per_step'], 'number'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['description', 'banner_image', 'sample_image'], 'default', 'value' => ''],
            [['image', 'banner_image', 'sample_image'], 'file', 'extensions' => 'png,jpg,jpeg,webp,gif'],
            [['title', 'image', 'banner_image', 'sample_image'], 'string', 'max' => 255],
            [
                ['price'],
                'match',
                'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'Price must be a valid number with up to 2 decimal places.'
            ],
            [
                ['sample_price'],
                'match',
                'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'Sample Price must be a valid number with up to 2 decimal places.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_category_id' => Yii::t('app', 'Product Category ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'banner_image' => Yii::t('app', 'Banner Image'),
            'sample_image' => Yii::t('app', 'Sample Image'),
            'sample_price' => Yii::t('app', 'Sample Price'),
            'price' => Yii::t('app', 'Price'),
            'material_per_step' => Yii::t('app', 'Material Per Step'),
            'installation_per_step' => Yii::t('app', 'Installation Per Step'),
            'display_order' => Yii::t('app', 'Display Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['product_category_id' => 'product_category_id']);
    }

    public function upload()
    {
        if (is_object($this->image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
            }

            $path = 'uploads/images/productcategory/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->image->saveAs($path . $name . '.' . $this->image->extension);
            $this->image = $path . $name . '.' . $this->image->extension;
        }
        if (is_object($this->banner_image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('banner_image')) && !empty($this->getOldAttribute('banner_image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('banner_image'));
            }

            $path = 'uploads/images/productcategory/bannerimage/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->banner_image->saveAs($path . $name . '.' . $this->banner_image->extension);
            $this->banner_image = $path . $name . '.' . $this->banner_image->extension;
        }
        if (is_object($this->sample_image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('sample_image')) && !empty($this->getOldAttribute('sample_image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('sample_image'));
            }

            $path = 'uploads/images/productcategory/sampleimage/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }

            $name = Yii::$app->MyFunctions->random_string(32);
            $this->sample_image->saveAs($path . $name . '.' . $this->sample_image->extension);
            $this->sample_image = $path . $name . '.' . $this->sample_image->extension;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $Portfolio = Productcategory::find()->orderBy('display_order DESC')->all();
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
