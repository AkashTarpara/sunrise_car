<?php

use app\models\Contactus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

ini_set("pcre.backtrack_limit", "5000000");

/** @var yii\web\View $this */
/** @var app\models\ContactusSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Contact Us');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contactus-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <!-- <?= Html::a(Yii::t('app', 'Add Contact Us'), ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?> -->
            <?php $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'First Name',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->first_name;
                    },
                    'format' => 'text',
                ],
                [
                    'label' => 'Last Name',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->last_name;
                    },
                    'format' => 'text',
                ],


                [
                    'label' => 'Email',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return  $model->email;
                    },
                    'format' => 'raw'
                ],

                [
                    'label' => 'Number',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return  $model->number;
                    },
                    'format' => 'raw'
                ],

                [
                    'label' => 'Address',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return  $model->address;
                    },
                    'format' => 'raw'
                ],

                [
                    'label' => 'Message',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return  $model->message;
                    },
                    'format' => 'raw'
                ],


                [
                    'label' => 'Created At',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return  $model->created_at;
                    },
                    'format' => 'raw'
                ],


                ['class' => 'yii\grid\ActionColumn'],
            ];

            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'fontAwesome' => true,
                'class' => "pull-right",
                'target' => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'icon' => '<i class="fa fa-file-excel-o" aria-hidden="true"></i>',
                    'label' => 'Export All',
                    'class' => 'btn btn-default',
                    'columnSelectorMenuOptions' => [
                        'style' => 'overflow-y: scroll, height: auto; max-height: 200px;  overflow-x: hidden;',
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_HTML => ['filename' => Yii::t('app', 'Test')],
                        ExportMenu::FORMAT_HTML => ['filename' => Yii::t('app', 'Test')],
                        ExportMenu::FORMAT_HTML => ['filename' => Yii::t('app', 'Test')],
                        ExportMenu::FORMAT_HTML => ['filename' => Yii::t('app', 'Test')],
                        ExportMenu::FORMAT_HTML => ['filename' => Yii::t('app', 'Test')],
                    ],
                    //'filename' => 'Installation_data'.date('dd-MM-yy')
                ],
            ]); ?>
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

                        //'contact_us_id',
                        'first_name',
                        'last_name',
                        'email:email',
                        'number',
                        //'message:ntext',
                        'created_at',
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{view} {delete}',
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
                                        'data-confirm' => 'Are you sure you want to delete this Contact Us?',
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