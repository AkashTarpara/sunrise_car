<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstallationSchedulingRequest */

$yesNo = function ($value) {
    return $value ? 'Yes' : 'No';
};

$labelize = function ($value) {
    if ($value === null || $value === '') {
        return '-';
    }

    return ucwords(str_replace('_', ' ', $value));
};

$sections = [
    'Contact Information' => [
        'Customer Name' => $model->customer_name,
        'Phone Number' => $model->phone_number,
        'Email Address' => $model->email_address,
    ],
    'Project Information' => [
        'Installation Address Line 1' => $model->installation_address_line1,
        'Installation Address Line 2' => $model->installation_address_line2,
        'Property Type' => $labelize($model->property_type),
        'Property Type Other' => $model->property_type_other,
    ],
    'Preferred Schedule' => [
        'Preferred Contact Time' => $labelize($model->preferred_contact_time),
        'Preferred Installation Timeframe' => $labelize($model->preferred_installation_timeframe),
    ],
    'Site Access Information' => [
        'Someone Present During Installation' => $yesNo($model->will_someone_be_present),
        'Gate Code Required' => $yesNo($model->gate_code_required),
        'Building Access Required' => $yesNo($model->building_access_required),
        'Elevator Reservation Required' => $yesNo($model->elevator_reservation_required),
        'Parking Restrictions' => $yesNo($model->parking_restrictions),
        'Other Access Restriction' => $yesNo($model->access_other),
        'Other Access Note' => $model->access_other_note,
    ],
    'Additional Project Notes' => [
        'Notes' => $model->additional_project_notes,
    ],
    'Acknowledgement' => [
        'Agreed' => $yesNo($model->agreed),
        'Signature' => $model->signature,
        'Date' => $model->signed_date,
    ],
];
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#18191b" style="background:#18191b; margin:0; padding:0;">
    <tr>
        <td valign="top" align="center" style="padding:28px 14px; font-family:Arial, Helvetica, sans-serif;">
            <table width="700" border="0" cellspacing="0" cellpadding="0" align="center" style="width:700px; max-width:700px; background:#202123; color:#f5f5f5; border-radius:18px; overflow:hidden; box-shadow:0 18px 44px rgba(0,0,0,0.38);">
                <tr>
                    <td bgcolor="#000000" style="background:#000000; padding:24px 30px; border-bottom:1px solid #343638;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td valign="middle" align="left">
                                    <div style="font-size:30px; line-height:30px; font-weight:700; letter-spacing:0.4px; color:#ffffff;">
                                        Luxury<br>
                                        <span style="color:#d8dddd;">Layers</span>
                                    </div>
                                    <div style="font-size:13px; line-height:18px; color:#aeb4b4; margin-top:4px;">Luxury to the Core</div>
                                </td>
                                <td valign="middle" align="right">
                                    <span style="display:inline-block; padding:11px 18px; border-radius:999px; background:#f0c64a; color:#0b0b0b; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                        INSTALLATION
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:34px 30px 20px; background:#202123;">
                        <div style="font-family:Georgia, 'Times New Roman', serif; font-size:34px; line-height:42px; font-style:italic; color:#ffffff; margin:0;">
                            New Installation Scheduling Request
                        </div>
                        <div style="font-size:14px; line-height:22px; color:#c7cccc; margin-top:10px;">
                            A customer submitted an installation scheduling request from the Luxury Layers website.
                        </div>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:22px;">
                            <tr>
                                <td width="50%" style="padding:12px 14px; background:#2f3032; border:1px solid #525457; border-radius:12px; color:#ffffff;">
                                    <div style="font-size:12px; color:#b7bbbb; text-transform:uppercase; letter-spacing:0.8px;">Request ID</div>
                                    <div style="font-size:20px; font-weight:700; margin-top:4px;">#<?= Html::encode($model->id) ?></div>
                                </td>
                                <td width="14">&nbsp;</td>
                                <td width="50%" style="padding:12px 14px; background:#2f3032; border:1px solid #525457; border-radius:12px; color:#ffffff;">
                                    <div style="font-size:12px; color:#b7bbbb; text-transform:uppercase; letter-spacing:0.8px;">Submitted</div>
                                    <div style="font-size:16px; font-weight:700; margin-top:4px;"><?= Html::encode(date('Y-m-d H:i:s', $model->created_at)) ?></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <?php foreach ($sections as $title => $rows) { ?>
                    <tr>
                        <td style="padding:14px 30px 0; background:#202123;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#262729; border:1px solid #414346; border-radius:16px; overflow:hidden;">
                                <tr>
                                    <td style="padding:17px 18px; background:#000000; border-bottom:1px solid #414346;">
                                        <h3 style="margin:0; font-size:18px; line-height:24px; color:#ffffff; font-weight:700;"><?= Html::encode($title) ?></h3>
                                    </td>
                                </tr>
                                <?php foreach ($rows as $label => $value) { ?>
                                    <tr>
                                        <td style="padding:0;">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #3b3d40;">
                                                <tr>
                                                    <td width="36%" valign="top" style="padding:13px 18px; color:#c6cccc; font-size:13px; line-height:19px; font-weight:700; background:#2d2e30;">
                                                        <?= Html::encode($label) ?>
                                                    </td>
                                                    <td valign="top" style="padding:13px 18px; color:#ffffff; font-size:14px; line-height:21px; background:#262729;">
                                                        <?= nl2br(Html::encode($value !== null && $value !== '' ? $value : '-')) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td style="padding:24px 30px 32px; background:#202123;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#000000; border-radius:16px;">
                            <tr>
                                <td align="center" style="padding:18px 22px; color:#b8bebe; font-size:12px; line-height:18px;">
                                    This notification was generated automatically by Luxury Layers.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
