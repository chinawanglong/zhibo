<?php
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="keyword" content=""/>
    <title>讲师榜单</title>
    <script src="/js/jquery-1.9.1.min.js"></script>
    <link href="/css/bangdan.css" rel="stylesheet" type="text/css" />
    <script src="/lib/layer/layer.js"></script>
</head>
<body>
<div class="grap">
    <h1>讲师榜单</h1>
    <div class="vote">
        <ul id='vote'>
            <?php
                if(!empty($teachers)){
                    foreach($teachers as $item){
            ?>
                        <li id="li_<?=$item['id'];?>">
                            <a  href="javascript:void(0)" onclick="do_vote(this)" item="<?=$item['id'];?>"></a>
                            <p class="v_name"><i class="percent"><?=$item['percent'];?></i><?=$item['name'];?></p>
                            <p class="percent_container"><span class="percent_line" style="width:<?=$item['percent'];?>"></span></p>
                            <p class="v_text">
                                <span>总票数：<i class="amount"><?=$item['zan_count'];?></i></span>
                            </p>
                        </li>
            <?php
                    }
                }
            ?>
        </ul>
    </div>
</div>
<div style="display:none">
</div>
</body>

<script type="text/javascript">
    var uid="";
    var temp_name="";
    function do_vote(a){
        var item = parseInt($(a).attr('item'));
        $.ajax({
            type:"POST",
            url:'/site/do-tvote',
            data:{tid:item},
            dataType:'json',
            success: function(data){
                if(data.error == 0){
                    layer.msg('投票成功');
                    get_vote_data();
                }else{
                    layer.msg(data.msg);
                }
            }
        });
    }


    function get_vote_data(){
        $.ajax({
            type:"GET",
            url:"/site/get-tvote-data",
            dataType:'json',
            success: function(result){
                if(result.error){
                    return;
                }
                for(var i in result['data']){
                    var item=result['data'][i];
                    var key=item['id']
                    $('#li_'+key).find('.percent').text(item['percent']);
                    $('#li_'+key).find('.percent_line').css("width",item['percent']);
                    $('#li_'+key).find('.amount').text(item['zan_count']);
                }
            }
        });
    }
</script>
</html>
