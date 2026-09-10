<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Newsletter */

$this->title = Yii::t('app', 'Create News & Blog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News & Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-create">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>