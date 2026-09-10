<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AboutusSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="aboutus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'about_us_id') ?>

    <?= $form->field($model, 'banner_title') ?>

    <?= $form->field($model, 'banner_sub_title') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'sub_title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
