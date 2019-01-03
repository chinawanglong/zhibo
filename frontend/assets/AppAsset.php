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
        /*'css/global.css',*/
        'css/style.css',
        'css/jquery.mCustomScrollbar.css'
    ];
    public $js = [
        'js/jquery-1.9.1.min.js',
        'js/jquery.cookie.js',
        'js/jquery.mCustomScrollbar.js',
        'js/jquery.mousewheel.min.js',
        'js/layer/layer.js',
        'js/room.base.js',
        'js/room.init.js',
    ];
    public $cssOptions = ['position' => \yii\web\View::POS_HEAD];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
