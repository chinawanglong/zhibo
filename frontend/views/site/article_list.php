<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 18:02
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?=Html::cssFile("@web/css/bootstrap.css");?>
<style type="text/css">
    .list_area .list-title{
        height: 42px;line-height: 42px;border-bottom: 1px solid #eee;font-size: 14px;color: #333;overflow: hidden;text-overflow: ellipsis;white-space:nowrap;
        background-color: #F8F8F8;border-radius: 2px 2px 0 0;padding: 0 0 0 20px;font-weight: bold;
    }
    .list_area .list{
        width:90%;
        margin:0 auto;
    }
    .list_area .list ul._list{
        list-style: none;
        padding:0px;
        margin:0px;
    }
    .list_area .list ul._list li {
        height: 38px;
        font-size: 15px;
        line-height: 38px;
        border-bottom: #ccc 1px solid;
        font-family: '微软雅黑';
    }
    .list_area .list ul._list li a {
        display: block;
        float: left;
        color: #000;
        text-decoration: none;
        padding-left: 10px;
    }
    .list_area .list._list a{
        text-decoration: none;
    }
</style>
<div class="list_area">
    <div class="list-title"><?=$article_type->name;?></div>
    <div class="list">
        <ul class="_list">
        <?php
           foreach ($articles as $article){
               $title=$article->title;
               $url=Url::to(['site/article-detail','id'=>$article->id]);
        ?>
            <li><a href="<?=$url;?>"><?=$title;?></a></li>
        <?php
           };
        ?>
        </ul>
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
    <!---list_area-->
</div>
