<?php
use yii\helpers\Html;
$this->title = 'Update Event';
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-update"><div class="card"><div class="card-header"><h5><?= Html::encode($this->title) ?></h5></div><div class="card-body"><?= $this->render('_form', ['model' => $model]) ?></div></div></div>
