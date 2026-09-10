<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
$initialPreview = [];
$initialPreviewConfig = [];
if ($model->productImages) {
    $i = 0;
    foreach ($model->productImages as $key => $value) {

        //$initialPreview[] = Yii::getAlias("@web").'/'.$value->image;


        $initialPreview[] = Yii::getAlias("@web") . '/' . $value->file;
        $initialPreviewConfig[$i]['url'] = Yii::$app->urlManager->createUrl(["product/deleteimage"]);
        $initialPreviewConfig[$i]['key'] = $value->product_image_id;


        $i++;
    }
}
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<style type="text/css">
    .contentpage .card {
        background-color: transparent;
        border-color: transparent;
    }

    .contentpage .row.title {
        border-radius: 10px;
        background-color: #000;
        margin: 0;
        padding: 15px 0;
    }

    .contentpage .dynamicform_wrapper,
    .contentpage .dynamicform_wrapper1,
    .contentpage .dynamicform_wrapper2,
    .contentpage .dynamicform_wrapper3 {

        tr.house-item,
        tr.house-item1,
        tr.house-item2 {
            border-radius: 10px;
            background-color: #000;
            margin: 0;
            padding: 15px 8px;
            float: left;
            width: 100%;
            margin-bottom: 15px;

            td.vcenter {
                width: 98%;
                border: 0;
            }

            .vcenter p.main-type-title {
                font-size: 20px;
                line-height: 24px;
                text-transform: capitalize;
                color: #FCD100;
                font-weight: 600;
            }

            .remove-house.btn-light-danger,
            .remove-house1.btn-light-danger,
            .remove-house2.btn-light-danger {
                background: #dc2626 !important;
                color: #ffffff !important;
                border-color: #dc2626 !important;
                padding: 0px 4px !important;
            }
        }

        th.text-end {
            border: 0;
            width: 100%;
        }

        .text-end button.add-house.btn,
        .text-end button.add-house1.btn,
        .text-end button.add-house2.btn {
            background-color: #FCD100;
            color: #000000;
            border-color: #FCD100 !important;
            transition: 0.5s;
            margin-top: 10px;
        }
    }

    .contentpage .contentpageseo {
        border-radius: 10px;
        background-color: #000;
        margin: 0;
        padding: 15px;
    }

    .table thead th.pagebuilder {
        background-color: transparent !important;
        font-size: 24px;
        line-height: 30px;
    }

    .contentpage .row.title .form-group label {
        font-size: 14px;
        line-height: 30px;
    }

    .contentpage .card-header {
        display: none;
    }
