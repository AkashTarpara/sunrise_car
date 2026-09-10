<?php
use yii\helpers\Html;
use app\models\Adminuser;
use app\models\Generalsetting;
//$adminuser=Adminuser::find()->where(['admin_id'=>1])->one();
$adminuser=Generalsetting::find()->where(['setting_id'=>1])->one();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0 " />
<title>Rajasthan Royals</title>
<style type="text/css">
body {
margin: 0 !important;
padding: 0 !important;
width: 100% !important;
-webkit-text-size-adjust: 100% !important;
-ms-text-size-adjust: 100% !important;
-webkit-font-smoothing: antialiased !important;
}
.ExternalClass * {line-height: 100%}
table {border-collapse: collapse !important;  padding: 0px !important;  border: none !important;  border-bottom-width:0px !important;  mso-table-lspace:0pt;  mso-table-rspace: 0pt;}
table td {border-collapse:collapse;}
img {
border: 0 !important;
display: block !important;
outline: none !important;
}
.applelinksWhite a {color:inherit !important; text-decoration:none !important;}
@media only screen and (max-width:480px){
	.wrapper{
		width:100% !important;}
	.hide{
		display:none !important;}
	.text{
		text-align:center !important;}	
	.padding{
		padding-top:15px !important;}	
	.padding_onn{
		padding-left:14px !important;
		padding-right:14px !important;}
	.bg_size{
		background-size:cover !important;}
	.side_space {
	    width: 20px !important; }	
	}
@media only screen and (min-width:481px) and (max-width:599px) {
	.wrapper{
		width:100% !important;}
	.hide{
		display:none !important;}
	.text{
		text-align:center !important;}	
	.padding{
		padding-top:15px !important;}	
	.padding_onn{
		padding-left:14px !important;
		padding-right:14px !important;}
	.bg_size{
		background-size:cover !important;}
	.side_space {
	 width: 20px !important; }	
	}
