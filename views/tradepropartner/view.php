<?php

use app\models\Tradepropartner;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tradepropartner $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trade Pro Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$statusLabels = [
    Tradepropartner::STATUS_PENDING => 'Pending',
    Tradepropartner::STATUS_APPROVED => 'Approved',
    Tradepropartner::STATUS_REJECTED => 'Rejected',
];
?>
<div class="tradepropartner-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <div class="float-end">
                <?= Html::beginForm(['update-status', 'id' => $model->id], 'post', ['class' => 'd-inline-flex', 'style' => 'gap:8px;']) ?>
                    <?= Html::dropDownList('status', $model->status, $statusLabels, ['class' => 'form-select form-select-sm']) ?>
                    <?= Html::submitButton('Update Status', ['class' => 'btn btn-sm btn-primary']) ?>
                <?= Html::endForm() ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-danger ms-2',
                    'data-confirm' => 'Are you sure you want to delete this Trade Pro application?',
                    'data-method' => 'POST',
                ]) ?>
            </div>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'full_name',
                        'email:email',
                        'phone',
                        'job_title',
                        'years_experience',
                        [
                            'label' => 'Area of Interest',
                            'value' => $model->interestSummary(),
                        ],
                        'project_type',
                        'project_location',
                        'project_start_date',
                        'project_size',
                        'project_details:ntext',
                        [
                            'attribute' => 'terms_accepted',
                            'value' => $model->terms_accepted ? 'Yes' : 'No',
                        ],
                        [
                            'label' => 'Electronic Signature',
                            'format' => 'raw',
                            'value' => $model->signature_image
                                ? Html::img(Url::base(true) . '/' . $model->signature_image, ['style' => 'max-width:250px;max-height:150px;border:1px solid #ddd;padding:4px;'])
                                : '—',
                        ],
                        'printed_name',
                        'signature_title',
                        'signed_date',
                        [
                            'attribute' => 'status',
                            'value' => $statusLabels[$model->status] ?? '',
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '',
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
