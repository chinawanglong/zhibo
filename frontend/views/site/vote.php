<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/24
 * Time: 10:00
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Votetype;

$votes = Votetype::find()/*->where(['status'=>1])*/
->all();
?>
<style type="text/css">
    .toupiao {
        padding: 2px 0;
        height: 42px;
        background: none;
    }

    .toupiao a {
        float: left;
        width: 45px;
        padding-right: 6px;
        height: 42px;
        text-align: right;
        margin-right: 1px;
        display: block;
        color: #ff0;
        line-height: 22px;
        background: url(/images/tp.png) left no-repeat;
    }

    .toupiao a em {
        display: block;
        color: #fff;
        line-height: 14px;
        font-style: normal;
    }

    .toupiao a.t_up {
        margin-left: 2px;
    }

    .t_up {
        background-position: 0 0 !important;
    }

    .t_leve {
        background-position: -64px 0 !important;
    }

    .t_down {
        background-position: -128px 0 !important;
    }

    /*#w_4 .t_up{background-position:0 -42px;}
    #w_4 .t_leve{background-position:-64px -42px;}
    #w_4 .t_down{background-position:-128px -42px;}
    #w_5 .t_up{background-position:0 -84px;}
    #w_5 .t_leve{background-position:-64px -84px;}
    #w_5 .t_down{background-position:-128px -84px;}
    */
    .mt {
        height: 30px;
        clear: both;
    }

    .mt li {
        margin-top: 5px;
        font-size: 14px;
        width: 62px;
        height: 25px;
        text-align: center;
        line-height: 25px;
        color: #fff;
        background: #FCA323;
        float: left;
        cursor: pointer;
        border-top: 1px solid #ccc;
    }

    .mt .curr {
        margin-top: 0px;
        height:30px;
        line-height: 30px;
        background: #ffffff;
        color: #e4393c;
        box-shadow: 2px 0 2px #DDDDDD;
    }
</style>
<script>
    function vote_g(o) {
        return document.getElementById(o);
    }
    function vote_N(n) {
        $(".nitem").removeClass("curr").addClass("line");
        $(".witem").removeClass("show").addClass("hide");
        $("#n_" + n).removeClass("line").addClass("curr");
        $("#w_" + n).removeClass("hide").addClass("show");
        show_vote(n);
    }
    function more_vote(value, vote_id, info) {
        $.post('/site/to-vote', {vid: vote_id, v: value, info: info}, function (data) {
            if (data.status) {
                layer.msg('投票成功');
                show_vote(vote_id);
            } else {
                var vote_name = $('#n_' + vote_id).text();
                layer.msg(data.msg, {shift: 6});
            }
        }, 'json');
    }
    function show_vote(vid) {
        $.post('/site/show-vote', {rid: 1, vid: vid}, function (data) {
            if (data.status) {
                var sum = data.v1 + data.v2 + data.v3;

                var v1 = sum ? Math.round(data.v1 / sum * 10000) / 100 : 0;
                var v2 = sum ? Math.round(data.v2 / sum * 10000) / 100 : 0;
                var v3 = sum ? Math.round(data.v3 / sum * 10000) / 100 : 0;
                $('#w_' + vid + " .t_up em").text(v1.toFixed(0) + "%");
                $('#w_' + vid + " .t_leve em").text(v2.toFixed(0) + "%");
                $('#w_' + vid + " .t_down em").text(v3.toFixed(0) + "%");
            }
        }, 'json');
    }
</script>

<div class="mt">
    <ul>
        <?php
        if (!empty($votes)) {
            foreach ($votes as $i => $item) {
                $id = $item->id;
                $name = $item->name;
                if ($i == 0) {
                    echo "<script>vote_N({$id});</script>";
                }
                echo "<li id='n_{$id}' class='" . ($i == 0 ? "curr" : "line") . " nitem' onclick='vote_N({$id});'>{$name}</li>";
            }
        }
        ?>
    </ul>
</div>
<div style="clear:both"></div>
<div class="toupiao">
    <?php
    if (!empty($votes)) {
        foreach ($votes as $i => $item) {
            $id = $item->id;
            $name = $item->name;
            $optiondata = strval($item->options);
            $optiondata_array = explode("|", $optiondata);
            $item_str = "<div id='w_{$id}' class='" . ($i == 0 ? "active" : "hide") . " witem'>";
            if (!empty($optiondata_array[0])) {
                $item_str .= "<a class='t_up' href='javascript:void(0)' onclick='javascript:more_vote(0,{$id},\"{$optiondata_array[0]}\")'>{$optiondata_array[0]}<em></em></a>";
            }
            if (!empty($optiondata_array[1])) {
                $item_str .= "<a class='t_leve' href='javascript:void(0)' onclick='javascript:more_vote(1,{$id},\"{$optiondata_array[1]}\")'>{$optiondata_array[1]}<em></em></a>";
            }
            if (!empty($optiondata_array[2])) {
                $item_str .= "<a class='t_down' href='javascript:void(0)' onclick='javascript:more_vote(2,{$id},\"{$optiondata_array[2]}\")'>{$optiondata_array[2]}<em></em></a>";
            }
            $item_str .= "</div>";
            echo $item_str;
        }
    }
    ?>
</div>

