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
<div class="text-center">
  <?php $image = Yii::getAlias("@web") . "/uploads/default/login_logo.png"; ?>
  <a href="#"><img src="<?= $image ?>" alt="img" style="padding: 20px; width:50%"></a>
</div>
<h4 class="text-center f-w-500 mb-3">Login with your email</h4>
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
<div class="form-group mb-3">
  <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => "Enter email"]) ?>
</div>
<div class="form-group mb-3">
  <?= $form->field($model, 'password')->passwordInput(['placeholder' => "Enter password"]) ?>
</div>
<div class="d-grid mt-4">
  <button type="submit" class="btn btn-primary btn-block">Login</button>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>