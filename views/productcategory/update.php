<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Productcategory $model */

$this->title = Yii::t('app', 'Update Product Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Category'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'product_category_id' => $model->product_category_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="productcategory-update">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>