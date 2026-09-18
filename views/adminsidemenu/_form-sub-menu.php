<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-rooms',
    'widgetItem' => '.room-item',
    'limit' => 100,
    'min' => 0,
    'insertButton' => '.add-room',
    'deleteButton' => '.remove-room',
    'model' => $modelsAdminsidemenusubdetail[0],
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
            <th>Inner Sub Menu</th>
            <th class="text-center">
                <button type="button" class="add-room  btn btn-sm btn-light-success"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
    </thead>
    <tbody class="container-rooms">
    <?php foreach ($modelsAdminsidemenusubdetail as $indexRoom => $modelRoom): ?>
        <tr class="room-item">
            <td class="vcenter">
                <?php
                    // necessary for update action.
                    if (! $modelRoom->isNewRecord) {
                        echo Html::activeHiddenInput($modelRoom, "[{$indexHouse}][{$indexRoom}]admin_sidemenu_sub_detail_id");
                    }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]title")->textInput(['maxlength' => true]) ?>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]controller_name")->textInput(['maxlength' => true]) ?> 
                    </div>
                </div>                               
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]action_name")->textInput(['maxlength' => true]) ?>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]icon")->textInput(['maxlength' => true]) ?> 
                    </div>
                </div>
                
            </td>
            <td class="text-center vcenter" style="width: 90px;">
                <button type="button" class="remove-room btn btn-sm btn-light-danger"><span class="fa fa-minus"></span></button>
            </td>
        </tr>
     <?php endforeach; ?>
    </tbody>
</table>
<?php DynamicFormWidget::end(); ?>