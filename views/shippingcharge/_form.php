<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Shippingcharge $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shippingcharge-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'min_mile')->textInput() ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'max_mile')->textInput() ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'price')->textInput() ?>
        </div>
    </div>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['shippingcharge/'], ['class' => 'btn btn-light-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>