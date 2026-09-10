<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Contentpagedetail $model */

$this->title = Yii::t('app', 'Update Contentpagedetail: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contentpagedetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'content_page_detail_id' => $model->content_page_detail_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="contentpagedetail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
