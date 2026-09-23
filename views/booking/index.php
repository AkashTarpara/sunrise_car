<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BookingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Bookings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'emptyText'    => 'No booking(s) found.',
                    'summary'      => "Showing {begin} - {end} of {totalCount} Record",
                    'layout'       => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>",
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'booking_number',

                        [
                            'attribute'    => 'fleet_id',
                            'label'        => 'Vehicle',
                            'value'        => function ($model) {
                                return $model->fleet ? $model->fleet->name : '-';
                            },
                        ],

                        [
                            'attribute' => 'passenger_data',
                            'label'     => 'Passenger',
                            'value'     => function ($model) {
                                $data = json_decode($model->passenger_data, true);
                                if (is_array($data)) {
                                    $name = $data['name'] ?? '';
                                    $phone = $data['phone'] ?? '';
                                    return $name . ($phone ? ' | ' . $phone : '');
                                }
                                return '-';
                            },
                            'filter' => false,
                        ],

                        'pickup_date',
                        'pickup_time',

                        [
                            'attribute' => 'total',
                            'label'     => 'Total',
                            'value'     => function ($model) {
                                return '$' . number_format((float)$model->total, 2);
                            },
                            'filter'    => false,
                        ],

                        [
                            'attribute' => 'payment_status',
                            'label'     => 'Payment',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                $status = $model->payment_status;
                                $badgeClass = 'secondary';
                                if ($status === 'paid') {
                                    $badgeClass = 'success';
                                } elseif ($status === 'pending') {
                                    $badgeClass = 'warning';
                                } elseif ($status === 'failed') {
                                    $badgeClass = 'danger';
                                }
                                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($status) . '</span>';
                            },
                            'filter'    => [
                                'pending' => 'Pending',
                                'paid'    => 'Paid',
                                'failed'  => 'Failed',
                            ],
                        ],

                        [
                            'attribute' => 'booking_status',
                            'label'     => 'Status',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                $status = $model->booking_status;
                                $badgeClass = 'secondary';
                                if ($status === 'confirmed') {
                                    $badgeClass = 'success';
                                } elseif ($status === 'pending_payment') {
                                    $badgeClass = 'warning';
                                } elseif ($status === 'cancelled') {
                                    $badgeClass = 'danger';
                                }
                                $label = ucwords(str_replace('_', ' ', $status));
                                return '<span class="badge badge-' . $badgeClass . '">' . $label . '</span>';
                            },
                            'filter'    => [
                                'pending_payment' => 'Pending Payment',
                                'confirmed'       => 'Confirmed',
                                'cancelled'       => 'Cancelled',
                            ],
                        ],

                        'created_at',

                        [
                            'class'         => 'yii\grid\ActionColumn',
                            'template'      => '{view}{delete}',
                            'headerOptions' => ['style' => 'min-width:120px;'],
                            'header'        => 'Action',
                            'buttons'       => [
                                'view'   => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" View "><i class="fas fa-eye"></i></button>', $url);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                        title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this Booking?',
                                        'data-method'  => 'POST',
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
