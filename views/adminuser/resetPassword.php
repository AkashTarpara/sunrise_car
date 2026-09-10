<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="manageteam-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="row"> 
    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <!-- <?= $form->field($model, 'old_password')->textInput(['maxlength' => true]) ?> -->
      <?= $form->field($model,'old_password',['inputOptions'=>['placeholder'=>'Enter old Password']])->passwordInput() ?>
    </div>
  </div>
  <div class="row"> 
    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <?= $form->field($model,'password',['inputOptions'=>['placeholder'=>'Enter New Password']])->passwordInput() ?>
    </div>
  </div>
  <div class="row"> 
    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <?= $form->field($model,'password_repeat',['inputOptions'=>['placeholder'=>'Repeat New Password']])->passwordInput() ?>
    </div>
  </div>
  <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
    <?= Html::a('Cancel',['/dashboard'], ['class' => 'btn btn-light-danger']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>