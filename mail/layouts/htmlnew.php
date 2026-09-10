<?php

use yii\helpers\Html;
use app\models\Adminuser;
use app\models\Generalsetting;
//$adminuser=Adminuser::find()->where(['admin_id'=>1])->one();
$adminuser = Generalsetting::find()->where(['setting_id' => 1])->one();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <title>Rajasthan Royals</title>

</head>

<body marginheight="0" marginwidth="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" bgcolor="#ffffff">
        <tr>
            <td valign="top" align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:600px;" bgcolor="#ffffff">

                    <tr>
                        <td align="center" valign="top">
                            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:600px;">
                                <tr>
                                    <td align="center" valign="top" style="background-color:#000">

                                        <?php $this->beginBody() ?>
                                        <?= $content ?>
                                        <?php $this->endBody() ?>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- <tr>
               	<td align="center" valign="top" style="padding-top:20px;"><img src="http://www.rajasthanroyals.com/assets/images/spark_img.png" width="256" style="display:block;" border="0"></td>
               </tr> -->
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
<?php $this->endPage() ?>