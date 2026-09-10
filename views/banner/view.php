<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="banner-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'banner_id',

                        'title',
                        'subtitle',
                        'url:url',
                        'btn_title',
                        'type',
                        // 'image',
                        // 'video',
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Image/Video',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                if ($model->type == 'Local') {
                                    return  '<div class="avatar">
                                        <a class="single_image" href="' . Yii::getAlias("@web") . "/" . $model->image . '">
                                                <img src="' . Yii::getAlias("@web") . "/" . $model->image . '" class="img-avatar" style="width:100px;" alt="Image">    
                                        </a>                                   
                                    </div>';
                                } else {
                                    return  '<div class="avatar">
                                        <video width="150" controls>
                                            <source src="' . Yii::getAlias("@web") . "/" . $model->video . '" type="video/mp4">
                                                <source src="mov_bbb.ogg" type="video/ogg">
                                                    Your browser does not support HTML5 video.
                                        </video>                                 
                                    </div>';
                                }
                            },
                        ],
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Mobile Video',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                if (!empty($model->mobile_video)) {
                                    return  '<div class="avatar">
                                        <video width="150" controls>
                                            <source src="' . Yii::getAlias("@web") . "/" . $model->mobile_video . '" type="video/mp4">
                                                <source src="mov_bbb.ogg" type="video/ogg">
                                                    Your browser does not support HTML5 video.
                                        </video>                                 
                                    </div>';
                                } else {
                                    return '';
                                }
                            },
                        ],
                        'display_order',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>