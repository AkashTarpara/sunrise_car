<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\InstallationSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="installation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'installation_id') ?>

    <?= $form->field($model, 'label') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'sub_title') ?>

    <?= $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'before_image') ?>

    <?php // echo $form->field($model, 'after_image') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
