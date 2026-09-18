<?php

use app\models\Tradepropartner;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

ini_set("pcre.backtrack_limit", "5000000");

/** @var yii\web\View $this */
/** @var app\models\TradepropartnerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Trade Pro Applications');
$this->params['breadcrumbs'][] = $this->title;

$statusLabels = [
    Tradepropartner::STATUS_PENDING => 'Pending',
    Tradepropartner::STATUS_APPROVED => 'Approved',
    Tradepropartner::STATUS_REJECTED => 'Rejected',
];
?>
<div class="tradepropartner-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?php $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'full_name', 'format' => 'text'],
                ['attribute' => 'email', 'format' => 'text'],
                ['attribute' => 'phone', 'format' => 'text'],
                ['attribute' => 'job_title', 'format' => 'text'],
                ['attribute' => 'years_experience', 'format' => 'text'],
                [
                    'label' => 'Area of Interest',
                    'value' => function ($model) {
                        return $model->interestSummary();
                    },
                    'format' => 'text',
                ],
                ['attribute' => 'project_type', 'format' => 'text'],
                ['attribute' => 'project_location', 'format' => 'text'],
                [
                    'label' => 'Status',
                    'value' => function ($model) use ($statusLabels) {
                        return $statusLabels[$model->status] ?? '';
                    },
                    'format' => 'text',
                ],
                [
                    'label' => 'Submitted At',
                    'value' => function ($model) {
                        return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '';
                    },
                    'format' => 'text',
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
                'filename' => 'trade_pro_partners_' . date('Y-m-d'),
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
                        'job_title',
                        [
                            'attribute' => 'status',
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
                            'buttons'  => [
                                'view'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" View "><i class="fas fa-eye"></i></button>', $url);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this Trade Pro application?',
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
