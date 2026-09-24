<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\Appuser */

$token = !empty($user->password_reset_token) ? $user->password_reset_token : '';

// Generate route URL
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/resetpassword', 'token' => $token]);

// Ensure scheme is HTTPS when appropriate (e.g., behind SSL proxy or production domain)
$isHttps = (
    (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
    || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    || (isset(Yii::$app->request) && Yii::$app->request->isSecureConnection)
    || (strpos($resetLink, 'sunriseblackcar.com') !== false)
);

if ($isHttps && strpos($resetLink, 'http://') === 0) {
    $resetLink = 'https://' . substr($resetLink, 7);
}

$projectName = !empty(Yii::$app->params['project_display_name']) ? Yii::$app->params['project_display_name'] : 'Sunrise Black Car';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <title>Reset Password - <?= Html::encode($projectName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; background-color: #f4f6f8; font-family: 'Montserrat', Arial, Helvetica, sans-serif; }
        .reset-btn:hover { background-color: #222222 !important; border-color: #222222 !important; }
    </style>
</head>

<body marginheight="0" marginwidth="0" style="margin: 0; padding: 20px 0; background-color: #f4f6f8;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#f4f6f8">
        <tr>
            <td align="center" valign="top" style="padding: 20px 10px;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" valign="top" style="background-color: #000000; padding: 30px 20px;">
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin: 0; font-family: 'Montserrat', Arial, sans-serif;">
                                <?= Html::encode($projectName) ?>
                            </h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td align="left" valign="top" style="padding: 40px 35px 30px 35px; background-color: #ffffff;">
                            <h2 style="font-size: 18px; font-weight: 700; color: #111111; margin: 0 0 15px 0; font-family: 'Montserrat', Arial, sans-serif;">
                                Hello <?= Html::encode(!empty($user->full_name) ? $user->full_name : '') ?>,
                            </h2>
                            <p style="font-size: 15px; line-height: 24px; color: #444444; margin: 0 0 25px 0;">
                                We received a request to reset the password for your account. Click the button below to set a new password:
                            </p>

                            <!-- Bulletproof Button -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" bgcolor="#000000" style="border-radius: 6px;">
                                                    <a href="<?= Html::encode($resetLink) ?>"
                                                       target="_blank"
                                                       class="reset-btn"
                                                       style="display: inline-block; padding: 16px 38px; font-family: 'Montserrat', Arial, Helvetica, sans-serif; font-size: 15px; font-weight: 700; color: #ffffff !important; text-decoration: none; border-radius: 6px; background-color: #000000; border: 1px solid #000000; text-transform: uppercase; letter-spacing: 1.5px; text-align: center; cursor: pointer; -webkit-tap-highlight-color: transparent;">
                                                        RESET PASSWORD
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Fallback Link -->
                            <p style="font-size: 13px; line-height: 20px; color: #777777; margin: 25px 0 8px 0; text-align: center;">
                                If the button above does not work, copy and paste this link into your browser:
                            </p>
                            <p style="font-size: 12px; line-height: 18px; text-align: center; margin: 0 0 25px 0; word-break: break-all;">
                                <a href="<?= Html::encode($resetLink) ?>" target="_blank" style="color: #4680ff; text-decoration: underline;">
                                    <?= Html::encode($resetLink) ?>
                                </a>
                            </p>

                            <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;" />

                            <p style="font-size: 13px; line-height: 20px; color: #888888; margin: 0;">
                                If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" valign="top" style="background-color: #f8f9fa; padding: 20px; border-top: 1px solid #eeeeee;">
                            <p style="font-size: 12px; line-height: 18px; color: #999999; margin: 0;">
                                &copy; <?= date('Y') ?> <?= Html::encode($projectName) ?>. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>