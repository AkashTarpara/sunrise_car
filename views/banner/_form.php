<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'subtitle')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'type')->dropDownList(['Image' => 'Image', 'Video' => 'Video',], ['class' => 'form-control ischoice']) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'btn_title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" id="Image" <?php if ($model->type == 'Image' || $model->isNewRecord) { ?> style="display: none;" <?php } ?>>

            <?= $form->field($model, 'image')->fileInput(['class' => 'form-control file-upload'])  ?>

            <img class="profile-pic1 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                        echo Yii::getAlias("@web") . "/" . $model->image;
                                                                    } ?>" height="100px" width="100px" />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" id="Video" <?php if ($model->type == 'Video' || $model->isNewRecord) { ?> style="display: block;" <?php } ?>>
            <?= $form->field($model, 'video')->fileInput(['class' => 'form-control']) ?>
            <?php if ($model->type == 'Video' && !empty($model->video)) { ?>
                <div class="avatar">
                    <video width="150" controls>
                        <source src="<?= Yii::getAlias("@web") . "/" . $model->video ?>" type="video/mp4">
                        <source src="mov_bbb.ogg" type="video/ogg">
                        Your browser does not support HTML5 video.
                    </video>
                </div>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" id="MobileVideo" <?php if ($model->type == 'Video' || $model->isNewRecord) { ?> style="display: block;" <?php } ?>>
            <?= $form->field($model, 'mobile_video')->fileInput(['class' => 'form-control']) ?>
            <?php if ($model->type == 'Video' && !empty($model->mobile_video)) { ?>
                <div class="avatar">
                    <video width="150" controls>
                        <source src="<?= Yii::getAlias("@web") . "/" . $model->mobile_video ?>" type="video/mp4">
                        <source src="mov_bbb.ogg" type="video/ogg">
                        Your browser does not support HTML5 video.
                    </video>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
        <?= Html::a('Cancel', ['banner/'], ['class' => 'btn btn-light-danger']) ?>
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
        $(".ischoice").each(function() {
            //alert($(this).val());
            ischoice = $(this).val();
            var x = document.getElementById("Image");
            var y = document.getElementById("Video");
            var z = document.getElementById("MobileVideo");
            if (ischoice == "Image") {
                y.style.display = "none";
                z.style.display = "none";
                x.style.display = "block";
            } else {
                x.style.display = "block";
                y.style.display = "block";
                z.style.display = "block";
            }
        });
    });
    $(document).ready(function() {
        $(".ischoice").change(function() {
            //alert($(this).val());
            ischoice = $(this).val();
            var x = document.getElementById("Image");
            var y = document.getElementById("Video");
            var z = document.getElementById("MobileVideo");
            if (ischoice == "Image") {
                y.style.display = "none";
                z.style.display = "none";
                x.style.display = "block";
            } else {
                x.style.display = "block";
                y.style.display = "block";
                z.style.display = "block";
            }
        });
    });
</script>