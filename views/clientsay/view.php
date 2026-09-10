<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Clientsay */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client Say'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="clientsay-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'client_say_id',
                        'name',
                        'designation',
                        //'company_name',
                        'description:ntext',
                        //'image',
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Image',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->image . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->image . '" class="img-avatar" style="width:100px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                            },
                        ],
                        /* [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Video',
                            'value' => function ($model) {
                                return  '<div class="avatar">
                                    <video width="150" controls>
                                        <source src="' . Yii::getAlias("@web") . "/" . $model->video . '" type="video/mp4">
                                            <source src="mov_bbb.ogg" type="video/ogg">
                                                Your browser does not support HTML5 video.
                                    </video>                                 
                                </div>';
                            },
                        ], */
                        'status',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>