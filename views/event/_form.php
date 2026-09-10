<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>
<div class="row">
    <div class="col-md-6"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-6"><?= $form->field($model, 'category')->textInput(['maxlength' => true, 'placeholder' => 'Concert, Sports, Festival']) ?></div>
</div>
<div class="row">
    <div class="col-md-3"><?= $form->field($model, 'event_date')->input('date') ?></div>
    <div class="col-md-3"><?= $form->field($model, 'event_time')->input('time') ?></div>
    <div class="col-md-6"><?= $form->field($model, 'venue')->textInput(['maxlength' => true]) ?></div>
</div>
<div class="row">
    <div class="col-md-6"><?= $form->field($model, 'location')->textInput(['maxlength' => true, 'placeholder' => 'Miami, FL']) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'latitude')->textInput(['placeholder' => '25.7617']) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'longitude')->textInput(['placeholder' => '-80.1918']) ?></div>
</div>
<?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
<?= $form->field($model, 'image')->fileInput() ?>
<?php if (!$model->isNewRecord && $model->image): ?>
    <img src="<?= Yii::$app->params['ImagePath'] . $model->image ?>" alt="Event image" style="max-width:240px;max-height:140px;margin-bottom:16px;">
<?php endif; ?>
<div class="row">
    <div class="col-md-6"><?= $form->field($model, 'status')->dropDownList(['Active' => 'Active', 'Inactive' => 'Inactive']) ?></div>
</div>
<div class="form-group custom-save-button">
    <?= Html::submitButton('Save', ['class' => 'btn btn-light-success']) ?>
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-light-danger']) ?>
</div>
<?php ActiveForm::end(); ?>
