<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Spacetype $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="spacetype-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['spacetype/'], ['class' => 'btn btn-light-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
