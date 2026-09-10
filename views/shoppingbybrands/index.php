<?php

use app\models\Shoppingbybrands;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ShoppingbybrandsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Shopping By Brand');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shoppingbybrands-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add Shopping By Brand'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
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

                        //'shopping_by_brands_id',
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Image',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->image . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->image . '" class="img-avatar" style="width:50px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                            },
                        ],
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Logo',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->logo . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->logo . '" class="img-avatar" style="width:50px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                            },
                        ],
                        'title',
                        // 'image',
                        // 'logo',
                        // 'display_order',
                        [
                            'class' => 'yii\grid\DataColumn',
                            'contentOptions' => ['width' => '10%'],
                            'format' => 'raw',
                            'label' => 'Order No',
                            'value' => function ($model) use ($total) {
                                return "
                                <form id='form_" . $model->shopping_by_brands_id . "' action='" . Yii::$app->urlManager->createUrl(['shoppingbybrands/changeorder']) . "' method='POST'>
                                <input type='hidden' value='" . $total . "' name='total'>
                                <input type='hidden' value='" . $model->shopping_by_brands_id . "' name='id'>
                                <input type='hidden' value='" . $model->display_order . "' name='current'>
                                <input type='text' value='" . $model->display_order . "' class='form-control' name='target'>
                                </form>
                                ";
                            },
                        ],
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'attribute' => 'Sequence',
                            'value' => function ($model) {
                                return  "<a href='javascript:;' class='btn btn-sm btn-light-info submit-order' id='" . $model->shopping_by_brands_id . "' >Change</a>";
                            },
                        ],

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
                                        <input type='checkbox' class='form-check-input input-warning statuschange' id='" . $model->shopping_by_brands_id . "'" . $checked . " value=" . $model->shopping_by_brands_id . ">
                                    </div>";
                            },
                            'filter' => true,
                            'filter' => ['Active' => "Activated", 'Inactive' => "Deactivated"],
                            'filterInputOptions' => ['class' => 'form-control selectpicker select2', 'prompt' => 'All'],
                        ],
                        //'status',
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
                                        'data-confirm' => 'Are you sure you want to delete this Shopping By Brand?',
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
    $(document).on("click", '.submit-order', function(e) {
        //alert($(this).attr('id'));
        $('#form_' + $(this).attr('id')).submit();
    });

    $(document).ready(function() {
        $('.statuschange').on('change', function() {
            //alert($(this).val());

            $.ajax({
                url: '<?= Yii::$app->urlManager->createUrl(['shoppingbybrands/changestatus']) ?>',
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