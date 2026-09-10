<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Userorder $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="userorder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'appuser_id')->textInput() ?>

    <?= $form->field($model, 'appuser_address_id')->textInput() ?>

    <?= $form->field($model, 'payment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_total')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'payment_date')->textInput() ?>

    <?= $form->field($model, 'order_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
