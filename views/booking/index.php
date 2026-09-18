<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Bookings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">
    <div class="card">
        <div class="card-header"><h5><?= Html::encode($this->title) ?></h5></div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => 'No booking(s) found.',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'booking_number',
                        [
                            'attribute' => 'fleet_id',
                            'label' => 'Vehicle',
                            'value' => static fn ($model) => $model->fleet ? $model->fleet->name : $model->fleet_id,
                        ],
                        'pickup_date',
                        'pickup_time',
                        'total',
                        'payment_status',
                        'booking_status',
                        'created_at',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {delete}',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
