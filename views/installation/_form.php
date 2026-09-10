<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\Installation $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="installation-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <hr>
        <h3 class="seotitle">Installation Banner</h3>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper2',
            'widgetBody' => '.container-items2',
            'widgetItem' => '.house-item2',
            'limit' => 100,
            'min' => 0,
            'insertButton' => '.add-house2',
            'deleteButton' => '.remove-house2',
            'model' => $installationbanner[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'label',
                'title',
                'sub_title',
                'button_title',
                'button_url',
                'image'
            ],
        ]); ?>

        <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
            <tbody class="container-items2">
                
                <?php foreach ($installationbanner as $indexinstallationbanner => $modelinstallationbanner) : ?>

                    <tr class="house-item2">
                        
                        <td class="vcenter">
                            
                            <?php
                                if (!$modelinstallationbanner->isNewRecord) {
                                    echo Html::activeHiddenInput($modelinstallationbanner, "[{$indexinstallationbanner}]installation_banner_id");
                                }
                            ?>


                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationbanner, "[{$indexinstallationbanner}]label")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationbanner, "[{$indexinstallationbanner}]title")->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationbanner, "[{$indexinstallationbanner}]sub_title")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationbanner, "[{$indexinstallationbanner}]button_title")->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationbanner, "[{$indexinstallationbanner}]button_url")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationbanner, "[{$indexinstallationbanner}]image")
                                        ->fileInput([
                                            'class' => 'form-control file-upload',
                                            'onchange' => 'readURL(this)'
                                        ]) ?>

                                    <div class="mt-2">
                                        <img 
                                            class="preview-image img-thumbnail <?= $modelinstallationbanner->isNewRecord ? 'hidden' : '' ?>"
                                            src="<?= !$modelinstallationbanner->isNewRecord
                                                ? Yii::$app->params['ImagePath'] . $modelinstallationbanner->image
                                                : '' ?>"
                                            style="height:95px;width:95px;object-fit:cover;"
                                        />
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center vcenter">
                            <button type="button" class="remove-house2 btn btn-sm btn-light-danger"><span class="fa fa-minus"></span></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <th class="text-end">
                <button type="button" class="add-house2 btn btn-sm btn-light-success"><span class="fa fa-plus"></span> Add New Images</button>
            </th>
        </table>
        <?php DynamicFormWidget::end(); ?>
    <hr>

    <hr>
        <h3 class="seotitle">Installation Process</h3>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.container-items',
            'widgetItem' => '.house-item',
            'limit' => 100,
            'min' => 0,
            'insertButton' => '.add-house',
            'deleteButton' => '.remove-house',
            'model' => $installationprocess[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'title',
                'image'
            ],
        ]); ?>

        <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
            <tbody class="container-items">
                
                <?php foreach ($installationprocess as $indexinstallationprocess => $modelinstallationprocess) : ?>

                    <tr class="house-item">
                        
                        <td class="vcenter">
                            
                            <?php
                                if (!$modelinstallationprocess->isNewRecord) {
                                    echo Html::activeHiddenInput($modelinstallationprocess, "[{$indexinstallationprocess}]installation_process_id");
                                }
                            ?>


                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationprocess, "[{$indexinstallationprocess}]title")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelinstallationprocess, "[{$indexinstallationprocess}]image")
                                        ->fileInput([
                                            'class' => 'form-control file-upload',
                                            'onchange' => 'readURL(this)'
                                        ]) ?>

                                    <div class="mt-2">
                                        <img 
                                            class="preview-image img-thumbnail <?= $modelinstallationprocess->isNewRecord ? 'hidden' : '' ?>"
                                            src="<?= !$modelinstallationprocess->isNewRecord
                                                ? Yii::$app->params['ImagePath'] . $modelinstallationprocess->image
                                                : '' ?>"
                                            style="height:95px;width:95px;object-fit:cover;"
                                        />
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center vcenter">
                            <button type="button" class="remove-house btn btn-sm btn-light-danger"><span class="fa fa-minus"></span></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <th class="text-end">
                <button type="button" class="add-house btn btn-sm btn-light-success"><span class="fa fa-plus"></span> Add New Images</button>
            </th>
        </table>
        <?php DynamicFormWidget::end(); ?>
    <hr>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <?= $form->field($model, 'sub_title')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'video')->fileInput([
            'class' => 'form-control file-upload',
            'accept' => 'video/*',
            'onchange' => 'previewVideo(this)'
        ]) ?>

        <video 
            class="video-preview <?= $model->isNewRecord ? 'hidden' : '' ?> img-thumbnail"
            style="height:95px; width:200px; object-fit:cover;"
            controls
        >
            <?php if (!$model->isNewRecord) : ?>
                <source src="<?= Yii::$app->params['ImagePath'] . $model->video ?>">
            <?php endif; ?>
        </video>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'before_image')->fileInput(['class' => 'form-control file-upload', 'onchange' => 'readURL(this)']) ?>

            <img 
                class="preview-image img-thumbnail <?= $model->isNewRecord ? 'hidden' : '' ?>"
                src="<?= !$model->isNewRecord 
                    ? Yii::$app->params['ImagePath'] . $model->before_image 
                    : '' ?>"
                height="95"
                width="95"
            />
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'after_image')->fileInput(['class' => 'form-control file-upload', 'onchange' => 'readURL(this)']) ?>

            <img 
                class="preview-image img-thumbnail <?= $model->isNewRecord ? 'hidden' : '' ?>"
                src="<?= !$model->isNewRecord 
                    ? Yii::$app->params['ImagePath'] . $model->after_image 
                    : '' ?>"
                height="95"
                width="95"
            />
        </div>    
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(".dynamicform_wrapper2").on("afterInsert", function(e, item) {

        // reset image preview
        $(item).find('.preview-image')
            .attr('src', '')
            .addClass('hidden');

        // reset file input
        $(item).find('input[type="file"]').val('');
    });
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {

        // reset image preview
        $(item).find('.preview-image')
            .attr('src', '')
            .addClass('hidden');

        // reset file input
        $(item).find('input[type="file"]').val('');
    });
    function readURL(input) {
        if (input.files && input.files[0]) {

            const reader = new FileReader();

            reader.onload = function (e) {

                // find closest container
                const container = $(input).closest('.col-xs-12');

                // find image inside same container
                const previewImage = container.find('.preview-image');

                previewImage.attr('src', e.target.result);
                previewImage.removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewVideo(input) {
        if (input.files && input.files[0]) {

            const file = input.files[0];
            const videoURL = URL.createObjectURL(file);

            // closest row/container
            const container = $(input).closest('.row');

            // find related video
            const video = container.find('.video-preview');

            video.removeClass('hidden');
            video.attr('src', videoURL);

            // reload video
            video[0].load();
        }
    }
</script>