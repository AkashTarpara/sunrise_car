<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Adminsidemenudetail */

$this->title = Yii::t('app', 'Create Adminsidemenudetail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Adminsidemenudetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminsidemenudetail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
