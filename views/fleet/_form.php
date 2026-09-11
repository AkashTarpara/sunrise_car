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
    <div class="col-md-3"><?= $form->field($model, 'passenger')->textInput(['type' => 'number', 'min' => 0]) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'laggage')->textInput(['type' => 'number', 'min' => 0]) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'type')->dropDownList(Fleet::typeOptions(), ['prompt' => 'Select Type']) ?></div>
    <div class="col-md-3"><?= $form->field($model, 'status')->dropDownList(['Active' => 'Active', 'Inactive' => 'Inactive']) ?></div>
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
