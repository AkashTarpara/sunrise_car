<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Shippingcharge $model */

$this->title = Yii::t('app', 'Create Shipping Charge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shipping Charge'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shippingcharge-create">
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