<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Contentpage $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Page'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contentpage-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'content_page_id',
                        'title',
                        'meta_title',
                        'meta_tag',
                        'meta_description:ntext',
                        'slug',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>

                <div class="card">
                    <div class="card-header">
                        <h5>Page Detail</h5>
                    </div>
                    <div class="card-body table-border-style">
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
                            'headerRowOptions'=>['class'=>'thead-inverse'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'content_page_detail_id',
                                //'content_page_id',
                                'type',
                                'section_title',
                                'title:ntext',
                                //'sub_title',
                                //'image',
                                //'button_1_title',
                                //'button_1_url:url',
                                //'button_2_title',
                                //'button_2_url:url',
                                //'logo',
                                //'video',
                                //'display_order',
                                //'status',
                                //'created_at',
                                //'updated_at',
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
                                            <input type='checkbox' class='form-check-input input-warning statuschange' id='".$model->content_page_detail_id."'".$checked." value=".$model->content_page_detail_id.">
                                        </div>";
                                    },
                                    'filter' => true,
                                    'filter'=>['Active'=>"Activated",'Inactive'=>"Deactivated"],
                                    'filterInputOptions'=>['class' => 'form-control selectpicker','prompt' => 'All'],
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'contentOptions' =>['width' => '10%'],
                                    'format' => 'raw',
                                    'label'=>'Order No',    
                                    'value' => function ($model) use ($total){
                                        return "
                                        <form id='form_".$model->content_page_detail_id."' action='".Yii::$app->urlManager->createUrl(['contentpagedetail/changeorder'])."' method='POST'>
                                        <input type='hidden' value='".$total."' name='total'>
                                        <input type='hidden' value='".$model->content_page_detail_id."' name='id'>
                                        <input type='hidden' value='".$model->content_page_id."' name='content_page_id'>
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
                                          return  "<a href='javascript:;' class='btn btn-sm btn-light-info submit-order' id='".$model->content_page_detail_id."' >Change</a>";
                                      },
                                ],
                                'created_at',
                            ],
                        ]); ?>
                    </div>
                </div>
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
            url: '<?= Yii::$app->urlManager->createUrl(['contentpagedetail/changestatus']) ?>',
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
            },
            error: function(xhr, status, error) {
                // Your error handling logic here
            }
        });

              
    });
});
</script>