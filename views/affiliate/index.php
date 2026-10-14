<?php

use app\models\Affiliate;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

ini_set("pcre.backtrack_limit", "5000000");

/** @var yii\web\View $this */
/** @var app\models\AffiliateSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Affiliate Applications');
$this->params['breadcrumbs'][] = $this->title;

$statusLabels = Affiliate::statusOptions();
?>
<div class="affiliate-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?php $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'full_name', 'format' => 'text'],
                ['attribute' => 'email', 'format' => 'text'],
                ['attribute' => 'phone', 'format' => 'text'],
                ['attribute' => 'model_year', 'format' => 'text'],
                ['attribute' => 'make_model', 'format' => 'text'],
                ['attribute' => 'exterior_finish', 'value' => function ($model) { return $model->optionLabel('exterior_finish'); }],
                ['attribute' => 'interior_upholstery', 'value' => function ($model) { return $model->optionLabel('interior_upholstery'); }],
                ['attribute' => 'vehicle_class', 'value' => function ($model) { return $model->optionLabel('vehicle_class'); }],
                ['attribute' => 'tlc_base_affiliation', 'format' => 'text'],
                ['attribute' => 'legal_business_name', 'format' => 'text'],
                ['attribute' => 'payout_method', 'value' => function ($model) { return $model->optionLabel('payout_method'); }],
                [
                    'label' => 'Status',
                    'value' => function ($model) use ($statusLabels) {
                        return $statusLabels[$model->status] ?? '';
                    },
                ],
                [
                    'label' => 'Submitted At',
                    'value' => function ($model) {
                        return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '';
                    },
                ],
            ];

            echo ExportMenu::widget([
                'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
                'columns' => $gridColumns,
                'fontAwesome' => true,
                'class' => "pull-right",
                'target' => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'icon' => '<i class="fa fa-file-excel-o" aria-hidden="true"></i>',
                    'label' => 'Export All',
                    'class' => 'btn btn-default',
                ],
                'filename' => 'affiliates_' . date('Y-m-d'),
            ]); ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => 'No record(s) found.',
                    'summary' => "Showing {begin} - {end} of {totalCount} Record",
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>",
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'full_name',
                        'email:email',
                        'phone',
                        'make_model',
                        [
                            'attribute' => 'vehicle_class',
                            'filter' => Affiliate::vehicleClassOptions(),
                            'value' => function ($model) {
                                return $model->optionLabel('vehicle_class');
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'filter' => $statusLabels,
                            'value' => function ($model) use ($statusLabels) {
                                return $statusLabels[$model->status] ?? '';
                            },
                        ],
                        [
                            'label' => 'Submitted At',
                            'value' => function ($model) {
                                return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '';
                            },
                        ],
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{view} {delete}',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            'header' => 'Action',
                            'urlCreator' => function ($action, $model) {
                                return \yii\helpers\Url::to([$action, 'id' => $model->affiliate_id]);
                            },
                            'buttons'  => [
                                'view'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" View "><i class="fas fa-eye"></i></button>', $url);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this affiliate application?',
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
