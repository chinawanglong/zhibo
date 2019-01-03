<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 21:33
 */
?>

<div id="sidebar" class="sidebar    responsive">

    <ul class="nav nav-list">
        <li>
            <a href="<?= Yii::$app->urlManager->createUrl(['site/index']); ?>">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text">后台首页</span>
            </a>

            <b class="arrow"></b>
        </li>

        <li class="">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon glyphicon glyphicon-cog"></i>
                <span class="menu-text">
								系统设置
							</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['site/config']); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        站点设置
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['room-role/index']); ?>" cid="room-role">
                        <i class="menu-icon fa fa-caret-right"></i>
                        房间角色设置
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['blacklist/index']); ?>" cid="blacklist">
                        <i class="menu-icon fa fa-user-times"></i>
                        房间黑名单用户管理
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['oprecord/index']); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        后台访问记录
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['config-category/index']); ?>"
                       cid="config-category">
                        <i class="menu-icon fa fa-caret-right"></i>
                        管理系统配置项
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['config-items/index']); ?>"
                       cid="config-items">
                        <i class="menu-icon fa fa-caret-right"></i>
                        管理系统配置项属性
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="#" class="dropdown-toggle" tid="0">
                <i class="menu-icon fa fa-home"></i>
                <span class="menu-text">房间管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <?php
                if (in_array("topadmin", Yii::$app->user->identity->rbacroles)) {
                    ?>
                    <li class="">
                        <a href="<?= Yii::$app->urlManager->createUrl(['zhibo/index']); ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            房间列表
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <?php
                }
                ?>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['zhibo/setup']); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        房间设置
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['image/index']); ?>" cid="image">
                        <i class="menu-icon fa fa-caret-right"></i>
                        背景设置
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['chat/index']); ?>" cid="chat">
                        <i class="menu-icon fa fa-caret-right"></i>
                        审核聊天
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['zhibo/setupvideo']); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        视频设置
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="<?= Yii::$app->urlManager->createUrl(['teacherzan/index']); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        讲师榜
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="#" class="dropdown-toggle" tid="2">
                        <i class="menu-icon fa fa-caret-right"></i>
                        喊单管理
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['shouted/index']); ?>" cid="shouted">
                                <i class="menu-icon fa fa-film blue"></i>
                                喊单管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['shouted-good/index']); ?>"  cid="shouted-good">
                                <i class="menu-icon fa fa-cubes"></i>
                                产品管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['shouted-teacher/index']); ?>"   cid="shouted-teacher">
                                <i class="menu-icon fa fa-user"></i>
                                讲师管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="#" class="dropdown-toggle" tid="2">
                        <i class="menu-icon fa fa-caret-right"></i>
                        功能管理
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['navigation/index']); ?>" cid="navigation">
                                <i class="menu-icon fa fa-flag orange"></i>
                                导航设置
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['popwindow/index']); ?>" cid="popwindow">
                                <i class="menu-icon fa fa-eye green"></i>
                                弹窗管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['course/update']); ?>" cid="course">
                                <i class="menu-icon fa fa-film blue"></i>
                                直播安排
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['votetype/index']); ?>" cid="votetype">
                                <i class="menu-icon fa fa-coffee red2"></i>
                                多空投票
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['vote-result/index']); ?>" cid="vote-result">
                                <i class="menu-icon fa fa-coffee red2"></i>
                                投票细明
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['advertise/index']); ?>" cid="advertise">
                                <i class="menu-icon glyphicon glyphicon-picture blue"></i>
                                常驻广告
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['expression/index']); ?>" cid="expression">
                                <i class="menu-icon fa fa-comments red2"></i>
                                表情管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="#" class="dropdown-toggle" tid="0">
                <i class="menu-icon fa fa-home"></i>
                <span class="menu-text">
								数据管理
							</span>

                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-users"></i>
                        <span class="menu-text"> 会员管理 </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['user/index']); ?>" cid="user">
                                <i class="menu-icon"></i>
                                会员列表
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['umobile/index']); ?>" cid="umobile">
                                <i class="menu-icon"></i>
                                手机号管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['onlineuser/index']); ?>" cid="onlineuser">
                                <i class="menu-icon"></i>
                                房间在线列表
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
                <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['visit/index-iframe']); ?>" cid="visit">
                            <i class="menu-icon fa fa-desktop"></i>
                            <span class="menu-text"> 访客列表 </span>
                        </a>
                        <b class="arrow"></b>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['onlinecount/minutecount-iframe']); ?>" cid="minutecount">
                        <i class="menu-icon fa fa-desktop"></i>
                        <span class="menu-text"> 并发详情 </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['customer-service/index']); ?>" cid="customer-service">
                        <i class="menu-icon fa fa-desktop"></i>
                        <span class="menu-text"> 客服管理 </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['customer-service/setupkefu']); ?>" cid="setupkefu">
                        <i class="menu-icon fa fa-desktop"></i>
                        <span class="menu-text"> 当前QQ管理 </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        文章管理
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['article-type/index']); ?>"
                               id="form-elements" cid="article-type">
                                <i class="menu-icon"></i>
                                文章栏目
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Yii::$app->urlManager->createUrl(['article/index']); ?>" cid="article">
                                <i class="menu-icon"></i>
                                文章列表
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <?php
        if (in_array("topadmin", Yii::$app->user->identity->rbacroles)) {
        ?>
        <li class="">
            <a href="<?= Yii::$app->urlManager->createUrl(['rbac/manage']); ?>" cid="rbac">
                <i class="menu-icon glyphicon glyphicon-user"></i>
                <span class="menu-text"> 后台角色 </span>
            </a>
            <b class="arrow"></b>
        </li>
            <?php
        }
        ?>
        <li class="">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon  fa fa-phone"></i>
                <span class="menu-text">
								关于美林
							</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="">
                    <a href="http://www.meilingzhibo.com" target="_blank">
                        <i class="menu-icon"></i>
                        官方网站
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="http://www.meilingzhibo.com/site/aboutsem" target="_blank">
                        <i class="menu-icon"></i>
                        竞价(SEM)-资源推广服务
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="http://www.meilingzhibo.com" target="_blank">
                        <i class="menu-icon"></i>
                        网站开发-SEO-平面设计
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="http://www.meilingzhibo.com/site/aboutsem" target="_blank">
                        <i class="menu-icon"></i>
                        推广实验室
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        <!-- nav-list -->
    </ul>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
           data-icon2="ace-icon fa fa-angle-double-right"></i>
        <!--sidebar-toggle sidebar-collapse-->
    </div>
</div>