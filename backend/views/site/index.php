            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">控制台</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$allusercount?></div>
                                    <div>总注册量!</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['user/index'])?>">
                            <div class="panel-footer">
                                <span class="pull-left">查看所有</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$usercount_today?></div>
                                    <div>今天注册!</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['user/index'])?>">
                            <div class="panel-footer">
                                <span class="pull-left">前往查看</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$onlinecount;?></div>
                                    <div>当前在线!</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['user/index'])?>">
                            <div class="panel-footer">
                                <span class="pull-left">前往查看</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <!--顶部row-->
            </div>
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        房间角色用户总览
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="flot-area col-lg-5">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="viprole-flot-pie-chart"></div>
                                    <!--float1-->
                                </div>
                                <div class="flot--name-tip">--会员角色--</div>
                                <!--flot-area-->
                            </div>
                            <div class="flot-area col-lg-5 col-lg-offset-2">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="companyrole-flot-pie-chart"></div>
                                    <!--flot2-->
                                </div>
                                <div class="flot--name-tip">--公司角色--</div>
                                <!--flot-area-->
                            </div>
                            <!--row-->
                        </div>
                        <!--panel-body-->
                    </div>
                    <!--panel-->
                </div>
                <!--会员总览-->
            </div>
<?php

    $this->registerJsFile("@web/bower_components/flot/excanvas.min.js",['position'=>$this::POS_END,'depends'=>\backend\assets\AceAsset::className()]);
    $this->registerJsFile("@web/bower_components/flot/jquery.flot.js",['position'=>$this::POS_END,'depends'=>\backend\assets\AceAsset::className()]);
    $this->registerJsFile("@web/bower_components/flot/jquery.flot.pie.js",['position'=>$this::POS_END,'depends'=>\backend\assets\AceAsset::className()]);
    $this->registerJsFile("@web/bower_components/flot/jquery.flot.resize.js",['position'=>$this::POS_END,'depends'=>\backend\assets\AceAsset::className()]);
    $this->registerJsFile("@web/bower_components/flot/jquery.flot.time.js",['position'=>$this::POS_END,'depends'=>\backend\assets\AceAsset::className()]);
    $this->registerJsFile("@web/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js",['position'=>$this::POS_END,'depends'=>\backend\assets\AceAsset::className()]);
    $js='
       //Flot Pie Chart
$(function() {

    $data1_str=\''.json_encode($vipcountdata).'\';
    var data=jQuery.parseJSON($data1_str);
    var plotObj = $.plot($("#viprole-flot-pie-chart"), data, {
        series: {
            pie: {
                show: true
            }
        },
        grid: {
            hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
            //content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
            content:function(label, x, y) {
                      return label+"："+ y;
            },
            shifts: {
                x: 20,
                y: 0
            },
            defaultTheme: false
        }
    });
    $data2_str=\''.json_encode($companycountdata).'\';
    var data=jQuery.parseJSON($data2_str);
    var plotObj = $.plot($("#companyrole-flot-pie-chart"), data, {
        series: {
            pie: {
                show: true
            }
        },
        grid: {
            hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
            //content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
            content:function(label, x, y) {
                      return label+"："+ y;
            },
            shifts: {
                x: 20,
                y: 0
            },
            defaultTheme: false
        }
    });
});
';
$this->registerJs($js,$this::POS_END, 'site-dashbord');
?>
<style type="text/css">
    .flot-chart {
        display: block;
        height: 400px;
    }
    .flot--name-tip{
        text-align: center;
        color:#999;
        font-size:12px;
        margin-top:15px;
        margin-bottom:15px;
    }
</style>