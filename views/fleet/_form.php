<?php

use app\models\Fleet;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>

<div class="row">
    <div class="col-md-6"><?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-6"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
</div>

<div class="row">
    <div class="col-md-3"><?= $form->field($model, 'passenger')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'laggage')->textInput(['type' => 'number', 'min' => 0]) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'type')->dropDownList(Fleet::typeOptions(), ['prompt' => 'Select Type']) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'status')->dropDownList(['Active' => 'Active', 'Inactive' => 'Inactive']) ?></div>
</div>

<div class="row">
    <div class="col-md-3"><?= $form->field($model, 'base_price')->textInput(['type' => 'number', 'min' => 0, 'step' => '0.01'])->label('Base Price ($)') ?></div>
    <div class="col-md-3"><?= $form->field($model, 'km_per_hour_price')->textInput(['type' => 'number', 'min' => 0, 'step' => '0.01'])->label('Price Per Mile ($)') ?></div>
    <div class="col-md-3"><?= $form->field($model, 'hourly_price')->textInput(['type' => 'number', 'min' => 0, 'step' => '0.01'])->label('Hourly Price ($/hr)')->hint('Hourly charter rate') ?></div>
    <div class="col-md-3"><?= $form->field($model, 'minimum_hours')->textInput(['type' => 'number', 'min' => 1, 'step' => 1])->label('Minimum Hours')->hint('Min hours required to book') ?></div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'is_available')->dropDownList([1 => 'Yes — Available', 0 => 'No — Unavailable (maintenance etc.)'])->hint('Manually mark this vehicle as available or unavailable regardless of bookings.') ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'available_after')->textInput(['type' => 'date'])->hint('Leave blank to let the system manage this automatically based on bookings. Set a date to block this vehicle until that date.') ?>
    </div>
</div>

<?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

<?php if (!$model->isNewRecord && $model->fleetImages): ?>
    <div class="row mb-3">
        <?php foreach ($model->fleetImages as $fleetImage): ?>
            <div class="col-md-3 mb-3">
                <div class="border p-2">
                    <img src="<?= Yii::$app->params['ImagePath'] . $fleetImage->image ?>" alt="Fleet image" style="width:100%;height:140px;object-fit:cover;margin-bottom:8px;">
                    <label>
                        <input type="checkbox" name="delete_image_ids[]" value="<?= $fleetImage->id ?>">
                        Delete image
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="form-group custom-save-button">
    <?= Html::submitButton('Save', ['class' => 'btn btn-light-success']) ?>
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-light-danger']) ?>
</div>

<?php ActiveForm::end(); ?>
