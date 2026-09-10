<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Spacetype $model */

$this->title = Yii::t('app', 'Update Spacetype');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spacetypes'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->space_type_id, 'url' => ['view', 'space_type_id' => $model->space_type_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="spacetype-update">
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
