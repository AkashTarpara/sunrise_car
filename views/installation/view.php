<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Installation $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Installations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="installation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'installation_id' => $model->installation_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'installation_id' => $model->installation_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'installation_id',
            'label',
            'title',
            'sub_title',
            'video',
            'before_image',
            'after_image',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
