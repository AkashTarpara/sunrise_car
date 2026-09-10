<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Userorderdetail $model */

$this->title = Yii::t('app', 'Update Userorderdetail: {name}', [
    'name' => $model->user_order_detail_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userorderdetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_order_detail_id, 'url' => ['view', 'user_order_detail_id' => $model->user_order_detail_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="userorderdetail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
