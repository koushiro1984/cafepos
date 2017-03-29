<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontendpos\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'lineicons/style.css',
        'js/gritter/css/jquery.gritter.css',
        'css/style.css',
        'css/style-responsive.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery.dcjqaccordion.2.7.js',
        'js/jquery.nicescroll.js',
        'js/gritter/js/jquery.gritter.js',
        'js/gritter-conf.js',
        'js/common-scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\AppAsset',
    ];
}
