<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Advertisement $model */

$this->title = Yii::t('app', 'Update Advertisement: {name}', [
    'name' => $model->advertisement_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advertisements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->advertisement_id, 'url' => ['view', 'advertisement_id' => $model->advertisement_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="advertisement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
