<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_specifications".
 *
 * @property int $product_specifications_id
 * @property int $product_id
 * @property string $title
 * @property string $created_at
 *
 * @property Product $product
 * @property ProductSpecificationsDetail[] $productSpecificationsDetails
 */
class Productspecifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_specifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'title'], 'required'],
            [['product_id'], 'integer'],
            [['created_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['title'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_specifications_id' => Yii::t('app', 'Product Specifications ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'title' => Yii::t('app', 'Title'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }

    /**
     * Gets query for [[ProductSpecificationsDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductSpecificationsDetails()
    {
        return $this->hasMany(Productspecificationsdetail::class, ['product_specifications_id' => 'product_specifications_id']);
    }
}
