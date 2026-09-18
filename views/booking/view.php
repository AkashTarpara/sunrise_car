<?php

use yii\helpers\Html;

$this->title = $model->booking_number;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$ride = is_array($model->ride_data) ? $model->ride_data : json_decode($model->ride_data, true);
$passenger = is_array($model->passenger_data) ? $model->passenger_data : json_decode($model->passenger_data, true);
$quote = is_array($model->quote_data) ? $model->quote_data : json_decode($model->quote_data, true);
?>
<div class="booking-view">
    <div class="card">
        <div class="card-header"><h5><?= Html::encode($model->booking_number) ?></h5></div>
        <div class="card-body">
            <p><strong>Vehicle:</strong> <?= Html::encode($model->fleet ? $model->fleet->name : $model->fleet_id) ?></p>
            <p><strong>Pickup:</strong> <?= Html::encode($model->pickup_date . ' ' . $model->pickup_time) ?></p>
            <p><strong>Route:</strong> <?= Html::encode($model->pickup) ?> to <?= Html::encode($model->dropoff) ?></p>
            <p><strong>Passenger:</strong> <?= Html::encode(($passenger['name'] ?? '') . ' / ' . ($passenger['email'] ?? '') . ' / ' . ($passenger['phone'] ?? '')) ?></p>
            <p><strong>Total:</strong> <?= Html::encode($model->currency . ' ' . $model->total) ?></p>
            <p><strong>Payment:</strong> <?= Html::encode($model->payment_status) ?> (<?= Html::encode($model->payment_intent_id ?: 'not created') ?>)</p>
            <p><strong>Booking status:</strong> <?= Html::encode($model->booking_status) ?></p>
            <p><strong>Notes:</strong> <?= nl2br(Html::encode($model->notes)) ?></p>
            <h6>Submitted ride data</h6>
            <pre><?= Html::encode(json_encode($ride, JSON_PRETTY_PRINT)) ?></pre>
            <h6>Submitted quote</h6>
            <pre><?= Html::encode(json_encode($quote, JSON_PRETTY_PRINT)) ?></pre>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>
</div>
