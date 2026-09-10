<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Adminsidemenu */

$this->title = Yii::t('app', 'Update Admin Side Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Side Menu'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->admin_sidemenu_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="adminsidemenu-update">
	<div class="card">
        <div class="card-header">
          <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
            'model' => $model,
            'modelsadminsidemenudetail' => $modelsadminsidemenudetail,
            'modelsAdminsidemenusubdetail' => $modelsAdminsidemenusubdetail,
            ]) ?>
        </div>
    </div>
</div>
