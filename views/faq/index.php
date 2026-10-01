<?php

use app\models\Faq;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\FaqSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'FAQ & Terms');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-index">
    <div class="card">
        <div class="card-header">
            <h5 class="float-start"><?= Html::encode($this->title) ?></h5>
            <?= Html::a(Yii::t('app', 'Add New'), ['create', 'type' => $searchModel->type], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'emptyText' => 'No record(s) found.',
                    'summary' => 'Showing {begin} - {end} of {totalCount} Record',
                    'layout' => "{items}\n<div align='center'>{summary}<div style='float: right;margin: 15px;'>{pager}</div></div>",
                    'tableOptions' => [
                        'class' => 'table',
                        'style' => 'text-align: center;',
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'type',
                            'filter' => Faq::typeList(),
                            'value' => function ($model) {
                                return $model->getTypeLabel();
                            },
                        ],
                        'title',
                        [
                            'attribute' => 'display_order',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width:140px;'],
                            'value' => function ($model) {
                                return Html::input('number', 'display_order', $model->display_order, [
                                    'class' => 'form-control form-control-sm faq-display-order',
                                    'min' => 0,
                                    'style' => 'width:90px;margin:0 auto;',
                                    'data-url' => Url::to(['updateorder', 'id' => $model->faq_id]),
                                    'data-old' => $model->display_order,
                                ]);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'filter' => ['Active' => 'Active', 'Inactive' => 'Inactive'],
                        ],
                        'created_at',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
                            'headerOptions' => ['style' => 'min-width:100px;'],
                            'header' => 'Action',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" title=" Update "><i class="far fa-edit"></i></button>', $url);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" title=" Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>', $url, [
                                        'data-confirm' => 'Are you sure you want to delete this item?',
                                        'data-method' => 'POST',
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs(<<<JS
$(document).on('change', '.faq-display-order', function () {
    var input = $(this);
    var value = $.trim(input.val());
    if (value === '' || parseInt(value, 10) < 0) {
        input.val(input.data('old'));
        toastr.error('Please enter a valid display order.');
        return;
    }
    var data = { display_order: value };
    data[yii.getCsrfParam()] = yii.getCsrfToken();
    input.prop('disabled', true);
    $.post(input.data('url'), data, function (res) {
        if (res && res.status == 1) {
            input.data('old', value);
            toastr.success(res.message);
        } else {
            input.val(input.data('old'));
            toastr.error(res && res.message ? res.message : 'Unable to update display order.');
        }
    }, 'json').fail(function () {
        input.val(input.data('old'));
        toastr.error('Unable to update display order.');
    }).always(function () {
        input.prop('disabled', false);
    });
});
JS);
?>
