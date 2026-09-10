<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\Contentpage $model */
/** @var yii\widgets\ActiveForm $form */
?>
<style type="text/css">
    .contentpage .card {
        background-color: transparent;
        border-color: transparent;
    }

    .contentpage .row.title {
        border-radius: 10px;
        background-color: #000;
        margin: 0;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .contentpage .dynamicform_wrapper tr.house-item {
        border-radius: 10px;
        background-color: #000;
        margin: 0;
        padding: 15px 8px;
    }

    .contentpage .contentpageseo {
        border-radius: 10px;
        background-color: #000;
        margin: 0;
        padding: 15px 15px;
    }

    .table thead th.pagebuilder {
        background-color: transparent !important;
        font-size: 24px;
        line-height: 30px;
    }

    .contentpage .dynamicform_wrapper .text-end button.add-house.btn {
        margin-top: 10px;
    }

    .contentpage .row.title .form-group label {
        font-size: 24px;
        line-height: 30px;
    }

    .contentpage .card-header {
        display: none;
    }

    .contentpage .dynamicform_wrapper .text-end button.add-house.btn {
        background-color: #FCD100;
        color: #000000;
        border-color: #FCD100 !important;
        transition: 0.5s;
    }

    .contentpage .dynamicform_wrapper th.text-end {
        border: 0;
    }

    .contentpage .dynamicform_wrapper tr.house-item {
        float: left;
        width: 100%;
        margin-bottom: 15px;
    }

    .contentpage .dynamicform_wrapper tr.house-item td.vcenter {
        width: 98%;
    }

    .contentpage .dynamicform_wrapper th.text-end {
        width: 100%;
    }

    .contentpage .dynamicform_wrapper .house-item .remove-house.btn-light-danger {
        background: #dc2626 !important;
        color: #ffffff !important;
        border-color: #dc2626 !important;
    }

    .contentpage .dynamicform_wrapper .house-item .remove-house.btn-light-danger {
        padding: 0px 4px !important;
    }

    .contentpage .dynamicform_wrapper th.text-end {
        border: 0;
    }

    .contentpage .dynamicform_wrapper tr.house-item td.vcenter {
        border: 0;
    }

    .contentpage .dynamicform_wrapper tr.house-item .vcenter p.main-type-title {
        font-size: 20px;
        line-height: 24px;
        text-transform: capitalize;
        color: #FCD100;
        font-weight: 600;
    }
</style>
<div class="contentpage-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <h3 class="seotitle">Page Title</h3>
    <div class="row title">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?php if (!$model->isNewRecord) { ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
            <?php } else { ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
            <?php } ?>
        </div>
    </div>


    <hr>
    <h3 class="seotitle">Page Builder</h3>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 100,
        'min' => 0,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $Contentpagedetail[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'type',
            'section_title',
            'title',
            'sub_title',
            'image',
            'button_1_title',
            'button_1_url',
            'button_2_title',
            'button_2_url',
            'logo',
            'video',
            'mobile_image',
            'url',
            'button_3_title',
            'button_3_url',
        ],
    ]); ?>
    <table class="table table-striped " style="overflow: auto;white-space: nowrap;">

        <thead>
            <!-- <tr>
                <th class="pagebuilder">Add Page Builder</th>
            </tr> -->
        </thead>
        <tbody class="container-items">
            <?php foreach ($Contentpagedetail as $indexpredictionuseranswer => $modelpredictionuseranswer) : ?>

                <tr class="house-item">
                    <td class="vcenter">
                        <?php
                        // necessary for update action.
                        if (!$modelpredictionuseranswer->isNewRecord) {
                            echo Html::activeHiddenInput($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]content_page_detail_id");
                        }
                        ?>
                        <div>
                            <p class="main-type-title" style="display: none;">Test</p>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]type")->dropDownList(['HeroBanner' => 'Hero Banner', 'Footer' => 'Footer', 'JoinUs' => 'Join Us',], ['class' => 'form-control ischoice', 'onchange' => 'toggleFields(this)', 'prompt' => 'Select Type',]) ?>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 media-type-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]media_type")->dropDownList(['Image' => 'Image', 'Video' => 'Video'], ['class' => 'form-control ischoicenew', 'onchange' => 'toggleFieldsMediatype(this)', 'prompt' => 'Select Media Type',]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 section-title-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]section_title")->textInput(['maxlength' => true]) ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 title-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]title")->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 sub-title-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]sub_title")->textarea(['rows' => 3, 'class' => 'form-control']) ?>
                            </div>

                        </div>
                        <div class="row">


                        </div>

                        <div class="row mb-3">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 image-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]image", ['options' => ['class' => ""]])->fileInput(['class' => 'form-control file-input file-upload addmoreimage', 'onchange' => 'readURL(this, event)']) ?>
                                <img id="preview_image_<?= $indexpredictionuseranswer ?>" class=" <?php if ($modelpredictionuseranswer->isNewRecord) {
                                                                                                        echo "hidden";
                                                                                                    } ?> img-thumbnail" src="<?php if (!$modelpredictionuseranswer->isNewRecord) {
                                                                                                                                    echo Yii::$app->params['ImagePath'] . $modelpredictionuseranswer->image;
                                                                                                                                } ?>" height="95px" width="95px" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 logo-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]logo", ['options' => ['class' => ""]])->fileInput(['class' => 'form-control file-input file-upload addmoreimagelogo logo-lable', 'onchange' => 'readURLlogo(this, event)']) ?>
                                <img id="preview_image_logo_<?= $indexpredictionuseranswer ?>" class=" <?php if ($modelpredictionuseranswer->isNewRecord) {
                                                                                                            echo "hidden";
                                                                                                        } ?> img-thumbnail" src="<?php if (!$modelpredictionuseranswer->isNewRecord) {
                                                                                                                                        echo Yii::$app->params['ImagePath'] . $modelpredictionuseranswer->logo;
                                                                                                                                    } ?>" height="95px" width="95px" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 video-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]video", ['options' => ['class' => ""]])->fileInput(['class' => 'form-control file-input']) ?>
                                <?php if (!empty($modelpredictionuseranswer->video)) { ?>
                                    <div class="avatar">
                                        <video width="150" controls>
                                            <source src="<?= Yii::$app->params['ImagePath'] . $modelpredictionuseranswer->video ?>" type="video/mp4">
                                            <source src="mov_bbb.ogg" type="video/ogg">
                                            Your browser does not support HTML5 video.
                                        </video>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 mobile-video-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]mobile_video", ['options' => ['class' => ""]])->fileInput(['class' => 'form-control file-input']) ?>
                                <?php if (!empty($modelpredictionuseranswer->mobile_video)) { ?>
                                    <div class="avatar">
                                        <video width="150" controls>
                                            <source src="<?= Yii::$app->params['ImagePath'] . $modelpredictionuseranswer->mobile_video ?>" type="video/mp4">
                                            <source src="mov_bbb.ogg" type="video/ogg">
                                            Your browser does not support HTML5 video.
                                        </video>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 mobile-image-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]mobile_image", ['options' => ['class' => ""]])->fileInput(['class' => 'form-control file-input file-upload addmoreimagemobileimage', 'onchange' => 'readURLmobileimage(this, event)']) ?>
                                <img id="preview_image_mobile_image_<?= $indexpredictionuseranswer ?>" class=" <?php if ($modelpredictionuseranswer->isNewRecord) {
                                                                                                                    echo "hidden";
                                                                                                                } ?> img-thumbnail" src="<?php if (!$modelpredictionuseranswer->isNewRecord) {
                                                                                                                                                echo Yii::$app->params['ImagePath'] . $modelpredictionuseranswer->mobile_image;
                                                                                                                                            } ?>" height="95px" width="95px" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4 url-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]url")->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 button-1-title-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]button_1_title")->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 button-1-url-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]button_1_url")->textInput(['maxlength' => true]) ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 button-2-title-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]button_2_title")->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 button-2-url-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]button_2_url")->textInput(['maxlength' => true]) ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 button-3-title-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]button_3_title")->textInput(['maxlength' => true]) ?>
                            </div>

                            <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 button-3-url-field" style="display: none;">
                                <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]button_3_url")->textInput(['maxlength' => true]) ?>
                            </div>

                        </div>

                        <p class="emapty-section-text" style="display: none;"></p>
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
    <h3 class="seotitle">SEO Details</h3>
    <div class="contentpageseo">
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
                <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>
            </div>
        </div>
        <div class="form-group custom-save-button">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
            <?= Html::a('Cancel', ['contentpage/'], ['class' => 'btn btn-light-danger']) ?>
            <a class="btn btn-light-success" href="javascript:void(0)">Preview</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(document).on('click', ".add-house", function() {
        description_en = '#' + $(".addmoreimage").last().attr('id');
        var parts = description_en.split('-');
        $('#preview_image_0' + parts[1]).attr('src', '');
        $('#preview_image_0' + parts[1]).addClass('hidden');

        logoimage = '#' + $(".addmoreimagelogo").last().attr('id');
        var logoparts = logoimage.split('-');
        $('#preview_image_logo_0' + logoparts[1]).attr('src', '');
        $('#preview_image_logo_0' + logoparts[1]).addClass('hidden');

        mobileimage = '#' + $(".addmoreimagemobileimage").last().attr('id');
        var mobileimaggeparts = mobileimage.split('-');
        $('#preview_image_mobile_image_0' + mobileimaggeparts[1]).attr('src', '');
        $('#preview_image_mobile_image_0' + mobileimaggeparts[1]).addClass('hidden');
        //console.log(parts[1]);
    });
