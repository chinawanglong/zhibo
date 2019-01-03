<?php

namespace backend\controllers;
use backend\models\Mailer;
use yii;
use yii\base\Event;
use yii\web\Controller;
use yii\data\Pagination;
use backend\models\Country;
use backend\models\Myclass;
use yii\base\ErrorException;
use yii\log\Logger;
use backend\models\Foo;
use backend\components\Redis;
use yii\helpers\Html;
use yii\helpers\Url;
use Acme\Test;
use yii\imagine\Image;
use Sunra\PhpSimple\HtmlDomParser;

class TestController extends Controller
{
    public function actionIndex()
    {
        $query = Country::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'countries' => $countries,
            'pagination' => $pagination,
        ]);
    }
    public function actionErrorhandle(){
        try {
            10/0;
        } catch (ErrorException $e) {
            Yii::warning("Division by zero.");
        }
    }
    /***日志-log***/
    public function actionLog(){
        //Yii::getLogger()->log('your site has been hacked', Logger::LEVEL_ERROR); //默认category为application即应用程序级别的日志
        Yii::info('your site has been hacked','Orders\test');
        exit("Response Ok!");
    }
    /***组件-对象-component/object***/
    public function actionObject(){
        $component = new MyClass(1, 2, ['prop1' => 3, 'prop2' => 4]);
        $component = \Yii::createObject([
            'class' => MyClass::className(),
            'prop1' => 3,
            'prop2' => 4,
        ], [1, 2]);
        echo "基本应用：".$component->user."</br>";
        echo "查询是否具有指定属性：".$component->hasProperty("user",true)."</br>";
        echo "是否可写  :".($component->canSetProperty('user')?"是":"否")."</br>";
        print_r($component);
    }
    /****事件测试******/
    public function actionEvent(){
        $foo = new Foo;
        // 处理器是全局函数
        //$foo->on(Foo::EVENT_HELLO, 'function_name');

        // 处理器是对象方法
        //$foo->on(Foo::EVENT_HELLO, [$object, 'methodName']);

        // 处理器是静态类方法
        //$foo->on(Foo::EVENT_HELLO, ['app\components\Bar', 'methodName']);

         // 处理器是匿名函数
        $foo->on(Foo::EVENT_HELLO, function ($event) {
            echo('123456');
            echo "</br>".$event->data;
            //事件处理逻辑
        },'jieke');
        $foo->trigger(Foo::EVENT_HELLO);
       /****事件测试****/
    }
    public function actionEvent1(){
        /***对象级别的绑定******/
         $Mailer=new \backend\models\Mailer;
         $Mailer->on($Mailer::EVENT_MESSAGE_SENT,function($event){
             echo $event->message."</br>";
         });
        $Mailer->off($Mailer::EVENT_MESSAGE_SENT);
         $Mailer->send('this is a mail');
        /***类级别的***/
        Event::on(Foo::className(), Foo::EVENT_HELLO, function ($event) {
            print_r($event->sender);
            echo time()."</br>";  // 显示 "app\models\Foo"
        });

        // 移除 Foo::EVENT_HELLO 事件的全部处理器
        Event::off(Foo::className(), Foo::EVENT_HELLO);
        Event::trigger(Foo::className(), Foo::EVENT_HELLO);

        /*******全局事件******/
        Yii::$app->on('bar', function ($event) {
            echo get_class($event->sender);  // 显示 "app\components\Foo"
        });
        Yii::$app->trigger('bar', new Event(['sender' => new Foo]));
    }
    /**********/
    public function actionBehavior(){
        $mailer=new Mailer();
        echo $mailer->prop1;
    }
    /*****redis测试****/
    public function actionRedis(){
        Redis::set('name','mike',5,'h');
        echo Redis::get('name');
    }
    /***获得redis val的测试***/
    public function actionRedisval(){
        echo "The value of redis key:name is".Redis::get('name');
    }
    /***使用Activeform***/
    public function actionUseform(){
        $model=new \backend\models\TestForm();
        if ($model->load(Yii::$app->request->post())&&$model->validate()&&$model->upload()) {
            print_r($model);
        } else {
            return $this->render('testform', [
                'model' => $model,
            ]);
        }
    }
    public function actionTranslate(){
        echo Yii::t('order','orderid');
       // echo Yii::t('order', 'orderinfo', time());
    }
    /*****使用邮箱*****/
    public function actionSendmail(){
        $mail=Yii::$app->mailer->compose()
            ->setFrom('18818223517@163.com')
            ->setTo('757045849@qq.com')
            ->setSubject('Message subject')
            ->setTextBody('Plain text content')
            ->setHtmlBody('<b>HTML content</b>');
        if($mail->send()){
            echo "Send Ok";
        }
        else{
            echo "Send Fail";
        }
    }
    /****添加一个自定义组件*****/
    public function actionAddcomponent(){
        $test_component=Yii::$app->test;
        echo $test_component->say()."</br>";
        print_r($test_component);
    }
    public function actionHelper(){
        $array=[
            'foo' => [
                'bar' => new Foo(),
            ]
        ];
        echo \yii\helpers\ArrayHelper::getValue($array,"foo.bar.name");
        /***检查键名的存在***/
        $data1 = [
            'userName' => 'Alex',
        ];

        $data2 = [
            'username' => 'Carsten',
        ];

        if (\yii\helpers\ArrayHelper::keyExists('username', $data1, false) || !\yii\helpers\ArrayHelper::keyExists('username', $data2, false)) {
            echo "</br>Please provide username.</br>";
        }
        /***检索列***/
        $data = [
            ['id' => '123', 'data' => 'abc'],
            ['id' => '345', 'data' => 'def'],
        ];
        $ids = \yii\helpers\ArrayHelper::getColumn($data, 'id');
        print_r($ids);
        echo "<br/>";
        $result = \yii\helpers\ArrayHelper::getColumn($data, function ($element) {
            return $element['id'];
        });
        print_r($result);
        echo "<br/>";
        /****重建数组索引*****/
        $array = [
            ['id' => '123', 'data' => 'abc'],
            ['id' => '345', 'data' => 'def'],
        ];
        $result = \yii\helpers\ArrayHelper::index($array, 'id');
        print_r($result);
        echo "<br/>";
        $result = \yii\helpers\ArrayHelper::index($array, function ($element) {
            return $element['id'];
        });
        print_r($result);
        echo "<br/>";
        /*****建立哈希表******/
        $array = [
            ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
            ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
            ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
        ];
        $result = \yii\helpers\ArrayHelper::map($array, 'id', 'name');
        print_r($result);
        echo "<br/>";
        $result = \yii\helpers\ArrayHelper::map($array, 'id', 'name', 'class');
        print_r($result);
        echo "<br/>";
        /********Array值的HTML编码***********/
        $data=[
            'a'=>'<a href="www.facebook.com">张明乐</a>',
            'b'=>'麦可'
        ];
        $encoded = \yii\helpers\ArrayHelper::htmlEncode($data);
        print_r($encoded);
        echo "</br>";
        $decoded = \yii\helpers\ArrayHelper::htmlDecode($data);
        print_r($decoded);
        echo "</br>";
        /***helper****/
    }
    public function actionHtmlhelper(){
        $model=new Foo();
        $model->name="test";
        $user=new \backend\models\User();
        $user->username="ji ke";
        $user->validate();
       //echo  Html::tag('p', Html::encode("张明乐"), ['class' => 'username']);
       //echo  Html::encode("<a href='http://www.baidu.com'>百度</a>");
       return $this->render('makeform',['model'=>$model,'user'=>$user]);
    }
    public function actionUrl(){
        $relativeHomeUrl = Url::home();
        $absoluteHomeUrl = Url::home(true);
        $httpsAbsoluteHomeUrl = Url::home('https');
        echo $relativeHomeUrl."<br/>";
        echo $absoluteHomeUrl."<br/>";
        echo $httpsAbsoluteHomeUrl."<br/>";
        echo  Url::toRoute(['product/view', 'id' => 42])."<br/>";
        echo Url::to('http://www.baidu.com','https')."<br/>";
        echo Yii::$app->urlManager->hostInfo."</br>";
        echo Url::to('@web/images/logo.gif')."</br>";
        /*****current-可以根据当前url的参数合并或者添加信息*****/
        echo Url::current();
        // /index.php?r=post/view&id=123
        echo Url::current(['src' => null])."</br>";
        // /index.php?r=post/view&id=100&src=google
        echo Url::current(['id' => 100])."</br>";
        echo Url::to(['http://www.baidu.com?r=site/test']);
    }
    /*********JSON生成测试********/
    public function actionJson(){
        $data=[
            ['max'=>1012,'min'=>147,'low'=>1005,'high'=>1011],
            ['max'=>1012,'min'=>147,'low'=>1005,'high'=>1011],
            ['max'=>1012,'min'=>147,'low'=>1005,'high'=>1011],
            ['max'=>1012,'min'=>147,'low'=>1005,'high'=>1011],
        ];
        $data=[
            [1111,1245,124],
            [1274,214,1810]
        ];
        echo yii\helpers\Json::encode($data);
    }
    /******composer名称空间注册*******/
    public function actionComposer(){
        $test=new \Acme\Test();
        $test->show_version();
        $test=new \app\models\Test();
        $test->showmessage();
    }
    /******Image库******/
    public function actionImage(){
        echo Yii::getAlias('@app');
        Image::thumbnail('@webroot/assets/image/test.jpg', 120, 120)
            ->save(Yii::getAlias('@runtime/thumb-test-image.jpg'), ['quality' => 50]);
    }
    /*******扩展测试**********/
    public function actionExtension(){
        /*****在main中配置的别名既可以用来定义又可以定义名称空间*****/
        Yii::setAlias('@extension',Yii::getAlias("@vendor/../src"));
        $ext=new \extension\User();
        $ext->showu();
        echo Yii::getAlias("@extension")."</br>";
        echo Yii::getAlias("@app");
        $test=new \app\models\Test();
        $test->showmessage();
        $user=new \extension\test\User();
        $user->showu();
        echo "</br>".Yii::getAlias("@webroot");
        echo "</br>".Yii::getAlias("@web");
    }
    public function actionMemcache(){
        Yii::$app->memcache->set('mid','12345678 of cache ,timestamp is '.time());
        echo Yii::$app->memcache->get('mid');
    }
    public function actionPachong(){
        $dom=HtmlDomParser::file_get_html("http://www.tantengvip.com/2013/10/phpquery/");
        if($dom){
            foreach($dom->find("img") as $element){
                echo $element->src."</br>";
            }
        }
        else{
            echo "无法抓取";
        }
        /***爬虫***/
    }
    public function actionCsrf(){
        $csrf=Yii::$app->request->getCsrfToken();
        echo $csrf."</br>";
        echo Yii::$app->request->csrfToken."</br>";
        echo Yii::$app->request->cookies['YII_CSRF_TOKEN'];
        if(Yii::$app->request->csrfToken==$_POST['_csrf']){
            return "its same";
        }
        return $this->render('csrf');
    }
    public function actionTest(){
        /*$config=\backend\models\ConfigCategory::getConfig('网站配置1');
        print_r($config);
        echo "</br>";
        print_r($config->items);
        echo "</br>";
        print_r($config->open_signup->val);*/

        $article_content=<<<JSON
         <img class="pic" src="http://oss-cn-shanghai.aliyuncs.com/mikestorage/img/articlepic/2015-09-15/jk_pic_up20150915130919_55374.jpg">
         <img class="pic" src="http://oss-cn-shanghai.aliyuncs.com/mikestorage/img/articlepic/2015-09-15/jk_pic_up20150915130919_55374.jpg">
JSON;
        $article_content1=<<<JSON
<img class="picitem" src="http://jieweiwo-resource1.stor.sinaapp.com/pic/2015-02-16/jk_pic_up20150216095232_67789.jpg">
<img class="picitem" src="http://jieweiwo1-resource1.stor.sinaapp.com/pic/2015-02-16/jk_pic_up20140216095232_67789.jpg">
JSON;


        $preg_str='|<img[^<>]*src=[\"\']((([^\"\']+).aliyuncs.com)/(([^\"\']+)/(jk_pic_up([^\"\']+))))[\"\']([^<>]*)>|is';
        if(preg_match_all($preg_str, $article_content, $imgs,PREG_PATTERN_ORDER)){
                 print_r($imgs);
        }
    }
    public function actionRevoke(){
        $auth=Yii::$app->rbac;
        $auth->revoke();
    }
    
    public function actionAuthtest(){
        \frontend\components\Common::authtest();
    }
}