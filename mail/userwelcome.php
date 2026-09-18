<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title></title>

</head>
<!--[if !mso]><!-->
<style type="text/css">

</style>
<!--<![endif]-->

<body marginheight="0" marginwidth="0">
    <!--content 1 -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" bgcolor="#000000">
        <tr>
            <td valign="top" align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:600px;">

                    <tr>
                        <td align="left" valign="top" bgcolor="#000000">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" bgcolor="#ffffff" style="width:100%;padding-right: 0px;padding-left: 0px;padding-top: 15px;">
                                <tr>
                                    <td class="padding_onn h2_size" align="center" valign="top" bgcolor="#ffffff" style="font-family: 'futuramedium'; font-size:14px; line-height: 19px; color:#000000; font-weight:normal; padding-bottom:30px;box-shadow: 0 10px 15px 0 rgba(0,0,0,0.10);border-radius: 0px 0px 0 0;text-align: left;padding-left: 70px;padding-right: 70px;">
                                        <h2 style="font-style: italic;font-weight: 800;">Hello, <?= (!empty($user->full_name)) ? $user->full_name : '' ?></h2>
                                        <p style="font-size: 15px;line-height: 22px;font-weight:normal;font-style: italic;margin: 26px 0 33px 0;text-align: center;">We're sending you this email because you requested a password reset. Click on this button to create a new password.</p>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" bgcolor="#000000">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:100%;padding-right: 0px;padding-left: 0px;">
                                <tr>
                                    <td style="line-height:1px; font-size:1px;" width="600"><a href="<? ?>" target="_blank"><img src="<?= Yii::$app->params['ImagePath'] ?>uploads/default/forgot_password_3.png" width="100%" alt="b1 OFFICE DESIGN" style="display:block; border:0px;" border="0"></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" bgcolor="#000000">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" bgcolor="#ffffff" style="width:100%;padding-right: 0px;padding-left: 0px;">
                                <tr>
                                    <td class="padding_onn h2_size" align="center" valign="top" bgcolor="#ffffff" style="font-family: 'futuramedium'; font-size:14px; line-height: 19px; color:#000000; font-weight:normal; padding-bottom:30px;box-shadow: 0 10px 15px 0 rgba(0,0,0,0.10);border-radius: 0px 0px 0 0;text-align: left;padding-left: 70px;padding-right: 70px;">
                                        <p style="font-size: 15px;line-height: 22px;font-weight:normal;font-style: italic;margin: 26px 0 33px 0;text-align: center;">If you didn't request a password reset, you can ignore this email. Your password will not be changed.</p>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

    </table>
</body>


</html>
<!--content 1 END-->