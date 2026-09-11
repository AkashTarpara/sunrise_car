<?php

use yii\helpers\Html;

$this->title = 'Update Fleet';
$this->params['breadcrumbs'][] = ['label' => 'Fleet', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fleet-update">
    <div class="card">
        <div class="card-header"><h5><?= Html::encode($this->title) ?></h5></div>
        <div class="card-body"><?= $this->render('_form', ['model' => $model]) ?></div>
    </div>
</div>