</style>
<div class="product-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row title">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'product_category_id')->dropDownList(
                    $model->productcategoryname,
                    [
                        'prompt' => "Select Category",
                        'class' => 'chosen-select selectpicker form-control input-md required',
                        //'multiple'=>'multiple',
                        'data-size' => '5',
                        'data-live-search' => 'true',
                        //'options'=>$model->selectedmatche($model->video_id)             
                    ]
                ); ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'price')->textInput() ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'sqft_in_box')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'price_per_box')->textInput() ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'image')->fileInput(['class' => 'form-control file-upload']) ?>

                <img class="profile-pic1 <?php if ($model->isNewRecord) {
                                                echo "hidden";
                                            } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                            echo Yii::$app->params['ImagePath'] . $model->image;
                                                                        } ?>" height="95px" width="95px" />
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'main_price')->textInput() ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'price_per_piece')->textInput() ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'save_button_price')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $form->field($model, 'need_to_display_price')->radioList([
                    1 => 'Yes',
                    0 => 'No',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            </div>
        </div>
    </div>
    <hr>
    <h3 class="seotitle">Product Specifications</h3>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 100,
        'min' => 0,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $modelsProductspecifications[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'title'
        ],
    ]); ?>
    <table class="table table-striped " style="overflow: auto;white-space: nowrap;">

        <thead>
            <!-- <tr>
                <th class="pagebuilder">Add Page Builder</th>
            </tr> -->
        </thead>
        <tbody class="container-items">
            <?php foreach ($modelsProductspecifications as $indexpredictionuseranswer => $modelpredictionuseranswer) : ?>

                <tr class="house-item">
                    <td class="vcenter">
                        <?php
                        // necessary for update action.
                        if (!$modelpredictionuseranswer->isNewRecord) {
                            echo Html::activeHiddenInput($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]product_specifications_id");
                        }
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]title")->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?= $this->render('_form-sub-menu', [
                                    'form' => $form,
                                    'indexHouse' => $indexpredictionuseranswer,
                                    'modelsProductspecificationsdetail' => $modelsProductspecificationsdetail[$indexpredictionuseranswer],
                                ]) ?>
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
            <button type="button" class="add-house btn btn-sm btn-light-success"><span class="fa fa-plus"></span> Add New Section</button>
        </th>
    </table>
    <?php DynamicFormWidget::end(); ?>
    <hr>

    <hr>
    <h3 class="seotitle">Images</h3>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper2',
        'widgetBody' => '.container-items2',
        'widgetItem' => '.house-item2',
        'limit' => 100,
        'min' => 0,
        'insertButton' => '.add-house2',
        'deleteButton' => '.remove-house2',
        'model' => $Productimage[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'type',
            'file',
            'video',
            'video_url',
        ],
    ]); ?>
    <table class="table table-striped " style="overflow: auto;white-space: nowrap;">

        <thead>
            <!-- <tr>
                <th class="pagebuilder">Add Page Builder</th>
            </tr> -->
        </thead>
        <tbody class="container-items2">
            <?php foreach ($Productimage as $indexpredictionuseranswer => $modelpredictionuseranswer) : ?>

                <tr class="house-item2">
                    <td class="vcenter">
                        <?php
                        // necessary for update action.
                        if (!$modelpredictionuseranswer->isNewRecord) {
                            echo Html::activeHiddenInput($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]product_image_id");
                        }
                        ?>


                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]type")->dropDownList(['Image' => 'Image', 'Video' => 'Video', 'Youtube' => 'Youtube',], ['class' => 'form-control ischoicedocumenttype', 'onchange' => 'toggledocumenttypeFields(this)', 'prompt' => 'Select Type',]) ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6  file-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]file", ['options' => ['class' => ""]])->fileInput([
                                    'class' => 'form-control file-input file-upload1 addmoreimage'
                                ]) ?>
                                <img id="preview_image_<?= $indexpredictionuseranswer ?>" class=" <?php if ($modelpredictionuseranswer->isNewRecord) {
                                                                                                        echo "hidden";
                                                                                                    } ?> img-thumbnailnew" src="<?php if (!$modelpredictionuseranswer->isNewRecord) {
                                                                                                                                    echo Yii::$app->params['ImagePath'] . $modelpredictionuseranswer->file;
                                                                                                                                } ?>" height="95px" width="95px" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6  video-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]video", ['options' => ['class' => ""]])->fileInput(['class' => 'form-control file-input file-upload addmoreimage']) ?>
                                <?php if ($modelpredictionuseranswer->type == 'Video' && !empty($modelpredictionuseranswer->video)) { ?>
                                    <div class="avatar">
                                        <video width="150" controls>
                                            <source src="<?= Yii::getAlias("@web") . "/" . $modelpredictionuseranswer->video ?>" type="video/mp4">
                                            <source src="mov_bbb.ogg" type="video/ogg">
                                            Your browser does not support HTML5 video.
                                        </video>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 video-url-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]video_url")->textInput(['maxlength' => true]) ?>
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

    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 10px;">
            <?php
            echo '<label class="control-label">Add Other Images</label>';
            echo FileInput::widget([
                'model' => $model,
                'attribute' => 'url[]',
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'browseLabel' => "Upload Image",
                    'browseIcon' => "<i class=\"glyphicon glyphicon-picture\"></i> ",
                    //'uploadUrl' => Url::to(['/product/uploadfile']),
                    //elErrorContainer: "#errorBlock"
                    // 'minFileCount' => 2,
                    // 'maxFileCount' =>5,
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'showCancel' => false,
                    'initialPreview' => $initialPreview,
                    'allowedFileTypes' => ["image"],
                    'initialPreviewAsData' => true,
                    'initialPreviewFileType' => 'image',
                    // 'initialCaption'=>"The Moon and the Earth",
                    'initialPreviewConfig' => $initialPreviewConfig,
                    //'uploadAsync' =>false,
                    //'previewFileType' => 'any',
                    //'previewFileType' => 'any',
                    'overwriteInitial' => false,
                    'initialPreviewAsData' => true,
                    //'maxFileSize'=>2800,
                    //'deleteUrl' => Url::to(['/product/deleteimage'])
                ]
            ]);
            ?>
        </div>
    </div> -->
    <?php
    $script = <<<JS
function calculatePricePerBox() {
    let price = parseFloat(document.getElementById('product-price').value) || 0;
    let sqft = parseFloat(document.getElementById('product-sqft_in_box').value) || 0;
    let total = price * sqft;
    document.getElementById('product-price_per_box').value = total.toFixed(2); // 2 decimal places
}

document.getElementById('product-price').addEventListener('input', calculatePricePerBox);
document.getElementById('product-sqft_in_box').addEventListener('input', calculatePricePerBox);

// Run once on page load (useful when editing an existing record)
calculatePricePerBox();
JS;
    $this->registerJs($script);
    ?>
    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['product/'], ['class' => 'btn btn-light-danger']) ?>
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
</script>
<script>
    function toggledocumenttypeFields(select) {
        var selectedType = select.value;
        //console.log(selectedType);
        var fileField = select.closest('.house-item2').querySelector('.file-field');
        var videoField = select.closest('.house-item2').querySelector('.video-field');
        var videoUrlField = select.closest('.house-item2').querySelector('.video-url-field');
        //console.log(descriptionId);
        if (selectedType === 'Image') {
            fileField.style.display = 'block';
            videoField.style.display = 'none';
            videoUrlField.style.display = 'none';
        } else if (selectedType === 'Video') {
            fileField.style.display = 'none';
            videoField.style.display = 'block';
            videoUrlField.style.display = 'none';
        } else if (selectedType === 'Youtube') {
            fileField.style.display = 'none';
            videoField.style.display = 'none';
            videoUrlField.style.display = 'block';
        } else {
            fileField.style.display = 'none';
            videoField.style.display = 'none';
            videoUrlField.style.display = 'none';
        }
    }
    // Initially toggle fields based on current selection
    document.querySelectorAll('.ischoicedocumenttype').forEach(function(select) {
        //console.log(select.value);
        toggledocumenttypeFields(select);
    });
</script>
<script>
    $('#product-description').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });
</script>
<script type="text/javascript">
    $(document).on('click', ".add-house2", function() {
        description_en = '#' + $(".addmoreimage").last().attr('id');
        var parts = description_en.split('-');
        $('#preview_image_0' + parts[1]).attr('src', '');
        $('#preview_image_0' + parts[1]).addClass('hidden');

        //console.log(parts[1]);
    });
</script>
