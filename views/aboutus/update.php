<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Aboutus $model */

$this->title = Yii::t('app', 'Update About Us');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'About Us'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'about_us_id' => $model->about_us_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="aboutus-update">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>