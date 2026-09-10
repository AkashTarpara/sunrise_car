<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdminsidemenudetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adminsidemenudetail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'admin_sidemenu_detail_id') ?>

    <?= $form->field($model, 'admin_sidemenu_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'controller_name') ?>

    <?= $form->field($model, 'action_name') ?>

    <?php // echo $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
