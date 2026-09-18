<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Fleet');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fleet-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add Fleet'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => 'No record(s) found.',
                    'summary' => 'Showing {begin} - {end} of {totalCount} Record',
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>",
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center;',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'label',
                        'name',
                        'passenger',
                        'laggage',
                        'type',
                        'status',
                        'created_at',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            'header' => 'Action',
                            'buttons' => [
                                'view' => function ($url) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-info" title="View"><i class="fas fa-eye"></i></button>', $url);
                                },
                                'update' => function ($url) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-success" title="Update"><i class="far fa-edit"></i></button>', $url);
                                },
                                'delete' => function ($url) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this Fleet?',
                                        'data-method' => 'POST',
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
