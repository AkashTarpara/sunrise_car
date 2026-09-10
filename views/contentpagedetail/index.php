<?php

use app\models\Contentpagedetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ContentpagedetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Contentpagedetails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contentpagedetail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Contentpagedetail'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'content_page_detail_id',
            'content_page_id',
            'type',
            'section_title',
            'title:ntext',
            //'sub_title',
            //'image',
            //'button_1_title',
            //'button_1_url:url',
            //'button_2_title',
            //'button_2_url:url',
            //'logo',
            //'video',
            //'display_order',
            //'status',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Contentpagedetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'content_page_detail_id' => $model->content_page_detail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
