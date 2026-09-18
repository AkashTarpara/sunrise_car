<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Adminuser */

$this->title = Yii::t('app', 'Create Adminuser');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Adminusers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-create">
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