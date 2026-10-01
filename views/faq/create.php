<?php
use yii\helpers\Html;
$this->title = 'Add FAQ / Terms';
$this->params['breadcrumbs'][] = ['label' => 'FAQ & Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create"><div class="card"><div class="card-header"><h5><?= Html::encode($this->title) ?></h5></div><div class="card-body"><?= $this->render('_form', ['model' => $model]) ?></div></div></div>
