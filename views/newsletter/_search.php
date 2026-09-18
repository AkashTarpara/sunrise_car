<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsletterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="newsletter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'newsletter_id') ?>

    <?= $form->field($model, 'news_catagory_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'thumbnail_image') ?>

    <?php // echo $form->field($model, 'image_height') ?>

    <?php // echo $form->field($model, 'image_width') ?>

    <?php // echo $form->field($model, 'thumbnail_height') ?>

    <?php // echo $form->field($model, 'thumbnail_width') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
