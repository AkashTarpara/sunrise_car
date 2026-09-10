<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Userorder $model */

$this->title = $model->user_order_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Order'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="userorder-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'user_order_id',
                        //'appuser_id',
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
                        [
                            //'attribute' => 'appuser_id',
                            'label' => "Recipient's Name",
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->name)) ? $model->appuserAddress->name : '';
                            },
                        ],
                        [
                            //'attribute' => 'appuser_id',
                            'label' => 'Address Line 1',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->address_line_1)) ? $model->appuserAddress->address_line_1 : '';
                            },
                        ],
                        [
                            //'attribute' => 'appuser_id',
                            'label' => 'Address Line 2',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->address_line_2)) ? $model->appuserAddress->address_line_2 : '';
                            },
                        ],
                        [
                            //'attribute' => 'appuser_id',
                            'label' => 'City',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->city)) ? $model->appuserAddress->city : '';
                            },
                        ],
                        [
                            //'attribute' => 'appuser_id',
                            'label' => 'State',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->state)) ? $model->appuserAddress->state : '';
                            },
                        ],
                        [
                            //'attribute' => 'appuser_id',
                            'label' => 'Postal Code',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->postal_code)) ? $model->appuserAddress->postal_code : '';
                            },
                        ],
                        [
                            //'attribute' => 'appuser_id',
                            'label' => 'Mobile Number',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->appuserAddress->mobile_number)) ? $model->appuserAddress->mobile_number : '';
                            },
                        ],
                        //'appuser_address_id',
                        'order_number',
                        'delivery_type',
                        'payment_type',
                        'payment_status',
                        'payment_id',
                        'sub_total',
                        'tax',
                        'shipping',
                        'total',
                        'payment_date',
                        'order_status',
                        'delivery_status',
                        'created_at',
                    ],
                ]) ?>

                <div class="card">
                    <div class="card-header">
                        <h5>User Order Detail</h5>
                    </div>
                    <div class="card-body table-border-style">
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
                            'headerRowOptions' => ['class' => 'thead-inverse'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'user_order_detail_id',
                                //'user_order_id',
                                //'product_id',
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'format' => 'raw',
                                    'label' => 'Image',
                                    'value' => function ($model) {
                                        //echo $model->status;exit;
                                        if (!empty($model->product->image)) {
                                            return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->product->image . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->product->image . '" class="img-avatar" style="width:50px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                                        }
                                    },
                                ],
                                [
                                    'attribute' => 'product_id',
                                    //'class' => 'yii\grid\DataColumn',
                                    'format' => 'raw',
                                    'headerOptions' => ['style' => 'min-width:150px;'],
                                    //'label'=>'Live on the site?',
                                    'value' => function ($model) {
                                        return (!empty($model->product->title)) ? $model->product->title : '';
                                    },
                                ],
                                [
                                    'attribute' => 'price',
                                    //'class' => 'yii\grid\DataColumn',
                                    'format' => 'raw',
                                    'headerOptions' => ['style' => 'min-width:150px;'],
                                    //'label'=>'Live on the site?',
                                    'value' => function ($model) {
                                        return (!empty($model->product->price_per_box)) ? $model->product->price_per_box : '';
                                    },
                                ],
                                //'price',
                                'quantity',

                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>