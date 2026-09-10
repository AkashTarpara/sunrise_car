<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Newsletter */
/* @var $form yii\widgets\ActiveForm */
//$files=Yii::$app->MyFunctions->getNewslatterNewObject($homescreenvideo)
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<div class="newsletter-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'sub_title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'image')->fileInput(['class' => 'form-control file-upload']) ?>

            <img class="profile-pic <?php if ($model->isNewRecord) {
                                        echo "hidden";
                                    } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                    echo Yii::$app->params['ImagePath'] . $model->image;
                                                                } ?>" height="200px" width="200px" />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'thumbnail_image')->fileInput(['class' => 'form-control file-upload1']) ?>

            <img class="profile-pic1 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                        echo Yii::$app->params['ImagePath'] . $model->thumbnail_image;
                                                                    } ?>" height="200px" width="200px" />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?php $date = (!empty($model->date)) ? date('Y-m-d', strtotime($model->date)) : date('Y-m-d') ?>
            <?= $form->field($model, 'date')->input('date', ['value' => $date, 'class' => 'form-control custom-date-picker']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, "is_featured")->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'meta_tag')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
        <?= Html::a('Cancel', ['newsletter/'], ['class' => 'btn btn-light-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(document).ready(function() {

        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-pic').removeClass("hidden");
                    $('.profile-pic').attr('src', e.target.result);
                    $('.mfp-close').click();
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
                    $('.profile-pic1').removeClass("hidden");
                    $('.profile-pic1').attr('src', e.target.result);
                    $('.mfp-close').click();
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

<script>
    $('#newsletter-description').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });
</script>