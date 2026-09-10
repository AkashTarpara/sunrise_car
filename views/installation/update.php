<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Installation $model */

$this->title = 'Update Installation: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Installations', 'url' => ['update']];
// $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'installation_id' => $model->installation_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="installation-update">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'installationbanner' => $installationbanner,
                'installationprocess' => $installationprocess
            ]) ?>
        </div>
    </div>
</div>
