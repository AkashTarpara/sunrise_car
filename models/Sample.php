<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sample".
 *
 * @property int $sample_id
 * @property string $first_name
 * @property string $last_name
 * @property string $company_name
 * @property string $email
 * @property string $phone_no
 * @property string $address
 * @property string $address_line_2
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $order_number
 * @property string $payment_type
 * @property string $payment_status
 * @property string $payment_id
 * @property float|null $sub_total
 * @property float|null $tax
 * @property float|null $shipping
 * @property float|null $total
 * @property string $payment_date
 * @property string $order_status
 * @property string $delivery_status
 * @property string $created_at
 */
class Sample extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sample';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'phone_no', 'address', 'city', 'state', 'zip_code', 'payment_type', 'payment_status', 'payment_id', 'order_status', 'product_name'], 'required'],
            [['sub_total', 'tax', 'shipping', 'total'], 'number'],
            [['payment_date', 'created_at', 'company_name', 'address_line_2', 'order_number'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            [['company_name', 'address_line_2', 'order_number'], 'default', 'value' => ''],
            [['delivery_status'], 'string'],
            [['first_name', 'last_name', 'company_name', 'email', 'phone_no', 'address', 'address_line_2', 'city', 'state', 'zip_code', 'order_number', 'payment_type', 'payment_status', 'payment_id', 'order_status', 'product_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sample_id' => Yii::t('app', 'Sample ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'company_name' => Yii::t('app', 'Company Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'address' => Yii::t('app', 'Address'),
            'address_line_2' => Yii::t('app', 'Address Line 2'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'zip_code' => Yii::t('app', 'Zip Code'),
            'product_name' => Yii::t('app', 'Product Name'),
            'order_number' => Yii::t('app', 'Order Number'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'payment_id' => Yii::t('app', 'Payment ID'),
            'sub_total' => Yii::t('app', 'Sub Total'),
            'tax' => Yii::t('app', 'Tax'),
            'shipping' => Yii::t('app', 'Shipping'),
            'total' => Yii::t('app', 'Total'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'order_status' => Yii::t('app', 'Order Status'),
            'delivery_status' => Yii::t('app', 'Delivery Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
