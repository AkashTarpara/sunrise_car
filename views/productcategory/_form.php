<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Productcategory $model */
/** @var yii\widgets\ActiveForm $form */
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<div class="productcategory-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'sample_price')->textInput(['maxlength' => true, 'placeholder' => 'e.g. 9.99']) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'material_per_step')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'installation_per_step')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'image')->fileInput(['class' => 'form-control file-upload']) ?>

            <img class="profile-pic1 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                        echo Yii::$app->params['ImagePath'] . $model->image;
                                                                    } ?>" height="95px" width="95px" />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'banner_image')->fileInput(['class' => 'form-control file-upload2']) ?>

            <img class="profile-pic2 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                        echo Yii::$app->params['ImagePath'] . $model->banner_image;
                                                                    } ?>" height="95px" width="95px" />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'sample_image')->fileInput(['class' => 'form-control file-upload3'])->hint('Optional. Upload a sample/swatch image for this category.') ?>

            <img class="profile-pic3 <?php if ($model->isNewRecord || empty($model->sample_image)) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord && !empty($model->sample_image)) {
                                                                        echo Yii::$app->params['ImagePath'] . $model->sample_image;
                                                                    } ?>" height="95px" width="95px" />
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'meta_tag')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['productcategory/'], ['class' => 'btn btn-light-danger']) ?>
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
        $(".file-upload2").on('change', function() {
            readURL(this);
        });
        $(".upload-button").on('click', function() {
            $(".file-upload2").click();
        });
    });
    $(document).ready(function() {
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-pic3').removeClass("hidden");
                    $('.profile-pic3').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".file-upload3").on('change', function() {
            readURL(this);
        });
    });
</script>
<script>
    $('#productcategory-description').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });
</script>
