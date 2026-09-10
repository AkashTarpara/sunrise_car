<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Userorderdetail $model */

$this->title = Yii::t('app', 'Create Userorderdetail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userorderdetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userorderdetail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
