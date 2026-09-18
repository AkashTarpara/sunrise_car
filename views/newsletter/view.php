<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Newsletter */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News & Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="newsletter-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'newsletter_id',
                        'title',
                        'sub_title',
                        //'description:ntext',
                        [
                            'attribute' => 'description',
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            //'contentOptions' => [ 'style' => 'background-color:black' ],
                            //'label'=>'course_description_en',
                            'value' => function ($model) {
                                return  $model->description;
                            }
                        ],
                        // 'image',
                        // 'thumbnail_image',
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
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Thumbnail Image',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->thumbnail_image . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->thumbnail_image . '" class="img-avatar" style="width:100px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                            },
                        ],
                        // 'image_height',
                        // 'image_width',
                        // 'thumbnail_height',
                        // 'thumbnail_width',
                        'meta_title',
                        'meta_tag',
                        'meta_description:ntext',
                        'is_featured',
                        //'type',
                        //'contact_type',
                        //'btn_title',
                        //'btn_url',
                        //'btn_type',
                        'date',
                        'slug',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>