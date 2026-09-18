<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class AppAsset extends AssetBundle
{
    //public $sourcePath = '@web';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'web/mytheme/fonts/inter/inter.css',
        'web/mytheme/fonts/tabler-icons.min.css',
        'web/mytheme/fonts/feather.css',
        'web/mytheme/fonts/fontawesome.css',
        'web/mytheme/fonts/material.css',
        'web/mytheme/css/style.css',
        'web/mytheme/css/style-preset.css',
        'web/mytheme/css/plugins/bootstrap-switch-button.min.css',
        'web/mytheme/customcss/customx.css',
        'web/mytheme/customcss/select2/css/select2.min.css',
        //'web/mytheme/css/plugins/dataTables.bootstrap5.min.css',
        //'web/mytheme/css/plugins/responsive.bootstrap5.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.css',

    ];
    public $js = [
        //'web/mytheme/js/plugins/apexcharts.min.js',
        //'web/mytheme/js/pages/dashboard-default.js',
        'web/mytheme/js/plugins/popper.min.js',
        'web/mytheme/js/plugins/simplebar.min.js',
        'web/mytheme/js/plugins/bootstrap.min.js',
        'web/mytheme/js/fonts/custom-font.js',
        'web/mytheme/js/pcoded.js',
        'web/mytheme/js/plugins/feather.min.js',
        'web/mytheme/js/plugins/bootstrap-switch-button.min.js',
        'web/mytheme/customcss/select2/js/select2.full.min.js',
        'web/mytheme/js/plugins/choices.min.js',
        //'web/mytheme/js/plugins/jquery.dataTables.min.js',
        //'web/mytheme/js/plugins/dataTables.bootstrap5.min.js',
        //'web/mytheme/js/plugins/dataTables.responsive.min.js',
        //'web/mytheme/js/plugins/responsive.bootstrap5.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.js',

    ];

    public $cssOptions = [
        'id' => 'main-style-link',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap4\BootstrapAsset',
    ];
}
