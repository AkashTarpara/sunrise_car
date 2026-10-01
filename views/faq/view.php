<?php
use yii\helpers\Html;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'FAQ & Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-view"><div class="card"><div class="card-header"><h5><?= Html::encode($model->title) ?></h5></div><div class="card-body">
    <p><strong>Type:</strong> <?= Html::encode($model->getTypeLabel()) ?></p>
    <p><strong>Display Order:</strong> <?= Html::encode($model->display_order) ?></p>
    <p><strong>Status:</strong> <?= Html::encode($model->status) ?></p>
    <div class="mb-4"><?= $model->description ?></div>
    <?= Html::a('Update', ['update', 'id' => $model->faq_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->faq_id], ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Delete this item?']) ?>
</div></div></div>
