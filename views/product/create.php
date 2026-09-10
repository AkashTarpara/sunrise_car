<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = Yii::t('app', 'Create Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">
    <div class="card">
        <div class="card-header">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'modelsProductspecifications' => $modelsProductspecifications,
                'modelsProductspecificationsdetail' => $modelsProductspecificationsdetail,
                'Productimage' => $Productimage,
            ]) ?>
        </div>
    </div>
</div>