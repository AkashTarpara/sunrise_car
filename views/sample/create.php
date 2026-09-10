<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sample $model */

$this->title = Yii::t('app', 'Create Sample');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
