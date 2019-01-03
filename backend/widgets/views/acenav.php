<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-27
 * Time: 上午9:57
 */
 $zhiboid = !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0;
 $front_url=Yii::$app->furlManager->createAbsoluteUrl(['site/index',"room"=>$zhiboid]);
?>
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default">
    <script type="text/javascript">
        try {
         ace.settings.check('navbar', 'fixed')
         } catch (e) {
         }
    </script>

    <div class="navbar-container" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">侧边栏切换</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>
        <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
                <small>
                    <i class="fa fa-leaf"></i>
                    后台管理
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="dropdown">
                    <a href="<?=$front_url;?>" target="_blank"><i class="fa fa-hand-o-right fa-fw"></i> 进入前台</a>
                </li>
                <li>
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
                <!-- navbar-buttons -->
            </ul>
        </div>

        <!-- .navbar-container -->
    </div>
    <!-- #navbar -->
</div>

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