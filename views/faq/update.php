<?php
use yii\helpers\Html;
$this->title = 'Update ' . $model->getTypeLabel();
$this->params['breadcrumbs'][] = ['label' => 'FAQ & Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-update"><div class="card"><div class="card-header"><h5><?= Html::encode($this->title) ?></h5></div><div class="card-body"><?= $this->render('_form', ['model' => $model]) ?></div></div></div>
