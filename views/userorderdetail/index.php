<?php

use app\models\Userorderdetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserorderdetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Userorderdetails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userorderdetail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Userorderdetail'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_order_detail_id',
            'user_order_id',
            'product_id',
            'price',
            'quantity',
            //'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Userorderdetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'user_order_detail_id' => $model->user_order_detail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
