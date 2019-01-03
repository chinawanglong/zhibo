<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/5
 * Time: 15:51
 */
 namespace frontend\components;
 use Yii;
 use yii\base\Exception;
 use yii\base\ErrorException;
 use yii\helpers\ArrayHelper;

 class Common{
     public static function compress_html($string) {
         //$string = str_replace("\r",'', $string); //清除换行符
         $string = str_replace("\n",'', $string); //清除换行符
         $string = str_replace("\t",'', $string); //清除制表符
         $pattern = array (
             "/> *([^ ]*) *</", //去掉注释标记
             "/<!--[^!]*-->/",
         );
         $replace = array (
             ">\\1<",
             //" ",
             "",
         );
         return preg_replace($pattern, $replace, $string);
     }

     /*
      * 生成图形验证码
      */
     public static function makeveryCode($num,$w,$h,$sessionname="yanzhengma") {
         Header("Content-type: image/PNG");
         Yii::$app->response->format=\yii\web\Response::FORMAT_RAW;
         $str = "123456789abcdefghijkmnpqrstuvwxyz";
         $code = '';
         for ($i = 0; $i < $num; $i++) {
             $code .= $str[mt_rand(0, strlen($str)-1)];
         }
         //将生成的验证码写入session，备验证页面使用
         Yii::$app->session->set($sessionname,trim($code));
         //创建图片，定义颜色值
         $im = imagecreate($w, $h);
         $black = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
         $gray = imagecolorallocate($im, 118, 151, 199);
         $bgcolor = imagecolorallocate($im, 235, 236, 237);

         //画背景
         imagefilledrectangle($im, 0, 0, $w, $h, $bgcolor);
         //画边框
         imagerectangle($im, 0, 0, $w-1, $h-1, $gray);
         //imagefill($im, 0, 0, $bgcolor);



         //在画布上随机生成大量点，起干扰作用;
         for ($i = 0; $i < 80; $i++) {
             imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
         }
         $font = Yii::getAlias("@webroot/css/font.ttf");
         //echo $font;
         //将字符随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
         for ($i=0;$i<$num;$i++)
         {
             $tmp =substr($code,$i,1);
             $array = array(-1,1);
             $p = array_rand($array);
             $an = $array[$p]*mt_rand(1,10);//角度
             $size = 20;
             imagettftext($im, $size, $an, 10+$i*$size, 28, $black,$font, $tmp);
         }
         imagepng($im);
         imagedestroy($im);
     }
     /*
    * 生成手机验证码
   */
     public static function makesmsCode($mobile,$num,$sessionname="smscode") {
         if(empty($mobile) || empty($num)){
             return false;
         }
         $result=['error'=>0,'code'=>'','msg'=>''];
         try{
             $str = "123456789";
             $code = '';
             for ($i = 0; $i < $num; $i++) {
                 $code .= $str[mt_rand(0, strlen($str)-1)];
             }
             $code=trim($code);
             $flag = 0;
             $params='';//要post的数据

             //以下信息自己填以下
             $argv = array(
                 'name'=>'zhiboshi',     //必填参数。用户账号
                 'pwd'=>'CD0883D7BCB36A248A57661D5417',     //必填参数。（web平台：基本资料中的接口密码）
                 'content'=>'您的验证码是：'.$code.'。请不要把验证码泄露给其他人。',   //必填参数。发送内容（1-500 个汉字）UTF-8编码
                 'mobile'=>$mobile,   //必填参数。手机号码。多个以英文逗号隔开
                 'stime'=>'',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
                 'sign'=>'【财经直播室】',    //必填参数。用户签名。
                 'type'=>'pt',  //必填参数。固定值 pt
                 'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
             );
             //print_r($argv);exit;
             //构造要post的字符串
             //echo $argv['content'];
             foreach ($argv as $key=>$value) {
                 if ($flag!=0) {
                     $params .= "&";
                     $flag = 1;
                 }
                 $params.= $key."="; $params.= urlencode($value);// urlencode($value);
                 $flag = 1;
             }
             $url = "http://web.daiyicloud.com/asmx/smsservice.aspx?".$params; //提交的url地址
             $return_str=file_get_contents($url);
             $con= substr($return_str, 0, 1 );  //获取信息发送后的状态

             $result['code']=$code;
             Yii::$app->session->set($sessionname,trim($code));

             if($con == '0'){

             }else{
                 $log="sms error,detail : post error \n";
                 Yii::info($log,"sms");
                 //throw new Exception($log);
             }
             return $result;
         }
         catch(Exception $e){
             $result['error']=1;
             $result['msg']=$e->getMessage();
             return $result;
         }
     }
     
     /*
      * 检测数据库连接是否可用
      */
     /**
      * 检查连接是否可用
      * @param  Link $dbconn 数据库连接
      * @return Boolean
      */
     public static function pdo_ping($dbconn){
         try{
             $dbconn->getAttribute(\PDO::ATTR_SERVER_INFO);
         }
         catch (\yii\base\ErrorException $e){
             echo "go away\n";
             return false;
         }
         catch (\Exception $e) {
             /*if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
                 return false;
             }*/
             return false;
         }
         return true;
     }
 }