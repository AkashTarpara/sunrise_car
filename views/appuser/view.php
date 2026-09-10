<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Appuser $model */

$this->title = $model->first_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="appuser-view">
    <div class="card">
        <div class="card-header">
            <h5 class="float-left"><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'appuser_id',
                        'first_name',
                        'last_name',
                        'full_name',
                        //'phone_number',
                        //'postal_code',
                        //'phone_verify',
                        //'otp',
                        'email:email',
                        'updated_at',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>