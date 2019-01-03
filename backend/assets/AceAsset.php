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
class AceAsset extends AssetBundle
{
    /**文件目录**/
    public $basePath = '@webroot';
    /**访问目录**/
    public $baseUrl = '@web';
    /***如果你指定这个$sourcePath路径,那么上面两个会被Yii生成的自动赋予，但是如果你显示设置sourcePath那么就应该和设置的basePath和baseUrl对应***/
    //public $sourcePath="@webroot";

    public $css = [
        'bower_components/bootstrap-switch/dist/css/bootstrap2/bootstrap-switch.css',
        'bower_components/aceadmin/assets/css/font-awesome.css',
        'bower_components/aceadmin/assets/css/ace-fonts.css',
        'bower_components/aceadmin/assets/css/ace.css',
        'css/mikeadmin.css'
    ];
    public $js = [
        'bower_components/bootstrap-switch/dist/js/bootstrap-switch.js',
        'bower_components/aceadmin/assets/js/ace-extra.js',
        'bower_components/aceadmin/assets/js/jquery.mobile.custom.js',
        'bower_components/aceadmin/assets/js/ace/elements.scroller.js',
        'bower_components/aceadmin/assets/js/ace/ace.js',
        'bower_components/aceadmin/assets/js/ace/ace.sidebar.js',
        'bower_components/aceadmin/assets/js/ace/ace.sidebar-scroll-1.js',
        'bower_components/aceadmin/assets/js/ace/ace.submenu-hover.js',
        'js/mikeadmin.js',
    ];
    public $cssOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'backend\assets\AppAsset'
    ];
}