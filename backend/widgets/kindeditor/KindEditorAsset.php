<?php

/**
 * Description of KindEditorAsset
 *  KindEditor资源组织文件
 * @author Qinn Pan <Pan JingKui, pjkui@qq.com>
 * @link http://www.pjkui.com
 * @QQ 714428042
 * @date 2015-3-4

 */
namespace backend\widgets\kindeditor;
use yii\web\AssetBundle;
class KindEditorAsset extends AssetBundle {
    /**文件目录**/
    public $basePath = '@webroot/lib/kindeditor';
    /**访问目录**/
    public $baseUrl = '@web/lib/kindeditor';
    //put your code here
    public $js=[
        'kindeditor.js',
        'lang/zh_CN.js',
       // 'kindeditor.js'
    ];
    public $css=[
        'themes/default/default.css'
    ];
    
    public $jsOptions=[
        'position' => \yii\web\View::POS_HEAD,
        'charset'=>'utf8',
    ];
}

?>
