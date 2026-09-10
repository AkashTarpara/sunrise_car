<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Subfloorcondition $model */

$this->title = Yii::t('app', 'Create Subfloorcondition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subfloorconditions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subfloorcondition-create">
    <div class="card">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <div class="card-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
