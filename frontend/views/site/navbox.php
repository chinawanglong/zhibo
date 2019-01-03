<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-06-26
 * Time: 11:18
 */
 ?>
<div class="navbox">
    <h3>赛事介绍</h3>
    <ul class="list">
         <li class="hqyg">
             <a class="nav-item r" target="_iframe" url="/site/teacher-rank" pwidth="850" pheight="600"><span>讲师榜</span></a>
         </li>
         <li class="hxnc">
             <a target="_iframe" url="/site/get-zhiboconfig?alias=about_course" pwidth="800" pheight="460">
                <span>课程安排</span>
             </a>
         </li>
        <li class="jsjs">
            <a onclick="random_customer();">
                <span>VIP申请</span>
            </a>
        </li>
        <li class="jymj">
            <a target="_iframe" url="/site/article-detail?id=32" pwidth="800" pheight="600">
                <span>赛事奖励</span>
            </a>
        </li>
    </ul>
</div>
<style type="text/css">
    .main-content .Y_LeftList .navbox h3 {
        width: 100%;
        color: #fff;
        font-weight: bold;
        font-size: 12px;
        position: relative;
        line-height: 36px;
        text-indent: 1em;
        text-align: center;
        overflow-y: hidden;
        background: url("../images/biaoti.png") no-repeat;
        background-size: 100% 100%;
    }

    .main-content .Y_LeftList .navbox .list{
        padding:10px;
        overflow: hidden;
        background:rgba(0,0,0,0.3);filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#4F000000,endcolorstr=#4F000000);
        padding-bottom:20px;
    }
    .main-content .Y_LeftList .navbox .list li{
        float:left;
        width: 83px;
        height: 83px;
        margin:15px 10px 10px 10px;
        text-align: center;
        border-radius: 10px;
        background-color: rgba(0,0,0,0.3);
        filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#4F000000,endcolorstr=#4F000000);
        color:#ffd575;
        background-position: 50% 30%;
        background-repeat:no-repeat;
    }
    .main-content .Y_LeftList .navbox .list li:hover{
        background-color:#ffd575;
        color:#000;
    }
    .main-content .Y_LeftList .navbox .list li a{
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
        position:relative;
    }
    .main-content .Y_LeftList .navbox .list li a span{
        width:100%;
        position: absolute;
        left:0px;bottom:10px;
        font-size:12px;
    }
    .main-content .Y_LeftList .navbox .list li.jsjs{
        background-image: url("/images/tu1.png");
    }
    .main-content .Y_LeftList .navbox .list li:hover.jsjs{
        background-image: url("/images/tu1_1.png");
    }
    .main-content .Y_LeftList .navbox .list li.jymj{
        background-image: url("/images/tu2.png");
    }
    .main-content .Y_LeftList .navbox .list li:hover.jymj{
        background-image: url("/images/tu2_2.png");
    }
    .main-content .Y_LeftList .navbox .list li.hqyg{
        background-image: url("/images/tu3.png");
    }
    .main-content .Y_LeftList .navbox .list li:hover.hqyg{
        background-image: url("/images/tu3_3.png");
    }
    .main-content .Y_LeftList .navbox .list li.hxnc{
        background-image: url("/images/tu4.png");
    }
    .main-content .Y_LeftList .navbox .list li:hover.hxnc{
        background-image: url("/images/tu4_4.png");
    }
</style>
