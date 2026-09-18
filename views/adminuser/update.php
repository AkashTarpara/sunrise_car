<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Adminuser */

$this->title = Yii::t('app', 'Update Admin user');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Users')];
//$this->params['breadcrumbs'][] = ['label' => $model->admin_id, 'url' => ['view', 'id' => $model->admin_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Admin');
?>
<div class="adminuser-update">
  <div class="card">
    <div class="card-header">
      <h5><?= Html::encode($this->title) ?></h5>
    </div>
    <div class="card-body">
      <?= $this->render('_formadmin', [
        'model' => $model,
        'rolsArr' => $rolsArr,
        'selectArr' => $selectArr
      ]) ?>
    </div>
  </div>
</div>