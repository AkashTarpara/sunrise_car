<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Contactus $model */

$this->title = Yii::t('app', 'Update Contactus: {name}', [
    'name' => $model->contact_us_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contactuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->contact_us_id, 'url' => ['view', 'contact_us_id' => $model->contact_us_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="contactus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
