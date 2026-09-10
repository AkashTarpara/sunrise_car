<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Installationcomplexity $model */

$this->title = Yii::t('app', 'Update Installation Complexity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Installationcomplexities'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->installation_complexity_id, 'url' => ['view', 'installation_complexity_id' => $model->installation_complexity_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="installationcomplexity-update">
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
