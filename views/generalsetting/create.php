<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Generalsetting */

$this->title = Yii::t('app', 'Create Generalsetting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generalsettings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generalsetting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
