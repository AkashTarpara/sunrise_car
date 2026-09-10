<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Shippingcharge $model */

$this->title = $model->shipping_charge_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shipping Charge'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="shippingcharge-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'shipping_charge_id',
                        'min_mile',
                        'max_mile',
                        'price',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>