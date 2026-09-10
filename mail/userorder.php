<?php

use yii\helpers\Html;
use app\models\Generalsetting;
/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $order common\models\Order */
/* @var $orderItems common\models\OrderItem[] */

$model = Generalsetting::find()->Where(['setting_id' => 1])->one();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Order Confirmation</title>
</head>

<body marginheight="0" marginwidth="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#000000">
        <tr>
            <td valign="top" align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="width:600px;">

                    <!-- Header -->
                    <tr>
                        <td align="left" valign="top" bgcolor="#ffffff" style="padding:30px; text-align:center; font-family:Montserrat, sans-serif;">
                            <h2 style="font-style: italic;font-weight: 800; margin:0;">
                                Hello, <?= !empty($user->appuser->full_name) ? Html::encode($user->appuser->full_name) : 'Customer' ?>
                            </h2>
                            <p style="font-size: 15px; line-height:22px; margin:20px 0;">
                                Thank you for shopping with us 🎉
                            </p>
                            <p style="font-size: 15px; line-height:22px; margin:10px 0;">
                                We’re happy to let you know that your order
                                <strong>#<?= Html::encode($user->order_number) ?></strong>
                                has been placed successfully.
                            </p>
                            <?php if ($user->delivery_type == 'Pickup') { ?>
                                <p style="font-size: 15px; line-height:22px; margin:10px 0;">
                                    <strong>Warehouse Address:</strong> <?= !empty($model->warehouse_address) ? $model->warehouse_address : '' ?>
                                </p>
                                <?php } ?>

                        </td>
                    </tr>

                    <!-- Order Summary -->
                    <tr>
                        <td bgcolor="#ffffff" style="padding:20px 30px;">
                            <h3 style="margin:0 0 15px 0; font-family:Montserrat, sans-serif;">Order Summary</h3>
                            <table width="100%" border="1" cellspacing="0" cellpadding="8" style="border-collapse:collapse; font-family:Montserrat, sans-serif; font-size:14px;">
                                <tr style="background:#f5f5f5;">
                                    <th align="left">Product</th>
                                    <th align="center">Qty</th>
                                    <th align="right">Price</th>
                                </tr>
                                <?php if ($user->userOrderDetails) {
                                    foreach ($user->userOrderDetails as $key => $value) { ?>
                                        <tr>
                                            <td><?= (!empty($value->product->title)) ? $value->product->title : '' ?></td>
                                            <td align="right"><?= $value->quantity ?></td>
                                            <td align="center">$<?= (!empty($value->product->price_per_box)) ? $value->product->price_per_box : '' ?></td>
                                        </tr>
                                <?php }
                                } ?>


                                <tr style="font-weight:bold;">
                                    <td colspan="2" align="right">Estimated Taxes:</td>
                                    <td align="right">$<?= number_format((float)$user->tax, 2, '.', '') ?></td>
                                </tr>
                                <tr style="font-weight:bold;">
                                    <td colspan="2" align="right">Shipping:</td>
                                    <td align="right">$<?= number_format((float)$user->shipping, 2, '.', '') ?></td>
                                </tr>
                                <tr style="font-weight:bold;">
                                    <td colspan="2" align="right">Total:</td>
                                    <td align="right">$<?= number_format((float)$user->total, 2, '.', '') ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#ffffff" style="padding:20px 30px; text-align:center; font-family:Montserrat, sans-serif;">
                            <p style="font-size: 14px; line-height:20px; margin:0;">
                                You will receive another email once your items are shipped 🚚
                            </p>
                            <p style="font-size: 14px; line-height:20px; margin:15px 0 0;">
                                If you have any questions, contact us at
                                <a href="mailto:<?= Yii::$app->params['contactEmail'] ?>" style="color:#000;"><?= Yii::$app->params['contactEmail'] ?></a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>