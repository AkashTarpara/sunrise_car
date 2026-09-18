<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Adminsidemenudetail */

$this->title = Yii::t('app', 'Update Adminsidemenudetail: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Adminsidemenudetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->admin_sidemenu_detail_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="adminsidemenudetail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
