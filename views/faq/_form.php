<?php

use app\models\Faq;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Faq $model */
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4"><?= $form->field($model, 'type')->dropDownList(Faq::typeList(), ['prompt' => 'Select Type']) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'display_order')->input('number', ['min' => 0]) ?></div>
    <div class="col-md-4"><?= $form->field($model, 'status')->dropDownList(['Active' => 'Active', 'Inactive' => 'Inactive']) ?></div>
</div>
<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>
<div class="form-group custom-save-button">
    <?= Html::submitButton('Save', ['class' => 'btn btn-light-success']) ?>
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-light-danger']) ?>
</div>
<?php ActiveForm::end(); ?>
<script>
    $('#faq-description').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });
</script>
