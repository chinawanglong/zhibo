<?php
namespace frontend\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\data\ActiveDataProvider;
use Sunra\PhpSimple\HtmlDomParser;

use frontend\components\Common;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use backend\models\ConfigCategory;
use backend\models\ConfigItems;
use backend\models\Expression;
use backend\models\Image;
use backend\models\CustomerService;
use backend\models\Tanchuang;

use backend\models\User;
use backend\models\Onlineuser;
use backend\models\RoomRole;
use backend\models\Course;
use backend\models\Zhibo;
use backend\models\Navigation;
use backend\models\Advertise;
use backend\models\Blacklist;
use backend\models\Popwindow;

use backend\models\Shouted;
use backend\models\ShoutedSearch;
use backend\models\Umobile;
use backend\models\Article;
use backend\models\ArticleType;
use backend\models\Visit;

/**
 * Site controller
 */
class SiteController extends Controller
{


    public function beforeAction($action)
    {
        if ($action->id == "checklogin") {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);

    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    /*****网站入口,判断进入直播室还是介绍页****/
    public function actionDefault(){
        $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
                /**检查是否将房间页做首页**/
        if(empty($siteconfig->homepage_withroom->val)){
            return $this->redirect(Yii::getAlias("@web/index"));
        }
        else{
            return $this->redirect(Yii::getAlias("@web/site/index"));
        }
    }

    //访问统计
    public function RecordVisit()
    {
        Yii::$app->session->set('visitor_time_statistics',time()+300);
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            if ($user->ncname) {
                $username = $user->ncname;
            } else {
                $username = $user->username;
            }
            $uid = $user->id;
        }else{
            $uid = 0;
            $username='游客';
        }
        $visit = new Visit();
        $isCrawler = $visit->isCrawler();
        if ($isCrawler) {
            return false;
        }
        $visit->reffer_type = (int)$visit->GetVisitReferrerType(Yii::$app->request->get('reffer'));

        $visit->setAttributes(array_merge(Yii::$app->request->get(), ['room_id' => Yii::$app->session->get("zhiboid"), 'ip' => Yii::$app->request->userIP,'username'=>$username,'uid'=>$uid]));
        $visit->save();
    }

    /**
     * 首页
     * @return string|string[]|null
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest && !empty($_COOKIE["guest_unable_video"])){
            //return "<div style='color:red;text-align: center;font-size: 20px;'>您的试看已结束,请先注册为会员再观看!<script type='text/javascript'>setTimeout(function(){window.location.href='/site/login';},1500);</script> </div>";
        }

        /*记录访客*/
        $this->RecordVisit();

        $roomid = Yii::$app->session->get("zhiboid");
        $passverify_val = Yii::$app->session->get("passverify_{$roomid}_val");
        $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
        $indexconfig = ConfigCategory::getConfigbyalias("indexconfig");
        $roomconfig = Zhibo::findOne($roomid);

        if(Yii::$app->user->isGuest && !empty($roomconfig->password) && trim($passverify_val) != trim($roomconfig->password)){
            return $this->redirect(['/site/passverify','room'=>$roomid]);
        }

        $guest_role = RoomRole::getConfigbyalias("guest");
       
