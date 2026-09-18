<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Contentpage $model */

$this->title = Yii::t('app', 'Update Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Page'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'content_page_id' => $model->content_page_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="contentpage-update">
    <div class="card">
        <div class="card-header">
          <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
            'model' => $model,
            'Contentpagedetail' => $Contentpagedetail,
            ]) ?>
        </div>
    </div>
</div>
