<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Warranty $model */

$this->title = Yii::t('app', 'Update Warranty: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Warranties'), 'url' => ['update']];
// $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'warranty_id' => $model->warranty_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="warranty-update">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'warrentydocument' => $warrentydocument,
                'warrantycertificate' => $warrantycertificate
            ]) ?>
        </div>
    </div>
</div>