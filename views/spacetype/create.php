<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Spacetype $model */

$this->title = Yii::t('app', 'Create Spacetype');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spacetypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spacetype-create">
    <div class="card">
        <!-- <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div> -->
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
