<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-27
 * Time: 上午9:57
 */
 $front_url=Yii::$app->furlManager->createAbsoluteUrl(['site/index']);
 ?>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.html">直播室管理</a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown">
        <a href="<?=$front_url;?>" target="_blank"><i class="fa fa-hand-o-right fa-fw"></i> 进入</a>
    </li>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-institution fa-fw"></i> 欢迎您！<?php if(!Yii::$app->user->isGuest){ echo Yii::$app->user->identity->username;}?>
    </a>
  </li>
  <li class="dropdown">

     <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
     </a>
     <ul class="dropdown-menu dropdown-user">
        <li><a href="<?=Yii::$app->urlManager->createUrl(['user/view','id'=>Yii::$app->user->getId()]);?>"><i class="fa fa-user fa-fw"></i> 个人资料</a>
        </li>
        <li><a href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-gear fa-fw"></i>修改密码</a>
        </li>
        <li class="divider"></li>
        <li><a href="<?=Yii::$app->urlManager->createUrl(['site/logout']);?>"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
        </li>
     </ul>
    <!-- /.dropdown-user -->
  </li>
<!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">

            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['site/index']);?>"><i class="fa fa-dashboard fa-fw"></i> 后台首页</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-globe fa-fw"></i> 网站设置<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['site/config']);?>">
                            <i class="fa fa-joomla fa-fw"></i> 站点设置</a>
                    </li>
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['site/indexconfig']);?>">
                            <i class="fa fa-file-text-o fa-fw"></i> 首页设置</a>
                    </li>
                </ul>

            </li>
            <li>
                <a href="#"><i class="fa fa-home fa-fw"></i> 房间管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['zhibo/setup']);?>" cid="zhibo">
                            <i class="fa  fa-gear fa-fw"></i> 房间设置</a>
                    </li>
                    <li>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['image/index']);?>" cid="image">
                            <i class="fa fa-image fa-fw"></i> 背景设置</a>
                    </li>
                    <li>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['chat-history/index']);?>" cid="chat-history">
                            <i class="fa fa-comments-o fa-fw"></i> 聊天记录</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['chat/index']);?>" cid="chat">
                            <i class="fa fa-edit fa-fw"></i> 聊天审核</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['zhibo/setupvideo']);?>" cid="zhibo">
                            <i class="fa  fa-video-camera fa-fw"></i> 视频设置</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa  fa-cubes fa-fw"></i> 功能管理<span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['navigation/index']);?>" cid="navigation">
                                    <i class="fa fa-chain fa-fw"></i> 导航设置</a>
                            </li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['popwindow/index']);?>" cid="popwindow">
                                    <i class="fa fa-eject fa-fw"></i> 弹窗管理</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['course/index']);?>" cid="course">
                                    <i class="fa fa-calendar fa-fw"></i> 直播安排</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['votetype/index']);?>" cid="votetype">
                                    <i class="fa fa-file-o fa-fw"></i> 多空投票</a></li>
                            <li style="display:none"><a href="<?=Yii::$app->urlManager->createUrl(['voteval/index']);?>" cid="votetype">
                                    <i class="fa fa-align-left fa-fw"></i> 投票管理</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['advertise/index']);?>" cid="advertise">
                                    <i class="fa fa-adn fa-fw"></i> 常驻广告</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['expression/index']);?>" cid="expression">
                                    <i class="fa fa-smile-o fa-fw"></i> 表情管理</a></li>
                            <!--三级导航-->
                        </ul>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="#"><i class="fa  fa-book fa-fw"></i> 文章管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['article-type/index']);?>" cid="article-type">
                            <i class="fa fa-sitemap fa-fw"></i> 文章栏目
                        </a>
                        <a href="<?=Yii::$app->urlManager->createUrl(['article/index']);?>" cid="article">
                            <i class="fa fa-list-alt fa-fw"></i> 文章列表
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['customer-service/index']);?>" cid="customer-service"><i class="fa fa-qq fa-fw"></i> 客服管理</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> 会员管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['user/index']);?>" cid="user">
                            <i class="fa fa-users fa-fw"></i> 会员列表</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['onlineuser/index']);?>" cid="onlineuser">
                            <i class="fa fa-desktop fa-fw"></i> 在线列表</a>
                    </li>
                    <li>
                        <a href="<?=Yii::$app->urlManager->createUrl(['room-role/index']);?>" cid="room-role">
                            <i class="fa  fa-user fa-fw"></i> 角色管理</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['rbac/manage']);?>" cid="rbac"><i class="fa fa-male fa-fw"></i> 后台角色</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-gears fa-fw"></i> 系统设置<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['config-category/index']);?>" cid="config-category">
                            <i class="fa fa-wrench fa-fw"></i> 管理系统配置项</a>
                    </li>
                    <li>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['config-items/index']);?>" cid="config-items">
                            <i class="fa fa-copy fa-fw"></i> 管理系统配置项属性</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
</nav>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">修改密码</h4>
            </div>
            <div class="modal-body">
                <form action="<?php Yii::$app->urlManager->createUrl(['user/update','id'=>Yii::$app->user->getId()]);?>" method="post" role="form" id="lyyform">
                        <fieldset>
                            <div class="form-group">
                                <input type="password" id="user-password3" class="form-control" name="password3" maxlength="255" placeholder="新密码">
                            </div>
                            <div class="form-group">
                                <input type="password" id="user-password4" class="form-control" name="password4" placeholder="确认密码">
                            </div>
                            <label id="l1" style="color:#ff0000;"></label>
                        </fieldset>
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取 消</button>
                <button type="button" class="btn btn-primary" id="lbtn">修 改</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php
$js="
    $(function(){
        $('#user-password4').blur(function(){
            var p1=$('#user-password3').val();
            var p2=$('#user-password4').val();
            if(p1 != p2){
                $('#l1').html('两次密码输入不一样,请重输');
                $('#user-password3').val('');
                $('#user-password4').val('');
            }else{
                $('#lbtn').click(function(){
                    $('#l1').html('');
                    $.ajax({
                        type: 'POST',
                        url:'".Yii::$app->urlManager->createUrl(['user/changepwd','id'=>Yii::$app->user->getId()])."',
                        data:$('#lyyform').serialize(),
                        error: function(request) {
                            alert('无法修改');
                        },
                        success: function(data) {
                            alert(data);
                            $(this).parents('.modal').modal('hide');
                            window.location.href='index.php';
                        }
                    });
                });
            }
        });

    });
";
$this->registerJs($js, $this::POS_END, 'nav');