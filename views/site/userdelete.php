<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
  .form-control,
  .datatable-input,
  .custom-select,
  .dataTable-selector,
  .dataTable-input {
    color: #fff !important;
    border-color: #E7E8EA !important;
    background-color: #333333 !important;
    min-height: calc(2.5em + 0.75rem + 2px) !important;
    padding: 0.375rem 0.7rem !important;
    font-size: 0.765625rem !important;
    border-radius: 6px !important;
    border: 0px solid #bec8d0 !important;
  }
</style>
<div class="delete-box" style="text-align:center; padding: 1%;" wi>
  <div class="login-logo">
    <a href="<?= Yii::$app->params['domain'] ?>"><img src=<?= Yii::getAlias("@web") . "/uploads/default/login_logo.svg" ?> alt=<?= Yii::$app->params['project_display_name'] ?> style="width: 165px;"></a>
  </div>


  <?php
  if ($model->is_deleted == 'No') { ?>
    <img src=<?= Yii::getAlias("@web") . "/uploads/default/delete_account.svg" ?> alt=<?= Yii::$app->params['project_display_name'] ?> class="user-image" style="padding-top: 3%;">
    <div class="row">

      <div class="col-md-12">
        <p style="color: #898989; font-size: 22px; margin-bottom: -1%;">Are you sure you want to</p>
        <p style="color: #FFFFFF; font-size: 25px; padding-bottom: 5%;"><b>DELETE YOUR ACCOUNT?</b></p>
      </div>

    </div>
    <div class="row" style="padding-bottom: 3%;">

      <div class="col-md-6">
        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['userlogin']) ?>">
          <img src=<?= Yii::getAlias("@web") . "/uploads/default/no.svg" ?> alt=<?= Yii::$app->params['project_display_name'] ?> class="user-image" style="width: 100%; height: 100%;">
        </a>
      </div>
      <div class="col-md-6">
        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['userdelete', 'id' => $model->appuser_id]) ?>">
          <img src=<?= Yii::getAlias("@web") . "/uploads/default/yes.svg" ?> alt=<?= Yii::$app->params['project_display_name'] ?> class="user-image" style="width: 100%; height: 100%;">
        </a>
      </div>

    </div>
  <?php } else { ?>
    <img src=<?= Yii::getAlias("@web") . "/uploads/default/successfully_delete.svg" ?> alt=<?= Yii::$app->params['project_display_name'] ?> class="user-image" style="padding-top: 3%;">
    <div class="row">

      <div class="col-md-12">
        <p style="color: #FFFFFF; font-size: 25px; margin-bottom: -1%; padding-top: 5%;">Your account has been successfully deleted.</p>
      </div>

    </div>
  <?php } ?>
</div>
<!-- /.login-box-body -->