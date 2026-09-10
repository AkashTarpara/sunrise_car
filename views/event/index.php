<?php
use yii\helpers\Html;
use yii\grid\GridView;
$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index"><div class="card"><div class="card-header"><h5 class="float-start"><?= Html::encode($this->title) ?></h5><?= Html::a('Add Event', ['create'], ['class' => 'btn btn-sm btn-shadow btn-success float-end px-3']) ?></div><div class="card-body table-border-style"><div class="table-responsive">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'emptyText' => 'No events found.',
    'columns' => [
        'event_id',
        'title',
        'category',
        'event_date',
        'event_time',
        'venue',
        'location',
        'status',
        'created_at',
        ['class' => 'yii\\grid\\ActionColumn', 'template' => '{view} {update} {delete}'],
    ],
]); ?>
</div></div></div></div>
