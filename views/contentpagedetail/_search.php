<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ContentpagedetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="contentpagedetail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'content_page_detail_id') ?>

    <?= $form->field($model, 'content_page_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'section_title') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'sub_title') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'button_1_title') ?>

    <?php // echo $form->field($model, 'button_1_url') ?>

    <?php // echo $form->field($model, 'button_2_title') ?>

    <?php // echo $form->field($model, 'button_2_url') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'display_order') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