</style>
</head>
<body marginheight="0" marginwidth="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" bgcolor="#ffffff">
	<tr>
    	<td valign="top" align="center">
        	<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:600px;" bgcolor="#000000">
            	<tr>
					<td class="hide" style="line-height:1px; font-size:1px;" width="600"><img src="<?=Yii::$app->params['domain']?>uploads/images/default/email/spacer_img.gif" height="1"  width="600" style="max-height:1px; min-height:1px; display:block; width:600px; min-width:600px;" border="0" /></td>
                </tr>
               <tr background="<?=Yii::$app->params['domain']?>uploads/default/email/spasrk_headre.png">
               		<td align="center" valign="top" >
                    	<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:600px;" >
                        	<tr>
                               <!--  <td valign="top" align="center" background="http://www.rajasthanroyals.com/assets/images/header_bg.png"  style="background-position:bottom center; background-repeat:no-repeat; height:153px;" height="153">
                                    <!--[if gte mso 9]>
                                  <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:153px;">
                                    <v:fill type="tile" src="http://www.rajasthanroyals.com/assets/images/header_bg.png" color="#000000" />
                                    <v:textbox inset="0,0,0,0">
                                  <![endif]-->
                                    <table width="560" border="0" cellspacing="0" cellpadding="0" align="center"  class="wrapper" style="width:560px;">
                                        <tr>
                                            <td  align="center" valign="top" >
                                                <table width="560" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:560px;">
                                                    <tr>
                                                        <td align="left" valign="top" width="5" class="hide"></td>
                                                        <td width="550" align="left" valign="top" style="padding-top:15px;" class="padding_onn"><a href="#"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/logo_white.png" alt="Rajasthan Royals" width="50" style="display:block;width:50px;margin-bottom: 10px;" border="0"></a></td>
                                                    </tr>
                                                </table>                                    
                                            </td>
                                        </tr>
                                    </table> 
                                    <!--[if gte mso 9]>
                                    </v:textbox>
                                  </v:rect>
                                  <![endif]
                               </td> -->
                          </tr>
                        </table>
                    </td>
               </tr>
               <tr>
               		<td align="center" valign="top" >
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
               <tr>
               		<td align="center" valign="top">
                    	<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:600px;">
                        	<tr>
                                <td valign="top" align="center" background="http://www.rajasthanroyals.com/assets/images/footer_bg.png"  style="background-position:top center; background-repeat:no-repeat; height:114px;" height="114">
                                    <!--[if gte mso 9]>
                                  <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:114px;">
                                    <v:fill type="tile" src="http://www.rajasthanroyals.com/assets/images/footer_bg.png" color="#000000" />
                                    <v:textbox inset="0,0,0,0">
                                  <![endif]-->
                                    <table width="560" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:560px;">
                                        <tr>
                                            <td  align="center" valign="top" style="padding-top:15px; padding-top:14px;">
                                                <table width="144" border="0" cellspacing="0" cellpadding="0" align="center" style="width:144px;">
                                                    <tr>
                                                        
                                                      <td align="left" valign="top" width="7"><a href="<?= $adminuser->facebook?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/fb_icon.png" alt="fb" width="7" height="12" style="display:block;" border="0"></a></td>
                                                        <td align="left" valign="top" width="10"></td>
                                                        
                                                  		<td align="left" valign="top" width="12"><a href="<?= $adminuser->twitter?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/tw_icon.png" alt="tw" width="12" height="12" style="display:block;" border="0"></a></td>
                                                        <td align="left" valign="top" width="9"></td>
                                                        
                                                      <td align="left" valign="top" width="15"><a href="<?= $adminuser->youtube?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/yt_icon.png" alt="yt" width="15" height="12" style="display:block;" border="0"></a></td>
                                                      <td align="left" valign="top" width="9"></td>
                                                      
                                                      <td align="left" valign="top" width="13"><a href="<?= $adminuser->snapchat?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/snapchat_icon.png" alt="snapchat" width="13" height="12" style="display:block;" border="0"></a></td>
                                                      <td align="left" valign="top" width="9"></td>
                                                      
                                                      <td align="left" valign="top" width="15"><a href="<?= $adminuser->instagram?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/insta_icon.png" alt="insta" width="15" height="12" style="display:block;" border="0"></a></td>
                                                      <td align="left" valign="top" width="9"></td>
                                                      
                                                      <td align="left" valign="top" width="15"><a href="<?= $adminuser->tiktok?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/playstore_icon.png" alt="playstore" width="13" height="12" style="display:block;" border="0"></a></td>
                                                      <td align="left" valign="top" width="9"></td>
                                                      
                                                      <td align="left" valign="top" width="12"><a href="<?= $adminuser->facebook?>" target="_blank"><img src="<?=Yii::$app->params['domain']?>uploads/default/email/apple_icon.png" alt="applestore" width="12" height="12" style="display:block;" border="0"></a></td>
                                                    </tr>
                                                </table>                                    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  align="center" valign="top" class="padding_onn">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="width:100%;">
                                                 <tr>
                                                    <td valign="top">
                                                        <table width="310" border="0" cellspacing="0" cellpadding="0" align="left" class="wrapper" style="width:310;">
                                                            <tr>
                                                            	<td class="text padding" align="left" valign="top" style="font-family:'work Sans', Arial, sans-serif; font-size:10px; color:#ffffff; line-height:12px; padding-top:10px;">Make sure you do not miss out on any future Rajasthan Royals updates by adding <a href="mailto:admin@rajasthanroyals.com" style=" text-decoration:none; color:#EA1985;">admin@rajasthanroyals.com</a> to your address book or safe-senders list.</td>
                                                            </tr>
                                                        </table> 
                                                        <table width="210" border="0" cellspacing="0" cellpadding="0" align="right" class="wrapper" style="width:210;">
                                                            <tr>
                                                            	<td class="text" align="left" valign="top" style="font-family:'work Sans', Arial, sans-serif; font-size:10px; color:#ffffff; line-height:12px; padding-top:10px;">Please drop us a line at  <a href="mailto:admin@rajasthanroyals.com" style=" text-decoration:none; color:#EA1985;">admin@rajasthanroyals.com</a> for any comments or suggestions.</td>
                                                            </tr>
                                                        </table>                                    
                                                    </td>
                                                </tr>   
                                                </table>                                    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top" style="font-family:'work Sans', Arial, sans-serif; font-size:10px; color:#ffffff; line-height:12px; font-weight:bold; padding-top:10px; text-decoration:underline;">Don't want deliciousness in your inbox? <a href="#" style=" text-decoration:underline; color:#EA1985;">Unsubscribe here</a></td>
                                        </tr>
                                        <tr>
                                        	<td height="15px"></td>
                                        </tr>
                                    </table>
                                    <!--[if gte mso 9]>
                                    </v:textbox>
                                  </v:rect>
                                  <![endif]-->
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
<?php $this->endPage() ?>
