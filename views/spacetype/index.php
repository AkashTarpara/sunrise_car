<?php

use app\models\Spacetype;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SpacetypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Spacetypes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spacetype-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add Space Type'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
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

                        //'space_type_id',
                        'type',
                        'price',
                        'created_at',
                        // 'updated_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
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
                                        'data-confirm' => 'Are you sure you want to delete this Product?',
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

    <div class="card">
        <div class="card-header">
            <h5 class="float-start">Sub floor</h5>
            <?= Html::a(Yii::t('app', 'Create Subfloorcondition'), ['subfloorcondition/create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProviderSubfloor,
                    'filterModel' => $searchModelSubfloor,
                    'emptyText' => 'No record(s) found.',
                    'summary' => "Showing {begin} - {end} of {totalCount} Record",
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>", //\n{summary}
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'subfloor_condition_id',
                        'type',
                        'price',
                        'created_at',
                        // 'updated_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
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
                                        title=" Update "><i class="far fa-edit"></i></button>', ['subfloorcondition/update', 'id' => $model->subfloor_condition_id],);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', ['subfloorcondition/delete', 'id' => $model->subfloor_condition_id], [
                                        'data-confirm' => 'Are you sure you want to delete this sub floor condition?',
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

    <div class="card">
        <div class="card-header">
            <h5 class="float-start">Installation complexity</h5>
            <?= Html::a(Yii::t('app', 'Create Installationcomplexity'), ['installationcomplexity/create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProviderInstallationcomplexitySearch,
                    'filterModel' => $searchModelInstallationcomplexitySearch,
                    'emptyText' => 'No record(s) found.',
                    'summary' => "Showing {begin} - {end} of {totalCount} Record",
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>", //\n{summary}
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'installation_complexity_id',
                        'type',
                        'price',
                        'created_at',
                        // 'updated_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
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
                                        title=" Update "><i class="far fa-edit"></i></button>', ['installationcomplexity/update', 'id' => $model->installation_complexity_id],);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', ['installationcomplexity/delete', 'id' => $model->installation_complexity_id], [
                                        'data-confirm' => 'Are you sure you want to delete this sub floor condition?',
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