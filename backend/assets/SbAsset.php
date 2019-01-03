<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-21
 * Time: 上午11:35
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\bootstrap\BootstrapAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SbAsset extends AssetBundle
{
    /**文件目录**/
    public $basePath = '@webroot';
    /**访问目录**/
    public $baseUrl = '@web';
    /***如果你指定这个$sourcePath路径,那么上面两个会被Yii生成的自动赋予，但是如果你显示设置sourcePath那么就应该和设置的basePath和baseUrl对应***/
    //public $sourcePath="@webroot";

    public $css = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
        'bower_components/metisMenu/dist/metisMenu.min.css',
        'bower_components/sbadmin/dist/css/sb-admin-2.css',
        'bower_components/font-awesome/css/font-awesome.min.css',
        'bower_components/bootstrap-switch/dist/css/bootstrap2/bootstrap-switch.css',
    ];
    public $js = [
        'bower_components/metisMenu/dist/metisMenu.min.js',
        'bower_components/bootstrap-switch/dist/js/bootstrap-switch.js',
        'bower_components/sbadmin/dist/js/sb-admin-2.js',
    ];
    public $cssOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'backend\assets\AppAsset'
    ];
}