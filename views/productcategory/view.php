<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Productcategory $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="productcategory-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'product_category_id',
                        'title',
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
                        [
                            'class' => 'yii\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Banner Image',
                            'value' => function ($model) {
                                //echo $model->status;exit;
                                return  '<div class="avatar">
                                            <a class="single_image" href="' . Yii::$app->params['ImagePath'] . $model->banner_image . '">
                                                    <img src="' . Yii::$app->params['ImagePath'] . $model->banner_image . '" class="img-avatar" style="width:50px;" alt="Image">    
                                            </a>                                   
                                        </div>';
                            },
                        ],
                        'description',
                        'price',
                        'material_per_step',
                        'installation_per_step',
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
