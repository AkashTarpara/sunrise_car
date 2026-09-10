<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sample $model */

$this->title = Yii::t('app', 'Update Sample: {name}', [
    'name' => $model->sample_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sample_id, 'url' => ['view', 'sample_id' => $model->sample_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sample-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
