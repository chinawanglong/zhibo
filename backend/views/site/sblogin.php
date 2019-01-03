<?php
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php //echo Html::csrfMetaTags(); ?>
    <title>后台登录</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=Yii::$app->request->baseUrl;?>/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=Yii::$app->request->baseUrl;?>/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=Yii::$app->request->baseUrl;?>/bower_components/sbadmin/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=Yii::$app->request->baseUrl;?>/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">请登录</h3>
                    </div>
                    <div class="panel-body">
                        <form id="loginform" role="form" action="<?=Yii::$app->urlManager->createUrl(['site/login']);?>" method="post">
                            <fieldset>
                                <?php
                                  echo  Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken());
                                ?>
                                <div class="form-group">
                                    <input id="username" class="form-control" placeholder="用户名" name="LoginForm[username]" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input id="password" class="form-control" placeholder="密码" name="LoginForm[password]" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="LoginForm[rememberMe]" type="checkbox" value="1">记住我
                                    </label>
                                </div>
                                <div class="error_summary">
                                    <?php
                                        if($model->hasErrors()){
                                           $errors=$model->errors;
                                           $info="";
                                           /****如果有错误****/
                                           foreach($errors as $attribute=>$error){
                                              $info.=$error[0]."</br>";
                                           }
                                           echo $info;
                                        }
                                    ?>
                                    <!--summary-->
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <div  id="submit" class="btn btn-lg btn-success btn-block">登录</div>
                            </fieldset>
                        </form>
                        <!--panel-body-->
                    </div>
                    <!--login-panel-->
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?=Yii::$app->request->baseUrl;?>/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=Yii::$app->request->baseUrl;?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=Yii::$app->request->baseUrl;?>/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=Yii::$app->request->baseUrl;?>/bower_components/sbadmin/dist/js/sb-admin-2.js"></script>
    <script>
        $(function(){
              var $username=$("#username");
              var $password=$("#password");
              var $submit=$("#submit");
              $submit.click(function(){
                  if(!$username.val()){
                      alert("请输入你的用户名！");
                      return false;
                  }

                  if(!$password.val()){
                      alert("请输入密码");
                      return false;
                  }
                  $("#loginform").submit();
                  return true;
              });
              $("#loginform").keypress(function(event)/*如果是enter键那么就执行相当于单击发送的操作*/
              {
                 if (event.keyCode == 13)
                 {
                      $("#loginform").submit();
                      event.preventDefault();
                 }
              });
              <?php
                 if($model->hasErrors()){
                    echo <<<JS
                    $('#submit').removeClass('btn-success').addClass('btn-warning');
JS;
                 }
              ?>
        });
    </script>
</body>

</html>
