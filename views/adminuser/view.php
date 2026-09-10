<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Adminuser */

$this->title = $model->admin_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Adminusers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->admin_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->admin_id], [
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
            'admin_id',
            'first_name',
            'last_name',
            'user_name',
            'email:email',
            'password',
            'profile_pic',
            'contact_no',
            'roles',
            'is_status',
            'time',
            'auth_key',
            'password_reset_token',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
