<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_order".
 *
 * @property int $user_order_id
 * @property int $appuser_id
 * @property int $appuser_address_id
 * @property string $payment_type
 * @property string $payment_status
 * @property string $payment_id
 * @property float|null $sub_total
 * @property float|null $total
 * @property string $payment_date
 * @property string $order_status
 * @property string $created_at
 *
 * @property Appuser $appuser
 * @property AppuserAddress $appuserAddress
 * @property UserOrderDetail[] $userOrderDetails
 */
class Userorder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $user_carts_id;
    public static function tableName()
    {
        return 'user_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appuser_id', 'payment_type', 'payment_status', 'payment_id', 'order_status', 'user_carts_id'], 'required', 'on' => 'apicreate'],
            [['appuser_id', 'appuser_address_id'], 'integer'],
            [['sub_total', 'total', 'tax', 'shipping'], 'number'],
            [['delivery_status'], 'string'],
            [['payment_date', 'created_at', 'user_carts_id', 'delivery_status', 'order_number', 'delivery_type'], 'safe'],
            ['created_at', 'default', 'value' => date('Y-m-d H:i:s')],
            ['delivery_type', 'default', 'value' => ''],
            [['payment_type', 'payment_status', 'payment_id', 'order_status', 'order_number', 'delivery_type'], 'string', 'max' => 255],
            [['appuser_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appuseraddress::class, 'targetAttribute' => ['appuser_address_id' => 'appuser_address_id']],
            [['appuser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appuser::class, 'targetAttribute' => ['appuser_id' => 'appuser_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_order_id' => Yii::t('app', 'User Order ID'),
            'appuser_id' => Yii::t('app', 'User Name'),
            'appuser_address_id' => Yii::t('app', 'User Address'),
            'order_number' => Yii::t('app', 'Order Number'),
            'delivery_type' => Yii::t('app', 'Delivery Type'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'payment_id' => Yii::t('app', 'Payment ID'),
            'sub_total' => Yii::t('app', 'Sub Total'),
            'tax' => Yii::t('app', 'Estimated Taxes'),
            'shipping' => Yii::t('app', 'Shipping'),
            'total' => Yii::t('app', 'Total'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'order_status' => Yii::t('app', 'Order Status'),
            'delivery_status' => Yii::t('app', 'Delivery Status'),
            'created_at' => Yii::t('app', 'Created At'),
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
     * Gets query for [[AppuserAddress]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppuserAddress()
    {
        return $this->hasOne(Appuseraddress::class, ['appuser_address_id' => 'appuser_address_id']);
    }

    /**
     * Gets query for [[UserOrderDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserOrderDetails()
    {
        return $this->hasMany(Userorderdetail::class, ['user_order_id' => 'user_order_id']);
    }

    public function sendOrderEmail()
    {

        try {
            Yii::$app->mailer->htmlLayout = "@app/mail/layouts/htmlnew";
            return Yii::$app->mailer->compose(['html' => 'userorder'], ['user' => $this, 'from' => 'user'])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['project_display_name']])
                ->setTo([
                    $this->appuser->email,
                    Yii::$app->params['supportEmail']    // main customer
                ])
                ->setSubject('🎉 Your Order #' . $this->order_number . ' Has Been Placed Successfully!')
                ->send();
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    public function sendOrderUpdateEmail()
    {

        try {
            Yii::$app->mailer->htmlLayout = "@app/mail/layouts/htmlnew";
            return Yii::$app->mailer->compose(['html' => 'userorderupdate'], ['user' => $this, 'from' => 'user'])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['project_display_name']])
                ->setTo([
                    $this->appuser->email
                ])
                ->setSubject('🎉 Your Order #' . $this->order_number . ' Has Been ' . $this->delivery_status . ' Successfully!')
                ->send();
        } catch (Exception $e) {
            return false;
        }
        return false;
    }


    public function getDeliverystatus()
    {
        $disabled = '';
        $status_data = array('Pending' => Yii::t('app', 'Pending'), 'Packaging' => Yii::t('app', 'Packaging'), 'Delivered' => Yii::t('app', 'Delivered'), 'Canceled' => Yii::t('app', 'Canceled'));
        return $status = Html::dropDownList(
            'Userorder[delivery_status]',
            $this->delivery_status,
            $status_data,
            [
                'id' => $this->user_order_id,
                'class' => "form-control delivery_status",
                //'data-size'=>'8',
                //'data-actions-box'=>'true',
                'onchange' => 'changeDeliverystatus(this)',
            ]
        ) . '<i class="fa fa-spinner fa-spin btn-spinner-payment-' . $this->user_order_id . ' hide"></i>';
    }
}