        $current_user = "";
        /***检查角色是否允许进入房间**/
        $current_role = "";
        $allow_roles = !empty($roomconfig->allowroles) ? unserialize($roomconfig->allowroles) : array();
        if(!is_array($allow_roles)){
            $allow_roles=[];
        }
        if (Yii::$app->user->isGuest) {
            $current_role = $guest_role;
        } else if (!empty(Yii::$app->user->identity->room_role)) {
            $current_role = Yii::$app->user->identity->room_role;
        } else {
            $current_role = $guest_role;
        }
        if (empty($current_role) || empty($roomconfig->allowroles) || !in_array($current_role->id, $allow_roles)) {
            return $this->redirect(['login']);
        }
        /**检查用户是否已经被列入黑名单**/
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            /**验证有没有在黑名单**/
            $blackitem = Blacklist::find()->where(['uid' => Yii::$app->user->id])->one();
            if (!empty($blackitem)) {
                Yii::$app->user->logout(false);
                return $this->redirect(['login']);
            }
        } else if ($temp_name = Yii::$app->request->cookies->getValue("temp_name")) {
            /**验证有没有在黑名单**/
            $blackitem = Blacklist::find()->where(['uid' => 0, 'temp_name' => $temp_name])->one();
            if (!empty($blackitem)) {
                return $this->redirect(['login']);
            }
        }

        $sitetopnav = Navigation::find()->where([/*'zhiboid'=>$roomid,*/'location' => 1,'status'=>1])->orderBy(['order'=>SORT_DESC])->all();
        $siteleftnav = Navigation::find()->where([/*'zhiboid'=>$roomid,*/'location' => 2,'status'=>1])->orderBy(['order'=>SORT_DESC])->all();
        $shipinbottomnav = Navigation::find()->where([/*'zhiboid'=>$roomid,*/'location' => 3,'status'=>1])->orderBy(['order'=>SORT_DESC])->all();
        $inputtopnav = Navigation::find()->where([/*'zhiboid'=>$roomid,*/'location' => 4,'status'=>1])->orderBy(['order'=>SORT_DESC])->all();
        $footernav = Navigation::find()->where([/*'zhiboid'=>$roomid,*/'location' => 5,'status'=>1])->orderBy(['order'=>SORT_DESC])->all();

        /**所有广告**/
        $advertises=Advertise::find()->where([/*'zhiboid'=>$roomid,*/'status'=>1])->limit(10)->orderBy(['order'=>SORT_DESC,'id'=>SORT_ASC])->all();

        $seeonline_auth = 1;
        $is_companyadmin = $current_role->isAdmin ? 1 :0;
        if (empty($current_role) || empty($current_role->see_online_num->val)) {
            $seeonline_auth = 0;
        }

        if($roomid == 1){
            $onlinecount = Onlineuser::find()->select("fd")->distinct()->count();
        }
        else{
            $onlinecount = Onlineuser::find()->where((['zhiboid'=>$roomid]))->select("fd")->distinct()->count();
        }
        
        if( !empty($roomconfig->base_online) ){
            $onlinecount += intval($roomconfig->base_online);
        }

        $article_groups=[
            'member_dynamic'=>'',
            'basic_courseware'=>'',
            'strategy_layout'=>'',
            'advance_jishu'=>''
        ];

        foreach ($article_groups as $alias=>$i){
            $article_type=ArticleType::find()->where(['zhiboid'=>$roomid,'code'=>$alias,'status'=>1])->one();
            if($article_type){
                $article_groups[$alias]=[];
                $article_groups[$alias]['type']=$article_type;
                $article_groups[$alias]['articles']=$article_type->getArticles()->limit(10)->asArray()->all();
            }
            else{
                unset($article_groups[$alias]);
            }
        }

        $other_accounts=[];
        if(!Yii::$app->user->isGuest){
            $other_accounts=User::find()->where(['parentid'=>Yii::$app->user->id,'status'=>1])->all();
        }

        if(!empty($siteconfig->chat_forbidden_words)){
            $chat_forbidden_words = $siteconfig->chat_forbidden_words->val;
            $chat_forbidden_words_array = explode("|",$chat_forbidden_words);
            $chat_forbidden_words_array = is_array($chat_forbidden_words_array) ? $chat_forbidden_words_array : [];
            $chat_forbidden_words_config = json_encode($chat_forbidden_words_array);
        }
        else{
            $chat_forbidden_words_config = json_encode([]);
        }

        return Common::compress_html(
            $this->renderPartial("newindex1.php", [
                'article_groups'=>$article_groups,
                'seeonline_auth' => $seeonline_auth,
                'is_companyadmin' => $is_companyadmin,
                'onlinecount' => $onlinecount,
                'siteconfig' => $siteconfig,
                'indexconfig' => $indexconfig,
                'roomconfig' => $roomconfig,
                'sitetopnav' => $sitetopnav,
                'siteleftnav' => $siteleftnav,
                'shipinbottomnav' => $shipinbottomnav,
                'other_accounts'=>$other_accounts,
                'advertises'=>$advertises,
                'chat_forbidden_words_config'=>$chat_forbidden_words_config
            ])
        );
    }

    public function actionDownroomtodesk()
    {
        header("Content-type:text/html;charset=utf-8");
        $roomid = Yii::$app->session->get("zhiboid");
        $zhibo = Zhibo::findOne($roomid);
        $file_name_output=!empty($zhibo)?$zhibo->name.".url":"财经直播间.url";
        $file_contents = printf("[InternetShortcut]
URL=%s
IconFile=%s
IconIndex=0
IDList=
HotKey=0
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2",Yii::$app->urlmanager->createAbsoluteUrl(["site/index","room"=>$roomid]),"");
        $file_size = strlen($file_contents);
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:" . $file_size);
        Header("Content-Disposition: attachment; filename=" . $file_name_output);
        echo $file_contents;
        /*下载到桌面测试函数*/
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            Yii::$app->user->logout(false);//执行下线
        }
        $roomid = Yii::$app->session->get("zhiboid");
        $model = new LoginForm();
        $guest_model = RoomRole::find()->where(['alias' => 'guest'])->one();
        $zhiboshi = Zhibo::find()->one();
        $customers = CustomerService::find()->andwhere(['zhiboid'=>$roomid,'status' => 1])->limit(7)->all();
        return $this->renderPartial('login', ['zhiboshi' => $zhiboshi, 'customers' => $customers]);
    }

    /**
     * 登录
     * @return false|string
     */
    public function actionTologin()
    {
        $result = ['error' => 0, 'info' => '', 'msg' => ''];
        try {
            if (!Yii::$app->user->isGuest) {
                throw new Exception('你已经登录');
            }
            if (Yii::$app->session->get("logincode") != Yii::$app->request->post('verycode')) {
                throw new Exception("验证码错误!");
            }
            $model = new LoginForm();
            $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
            if ($model->load(Yii::$app->request->post()) && $model->login()) {

                /**查看昵称有没有通过审核**/
                if(!empty($siteconfig->check_nickname->val)&&Yii::$app->user->identity->status==2){
                    Yii::$app->user->logout(false);
                    throw new Exception("你的昵称还未被审核通过,请稍后再试!");
                }
                if(Yii::$app->user->identity->status!=1){
                    Yii::$app->user->logout(false);
                    throw new Exception("你当前被限制!");
                }
                /**登陆后验证有没有在黑名单**/
                $blackitem = Blacklist::find()->where(['uid' => Yii::$app->user->id])->one();
                if (!empty($blackitem)) {
                    Yii::$app->user->logout(false);
                    throw new Exception("失败,你当前被禁止登陆!");
                }
                /*保存到在线列表*/
                $onlineuser = new Onlineuser();
                $onlineuser->uid = Yii::$app->user->id;
                $onlineuser->ip = Yii::$app->request->userIP;
                $onlineuser->time = date('Y-m-d H:i:s', time());
                $onlineuser->save();
                return json_encode($result);
            } else {
                $errors = $model->errors;
                foreach ($model->errors as $attribute => $erroritem) {
                    $result['info'][$attribute] = implode(",", $erroritem);
                }
                throw new Exception('登录失败');
            }
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }

    /**
     * 是否已登录
     * @return false|string|\yii\web\Response
     */
    public function actionChecklogin()
    {
        $guestrole = RoomRole::getConfigbyalias('guest');
        if (!!empty($guestrole)) {
            $guestrole = RoomRole::getConfig("游客");
        }
        $result = ['error' => 0, 'data' => ['state' => 0, 'roleid' => 0, 'rolename' => '', 'rolepic' => '', 'nickname' => '', 'img'=>'','uid' => 0,'ip'=>''], 'msg' => ''];
        try {
            $roomid = Yii::$app->session->get("zhiboid");

            if (!Yii::$app->request->isAjax) {
                return $this->goHome();
            }
            if (!Yii::$app->user->isGuest) {
                $user = Yii::$app->user->identity;
                //登录成功，查看是否有昵称
                if ($user->ncname) {
                    //有昵称，显示昵称
                    $result['data']['nickname'] = $user->ncname;
                } else {
                    //没有昵称显示用户名
                    $result['data']['nickname'] = $user->username;
                }
                $result['data']['img'] = !empty($user->img)? $user->img : "";
                $result['data']['state'] = 1;
                $result['data']['roomid'] = $roomid;
                $result['data']['uid'] = $user->id;
                if ($roomrole = $user->room_role) {
                    $result['data']['roleid'] = $roomrole->id;
                    $result['data']['rolename'] = !empty($roomrole->name) ? $roomrole->name : "";
                    $result['data']['rolepic'] = !empty($roomrole->role_pic) ? $roomrole->role_pic->val : "";
                }
            } else {
                if (!($temp_name = Yii::$app->request->cookies->getValue("temp_name"))) {
                    $temp_name = "游客-" . Onlineuser::getuniqname(5);
                    $expire = time() + 86400 * 30 * 12;
                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'temp_name',
                        'value' => $temp_name,
                        'expire' => $expire,
                        'path' => '/'
                    ]));
                }
                $result['data']['nickname'] = $temp_name;
                $result['data']['state'] = 0;
                $result['data']['roomid']= $roomid;
                $result['data']['uid'] = 0;
                $result['data']['roleid'] = $guestrole->id;
                $result['data']['rolename'] = "游客";
                $result['data']['rolepic'] = ((!empty($guestrole) && !empty($guestrole->role_pic)) ? $guestrole->role_pic->val : '');
                /*否则是游客*/
            }

            $result['data']['ip']= getIP()."  (".Yii::$app->request->hostInfo.")";/*Yii::$app->request->userIP*/;

            return json_encode($result);
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }

    /**
     * 注册
     * @return string
     */
    public function actionSignup()
    {
        $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
        if (empty($siteconfig->open_signup->val)) {
            $html = "<div style='text-align: center;color: red;'>当前站点不提供个人注册,请联系客服!
                 <script type='text/javascript'>setTimeout(function(){window.location.href='" . Yii::$app->homeUrl . "';},2000);</script>
             </div>";
            return $html;
        }
        $zhiboshi = Zhibo::find()->one();//直播室的配置
        return $this->renderPartial('signup', ["zhiboshi" => $zhiboshi]);
    }

    /*public function actionTosignup()
    {
        $result = ['error' => 0, 'info' => '', 'msg' => ''];
        try {
            $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
            if (empty($siteconfig->open_signup->val)) {
                throw new Exception('当前站点不提供个人注册,请联系客服!');
            }
            if (!Yii::$app->request->isPost) {
                throw new Exception('你提交的数据无法被验证!');
            }
            if (!Yii::$app->user->isGuest) {
                throw new Exception('请先退出!');
            }
            if (trim(Yii::$app->session->get("smscode")) != trim(Yii::$app->request->post('smscode'))) {
                throw new Exception("验证码错误!");
            }
            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            } else {
                $errors = !empty($model->errors) ? $model->errors : $model->user->errors;
                foreach ($errors as $attribute => $erroritem) {
                    $result['info'][$attribute] = implode(",", $erroritem);
                }
                throw new Exception(print_r($errors, true));
            }

            //添加手机号到手机列表
            $umobile=new Umobile();
            $umobile->mobile=$model->mobile;
            $umobile->type=2;
            $umobile->info="注册成功";
            $umobile->time=date("Y-m-d H:i:s");
            $umobile->save();

            Yii::$app->user->login($model->user,3600*30*12);

            return json_encode($result);
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }*/

    public function actionTosignup()
    {
        $result = ['error' => 0, 'info' => '', 'msg' => ''];
        try {
            $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
            if (empty($siteconfig->open_signup->val)) {
                throw new Exception('当前站点不提供个人注册,请联系客服!');
            }
            if (!Yii::$app->request->isPost) {
                throw new Exception('你提交的数据无法被验证!');
            }
            if (!Yii::$app->user->isGuest) {
                throw new Exception('请先退出!');
            }
            if (Yii::$app->session->get("regcode") != Yii::$app->request->post('verycode')) {
                throw new Exception("验证码错误!");
            }
            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            } else {
                $errors = !empty($model->errors) ? $model->errors : $model->user->errors;
                foreach ($errors as $attribute => $erroritem) {
                    $result['info'][$attribute] = implode(",", $erroritem);
                }
                throw new Exception(print_r($errors, true));
            }
            Yii::$app->user->login($model->user,0);
            return json_encode($result);
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }


    /**
     * 登出
     * @return array
     */
    public function actionTologout()
    {
        $result = ['error' => 0, 'msg' => ''];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            if (Yii::$app->user->isGuest) {
                throw new Exception('你还没有登录');
            }
            Yii::$app->user->logout(false);
            return $result;
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

    /**
     * 重置密码
     * @return false|string
     */
    public function actionResetpass()
    {
        $result = ['error' => 0, 'msg' => ''];
        try {
            if (Yii::$app->user->isGuest) {
                throw new Exception('你还没有登录');
            }
            $model = new LoginForm();
            $user = Yii::$app->user->identity;
            $user->scenario = "updatewithpwd";
            $form = Yii::$app->request->post('ResetForm', []);
            if (!$form['oldpass']) {
                $result['info']['oldpass'] = "请输入原始密码";
            } else {
                if (!$user->validatePassword($form['oldpass'])) {
                    $result['info']['oldpass'] = "原始密码不正确";
                    throw new Exception("原始密码不正确");
                }
            }
            if (!$form['newpass']) {
                $result['info']['newpass'] = "请输入新密码";
                throw new Exception("请输入新密码");
            }
            if (!$form['repeatpass']) {
                $result['info']['repeatpass'] = "请输入重复密码";
                throw new Exception("请输入重复密码");
            }
            if (trim($form['newpass']) != trim($form['repeatpass'])) {
                $result['info']['repeatpass'] = "两次密码输入不一样";
                throw new Exception("两次密码输入不一样");
            }
            $user->setPassword($form['newpass']);
            if ($user->save(false)) {
                return json_encode($result);
            } else {
                throw new Exception("更新密码失败");
            }

        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }

    /**
     * 重置昵称
     * @return false|string
     */
    public function actionResetnick()
    {
        $result = ['error' => 0, 'msg' => ''];
        try {
            if (Yii::$app->user->isGuest) {
                throw new Exception('你还没有登录');
            }
            //查看用户是否能够修改昵称
            $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
            if(empty($siteconfig->uchangenickname->val)){
                throw new Exception('当前设置不能修改昵称!');
            }
            $nickname = Yii::$app->request->post("newnickname");
            if (!$nickname) {
                throw new Exception("新昵称不能为空!");
            }
            /**修改昵称**/
            $uid=intval(Yii::$app->request->post('uid',0));
            if(empty($uid)){
                $user = Yii::$app->user->identity;
            }
            else{
                $user = User::findOne($uid);
            }
            
            $user->scenario = "update";
            $user->ncname = HtmlPurifier::process($nickname, ['HTML.Allowed' => '']);
            if (!$user->save()) {
                throw new Exception("设置失败");
            }
            return json_encode($result);

        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }

    /*
     * 获得直播配置
     */
    public function actionGetZhiboconfig(){
        $alias = Yii::$app->request->get("alias");
        if( empty($alias) || !in_array($alias,['about_course','about_zhibo','about_company','about_teacher'])){
            return "";
        }

        if($alias == 'about_course'){
            $roomid = Yii::$app->session->get("zhiboid");
        }
        else{
            $roomid = 1;
        }

        $zhibo = Zhibo::findOne($roomid);
        $html = "<div><style type='text/css'>*{margin:0px;padding:0px;}img{width:100%;}</style>";
        if ($zhibo) {
            return $html .= $zhibo->{$alias} . "</div>";
        } else {
            return $html."</div>";
        }
    }

    //彩条
    public function actionColorbar()
    {
        if (Yii::$app->request->isGet) {
            $model = Expression::find()->select(['id', 'address', 'name', 'prefix'])->where(['type' => 2, 'status' => 1])->all();
            if ($model) {
                $caitiao = array();
                foreach ($model as $val) {
                    $str = '<dl id="c_' . $val->prefix . '" class="clearfix " isface="2" pack="' . $val->id . '" style="display: block;">';
                    $getcol = include_once($_SERVER['DOCUMENT_ROOT'] . $val->address . '.php');
                    $number = 0;
                    foreach ($getcol as $k => $v) {
                        if ($number <= 5) {
                            $str .= '<dd onclick="sendCaitiao(' . "'" . $val->prefix . $v . "'" . ')">' . $v . '</dd>';
                            $vs = $val->address . '/' . $k;
                            $caitiao = array_merge($caitiao, array($val->prefix . $v => $vs));
                        }
                        $number++;
                    }
                    $str .= '</dl><div class="clearfix"></div><ul id="caitiaonav"><li rel="' . $val->prefix . '" class="active" isnav="1" id="caitiao_nav_' . $val->id . '">' . $val->name . '</li></ul>';

                }
                $tt = Yii::$app->request->get('caitiao');
                if ($tt == 'get') {
                    echo json_encode($caitiao);
                } else {
                    echo $str;
                }
            }
        }
    }

    /***加载公司客服,广告,弹窗等素材***/
    public function actionCompanyload()
    {
        $result = [
            'error' => 0,
            'data' => [
                'popwindows' => [],
                'customers' => []
            ],
            'msg' => ''
        ];
        try {
            $roomid = Yii::$app->session->get("zhiboid");
            /**加载弹窗**/
            $popwindows = Popwindow::find()->where(['in', 'type', [1, 2, 3, 4]])->andWhere(['zhiboid'=>$roomid,'status' => 1])->all();
            $result['data']['popwindows'] = ArrayHelper::index($popwindows, 'name');
            if ($popwindows) {
                $result['data']['popwindows'] = ArrayHelper::toArray($popwindows, [
                    'id',
                    'name',
                    'link',
                    'img',
                    'type',
                    'time' => function ($model) {
                        return abs($model->time);
                    },
                    'interval' => function ($model) {
                        return abs($model->interval);
                    },
                    'boffset',
                    'kfnum' => function ($model) {
                        return abs($model->kfnum);
                    },
                    'showkf',
                    'pwidth',
                    'pheight'
                ]);
            }
            /**加载客服**/
            $customers = CustomerService::find()->select(['name', 'account'])->where(['>=', 'endtime', date('H')])->andwhere(['zhiboid'=>$roomid,'status' => 1])->limit(8)->all();
            if ($customers) {
                foreach ($customers as $i => $customer) {
                    if(isMobile()){
                        $customer_url="http://wpa.qq.com/msgrd?v=3&uin={$customer->account}&site=在线咨询&menu=yes";
                        $customer_url="mqqwpa://im/chat?chat_type=wpa&uin={$customer->account}&version=1&src_type=web&web_src=oicqzone.com";
                    }
                    else{
                        $customer_url = "tencent://message/?uin={$customer->account}&Site=&menu=yes";
                    }
                    $result['data']['customers'][] = ['name' => $customer->name, 'account' => $customer->account, 'url' => $customer_url];
                }
            }
            return json_encode($result);
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }

    /**
     * 获取验证码
     */
    public function actionGetverycode()
    {
        Yii::$app->session->open();
        if (!$name = Yii::$app->request->get("name")) {
            $name = "verycode";
        }
        return Common::makeveryCode(4, 100, 36, $name);
        /**获得验证码**/
    }

    /**
     * 验证码
     * @return int
     */
    public function actionCodevery()
    {
        Yii::$app->session->open();
        $codename = Yii::$app->request->get('name');
        $code = Yii::$app->request->get('code');
        if ($codename && $code) {
            if (Yii::$app->session->get($codename) == trim($code)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
        /**验证验证码**/
    }

    /*
     * 获得手机验证码
     */
    public function actionGet_smscode(){
        $result=['error'=>0,'code'=>'','msg'=>''];
        try{
            $mobile=Yii::$app->request->post("mobile");
            $source=Yii::$app->request->post("source");
            if(empty($mobile)){
                throw new Exception("请输入手机号");
            }

            /*
             * 发送限制
             */
            $last_sms_sendtime=0;
            $last_sms_sendcount=0;
            if(!empty(Yii::$app->session->get("last_sms_sendtime"))){
               $last_sms_sendtime=intval(Yii::$app->session->get("last_sms_sendtime"));
               if(time()-$last_sms_sendtime<60){
                   throw new Exception("两次发送间隔不能低于60秒");
               }
            }

            if(!empty(Yii::$app->session->get("last_sms_sendcount"))){
                $last_sms_sendcount=intval(Yii::$app->session->get("last_sms_sendcount"));
                if($last_sms_sendcount>=20){
                    throw new Exception("你今日的短信发送次数已经超出了限制");
                }
            }


            $sms_result=Common::makesmsCode($mobile,6);
            $umobile=new Umobile();
            $umobile->mobile=$mobile;
            if(!empty($sms_result['error']) || empty($sms_result['code'])){
                if($source=="choujiang"){
                    $umobile->info="抽奖验证码发送失败";
                    $umobile->type=3;
                }
                else if($source=="register"){
                    $umobile->info="注册验证码发送失败";
                    $umobile->type=4;
                }
                else if($source=="platform_search"){
                    $umobile->info="平台查询验证码发送失败";
                    $umobile->type=7;
                }
                $umobile->time=date("Y-m-d H:i:s");
                $umobile->save();
                throw new Exception("验证码发送失败!");
            }

            /*
             * 记录发送时间和发送次数
             */
            $last_sms_sendtime=time();
            $last_sms_sendcount=$last_sms_sendcount+1;
            Yii::$app->session->set("last_sms_sendtime",time());
            Yii::$app->session->set("last_sms_sendcount",$last_sms_sendcount);

            if($source=="choujiang"){
                $umobile->info="抽奖验证码已发送";
                $umobile->type=5;
            }
            else if($source=="register"){
                $umobile->info="注册验证码已发送";
                $umobile->type=6;
            }
            else if($source=="platform_search"){
                $umobile->info="平台查询验证码已发送";
                $umobile->type=8;
            }
            $umobile->time=date("Y-m-d H:i:s");
            $umobile->save();



            $result['code']=$sms_result['code'];
            return json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
    }

    /*
     * 屏蔽undefined报错
     */
    public function actionUndefined(){
        return "";
    }

    /*
     *建仓提醒
     */
     public function actionShowJianCang(){

         if(Yii::$app->user->isGuest){
             return "<div style='font-size: 20px;line-height: 2em; font-weight:bold;color:red;text-align: center'>游客身份没有权限查看,请点击页面右上方进行注册!</div>";
         }
         /*else if(empty(Yii::$app->user->identity->teacher)) {
             return "<div style='font-size: 20px;line-height: 2em; font-weight:bold;color:red;text-align: center'>你还没有选择对应的老师,请联系对应的老师助理进行设定!</div>";
         }*/
         $identify=Yii::$app->user->identity;
         if(!empty($identify->room_role->lookup_singalservice->val)){
             //return Yii::$app->response->redirect("http://zhibo.hbgqyllh.com/ShoutListNew.asp?roo=7000");
         }
         else{
             return "<div style='font-size: 20px;line-height: 2em; font-weight:bold;color:red;text-align: center'>你当前的会员角色没有权限查看!</div>";
         }


         $teacher=intval(Yii::$app->user->identity->teacher);
         $searchModel = new ShoutedSearch;
         $query = Shouted::find();
         $query->where(["and",['status'=>1,'pingprice' =>'','pingtime' => null/*,'postname'=>$teacher*/]])->orderBy(['id'=>SORT_DESC]);
         $dataProvider = new ActiveDataProvider([
             'query' => $query,
         ]);

         return $this->render('show_jiancang', [
             'dataProvider' => $dataProvider,
             'searchModel' => $searchModel,
         ]);
     }

    /*
    *平仓提醒
    */
    public function actionShowPingCang(){
        $searchModel = new ShoutedSearch;
        $query = Shouted::find();
        $query->where(["and",['status'=>1],['not', ['pingprice' => "",'pingtime' => null]]])->orderBy(['id'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('show_pingcang', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /*
     * 查看是否有抽奖
     */
    public function actionGetLuckDraw(){
        $result=['error'=>0,'msg'=>''];
        try{
            $mobile=Yii::$app->request->get("mobile");
            $code=Yii::$app->request->get("code");
            if(empty($mobile)){
                throw new Exception("手机号不能为空");
            }

            if(trim($code)!=Yii::$app->session->get("smscode")){
                throw new Exception("验证码不正确");
            }

            $if_exist=Umobile::find()->where(['mobile'=>$mobile,'type'=>1])->count();
            if($if_exist){
                throw new Exception("您已经抽过奖了!");
            }
        }
        catch (Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
        }
        return json_encode($result);
    }

    /*
     * 登记手机号码
     */
    public function actionRegTel(){
        $result=['error'=>0,'msg'=>''];
        try{
            $mobile=Yii::$app->request->get("mobile");
            $info=Yii::$app->request->get("info");
            if(empty($mobile)){
                throw new Exception("手机号不能为空");
            }
            $if_exist=Umobile::find()->where(['mobile'=>$mobile,'type'=>1])->count();
            if($if_exist){
                throw new Exception("手机号码已存在!");
            }

            $umobile=new Umobile();
            $umobile->mobile=$mobile;
            $umobile->type=1;
            $umobile->info=$info;
            $umobile->time=date("Y-m-d H:i:s");
            $umobile->save();
            return json_encode($result);
        }
        catch (Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
        }
        return json_encode($result);
    }

    /*
     * 显示文章内容
     */
    public function actionArticleDetail(){
        $id=Yii::$app->request->get("id");
        $article=Article::findOne($id);
        if($article){
            $guest_role = RoomRole::getConfigbyalias("guest");
            $current_role = "";
            if (Yii::$app->user->isGuest) {
                $current_role = $guest_role;
            } else if (!empty(Yii::$app->user->identity->room_role)) {
                $current_role = Yii::$app->user->identity->room_role;
            } else {
                $current_role = $guest_role;
            }

            /**检查权限**/
            $article_type=$article->type;
            $type_roles_array=!empty($article_type->role)?unserialize($article_type->role):[];

            if(!empty($type_roles_array) && Yii::$app->user->isGuest && !in_array($guest_role->id,$type_roles_array)){
                return "<h2 style='text-align: center;color:red;margin-top: 50px'>请点击右上方登录</h2>";
                /*如果客户是游客并且*/
            }
            else if(!empty($type_roles_array) && !in_array($current_role->id,$type_roles_array)){
                return "<h2 style='text-align: center;color:red;margin-top: 50px'>没有权限查看,请点击下方高级助理进行获取!</h2>";
            }
            else{
                return $this->render("article_detail",["article"=>$article]);
            }
        }
        else{
            return "not exist!";
        }

    }

    /*
     * 文章列表
     */
    public function actionListType(){
        $typeid=Yii::$app->request->get('id');
        $article_type=ArticleType::findOne($typeid);
        if($article_type){
            $query = Article::find()->where(['typeid'=>$typeid]);
            $pagination = new  \yii\data\Pagination(['defaultPageSize' => 2,
                'totalCount' => $query->count(),]);
            $articles = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            return $this->render('article_list', [
                'article_type'=>$article_type,
                'articles' => $articles,
                'pagination' => $pagination,
            ]);
        }
        else{
            return "type not exist";
        }
    }

    /*
     * 平台查询
     */
    public function actionPlatformSearch(){
        return $this->renderPartial('platform_search');
    }

    /*
     * 讲师榜
     */
    public function actionTeacherRank(){
        $roomid = Yii::$app->session->get("zhiboid");
        $teachers=\backend\models\ShoutedTeacher::find()->where(['if_zan'=>1/*,'zhiboid'=>$roomid*/])->orderBy(['zan_count'=>SORT_DESC])->asArray()->all();
        $zan_count=0;
        $teachers_array=[];
        foreach($teachers as $i=>$item){
            $zan_count += $item['zan_count'];
        }

        foreach($teachers as $i=>$item){
            $item['percent'] = ($zan_count ? round(intval($item['zan_count'])/ $zan_count,3) * 100 : 0)."%" ;
            $teachers_array[] = $item;
        }

        return $this->renderPartial('teacher_rank',['teachers'=>$teachers_array]);
    }

    /*
     * 讲师点赞操作
     */
    public function actionDoTvote(){
        $result=['error'=>0,'zancount'=>0,'msg'=>''];
        try{
            $uid= intval(Yii::$app->user->id);
            $temp_name=Yii::$app->request->cookies->getValue("temp_name");
            $tid=intval(Yii::$app->request->post("tid"));
            $teacher=\backend\models\ShoutedTeacher::find()->where(['id'=>$tid])->one();

            throw new Exception("本轮投票已经截止!");

            if(empty($uid) && empty($temp_name)){
                $temp_name = "游客-" . Onlineuser::getuniqname(5);
                $expire = time() + 86400 * 30 * 12;
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'temp_name',
                    'value' => $temp_name,
                    'expire' => $expire,
                    'path' => '/'
                ]));
            }
            if(empty($teacher)){
                throw new Exception("系统错误");
            }

            $if_zan=0;
            $if_zan_unit = 1; /* 1代表日,7代表星期,30代表月,365代表年 */
            $if_zan_unitstr = ($if_zan_unit==1) ? "日" : ($if_zan_unit==7 ? "星期" : ($if_zan_unit==30 ? "月" : ($if_zan_unit==365 ? "年" : "未知")));
            $if_zan_time = "";
            $if_zan_limit = 1;
            if(!empty($uid)){
                $if_zan_query = \backend\models\Teacherzan::find()->where(['tid'=>$tid,'uid'=>$uid]);
            }
            else if(!empty($temp_name)){
                $if_zan_query = \backend\models\Teacherzan::find()->where(['and',['tid'=>$tid],['like','name',$temp_name]]);
            }

            if($if_zan_unit == 1){
                $if_zan_time = date("Y-m-d");
            }
            else if($if_zan_unit == 7){
                $if_zan_time = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
            }
            else if($if_zan_unit == 30){
                $if_zan_time = date("Y-m-01 00:00:00");
            }
            else if($if_zan_unit == 365){
                $if_zan_time = date("Y-01-01 00:00:00");
            }

            $if_zan_query->andWhere([">","time",$if_zan_time]);

            $if_zan = $if_zan_query->count();

            if($if_zan >= $if_zan_limit){
                throw new Exception("你本{$if_zan_unitstr}已经赞过 {$if_zan_limit} 次了呦!");
            }


            $zan=new \backend\models\Teacherzan();
            $zan->uid = $uid;
            $zan->name = $uid ? "" : $temp_name;
            $zan->tid = $tid;
            $zan->time = date("Y-m-d H:i:s",time());

            $teacher->zan_count = $teacher->zan_count + 1;

            if(!($zan->save() && $teacher->save())){
                throw new Exception("操作失败!");
            }

            $result['zancount'] = $teacher->zan_count;
        }
        catch(Exception $e){
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
        }
        return json_encode($result);
    }



    /*
     * 获得点赞数据
     */
    public function actionGetTvoteData(){
        $result=['error'=>0,'data'=>[],'msg'=>''];
        try{
            $roomid = Yii::$app->session->get("zhiboid");
            $roomid = 1;
            $teachers=\backend\models\ShoutedTeacher::find()->where(['if_zan'=>1,'zhiboid'=>$roomid])->asArray()->all();
            $zan_count=0;
            $teachers_array=[];
            foreach($teachers as $i=>$item){
                $zan_count += $item['zan_count'];
            }

            foreach($teachers as $i=>$item){
                $item['percent'] = ($zan_count ? round(intval($item['zan_count'])/ $zan_count,3) * 100 : 0)."%" ;
                $teachers_array[] = $item;
            }
            $result['data'] = $teachers_array;

        }
        catch(Exception $e){
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
        }
        return json_encode($result);
    }

    /*
     * 二维码
     */
    public function actionShowqrcode(){
        header('Content-Type: image/png');
        //$data = urldecode(Yii::$app->request->get("data"));
        $data = Yii::$app->request->get("data");
        if(empty($data)){
            $data= Yii::$app->urlmanager->createAbsoluteUrl(['site/index']);
        }
        //return $data;
        //定义纠错级别
        $errorLevel = "L";
        //定义生成图片宽度和高度;默认为3
        $size = "4";
        //文件名称
        \QRcode::png($data, false, $errorLevel, $size);
        exit;
    }

    /*
     * 房间密码
     */
    public function actionPassverify(){
        $roomid = Yii::$app->session->get("zhiboid");
        $zhibo = Zhibo::findOne($roomid);
        if(empty($zhibo)){
            return false;
        }
        if(!empty($_POST['password'])){
            $password=strval(trim($_POST['password']));
            if($password !=$zhibo->password){
                return "<script type='text/javascript'>alert('密码错误!');window.location.href='/site/passverify';</script>";
            }
            else{
                Yii::$app->session->set("passverify_{$roomid}_val",$password);
                return $this->redirect(['site/index','room'=>$roomid]);
            }
        }

        return $this->renderPartial('passverify',['roomconfig'=>$zhibo]);
    }

    /*
     * 外部文件访问
     */
    public function actionQqouter(){
        return $this->renderPartial('outer');
    }

    /*
      * 加载authiframe框
      */
    public function actionLoad_iframe(){
        $iframename=Yii::$app->request->get("name");
        if(!in_array($iframename,['login','reg','resetnick','resetpass','changephoto','zhibocontrol'])){
            return "";
        }
        return $this->renderPartial('authiframe',['iframename'=>$iframename]);
    }

    /*
     * 添加子账号
     */
    public function actionAddchilduser(){
        $result = ['error'=>0,'msg'=>''];
        try{
            if (Yii::$app->user->isGuest) {
                throw new Exception("请登录");
            }

            $roomid = Yii::$app->user->identity->zhiboid;
            $uid= intval(Yii::$app->user->id);

            $current_role = Yii::$app->user->identity->room_role;

            if(empty($current_role) || !$current_role->isAdmin){
                throw new Exception("没有权限");
            }

            $childrenuser=Yii::$app->request->post("children",[]);
            foreach($childrenuser as $i=>$child){
                $nickname=$child['nickname'];
                $roleid=$child['roleid'];
                $user = new User();
                $user->username = base64_encode($nickname);
                $user->email = base64_encode($nickname)."@yuli.cn";
                $user->ncname = $nickname;
                $user->mobile = "virtual _" . base64_encode($nickname);
                $user->roomrole = $roleid;//普通房间角色
                $user->role = "";//rbac无
                $user->password = "virtual_pass";
                $user->password2 = "virtual_pass";
                $user->password_hash = "virtual_hash";
                $user->parentid = $uid;
                $user->zhiboid = $roomid;
                $user->status = 1;
                if(!$user->save()){
                    throw new Exception("添加失败!");
                }
            }

            return "<script type='text/javascript'>alert('操作成功!');parent.location.reload();</script>";
        }
        catch(Exception $e){
            return "<script type='text/javascript'>alert('".$e->getMessage()."');window.history.back();</script>";
        }
    }

    /*
     * 添加子账号
    */
    public function actionSwitchteacher(){
        $result = ['error'=>0,'msg'=>''];
        try{
            if (Yii::$app->user->isGuest) {
                throw new Exception("请登录");
            }

            $roomid = Yii::$app->user->identity->zhiboid;
            $uid= intval(Yii::$app->user->id);

            $current_role = Yii::$app->user->identity->room_role;
            if(empty($current_role) || !$current_role->isAdmin){
                throw new Exception("没有权限");
            }

            $current_tid = Yii::$app->request->post("current_tid",0);

            Yii::$app->db->createCommand()->update('shouted_teacher', ['if_current' => 0],['zhiboid'=>$roomid])->execute();

            $teacher = \backend\models\ShoutedTeacher::findOne($current_tid);

            if($teacher){
                $teacher->if_current=1;
                if(!$teacher->save()){
                    throw new Exception("操作失败!");
                }
            }

            return "<script type='text/javascript'>alert('操作成功!');window.history.back();</script>";

        }
        catch(Exception $e){
            return "<script type='text/javascript'>alert('".$e->getMessage()."');window.history.back();</script>";
        }
    }

}
