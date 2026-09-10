<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Contactus $model */

$this->title = $model->contact_us_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contact Us'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contactus-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'contact_us_id',
                        'first_name',
                        'last_name',
                        'email:email',
                        'number',
                        'address',
                        'message:ntext',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>