</script>
<script>
    function readURL(input, index) {
        if (input.files && input.files[0]) {
            var index = $(input).closest('.house-item').index();
            //console.log(index);
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview_image_0' + index).attr('src', e.target.result);
                $('#preview_image_0' + index).removeClass('hidden');
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    function readURLlogo(input, index) {
        if (input.files && input.files[0]) {
            var index = $(input).closest('.house-item').index();
            //console.log(index);
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview_image_logo_0' + index).attr('src', e.target.result);
                $('#preview_image_logo_0' + index).removeClass('hidden');
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    function readURLmobileimage(input, index) {
        if (input.files && input.files[0]) {
            var index = $(input).closest('.house-item').index();
            //console.log(index);
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview_image_mobile_image_0' + index).attr('src', e.target.result);
                $('#preview_image_mobile_image_0' + index).removeClass('hidden');
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
</script>
<script>
    function toggleFields(select) {
        var selectedType = select.value;
        //console.log(selectedType);
        var sectionTitleField = select.closest('.house-item').querySelector('.section-title-field');
        var mediaTypeField = select.closest('.house-item').querySelector('.media-type-field');
        var titleField = select.closest('.house-item').querySelector('.title-field');
        var subTitleField = select.closest('.house-item').querySelector('.sub-title-field');
        var imageField = select.closest('.house-item').querySelector('.image-field');
        var logoField = select.closest('.house-item').querySelector('.logo-field');
        var videoField = select.closest('.house-item').querySelector('.video-field');
        var mobilevideoField = select.closest('.house-item').querySelector('.mobile-video-field');
        var button1TitleField = select.closest('.house-item').querySelector('.button-1-title-field');
        var button1UrlField = select.closest('.house-item').querySelector('.button-1-url-field');
        var button2TitleField = select.closest('.house-item').querySelector('.button-2-title-field');
        var button2UrlField = select.closest('.house-item').querySelector('.button-2-url-field');
        var emaptySectionText = select.closest('.house-item').querySelector('.emapty-section-text');
        var emaptySectionText = select.closest('.house-item').querySelector('.emapty-section-text');
        var urlField = select.closest('.house-item').querySelector('.url-field');
        var mobileImageField = select.closest('.house-item').querySelector('.mobile-image-field');
        var maintypetitle = select.closest('.house-item').querySelector('.main-type-title');
        var subtitleLabel = select.closest('.house-item').querySelector('.sub-title-lable');
        var logoLabel = select.closest('.house-item').querySelector('.logo-lable');
        var descriptionId = select.closest('.house-item').querySelector('.descriptionnew');
        var button3TitleField = select.closest('.house-item').querySelector('.button-3-title-field');
        var button3UrlField = select.closest('.house-item').querySelector('.button-3-url-field');
        //console.log(descriptionId);
        if (selectedType === 'HeroBanner') {
            sectionTitleField.style.display = 'none';
            emaptySectionText.style.display = 'none';
            mediaTypeField.style.display = 'none';
            maintypetitle.style.display = 'block';
            maintypetitle.textContent = 'Hero Banner';
            titleField.style.display = 'block';
            subTitleField.style.display = 'block';
            imageField.style.display = 'block';
            logoField.style.display = 'none';
            videoField.style.display = 'block';
            mobilevideoField.style.display = 'none';
            button1TitleField.style.display = 'block';
            button1UrlField.style.display = 'block';
            button2TitleField.style.display = 'none';
            button2UrlField.style.display = 'none';
            urlField.style.display = 'none';
            mobileImageField.style.display = 'none';
            button3TitleField.style.display = 'none';
            button3UrlField.style.display = 'none';
        } else if (selectedType === 'Footer') {
            sectionTitleField.style.display = 'none';
            emaptySectionText.style.display = 'none';
            mediaTypeField.style.display = 'none';
            maintypetitle.style.display = 'block';
            maintypetitle.textContent = 'Footer';
            titleField.style.display = 'block';
            subTitleField.style.display = 'block';
            imageField.style.display = 'none';
            logoField.style.display = 'none';
            videoField.style.display = 'none';
            mobilevideoField.style.display = 'none';
            button1TitleField.style.display = 'block';
            button1UrlField.style.display = 'block';
            button2TitleField.style.display = 'none';
            button2UrlField.style.display = 'none';
            urlField.style.display = 'none';
            mobileImageField.style.display = 'none';
            button3TitleField.style.display = 'none';
            button3UrlField.style.display = 'none';
        } else if (selectedType === 'JoinUs') {
            sectionTitleField.style.display = 'none';
            emaptySectionText.style.display = 'none';
            mediaTypeField.style.display = 'none';
            maintypetitle.style.display = 'block';
            maintypetitle.textContent = 'Join Us';
            titleField.style.display = 'block';
            subTitleField.style.display = 'block';
            imageField.style.display = 'block';
            logoField.style.display = 'none';
            videoField.style.display = 'none';
            mobilevideoField.style.display = 'none';
            button1TitleField.style.display = 'none';
            button1UrlField.style.display = 'none';
            button2TitleField.style.display = 'none';
            button2UrlField.style.display = 'none';
            urlField.style.display = 'none';
            mobileImageField.style.display = 'none';
            button3TitleField.style.display = 'none';
            button3UrlField.style.display = 'none';
        } else {
            sectionTitleField.style.display = 'none';
            emaptySectionText.style.display = 'none';
            mediaTypeField.style.display = 'none';
            maintypetitle.style.display = 'none';
            titleField.style.display = 'none';
            subTitleField.style.display = 'none';
            imageField.style.display = 'none';
            logoField.style.display = 'none';
            videoField.style.display = 'none';
            mobilevideoField.style.display = 'none';
            button1TitleField.style.display = 'none';
            button1UrlField.style.display = 'none';
            button2TitleField.style.display = 'none';
            button2UrlField.style.display = 'none';
            urlField.style.display = 'none';
            mobileImageField.style.display = 'none';
            button3TitleField.style.display = 'none';
            button3UrlField.style.display = 'none';
        }


    }

    // Initially toggle fields based on current selection
    document.querySelectorAll('.ischoice').forEach(function(select) {
        console.log(select.value);
        toggleFields(select);


    });
</script>
<script>
    function toggleFieldsMediatype(select) {
        var selectedType = select.value;
        //console.log(selectedType);

        var imageField = select.closest('.house-item').querySelector('.image-field');
        var videoField = select.closest('.house-item').querySelector('.video-field');
        var mobilevideoField = select.closest('.house-item').querySelector('.mobile-video-field');

        if (selectedType === 'Image') {
            imageField.style.display = 'block';
            videoField.style.display = 'none';

        } else if (selectedType === 'Video') {
            imageField.style.display = 'none';
            videoField.style.display = 'block';
            mobilevideoField.style.display = 'block';
        } else {
            imageField.style.display = 'none';
            videoField.style.display = 'none';
            mobilevideoField.style.display = 'none';
        }
    }

    // Initially toggle fields based on current selection
    /* document.querySelectorAll('.ischoice').forEach(function(select) {

        if (select.value == 'HeroBannerNonLive') {
            document.querySelectorAll('.ischoicenew').forEach(function(select) {
                //console.log();
                if (select.value != '') {
                    toggleFieldsMediatype(select);
                }

            });
            //toggleFieldsMediatype(select);
        }
    }); */
</script>