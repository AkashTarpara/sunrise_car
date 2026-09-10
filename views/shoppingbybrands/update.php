<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Shoppingbybrands $model */

$this->title = Yii::t('app', 'Update Shopping By Brands');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shopping By Brands'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'shopping_by_brands_id' => $model->shopping_by_brands_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shoppingbybrands-update">
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