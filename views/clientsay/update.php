<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Clientsay */

$this->title = Yii::t('app', 'Update Client Say');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client Say'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->client_say_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="clientsay-update">
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