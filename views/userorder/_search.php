<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserorderSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="userorder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_order_id') ?>

    <?= $form->field($model, 'appuser_id') ?>

    <?= $form->field($model, 'appuser_address_id') ?>

    <?= $form->field($model, 'payment_type') ?>

    <?= $form->field($model, 'payment_status') ?>

    <?php // echo $form->field($model, 'payment_id') ?>

    <?php // echo $form->field($model, 'sub_total') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'payment_date') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
