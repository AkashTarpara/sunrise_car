<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Generalsetting */
/* @var $form yii\widgets\ActiveForm */
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<div class="generalsetting-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'terms_conditions')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'terms_conditions_trade_pro')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'privacy_policy')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'pick_up_delivery')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'user_terms_conditions')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'user_privacy_policy')->textarea(['rows' => 6]) ?>
        </div>
    </div> -->
    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'user_terms_service')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'club_terms_service')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'ticket_policy')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'cookie')->textarea(['rows' => 6]) ?>
        </div>
    </div> -->
    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'about_us')->textarea(['rows' => 6]) ?>
        </div>
    </div> -->
    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'code_of_conduct')->textarea(['rows' => 6]) ?>
        </div>
    </div> -->
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'sps_flooring_title')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'sps_flooring_sub_title')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'weekly_bestsellers_title')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'weekly_bestsellers_sub_title')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'clients_say_title')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'clients_say_sub_title')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'facebook')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'instagram')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'twitter')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'youtube')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'tiktok')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'snapchat')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'linkedin')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'contact_no')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'telephone_no')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'address')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'map_url')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'tax')->textInput() ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'sample_shipping_charge')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'warehouse_address')->textInput() ?>
        </div>

    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'latitude')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'longitude')->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'delivery_charge')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'agree_delivery')->textInput() ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'contact_us_image')->fileInput(['class' => 'form-control file-upload']) ?>

            <img class="profile-pic <?php if ($model->isNewRecord) {
                                        echo "hidden";
                                    } ?> img-thumbnail" src="<?php if (!$model->isNewRecord) {
                                                                    echo Yii::getAlias("@web") . "/" . $model->contact_us_image;
                                                                } ?>" height="95px" width="95px" />
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
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'need_to_display_roomvo')->radioList([
                1 => 'Yes',
                0 => 'No',
            ]) ?>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'api_key')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'android_app_version')->textInput() ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'ios_app_version')->textInput() ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <?= $form->field($model, 'force_update')->dropDownList(['Yes' => 'Yes', 'No' => 'No']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'version_message')->textInput() ?>
        </div>
    </div> -->

    <hr>
    <h3 class="seotitle">Advertisement</h3>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper2',
        'widgetBody' => '.container-items2',
        'widgetItem' => '.house-item2',
        'limit' => 2,
        'min' => 0,
        'insertButton' => '.add-house2',
        'deleteButton' => '.remove-house2',
        'model' => $advertisement[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'image'
        ],
    ]); ?>

    <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
        <tbody class="container-items2">

            <?php foreach ($advertisement as $indexadvertisement => $modeladvertisement) : ?>

                <tr class="house-item2">

                    <td class="vcenter">

                        <?php
                        if (!$modeladvertisement->isNewRecord) {
                            echo Html::activeHiddenInput($modeladvertisement, "[{$indexadvertisement}]advertisement_id");
                        }
                        ?>


                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <?= $form->field($modeladvertisement, "[{$indexadvertisement}]image")
                                    ->fileInput([
                                        'name' => "AdvertisementProduct[{$indexadvertisement}][image]",
                                        'class' => 'form-control file-upload',
                                        'onchange' => 'readURL(this)'
                                    ]) ?>

                                <div class="mt-2">
                                    <img
                                        class="preview-image img-thumbnail <?= $modeladvertisement->isNewRecord ? 'hidden' : '' ?>"
                                        src="<?= !$modeladvertisement->isNewRecord
                                                    ? Yii::$app->params['ImagePath'] . $modeladvertisement->image
                                                    : '' ?>"
                                        style="height:95px;width:95px;object-fit:cover;" />
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
    <h3 class="seotitle">Advertisement Home</h3>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 2,
        'min' => 0,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $advertisementhome[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'image'
        ],
    ]); ?>

    <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
        <tbody class="container-items">

            <?php foreach ($advertisementhome as $indexadvertisementhome => $modeladvertisementhome) : ?>

                <tr class="house-item">

                    <td class="vcenter">

                        <?php
                        if (!$modeladvertisementhome->isNewRecord) {
                            echo Html::activeHiddenInput($modeladvertisementhome, "[{$indexadvertisementhome}]advertisement_id");
                        }
                        ?>


                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <?= $form->field($modeladvertisementhome, "[{$indexadvertisementhome}]image")
                                    ->fileInput([
                                        'name' => "AdvertisementHome[{$indexadvertisementhome}][image]",
                                        'class' => 'form-control file-upload',
                                        'onchange' => 'readURL(this)'
                                    ]) ?>

                                <div class="mt-2">
                                    <img
                                        class="preview-image img-thumbnail <?= $modeladvertisementhome->isNewRecord ? 'hidden' : '' ?>"
                                        src="<?= !$modeladvertisementhome->isNewRecord
                                                    ? Yii::$app->params['ImagePath'] . $modeladvertisementhome->image
                                                    : '' ?>"
                                        style="height:95px;width:95px;object-fit:cover;" />
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

    <div class="form-group custom-save-button">
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
</script>
<script>
    $('#generalsetting-terms_conditions').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });

    $('#generalsetting-terms_conditions_trade_pro').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });

    $('#generalsetting-privacy_policy').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });

    $('#generalsetting-about_us').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });
    $('#generalsetting-pick_up_delivery').summernote({
        <?= Yii::$app->params['summernote'] ?>,
    });
</script>
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

                reader.onload = function(e) {

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