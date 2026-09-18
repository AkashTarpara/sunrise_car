<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Adminsidemenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adminsidemenu-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row"> 
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <!-- <?= $form->field($model, 'is_multiple')->dropDownList([ 'Yes' => 'Yes', 'No' => 'No', ]) ?> -->
            <?= $form->field($model, 'controller_name')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="row"> 
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'action_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row"> 
        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $form->field($model, 'sub_menu_action_name')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <hr>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 100,
        'min' => 0,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $modelsadminsidemenudetail[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'title',
            'controller_name',
            'action_name',
            'icon'
        ],
    ]); ?>
    <table class="table table-striped " style="overflow: auto;white-space: nowrap;">
        <thead>
            <tr>
                <th>Add Sub Menu</th>
                <th class="text-center">
                    <button type="button" class="add-house btn btn-sm btn-light-success"><span class="fa fa-plus"></span></button>
                </th>
            </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($modelsadminsidemenudetail as $indexpredictionuseranswer => $modelpredictionuseranswer): 
            //echo "<pre>";print_r($modelsInnersubmenu[$indexpredictionuseranswer][0]);exit;
            ?>
            <tr class="house-item">
                <td class="vcenter">
                    <?php
                        // necessary for update action.
                        if (! $modelpredictionuseranswer->isNewRecord) {
                            echo Html::activeHiddenInput($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]admin_sidemenu_detail_id");
                        }
                    ?>
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]title")->textInput(['maxlength' => true]) ?>                
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]controller_name")->textarea(['rows' => 3]) ?>                
                        </div>
                    </div>                               
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]action_name")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <?= $form->field($modelpredictionuseranswer, "[{$indexpredictionuseranswer}]icon")->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?= $this->render('_form-sub-menu', [
                                'form' => $form,
                                'indexHouse' => $indexpredictionuseranswer,
                                'modelsAdminsidemenusubdetail' => $modelsAdminsidemenusubdetail[$indexpredictionuseranswer],
                                //'modelsInnersubmenu' => (empty($modelsInnersubmenu[$indexpredictionuseranswer]))?$modelsInnersubmenu:$modelsInnersubmenu[$indexpredictionuseranswer],
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
    </table>
    <?php DynamicFormWidget::end(); ?>
    <hr>


    <div class="form-group custom-save-button">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-light-success']) ?>
        <?= Html::a('Cancel',['adminsidemenu/'], ['class' => 'btn btn-light-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
