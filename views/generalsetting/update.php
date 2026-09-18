<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Generalsetting */

$this->title = Yii::t('app', 'Update General Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'General Settings'), 'url' => ['dashboard/']];
//$this->params['breadcrumbs'][] = ['label' => $model->setting_id, 'url' => ['view', 'id' => $model->setting_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="generalsetting-update">
	<div class="card">
        <div class="card-header">
          <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
          <?= $this->render('_form', [
          'model' => $model,
          'advertisement' => $advertisement,
          'advertisementhome' => $advertisementhome,
          ]) ?>
        </div>
    </div>
</div>
