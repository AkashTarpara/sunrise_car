<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * @property int $product_id
 * @property int $product_category_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property float|null $price
 * @property int $display_order
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property ProductCategory $productCategory
 * @property ProductImage[] $productImages
 * @property ProductSpecifications[] $productSpecifications
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $url;
    public static function tableName()
    {
        return 'product';
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
            [['product_category_id', 'title', 'description', 'image'], 'required', 'on' => 'create'],
            [['product_category_id', 'title', 'description'], 'required', 'on' => 'update'],
            [['product_category_id', 'display_order'], 'integer'],
            [['description', 'status'], 'string'],
            [['price', 'price_per_box', 'sqft_in_box', 'main_price', 'price_per_piece'], 'number'],
            [['created_at', 'updated_at', 'slug', 'url', 'sqft_in_box', 'save_button_price', 'need_to_display_price'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['sqft_in_box', 'save_button_price'], 'default', 'value' => ''],
            [['image'], 'file', 'extensions' => 'png,jpg,jpeg,webp,gif'],
            [['title', 'image', 'slug', 'save_button_price'], 'string', 'max' => 255],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Productcategory::class, 'targetAttribute' => ['product_category_id' => 'product_category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'product_category_id' => Yii::t('app', 'Product Category'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'price' => Yii::t('app', 'Price'),
            'sqft_in_box' => Yii::t('app', 'Sqft In Box'),
            'price_per_box' => Yii::t('app', 'Price Per Box'),
            'main_price' => Yii::t('app', 'Main Price'),
            'price_per_piece' => Yii::t('app', 'Price Per Piece'),
            'save_button_price' => Yii::t('app', 'Limited Time Deal'),
            'display_order' => Yii::t('app', 'Display Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ProductCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(Productcategory::class, ['product_category_id' => 'product_category_id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(Productimage::class, ['product_id' => 'product_id']);
    }

    /**
     * Gets query for [[ProductSpecifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductSpecifications()
    {
        return $this->hasMany(Productspecifications::class, ['product_id' => 'product_id']);
    }

    public function getProductcategoryname()
    {
        $datalist   = Productcategory::find()->select(['product_category_id', 'title'])->where(['status' => 'Active'])->orderBy(['display_order' => SORT_ASC])->all();
        $list   = ArrayHelper::map($datalist, 'product_category_id', 'title');

        return $list;
    }

    public function upload()
    {
        if (is_object($this->image)) {

            if (file_exists(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image')) && !empty($this->getOldAttribute('image'))) {
                unlink(Yii::getAlias("@app") . "/" . $this->getOldAttribute('image'));
            }

            $path = 'uploads/images/product/';
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
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $Portfolio = Product::find()->orderBy('display_order DESC')->all();
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
