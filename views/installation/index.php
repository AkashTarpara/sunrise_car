<?php

use app\models\Installation;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\InstallationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Installations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Installation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'installation_id',
            'label',
            'title',
            'sub_title',
            'video',
            //'before_image',
            //'after_image',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Installation $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'installation_id' => $model->installation_id]);
                 }
            ],
        ],
    ]); ?>


</div>
