<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Userorder $model */

$this->title = Yii::t('app', 'Update Userorder: {name}', [
    'name' => $model->user_order_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userorders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_order_id, 'url' => ['view', 'user_order_id' => $model->user_order_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="userorder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
