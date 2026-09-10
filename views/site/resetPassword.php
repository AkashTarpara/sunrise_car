<style type="text/css">
  .form-group .form-control-feedback {
    top: 0;
    pointer-events: initial;
    /* or - auto // or -  unset  */
  }
</style>
<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\Alert;

$this->title = 'Reset Password';
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
  <?php $image = Yii::getAlias('@web') . '/uploads/default/home-0.webp'; ?>
  <a href="#"><img src="<?= $image ?>" alt="img" style="padding: 20px; width:50%"></a>
</div>
<h4 class="text-center f-w-500 mb-3">Reset your Password</h4>
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
<?php $fieldOptions3 = [
  'options' => ['class' => 'form-group has-feedback'],
  'inputTemplate' => "{input}<span toggle='#password-field' class='form-control-feedback glyphicon glyphicon-eye-close toggle-password'></span>"
]; ?>
<?php $fieldOptions4 = [
  'options' => ['class' => 'form-group has-feedback'],
  'inputTemplate' => "{input}<span toggle='#password-field' class='form-control-feedback glyphicon glyphicon-eye-close toggle-password1'></span>"
]; ?>
<div class="form-group mb-3">
  <?= $form->field($model, 'password', $fieldOptions3)->label('Password')->passwordInput() ?>
</div>
<div class="form-group mb-3">
  <?= $form->field($model, 'password_repeat', $fieldOptions4)->label('Confirm Password')->passwordInput() ?>
</div>
<div class="d-grid mt-4">
  <button type="submit" class="btn btn-primary btn-block">Reset</button>
</div>
<?php ActiveForm::end(); ?>

<script type='text/javascript'>
  $(document).on('click', '.toggle-password', function() {
    $(this).toggleClass("glyphicon glyphicon-eye-open glyphicon glyphicon-eye-close");
    var input = $("#passwordform-password");
    input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
  });
  $(document).on('click', '.toggle-password1', function() {
    $(this).toggleClass("glyphicon glyphicon-eye-open glyphicon glyphicon-eye-close");
    var input = $("#passwordform-password_repeat");
    input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
  });
</script>