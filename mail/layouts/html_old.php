<?php
use yii\helpers\Html;
//use app\models\Adminuser;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
//$adminuser=Adminuser::find()->where(['admin_id'=>1])->one();
$web_url=Yii::$app->params['web_url'];
$twitter_url=Yii::$app->params['twitter_url'];
$linkedIn_url=Yii::$app->params['linkedIn_url'];
$instagram_url=Yii::$app->params['instagram_url'];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<style type="text/css">

body {width: 100% !important; height: 100%; margin: 0; line-height: 1.4; background-color: #eff9ff; color: #74787E; -webkit-text-size-adjust: none;}

@media only screen and (max-width: 600px) {
    .email-body_inner {
    width: 100% !important;
    }
    .email-footer {
    width: 100% !important;
    }
}
@media only screen and (max-width: 500px) {
    .button {
    width: 100% !important;
    }
}
</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body style="-webkit-text-size-adjust: none; box-sizing: border-box; color: #74787E; font-family: Arial, Helvetica, sans-serif; height: 100%; line-height: 1.4; margin: 0; width: 100% !important;" bgcolor="#eff9ff">
<div id="mailsub" class="notification" align="center">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;">
		<tr>
			<td align="center" bgcolor="#eff3f8">
				<table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
					<tr>
						<td>
							<!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
						</td>
					</tr>
					<!--header -->
					<tr>
						<td align="center" bgcolor="#c52e73">
						<!-- padding -->
						<table width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr><td align="center"><!--
								Item --><div class="mob_center_bl" style="display: inline-block; width: 115px;">
									<table class="mob_center" width="115" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
										<tr><td align="left" valign="middle">
											<!-- padding --><div style="height: 10px; line-height: 20px; font-size: 10px;"> </div>
											<table width="115" border="0" cellspacing="0" cellpadding="0" >
												<tr><td align="left" valign="top" class="mob_center">
													<a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
														<font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
													<img src="<?=Yii::$app->params['domain']?>uploads/default/home-0.webp" width="100%"  alt="<?=Yii::$app->params['project_display_name']?>" border="0" style="display: block;" /></font></a>
												</td></tr>
											</table>
										</td></tr>
									</table></div></td>
								</tr>
							</table>
							<div style="height: 10px; line-height: 30px; font-size: 10px;">&nbsp;</div>
						</td>
					</tr>
					
					<?php $this->beginBody() ?>
			    	
			    	<?= $content ?>

			    	<?php $this->endBody() ?>

			    	<tr>
			    		<td class="iage_footer" align="center" bgcolor="#ffffff">
							<!-- padding --><div style="height: 10px; line-height: 80px; font-size: 10px;"> </div>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr><td align="center">
									<font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
									<span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
										
										<table width="15%" border="0" cellspacing="0" cellpadding="0">
											<tr align="center">
												
												<td style=" margin: 3px;"><a href="<?= $web_url?>"><img src="<?=Yii::$app->params['domain']?>uploads/images/default/web.png" width="100%"  alt="www" border="0" style="display: block;"/></a></td>

												<td style=" margin: 3px;"><a href="<?= $twitter_url?>"><img src="<?=Yii::$app->params['domain']?>uploads/images/default/Twitter.png" width="100%"  alt="Twitter" border="0" style="display: block;"/></a></td>

												<td style=" margin: 3px;"><a href="<?= $linkedIn_url?>"><img src="<?=Yii::$app->params['domain']?>uploads/images/default/LinkedIn.png" width="100%"  alt="linkedin" border="0" style="display: block;"/></a></td>

												<td style=" margin: 3px;"><a href="<?= $instagram_url ?>"><img src="<?=Yii::$app->params['domain']?>uploads/images/default/Instagram.png" width="100%"  alt="Instagram" border="0" style="display: block; "/></a></td>

											</tr>
										</table>
									
									</span></font>
								</td></tr>
							</table>
							<!-- padding --><div style="height: 10px; line-height: 30px; font-size: 10px;"> </div>
						</td>
					</tr>
					<tr>
						<td class="iage_footer" align="center" bgcolor="#c52e73">
						<!-- padding --><div style="height: 10px; line-height: 80px; font-size: 10px;"> </div>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr><td align="center">
									<font face="Arial, Helvetica, sans-serif" size="3" color="#ffffff" style="font-size: 13px;">
									<span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #ffffff;">
										<?php echo date('Y') ?> © <?=Yii::$app->params['project_display_name']?>. ALL Rights Reserved.
									</span></font>
								</td></tr>
							</table>
							<!-- padding --><div style="height: 10px; line-height: 30px; font-size: 10px;"> </div>
						</td>
					</tr>
					<!--footer END-->
					<tr>
						<td>
							<!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
<?php $this->endPage() ?>
