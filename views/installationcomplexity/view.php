<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Installationcomplexity $model */

$this->title = $model->installation_complexity_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Installationcomplexities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="installationcomplexity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'installation_complexity_id' => $model->installation_complexity_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'installation_complexity_id' => $model->installation_complexity_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'installation_complexity_id',
            'type',
            'price',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
