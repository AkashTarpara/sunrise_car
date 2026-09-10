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
    'model' => $modelsProductspecificationsdetail[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'title',
        'sub_title',
    ],
]); ?>
<table class="table table-striped " style="overflow: auto;white-space: nowrap;">
    <thead>
        <tr>
            <th>Product Specifications Detail</th>
            <th class="text-center">
                <button type="button" class="add-room  btn btn-sm btn-light-success"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
    </thead>
    <tbody class="container-rooms">
        <?php foreach ($modelsProductspecificationsdetail as $indexRoom => $modelRoom) : ?>
            <tr class="room-item">
                <td class="vcenter">
                    <?php
                    // necessary for update action.
                    if (!$modelRoom->isNewRecord) {
                        echo Html::activeHiddenInput($modelRoom, "[{$indexHouse}][{$indexRoom}]product_specifications_detail_id");
                    }
                    ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]title")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]sub_title")->textInput(['maxlength' => true]) ?>
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