<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

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
        'css/site.css',
        'http://fonts.googleapis.com/css?family=Economica%7COld+Standard+TT:400,400italic,700',
        'css/animate.css',
        'css/foodster.css',
        'css/custom.css',
    ];
    public $js = [
        'js/plugins/lettering.js',
        'js/plugins/textillate.js',
        'js/plugins/jquery.easing.1.3.min.js',
        'js/plugins/wow.min.js',
        'js/foodster.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\AppAsset',
    ];
}
