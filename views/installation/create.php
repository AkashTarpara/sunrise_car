<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Installation $model */

$this->title = 'Create Installation';
$this->params['breadcrumbs'][] = ['label' => 'Installations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
