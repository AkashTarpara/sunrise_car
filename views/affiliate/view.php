<?php

use app\models\Affiliate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Affiliate $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Affiliate Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$fileRow = function ($attribute) use ($model) {
    $path = $model->$attribute;
    if (empty($path)) {
        return ['attribute' => $attribute, 'value' => '—'];
    }

    $url = Url::base(true) . '/' . $path;
    $isPdf = strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';

    return [
        'attribute' => $attribute,
        'format' => 'raw',
        'value' => $isPdf
            ? Html::a('<i class="fa fa-file-pdf-o"></i> View PDF', $url, ['target' => '_blank', 'rel' => 'noopener'])
            : Html::a(Html::img($url, ['style' => 'max-width:200px;max-height:140px;border:1px solid #ddd;padding:4px;']), $url, ['target' => '_blank', 'rel' => 'noopener']),
    ];
};
?>
<div class="affiliate-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <div class="float-end">
                <?= Html::beginForm(['update-status', 'id' => $model->affiliate_id], 'post', ['class' => 'd-inline-flex', 'style' => 'gap:8px;']) ?>
                    <?= Html::dropDownList('status', $model->status, Affiliate::statusOptions(), ['class' => 'form-select form-select-sm']) ?>
                    <?= Html::textInput('admin_notes', $model->admin_notes, ['class' => 'form-control form-control-sm', 'placeholder' => 'Admin notes']) ?>
                    <?= Html::submitButton('Update Status', ['class' => 'btn btn-sm btn-primary']) ?>
                <?= Html::endForm() ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->affiliate_id], [
                    'class' => 'btn btn-sm btn-danger ms-2',
                    'data-confirm' => 'Are you sure you want to delete this affiliate application?',
                    'data-method' => 'POST',
                ]) ?>
            </div>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'affiliate_id',
                        'full_name',
                        'email:email',
                        'phone',
                        'model_year',
                        'make_model',
                        ['attribute' => 'exterior_finish', 'value' => $model->optionLabel('exterior_finish')],
                        ['attribute' => 'interior_upholstery', 'value' => $model->optionLabel('interior_upholstery')],
                        ['attribute' => 'vehicle_class', 'value' => $model->optionLabel('vehicle_class')],
                        'tlc_base_affiliation',
                        $fileRow('photo_front'),
                        $fileRow('photo_cabin'),
                        $fileRow('photo_rear_plate'),
                        $fileRow('photo_driver_headshot'),
                        $fileRow('doc_dmv_license'),
                        $fileRow('doc_dmv_license_back'),
                        $fileRow('doc_tlc_license'),
                        $fileRow('doc_tlc_inspection'),
                        $fileRow('doc_dmv_inspection'),
                        $fileRow('doc_livery_registration'),
                        $fileRow('doc_commercial_insurance'),
                        $fileRow('doc_liability_coi'),
                        'legal_business_name',
                        'tax_id',
                        ['attribute' => 'payout_method', 'value' => $model->optionLabel('payout_method')],
                        'routing_number',
                        'account_number',
                        $fileRow('doc_w9'),
                        $fileRow('doc_voided_check'),
                        ['attribute' => 'status', 'value' => $model->getStatusLabel()],
                        'admin_notes:ntext',
                        ['attribute' => 'created_at', 'value' => $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : ''],
                        ['attribute' => 'updated_at', 'value' => $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : ''],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
