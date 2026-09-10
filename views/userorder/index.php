<?php

use app\models\Userorder;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserorderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'User Order');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userorder-index">
    <div class="card">
        <!-- <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add User Order'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div> -->
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => 'No record(s) found.',
                    'summary' => "Showing {begin} - {end} of {totalCount} Record",
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>", //\n{summary}
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'user_order_id',
                        //'appuser_id',
                        //'appuser_address_id',
                        [
                            'attribute' => 'appuser_id',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuser->full_name)) ? $model->appuser->full_name : '';
                            },
                        ],
                        // [
                        //     'attribute' => 'appuser_address_id',
                        //     //'class' => 'yii\grid\DataColumn',
                        //     'format' => 'raw',
                        //     'headerOptions' => ['style' => 'min-width:150px;'],
                        //     //'label'=>'Live on the site?',
                        //     'value' => function ($model) {
                        //         return (!empty($model->appuserAddress->address_line_1)) ? $model->appuserAddress->address_line_1 : '';
                        //     },
                        // ],
                        //'payment_type',
                        'order_number',
                        [
                            'attribute' => 'delivery_type',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            //'label'=>'Match Status',
                            'headerOptions' => ['style' => 'min-width:130px;'],
                            'value' => function ($model) {
                                return  $model->delivery_type;
                            },
                            'filter' => true,
                            'filter' => ['Delivery' => "Delivery", 'Pickup' => "Pickup"],
                            'filterInputOptions' => ['class' => 'form-control selectpicker', 'prompt' => 'All'],
                        ],
                        'payment_status',
                        'payment_id',
                        'sub_total',
                        'total',
                        //'payment_date',
                        'order_status',
                        //'delivery_status',
                        [
                            'attribute' => 'delivery_status',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            //'label'=>'Match Status',
                            'headerOptions' => ['style' => 'min-width:130px;'],
                            'value' => function ($model) {
                                return  $model->getDeliverystatus();
                            },
                            'filter' => true,
                            'filter' => ['Pending' => "Pending", 'Packaging' => "Packaging", 'Delivered' => "Delivered", 'Canceled' => "Canceled"],
                            'filterInputOptions' => ['class' => 'form-control selectpicker', 'prompt' => 'All'],
                        ],

                        'created_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{view}{delete}',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'options' => ['style' => 'min-width:200px;'],
                            'header' => 'Action',
                            'buttons'  => [
                                'view'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" View "><i class="fas fa-eye"></i></button>', $url);
                                },

                                'delete'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this User Order?',
                                        'data-method' => 'POST'
                                    ]);
                                },
                            ],

                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function changeDeliverystatus(sttId) {

        var dynamic_module_id = $(sttId).attr('id');
        $(".btn-spinner-payment-" + dynamic_module_id).removeClass("hide");
        var status_value = $(sttId).val();
        $.ajax({
            type: 'POST',
            data: {
                status: $(sttId).val(),
                id: dynamic_module_id
            },
            url: "<?= Yii::$app->urlManager->createUrl("userorder/deliverystatus") ?>",
            success: function(result) {
                //$("#"+dynamic_module_id).load(location.href + " #"+dynamic_module_id);
                //$(".sort_type").load(location.href + " .sort_type");
                //$.pjax.reload({container: "#manage-player-type", timeout: 2000});
                //$.pjax.reload({container: "#manage-player-type", async:false});                
            }
        });
    }
</script>