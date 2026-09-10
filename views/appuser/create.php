<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Appuser $model */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appuser-create">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'rolsArr' => $rolsArr,
                'selectArr' => $selectArr
            ]) ?>
        </div>
    </div>
</div>