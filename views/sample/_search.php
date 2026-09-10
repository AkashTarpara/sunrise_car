<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SampleSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sample-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sample_id') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'company_name') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone_no') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'address_line_2') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'zip_code') ?>

    <?php // echo $form->field($model, 'order_number') ?>

    <?php // echo $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'payment_status') ?>

    <?php // echo $form->field($model, 'payment_id') ?>

    <?php // echo $form->field($model, 'sub_total') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'shipping') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'payment_date') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <?php // echo $form->field($model, 'delivery_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
