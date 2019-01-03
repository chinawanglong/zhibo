<?php
use yii\helpers\Html;
?>
<?= Html::cssFile('@web/lib/flexslider/flexslider.css'); ?>
<?= Html::jsFile('@web/lib/flexslider/jquery.flexslider-min.js'); ?>

<style>
    .mCSB_inside > .mCSB_container{
        margin-right: 15px;
    }
    .main-content .main .main-top .chat-video .right-area .nav-area .tab-contents{
        overflow: hidden;
    }
    .main-content .main .main-top .chat-video .right-area .nav-area .tab-contents .tab-content{
        padding:0px;
    }

    #n {
        margin: 10px auto;
        width: 920px;
        border: 1px solid #CCC;
        font-size: 12px;
        line-height: 30px;
    }

    #n a {
        padding: 0 4px;
        color: #333
    }

    /* flexslider */
    .tab-content.flexslider {
        position: absolute;
        width: 100%;
        top:0px;
        bottom:0px;
        left:0px;
        right:0px;
        overflow: hidden !important;
        background: url(/images/lunbo/loading.gif) 50% no-repeat;
        margin: 0px;
        border:none;
    }

    .slides {
        position: relative;
        z-index: 1;
        height: 100%;
        width: 100%;
    }

    .slides li {
        height: 100%;
        background-size: 100% 100% !important;
    }

    .slides li a {
        display: block;
        width: 100%;
        height: 100%;
        text-align: left;
        text-indent: -9999px;
    }

    .flex-control-nav {
        position: absolute;
        bottom: 10px;
        z-index: 2;
        width: 100%;
        text-align: center;
    }

    .flex-control-nav li {
        display: inline-block;
        width: 14px;
        height: 14px;
        margin: 0 5px;
        zoom: 1;
    }

    .flex-control-nav a {
        display: inline-block;
        width: 14px;
        height: 14px;
        line-height: 40px;
        overflow: hidden;
        background: url(/images/lunbo/dot.png) right 0 no-repeat;
        cursor: pointer;
    }

    .flex-control-nav .flex-active {
        background-position: 0 0;
    }

    .flex-direction-nav {
        position: absolute;
        z-index: 3;
        left: 0;
        width: 100%;
        top: 45%;
    }

    .flex-direction-nav li a {
        display: block;
        width: 50px;
        height: 50px;
        overflow: hidden;
        cursor: pointer;
        position: absolute;
    }

    .flex-direction-nav li a.flex-prev {
        left: 30px;
        background: url(/images/l.png) center center no-repeat;
        /*display: none;*/
    }

    .flex-direction-nav li a.flex-next {
        right: 30px;
        background: url(/images/r.png) center center no-repeat;
        /*display: none;*/
    }
</style>
<script>

    //$(".slides a").each(function(){
    //$(this).fancybox();
    //});

    $(function () {
        var $tab_contents=$(".main-content .main .main-top .chat-video .right-area .nav-area .tab-contents");
        $('.tab-content.flexslider').flexslider({
            directionNav: true,
            pauseOnAction: false
        });
    });
    function flexprev() {
        $(".flex-prev").show();
        $(".flex-next").show();
    }
    function flexnext() {
        setTimeout(function () {
            $(".flex-prev").hide();
            $(".flex-next").hide();
        }, 2000);
    }
</script>