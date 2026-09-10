<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_specifications_detail".
 *
 * @property int $product_specifications_detail_id
 * @property int $product_specifications_id
 * @property string $title
 * @property string $sub_title
 * @property string $created_at
 *
 * @property ProductSpecifications $productSpecifications
 */
class Productspecificationsdetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public  $sort_order;
    public static function tableName()
    {
        return 'product_specifications_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_specifications_id', 'title'], 'required'],
            [['product_specifications_id'], 'integer'],
            [['created_at', 'sort_order'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['sub_title'], 'default', 'value' => ''],
            [['title', 'sub_title'], 'string', 'max' => 255],
            [['product_specifications_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductSpecifications::class, 'targetAttribute' => ['product_specifications_id' => 'product_specifications_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_specifications_detail_id' => Yii::t('app', 'Product Specifications Detail ID'),
            'product_specifications_id' => Yii::t('app', 'Product Specifications ID'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[ProductSpecifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductSpecifications()
    {
        return $this->hasOne(ProductSpecifications::class, ['product_specifications_id' => 'product_specifications_id']);
    }
}
