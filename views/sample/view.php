<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Sample $model */

$this->title = $model->sample_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sample'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sample-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'sample_id',
                        'first_name',
                        'last_name',
                        'company_name',
                        'email:email',
                        'phone_no',
                        'address',
                        'address_line_2',
                        'city',
                        'state',
                        'zip_code',
                        'product_name',
                        'order_number',
                        'payment_type',
                        'payment_status',
                        'payment_id',
                        'sub_total',
                        'tax',
                        'shipping',
                        'total',
                        'payment_date',
                        //'order_status',
                        //'delivery_status',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>