<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"><link rel="icon" href="https://static.jianshukeji.com/highcharts/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://img.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
    <script src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/data.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/series-label.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/oldie.js"></script>
    <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
    <script src="https://img.hcharts.cn/highcharts/themes/dark-unica.js"></script>
    <link href="http://mall.huasuhui.com/admin/templates/default/css/skin_0.css" rel="stylesheet" type="text/css" id="cssfile2" />
    <style type="text/css">
        a{color:#007bc4/*#424242*/; text-decoration:none;}
        a:hover{text-decoration:underline}
        ol,ul{list-style:none}
        body{height:100%; font:12px/18px Tahoma, Helvetica, Arial, Verdana, "\5b8b\4f53", sans-serif; color:#51555C;}
        img{border:none}
        .demo{width:500px; margin:20px auto}
        .demo h4{height:32px; line-height:32px; font-size:14px}
        .demo h4 span{font-weight:500; font-size:12px}
        .demo p{line-height:28px;}
        input{width:200px; height:20px; line-height:20px; padding:2px; border:1px solid #d3d3d3}
        pre{padding:6px 0 0 0; color:#666; line-height:20px; background:#f7f7f7}

        .ui-timepicker-div .ui-widget-header { margin-bottom: 8px;}
        .ui-timepicker-div dl { text-align: left; }
        .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
        .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
        .ui-timepicker-div td { font-size: 90%; }
        .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
        .ui_tpicker_hour_label,.ui_tpicker_minute_label,.ui_tpicker_second_label,.ui_tpicker_millisec_label,.ui_tpicker_time_label{padding-left:20px}
    </style>

</head>
<body>
<form method="get"  name="formSearch" id="formSearch">
    <input type="hidden" name="" value="" />
    <div class="w100pre" style="width: 100%;">
        <table class="tb-type1 noborder search left">
            <tbody>
            <tr>
                <td>
                    <?php if($is_super_admin && !empty($zhibo)){ ?>

                        <select name="zhiboid"  class="querySelect">
                            <option >请选择直播室</option>
                            <?php foreach($zhibo as $val){?>
                                <option value="<?php echo $val['id'] ?>"  <?php echo (!empty($search_arr['zhiboid']) && $search_arr['zhiboid']==$val['id']) ?'selected':''; ?>> <?php echo $val['name'] ?></option>
                            <?php }?>
                        </select>
                    <?php }?>
                    <select name="search_type" id="search_type" class="querySelect">
                        <option value="day" <?php echo $search_arr['search_type']=='day'?'selected':''; ?>>按照天统计</option>
                        <option value="week" <?php echo $search_arr['search_type']=='week'?'selected':''; ?>>按照周统计</option>
                        <option value="month" <?php echo $search_arr['search_type']=='month'?'selected':''; ?>>按照月统计</option>
                        <option value="range" <?php echo $search_arr['search_type']=='range'?'selected':''; ?>>按照时间范围统计</option>
                    </select></td>
                <td id="searchtype_day" style="display:none;">
                    <input class="txt date" type="text" value="<?php echo @date('Y-m-d',$search_arr['day']['search_time']);?>" id="search_time" name="search_time">
                </td>
                <td id="searchtype_week" style="display:none;">
                    <select name="searchweek_year" class="querySelect">
                        <?php foreach ($year_arr as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php echo $search_arr['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
                        <?php } ?>
                    </select>
                    <select name="searchweek_month" class="querySelect">
                        <?php foreach ($month_arr as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php echo $search_arr['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>
                        <?php } ?>
                    </select>
                    <select name="searchweek_week" class="querySelect">
                        <?php foreach ($week_arr as $k=>$v){?>
                            <option value="<?php echo $v['key'];?>" <?php echo $search_arr['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td id="searchtype_month" style="display:none;">
                    <select name="searchmonth_year" class="querySelect">
                        <?php foreach ($year_arr as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php echo $search_arr['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
                        <?php } ?>
                    </select>
                    <select name="searchmonth_month" class="querySelect">
                        <?php foreach ($month_arr as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php echo $search_arr['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td id="searchtype_range" style=>
                    <input class="txt date" style="width:110px" type="text" value="<?php echo $search_arr['stime'];?>" id="stime" name="stime">-<input class="txt date"  style="width:110px" type="text" value="<?php echo $search_arr['etime'];?>" id="etime" name="etime">
                </td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search tooltip" >&nbsp;</a></td>
            </tr>

            </tbody>
        </table>
        <span class="right" style="margin:12px 0px 6px 4px;">

        </span>
    </div>
</form>
<div id="container" style="min-width:400px;height:400px"></div>
<script type="text/javascript" src="http://mall.huasuhui.com/data/resource/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="http://mall.huasuhui.com/data/resource/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript" src="http://mall.huasuhui.com/data/resource/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="http://mall.huasuhui.com/data/resource/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script>
    var chart = null;
    var data = (<?php echo $json;?>);
    chart = Highcharts.chart('container', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: '用户在线数量统计图（一分钟统计一次）'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                '鼠标拖动可以进行缩放' : '手势操作进行缩放'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {
                millisecond: '%H:%M:%S.%L',
                second: '%H:%M:%S',
                minute: '%H:%M',
                hour: '%H:%M',
                day: '%m-%d',
                week: '%m-%d',
                month: '%Y-%m',
                year: '%Y'
            }
        },
        tooltip: {
            dateTimeLabelFormats: {
                millisecond: '%H:%M:%S.%L',
                second: '%H:%M:%S',
                minute: '%H:%M',
                hour: '%H:%M',
                day: '%Y-%m-%d',
                week: '%m-%d',
                month: '%Y-%m',
                year: '%Y'
            }
        },
        yAxis: {
            title: {
                text: '在线量'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },
        series: [{
            type: 'area',
            name: '在线量',
            data: data
        }]
    });
    (function(b){"object"===typeof module&&module.exports?module.exports=b:b(Highcharts)})(function(b){(function(a){a.createElement("link",{href:"https://fonts.googleapis.com/css?family\x3dSignika:400,700",rel:"stylesheet",type:"text/css"},null,document.getElementsByTagName("head")[0]);a.wrap(a.Chart.prototype,"getContainer",function(a){a.call(this);this.container.style.background="url(http://www.highcharts.com/samples/graphics/sand.png)"});a.theme={colors:"#f45b5b #8085e9 #8d4654 #7798BF #aaeeee #ff0066 #eeaaee #55BF3B #DF5353 #7798BF #aaeeee".split(" "),
        chart:{backgroundColor:null,style:{fontFamily:"Signika, serif"}},title:{style:{color:"black",fontSize:"16px",fontWeight:"bold"}},subtitle:{style:{color:"black"}},tooltip:{borderWidth:0},legend:{itemStyle:{fontWeight:"bold",fontSize:"13px"}},xAxis:{labels:{style:{color:"#6e6e70"}}},yAxis:{labels:{style:{color:"#6e6e70"}}},plotOptions:{series:{shadow:!0},candlestick:{lineColor:"#404048"},map:{shadow:!1}},navigator:{xAxis:{gridLineColor:"#D0D0D8"}},rangeSelector:{buttonTheme:{fill:"white",stroke:"#438eb9",
            "stroke-width":1,states:{select:{fill:"#438eb9"}}}},scrollbar:{trackBorderColor:"#C0C0C8"},background2:"#E0E0E8"};a.setOptions(a.theme)})(b)});
    $(function () {
        //统计数据类型
        var s_type = $("#search_type").val();
        $('#search_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#stime,#etime').datetimepicker();
        show_searchtime();
        $("#search_type").change(function () {
            show_searchtime();
        });
        //展示搜索时间框
        function show_searchtime() {
            s_type = $("#search_type").val();
            $("[id^='searchtype_']").hide();
            $("#searchtype_" + s_type).show();
        }

        //更新周数组
        $("[name='searchweek_month']").change(function () {
            var year = $("[name='searchweek_year']").val();
            var month = $("[name='searchweek_month']").val();
            $("[name='searchweek_week']").html('');
            $.getJSON('index.php?act=common&op=getweekofmonth', {y: year, m: month}, function (data) {
                if (data != null) {
                    for (var i = 0; i < data.length; i++) {
                        $("[name='searchweek_week']").append('<option value="' + data[i].key + '">' + data[i].val + '</option>');
                    }
                }
            });
        });


        $('#ncsubmit').click(function () {
            var _data = $("form").serialize();
            window.location.href = '/index.php?r=onlinecount/minutecount&'+_data;
        });
    })
</script>
</body>
</html>