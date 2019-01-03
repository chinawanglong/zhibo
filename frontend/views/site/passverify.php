<?php
?>
<!DOCTYPE html>
<html >
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title><?=$roomconfig->title;?></title>
    <link href="" rel="shortcut icon" type="image/x-icon">
    <meta name="Keywords" content="<?=$roomconfig->title;?>">
    <meta name="description" content="<?=$roomconfig->title;?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="http://demo.meilingzhibo.com/lib/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://demo.meilingzhibo.com/css/bootstrap.css">
    <script type="text/javascript" src="http://demo.meilingzhibo.com/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="http://demo.meilingzhibo.com/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="http://demo.meilingzhibo.com/css/nopassword.css">

    <style type="text/css">
        html{
            width: 100%;
            height: 100%;
        }
        body{
            width: 100%;
            height: 100%;
        }


    </style>
</head>
<body>
<div class="container goin-room">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
            <h1 style="    margin: 120px 0 25px;" >
                <img src="<?= $roomconfig->logo; ?>" style="max-width: 400px; min-width: 100px;" /></h1>
            <form id="goin-form" action="/site/passverify" method="post">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" id="password" name="password" value="" onfocus="this.type='password'" placeholder="请输入房间密码">
						<span class="input-group-btn">
							<button class="btn btn-success" type="submit">进入房间</button>
						</span>
                </div>
                <!-- /input-group -->
            </form>
            <ul class="customer-list">
            </ul>
        </div>
    </div>
</div>
</body>
</html>