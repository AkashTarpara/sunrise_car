<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Subfloorcondition $model */

$this->title = Yii::t('app', 'Update Subfloorcondition: {name}', [
    'name' => $model->subfloor_condition_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subfloorconditions'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->subfloor_condition_id, 'url' => ['view', 'subfloor_condition_id' => $model->subfloor_condition_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="subfloorcondition-update">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
