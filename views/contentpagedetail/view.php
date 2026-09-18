<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Contentpagedetail $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contentpagedetails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contentpagedetail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'content_page_detail_id' => $model->content_page_detail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'content_page_detail_id' => $model->content_page_detail_id], [
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
            'content_page_detail_id',
            'content_page_id',
            'type',
            'section_title',
            'title:ntext',
            'sub_title',
            'image',
            'button_1_title',
            'button_1_url:url',
            'button_2_title',
            'button_2_url:url',
            'logo',
            'video',
            'display_order',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
