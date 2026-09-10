<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_carts".
 *
 * @property int $user_carts_id
 * @property int $appuser_id
 * @property int $product_id
 * @property float|null $price
 * @property int $quantity
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Appuser $appuser
 * @property Product $product
 */
class Usercarts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_carts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appuser_id', 'product_id', 'quantity'], 'required'],
            [['appuser_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['appuser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appuser::class, 'targetAttribute' => ['appuser_id' => 'appuser_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_carts_id' => Yii::t('app', 'User Carts ID'),
            'appuser_id' => Yii::t('app', 'Appuser ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'price' => Yii::t('app', 'Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Appuser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppuser()
    {
        return $this->hasOne(Appuser::class, ['appuser_id' => 'appuser_id']);
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
