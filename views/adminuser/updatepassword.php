<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Businesses */

$this->title = Yii::t('app', 'Update Password');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update Password '), 'url' => ['/businessesadmin']];
//$this->params['breadcrumbs'][] = ['label' => $model->businesses_id, 'url' => ['view', 'id' => $model->businesses_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update Password');
?>
<div class="adminuser-update">
  <div class="card">
    <div class="card-header">
      <h5><?= Html::encode($this->title) ?></h5>
    </div>
    <div class="card-body">
      <?= $this->render('resetPassword', [
      'model' => $model,
      ]) ?>
    </div>
  </div>
</div>
