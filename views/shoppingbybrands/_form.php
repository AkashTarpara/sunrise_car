<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Shoppingbybrands $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shoppingbybrands-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
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
            <?= $form->field($model, 'logo')->fileInput(['class' => 'form-control file-upload2']) ?>

            <img class="profile-pic2 <?php if ($model->isNewRecord) {
                                            echo "hidden";
                                        } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                        echo Yii::$app->params['ImagePath'] . $model->logo;
                                                                    } ?>" height="95px" width="95px" />
        </div>
    </div>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['shoppingbybrands/'], ['class' => 'btn btn-light-danger']) ?>
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
</script>