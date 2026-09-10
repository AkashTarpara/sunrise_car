<?php
use yii\helpers\Html;
$this->title = 'Create Event';
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create"><div class="card"><div class="card-header"><h5><?= Html::encode($this->title) ?></h5></div><div class="card-body"><?= $this->render('_form', ['model' => $model]) ?></div></div></div>
