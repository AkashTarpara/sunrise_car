<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipping_charge".
 *
 * @property int $shipping_charge_id
 * @property int|null $min_mile
 * @property int|null $max_mile
 * @property float|null $price
 * @property string $status
 * @property string $created_at
 * @property string|null $updated_at
 */
class Shippingcharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_charge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['min_mile', 'max_mile', 'price'], 'required'],
            [['min_mile', 'max_mile'], 'integer'],
            [['price'], 'number'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shipping_charge_id' => Yii::t('app', 'Shipping Charge ID'),
            'min_mile' => Yii::t('app', 'Min Mile'),
            'max_mile' => Yii::t('app', 'Max Mile'),
            'price' => Yii::t('app', 'Price'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
