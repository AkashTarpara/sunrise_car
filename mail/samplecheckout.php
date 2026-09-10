<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sample */

$fullName = trim($model->first_name . ' ' . $model->last_name);
$formatMoney = function ($value) {
    return '$' . number_format((float)$value, 2, '.', '');
};

$sections = [
    'Customer Information' => [
        'Name' => $fullName,
        'Company' => $model->company_name,
        'Email' => $model->email,
        'Phone' => $model->phone_no,
    ],
    'Shipping Address' => [
        'Address' => $model->address,
        'Address Line 2' => $model->address_line_2,
        'City' => $model->city,
        'State' => $model->state,
        'Zip Code' => $model->zip_code,
    ],
    'Sample Order' => [
        'Product' => $model->product_name,
        'Order Number' => $model->order_number,
        'Order Status' => $model->order_status,
        'Delivery Status' => $model->delivery_status,
    ],
    'Payment' => [
        'Payment Type' => $model->payment_type,
        'Payment Status' => $model->payment_status,
        'Payment ID' => $model->payment_id,
        'Payment Date' => $model->payment_date,
        'Sub Total' => $formatMoney($model->sub_total),
        'Estimated Taxes' => $formatMoney($model->tax),
        'Shipping' => $formatMoney($model->shipping),
        'Total' => $formatMoney($model->total),
    ],
];
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#000000" style="background:#000000; margin:0; padding:0;">
    <tr>
        <td valign="top" align="center" style="padding:28px 14px; font-family:Arial, Helvetica, sans-serif;">
            <table width="680" border="0" cellspacing="0" cellpadding="0" align="center" style="width:680px; max-width:680px; background:#ffffff; color:#151515;">
                <tr>
                    <td style="padding:28px 30px; text-align:center; border-bottom:1px solid #e8e8e8;">
                        <div style="font-size:30px; line-height:32px; font-weight:700; color:#000000;">
                            Luxury<br><span style="color:#707070;">Layers</span>
                        </div>
                        <div style="font-size:13px; line-height:18px; color:#777777; margin-top:5px;">Luxury to the Core</div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px;">
                        <h1 style="font-family:Georgia, 'Times New Roman', serif; font-size:30px; line-height:38px; font-style:italic; color:#000000; margin:0;">
                            Sample Checkout Confirmation
                        </h1>
                        <p style="font-size:15px; line-height:23px; color:#333333; margin:14px 0 0;">
                            Thank you<?= !empty($fullName) ? ', ' . Html::encode($fullName) : '' ?>. Your sample checkout
                            <strong>#<?= Html::encode($model->order_number) ?></strong> has been received successfully.
                        </p>
                    </td>
                </tr>

                <?php foreach ($sections as $title => $rows) { ?>
                    <tr>
                        <td style="padding:0 30px 18px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #dedede; border-collapse:collapse;">
                                <tr>
                                    <td colspan="2" style="padding:14px 16px; background:#111111; color:#ffffff; font-size:17px; line-height:23px; font-weight:700;">
                                        <?= Html::encode($title) ?>
                                    </td>
                                </tr>
                                <?php foreach ($rows as $label => $value) { ?>
                                    <tr>
                                        <td width="34%" valign="top" style="padding:12px 16px; border-top:1px solid #eeeeee; background:#f7f7f7; color:#555555; font-size:13px; line-height:19px; font-weight:700;">
                                            <?= Html::encode($label) ?>
                                        </td>
                                        <td valign="top" style="padding:12px 16px; border-top:1px solid #eeeeee; color:#151515; font-size:14px; line-height:21px;">
                                            <?= nl2br(Html::encode($value !== null && $value !== '' ? $value : '-')) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td style="padding:4px 30px 30px; text-align:center; color:#666666; font-size:13px; line-height:20px;">
                        <?php if (!empty(Yii::$app->params['contactEmail'])) { ?>
                            Questions? Contact us at
                            <a href="mailto:<?= Html::encode(Yii::$app->params['contactEmail']) ?>" style="color:#000000;">
                                <?= Html::encode(Yii::$app->params['contactEmail']) ?>
                            </a>.
                        <?php } else { ?>
                            This notification was generated automatically by Luxury Layers.
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
