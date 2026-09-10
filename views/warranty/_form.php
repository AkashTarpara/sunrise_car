<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\Warranty $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="warranty-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'sub_title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'image')->fileInput(['class' => 'form-control file-upload', 'onchange' => 'readURL(this)']) ?>

            <img 
                class="preview-image img-thumbnail <?= $model->isNewRecord ? 'hidden' : '' ?>"
                src="<?= !$model->isNewRecord 
                    ? Yii::$app->params['ImagePath'] . $model->image 
                    : '' ?>"
                height="95"
                width="95"
            />
        </div>
    </div>

    <hr>
        <h3 class="seotitle">Warrenty Document</h3>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.container-items',
            'widgetItem' => '.house-item',
            'limit' => 100,
            'min' => 0,
            'insertButton' => '.add-house',
            'deleteButton' => '.remove-house',
            'model' => $warrentydocument[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'title',
                'file'
            ],
        ]); ?>

        <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
            <tbody class="container-items">
                
                <?php foreach ($warrentydocument as $indexwarrentydocument => $modelwarrentydocument) : ?>

                    <tr class="house-item">
                        
                        <td class="vcenter">
                            
                            <?php
                                if (!$modelwarrentydocument->isNewRecord) {
                                    echo Html::activeHiddenInput($modelwarrentydocument, "[{$indexwarrentydocument}]warranty_document_id");
                                }
                            ?>


                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelwarrentydocument, "[{$indexwarrentydocument}]title")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelwarrentydocument, "[{$indexwarrentydocument}]file")
                                        ->fileInput([
                                            'class' => 'form-control file-upload',
                                            'accept' => '.pdf,application/pdf',
                                            'onchange' => 'readURL(this)'
                                        ]) ?>

                                    <div class="mt-2">
                                        <a 
                                            href="<?= !$modelwarrentydocument->isNewRecord
                                                ? Yii::$app->params['ImagePath'] . $modelwarrentydocument->file
                                                : 'javascript:void(0)' ?>"
                                            target="_blank"
                                        >
                                        <img 
                                            class="preview-image img-thumbnail <?= $modelwarrentydocument->isNewRecord ? 'hidden' : '' ?>"
                                            src="<?= !$modelwarrentydocument->isNewRecord
                                                ? Yii::$app->params['ImagePath'] . 'uploads/default/PDF_file_icon.png'
                                                : '' ?>"
                                            style="height:95px;width:95px;object-fit:cover;"
                                        />
                                        </a>
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

    <hr>
        <h3 class="seotitle">Warrenty Certificate</h3>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper2',
            'widgetBody' => '.container-items2',
            'widgetItem' => '.house-item2',
            'limit' => 100,
            'min' => 0,
            'insertButton' => '.add-house2',
            'deleteButton' => '.remove-house2',
            'model' => $warrantycertificate[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'image'
            ],
        ]); ?>

        <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
            <tbody class="container-items2">
                
                <?php foreach ($warrantycertificate as $indexwarrantycertificate => $modelwarrantycertificate) : ?>

                    <tr class="house-item2">
                        
                        <td class="vcenter">
                            
                            <?php
                                if (!$modelwarrantycertificate->isNewRecord) {
                                    echo Html::activeHiddenInput($modelwarrantycertificate, "[{$indexwarrantycertificate}]warranty_certificate_id");
                                }
                            ?>


                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <?= $form->field($modelwarrantycertificate, "[{$indexwarrantycertificate}]image")
                                        ->fileInput([
                                            'class' => 'form-control file-upload',
                                            'onchange' => 'readURL(this)'
                                        ]) ?>

                                    <div class="mt-2">
                                        <img 
                                            class="preview-image img-thumbnail <?= $modelwarrantycertificate->isNewRecord ? 'hidden' : '' ?>"
                                            src="<?= !$modelwarrantycertificate->isNewRecord
                                                ? Yii::$app->params['ImagePath'] . $modelwarrantycertificate->image
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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
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
                
            const file = input.files[0];
            const isPdf = file.type === 'application/pdf';

            // find closest container
            const container = $(input).closest('.col-xs-12');

            // find preview image
            const previewImage = container.find('.preview-image');

            // find preview link
            const previewLink = container.find('.preview-link');
            
            if (isPdf) {

                previewImage.attr(
                    'src',
                    '<?= Yii::$app->params['ImagePath'] . "uploads/default/PDF_file_icon.png" ?>'
                );

                previewImage.removeClass('hidden');

                // create temporary blob URL for preview
                const pdfURL = URL.createObjectURL(file);

                previewLink.attr('href', pdfURL);

            } else {

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
    }
</script>