<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'product_id',
                        //'product_category_id',
                        [
                            'attribute' => 'product_category_id',
                            //'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'min-width:150px;'],
                            //'label'=>'Live on the site?',
                            'value' => function ($model) {
                                return (!empty($model->productCategory->title)) ? $model->productCategory->title : '';
                            },
                        ],
                        'title',
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
                        //'image',
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Image',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->image . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->image . '" class="img-avatar" style="width:50px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                            },
                        ],
                        'price',
                        'sqft_in_box',
                        'price_per_box',
                        'main_price',
                        'price_per_piece',
                        'save_button_price',
                        'slug',
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
