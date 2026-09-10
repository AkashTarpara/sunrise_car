<?php

use yii\helpers\Html;
use app\models\Appuserissignup;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
// echo '<pre>';
// print_r(implode(",", $formattedWeek));
// exit;
?>
<?= Html::jsFile('@web/web/mytheme/js/plugins/apexcharts.min.js'); ?>
<!-- <?= Html::jsFile('@web/web/mytheme/js/pages/w-chart.js'); ?> -->
<!-- <?= Html::jsFile('@web/web/mytheme/js/pages/dashboard-default.js'); ?> -->
<div class="row">
  <!-- [ sample-page ] start -->
  <?= $this->title; ?>
</div>