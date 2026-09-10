<?php
use yii\helpers\Html;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view"><div class="card"><div class="card-header"><h5><?= Html::encode($model->title) ?></h5></div><div class="card-body">
    <?php if ($model->image): ?><img src="<?= Yii::$app->params['ImagePath'] . $model->image ?>" alt="Event image" style="max-width:420px;max-height:240px;margin-bottom:20px;"><?php endif; ?>
    <p><strong>Category:</strong> <?= Html::encode($model->category) ?></p>
    <p><strong>Date:</strong> <?= Html::encode($model->event_date) ?> <?= Html::encode($model->event_time) ?></p>
    <p><strong>Location:</strong> <?= Html::encode($model->location) ?></p>
    <p><?= nl2br(Html::encode($model->description)) ?></p>
    <?= Html::a('Update', ['update', 'id' => $model->event_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->event_id], ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Delete this event?']) ?>
</div></div></div>
