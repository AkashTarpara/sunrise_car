<?php

use app\models\Aboutus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AboutusSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Aboutuses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aboutus-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Aboutus'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'about_us_id',
            'banner_title',
            'banner_sub_title',
            'image',
            'title',
            //'sub_title',
            //'description:ntext',
            //'status',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Aboutus $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'about_us_id' => $model->about_us_id]);
                 }
            ],
        ],
    ]); ?>


</div>
