<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Installationcomplexity $model */

$this->title = Yii::t('app', 'Create Installationcomplexity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Installationcomplexities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installationcomplexity-create">
    <div class="card">
        <!-- <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div> -->
    <!-- </div> -->
    <div class="card-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
