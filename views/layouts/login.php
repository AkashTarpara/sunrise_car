<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use diecoding\toastr\ToastrFlash;

$asset      = app\assets\AppAsset::register($this);
//echo $asset; exit;
$baseUrl    = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias("@web") . "/uploads/default/favicon-32x32.png" ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= Yii::getAlias("@web") . "/uploads/default/favicon-32x32.png" ?>">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ff86d6">
  <meta name="msapplication-TileColor" content="#ff86d6">
  <meta name="theme-color" content="#ffffff">



  <style type="text/css">
    .has-error {
      color: #a94442;
    }

    .auth-main .auth-wrapper.v1 .auth-form {
      background: unset !important;
      ;
    }

    .card {
      background-color: transparent !important;
      border-color: transparent !important;
    }
  </style>
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode(Yii::$app->name) ?></title>
  <?php $this->head() ?>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="light">
  <?php $this->beginBody() ?>
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <div class="auth-main">
    <div class="auth-wrapper v1">
      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <?= ToastrFlash::widget(); ?>
            <?= $content ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>