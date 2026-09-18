<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Adminuser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adminuser-form">

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
            <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'profile_pic')->fileInput(['class' => 'form-control file-upload'])  ?>
            <img class="profile-pic1 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                                                                echo Yii::getAlias("@web") . "/" . $model->profile_pic;
                                                                                                            } ?>" height="95px" width="95px" />
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?= $form->field($model, 'site_logo')->fileInput(['class' => 'form-control file-upload1'])  ?>
            <img class="profile-pic2 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                                                                echo Yii::getAlias("@web") . "/" . $model->site_logo;
                                                                                                            } ?>" height="95px" width="95px" />
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
        <?= Html::a('Cancel', ['dashboard/'], ['class' => 'btn btn-light-danger']) ?>
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