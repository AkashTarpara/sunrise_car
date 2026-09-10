<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Productcategory $model */

$this->title = Yii::t('app', 'Create Product Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Productcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productcategory-create">
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