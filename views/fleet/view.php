<?php

use yii\helpers\Html;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fleet', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fleet-view">
    <div class="card">
        <div class="card-header"><h5><?= Html::encode($model->name) ?></h5></div>
        <div class="card-body">
            <?php if ($model->fleetImages): ?>
                <div class="row mb-3">
                    <?php foreach ($model->fleetImages as $fleetImage): ?>
                        <div class="col-md-3 mb-3">
                            <img src="<?= Yii::$app->params['ImagePath'] . $fleetImage->image ?>" alt="Fleet image" style="width:100%;height:160px;object-fit:cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <p><strong>Label:</strong> <?= Html::encode($model->label) ?></p>
            <p><strong>Name:</strong> <?= Html::encode($model->name) ?></p>
            <p><strong>Passenger:</strong> <?= Html::encode($model->passenger) ?></p>
            <p><strong>Laggage:</strong> <?= Html::encode($model->laggage) ?></p>
            <p><strong>Type:</strong> <?= Html::encode($model->type) ?></p>
            <p><strong>Status:</strong> <?= Html::encode($model->status) ?></p>
            <p><?= nl2br(Html::encode($model->description)) ?></p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-method' => 'post',
                'data-confirm' => 'Delete this fleet?',
            ]) ?>
        </div>
    </div>
</div>
