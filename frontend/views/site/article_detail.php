<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 9:39
 */
?>
<style type="text/css">
    .article_detail .title{
        text-align: center;
        font: 24px Microsoft YaHei;
        letter-spacing: 2px;
        color: #282828;
    }
    .article_detail .info{
        text-align: center;
        font: 14px Tahoma,Arial;
        color: #999;
        margin-right: 10px;
        line-height: 24px;
    }
    .article_detail .content{
        padding: 0 10px;
        margin-top: 20px;
        font: 14px Microsoft YaHei;
        color: #555;
        line-height: 24px;
    }
</style>

<div class="article_detail">
    <h2 class="title">
        <?=$article->title;?>
    </h2>
    <div class="info">
         <?=date("Y-m-d H:i:s",$article->created_at);?>
    </div>
    <div class="content">
        <?=$article->content;?>
    </div>
</div>
