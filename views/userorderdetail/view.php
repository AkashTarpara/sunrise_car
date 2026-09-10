<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Userorderdetail $model */

$this->title = $model->user_order_detail_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userorderdetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="userorderdetail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'user_order_detail_id' => $model->user_order_detail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'user_order_detail_id' => $model->user_order_detail_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_order_detail_id',
            'user_order_id',
            'product_id',
            'price',
            'quantity',
            'created_at',
        ],
    ]) ?>

</div>
