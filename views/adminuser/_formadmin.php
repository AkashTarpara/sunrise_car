<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Appuser $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="appuser-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <?php if ($model->isNewRecord) { ?>
            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
            </div>
        <?php } ?>
        <div class="col-md-6">
            <?= $form->field($model, 'rols')->dropDownList($rolsArr, [
                'class' => 'form-control select2',
                'dropdown-css-class' => 'select2-purple',
                'multiple' => 'multiple',
                'data-size' => '50',
                'data-live-search' => 'true',
                'multiple data-actions-box' => 'true',
                'options' => $selectArr,
            ]); ?>
        </div>
    </div>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
        <?= Html::a('Cancel', ['/adminuser'], ['class' => 'btn btn-light-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-pic1').removeClass("hidden");
                    $('.profile-pic1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".file-upload").on('change', function() {
            readURL(this);
        });
        $(".upload-button").on('click', function() {
            $(".file-upload").click();
        });
    });

    $(document).ready(function() {
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-pic2').removeClass("hidden");
                    $('.profile-pic2').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".file-upload1").on('change', function() {
            readURL(this);
        });
        $(".upload-button").on('click', function() {
            $(".file-upload1").click();
        });
    });
</script>