<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminsidemenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Admin Side Menu');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminsidemenu-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add Admin Side Menu'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?php Pjax::begin(['id' => 'my-pjax-container']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText'=>'No record(s) found.',
                    'summary' => "Showing {begin} - {end} of {totalCount} Record",
                    'layout'=>"{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>",//\n{summary}
                    'tableOptions' => [
                        'class' => 'table',
                        'style'=>'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'admin_sidemenu_id',
                        'title',
                        //'controller_name',
                        //'action_name',
                        //'icon',
                        //'status',
                        [
                            'attribute' => 'status', // Assuming 'status' is the attribute representing the toggle state in your model
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            'value' => function ($model) {
                                $checked='';
                                if($model->status == 'Active'){
                                    $checked='checked';
                                }
                               return "<div class='form-check form-switch custom-switch-v1 mb-2'>
                                        <input type='checkbox' class='form-check-input input-warning statuschange' id='".$model->admin_sidemenu_id."'".$checked." value=".$model->admin_sidemenu_id.">
                                    </div>";
                            },
                            'filter' => true,
                            'filter'=>['Active'=>"Activated",'Inactive'=>"Deactivated"],
                            'filterInputOptions'=>['class' => 'form-control selectpicker','prompt' => 'All'],
                        ],
                        /*[
                            'attribute'=>'status',
                            //'class' => 'yii\grid\DataColumn',
                            'format'=>'raw',
                            'label'=>'Status',
                            'value'=>function($model){
                                return  $model->IsActive();
                            },
                            'filter' => true,
                            'filter'=>['Active'=>"Activated",'Inactive'=>"Deactivated"],
                            'filterInputOptions'=>['class' => 'form-control selectpicker','prompt' => 'All'],
                        ],*/
                        //'display_order',
                        [
                            'class' => 'yii\grid\DataColumn',
                            'contentOptions' =>['width' => '10%'],
                            'format' => 'raw',
                            'label'=>'Order No',    
                            'value' => function ($model) use ($total){
                                return "
                                <form id='form_".$model->admin_sidemenu_id."' action='".Yii::$app->urlManager->createUrl(['adminsidemenu/changeorder'])."' method='POST'>
                                <input type='hidden' value='".$total."' name='total'>
                                <input type='hidden' value='".$model->admin_sidemenu_id."' name='id'>
                                <input type='hidden' value='".$model->display_order."' name='current'>
                                <input type='text' value='".$model->display_order."' class='form-control' name='target'>
                                </form>
                                ";
                            },
                        ],
                        [
                              'class' => 'yii\grid\DataColumn',
                              'format'=>'raw',
                              'attribute'=>'Sequence',
                              'value'=>function($model){
                                  return  "<a href='javascript:;' class='btn btn-sm btn-light-info submit-order' id='".$model->admin_sidemenu_id."' >Change</a>";
                              },
                        ],
                        'created_at',

                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'options' => ['style' => 'min-width:200px;'],
                            'header'=>'Actions',
                            'buttons'  => [
                                'view'   => function ($url, $model) {
                                    return Html::a('<button class="btn btn-sm btn-icon btn-link-info"><i class="ico fa fa-eye"></i></button>', $url, ['title' => 'view']);
                                },

                                'update'   => function ($url, $model) {
                                    return Html::a('<button class="btn btn-sm btn-icon btn-link-success"><i class="ico fa fa-edit"></i></button>', $url, ['title' => 'update']);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<button class="btn btn-sm btn-icon btn-link-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                                'title' => Yii::t('app', 'Delete'),
                                                'data-confirm'=>'Are you sure you want to delete this Admin Side Menu?',
                                                'data-method'=>'POST'
                                    ]);
                                },
                            ],
                                
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).on("click",'.submit-order',function(e){
  //alert($(this).attr('id'));
  $('#form_'+$(this).attr('id')).submit();
});

$(document).ready(function(){
    $('.statuschange').on('change', function() {
        //alert($(this).val());

        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(['adminsidemenu/changestatus']) ?>',
            type: 'POST',
            data: {
                id:$(this).val()
            },
            success: function(res) {
                toastr.success(res.message, '', {
                    positionClass: 'toast-top-right',
                    timeout: 3000, // Dismiss after 3 seconds
                    closeButton: false,
                    progressBar: true,
                    preventDuplicates: true
                });
                //$('.status-switch').on('change', function() {
                     
                $(".pc-navbar").load(location.href + " .pc-navbar");
                //$('#'+$(this).attr('id')).checked = true;;
                //var id = document.getElementById($(this).val());
                //console.log($('#'+$(this).attr('id')).removeAttribute("checked"));
                //document.getElementById($(this).val()).removeAttribute("checked");
                /*console.log(res.status);
                var checkbox = $('[value="' + data.admin_sidemenu_id + '"]');
                // Check if the checkbox exists
                if (checkbox.length > 0) {
                    // Update the checked state of the checkbox
                    checkbox.prop('checked', newState);
                    // Trigger the change event to update the toggle switch appearance
                    checkbox.trigger('change');
                }*/
                //alert(JSON.parse(JSON.stringify(res)))
                //$('input[data-toggle="switchbutton"]').bootstrapToggle();
                // Your success logic here
                
                // Reload PJAX container after AJAX success
                //$.pjax.reload({container: '#my-pjax-container'});
                //$('input[data-toggle="switchbutton"]').bootstrapToggle();
            },
            error: function(xhr, status, error) {
                // Your error handling logic here
            }
        });

        //state = $(this).val();
              
    });
});
</script>
