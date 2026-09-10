<?php

use app\models\Shippingcharge;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ShippingchargeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Shipping Charge');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shippingcharge-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add Shipping Charge'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "Showing {begin} - {end} of {totalCount} Record",
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>", //\n{summary}
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'shipping_charge_id',
                        'min_mile',
                        'max_mile',
                        'price',
                        //'status',
                        [
                            'attribute' => 'status', // Assuming 'status' is the attribute representing the toggle state in your model
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            'value' => function ($model) {
                                $checked = '';
                                if ($model->status == 'Active') {
                                    $checked = 'checked';
                                }
                                return "<div class='form-check form-switch custom-switch-v1 mb-2'>
                                        <input type='checkbox' class='form-check-input input-warning statuschange' id='" . $model->shipping_charge_id . "'" . $checked . " value=" . $model->shipping_charge_id . ">
                                    </div>";
                            },
                            'filter' => true,
                            'filter' => ['Active' => "Activated", 'Inactive' => "Deactivated"],
                            'filterInputOptions' => ['class' => 'form-control selectpicker select2', 'prompt' => 'All'],
                        ],
                        'created_at',
                        //'updated_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'options' => ['style' => 'min-width:200px;'],
                            'header' => 'Action',
                            'buttons'  => [
                                'view'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" View "><i class="fas fa-eye"></i></button>', $url);
                                },

                                'update'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Update "><i class="far fa-edit"></i></button>', $url);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this Shipping Charge?',
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
    $(document).ready(function() {
        $('.statuschange').on('change', function() {
            //alert($(this).val());

            $.ajax({
                url: '<?= Yii::$app->urlManager->createUrl(['shippingcharge/changestatus']) ?>',
                type: 'POST',
                data: {
                    id: $(this).val()
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

            //state = $(this).val();

        });
    });
</script>