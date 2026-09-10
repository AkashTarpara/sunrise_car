<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_order_detail".
 *
 * @property int $user_order_detail_id
 * @property int $user_order_id
 * @property int $product_id
 * @property float|null $price
 * @property int $quantity
 * @property string $created_at
 *
 * @property Product $product
 * @property UserOrder $userOrder
 */
class Userorderdetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_order_id', 'product_id', 'quantity'], 'required'],
            [['user_order_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['created_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
            [['user_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userorder::class, 'targetAttribute' => ['user_order_id' => 'user_order_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_order_detail_id' => Yii::t('app', 'User Order Detail ID'),
            'user_order_id' => Yii::t('app', 'User Order ID'),
            'product_id' => Yii::t('app', 'Product'),
            'price' => Yii::t('app', 'Price'),
            'quantity' => Yii::t('app', 'Quantity (Box)'),
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
     * Gets query for [[UserOrder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserOrder()
    {
        return $this->hasOne(Userorder::class, ['user_order_id' => 'user_order_id']);
    }
}
