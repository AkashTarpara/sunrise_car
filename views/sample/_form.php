<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Sample $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sample-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_total')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'shipping')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'payment_date')->textInput() ?>

    <?= $form->field($model, 'order_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_status')->dropDownList([ 'Pending' => 'Pending', 'Delivered' => 'Delivered', 'Canceled' => 'Canceled', 'Packaging' => 'Packaging', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
