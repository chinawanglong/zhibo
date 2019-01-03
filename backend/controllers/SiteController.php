<?php
namespace backend\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\components\Common;
use backend\models\LoginForm;
use backend\models\SignupForm;
use backend\models\ConfigCategory;
use backend\models\ConfigItems;
use backend\models\Oprecord;
use backend\models\RoomRole;
use backend\models\User;
use backend\models\Onlineuser;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','signup'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','test','upload','kupload','changeurl'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['config'],
                        'allow' => true,
                        'roles' => ['topadmin'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'kupload'=>[
                'class'=>'backend\widgets\kindeditor\KindEditorAction'
            ]
        ];
    }
    public function beforeAction($action){
        if($action->id=="upload"){
            $this->enableCsrfValidation=false;
        }
        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        $roomid = Yii::$app->session->get("zhiboid");

        $usercount=User::find()->where(['like','zhiboid',$roomid])->count();
        $usercount_today=User::find()->where("created_at>".strtotime("today"))->andWhere(['like','zhiboid',$roomid])->count();


        if($roomid == 1){
            $onlinecount=Onlineuser::find()->select("fd")->distinct()->count();
        }
        else{
            $onlinecount=Onlineuser::find()->select("fd")->where(['zhiboid'=>$roomid])->distinct()->count();
        }

        $viproles=RoomRole::find()->select('child.*')->from("config_category as child,config_category as parent")->Where('parent.id=child.parentid ')->andWhere(['like','parent.alias','vip'])->union(RoomRole::find()->where(['like','alias','vip']))->all();
        $companyroles=RoomRole::find()->select('child.*')->from("config_category as child,config_category as parent")->Where('parent.id=child.parentid')->andWhere(['like','parent.alias','company'])->union(RoomRole::find()->where(['like','alias','company']))->all();
        $vip_data=[];
        $company_data=[];
        if($viproles){
            foreach($viproles as $i=>$role){
                $count=User::find()->where(['roomrole'=>$role->id])->count();
                $data=['label'=>$role->name,'data'=>$count];
                $vip_data[]=$data;
            }
        }

        if($companyroles){
            foreach($companyroles as $i=>$role){
                $count=User::find()->where(['roomrole'=>$role->id])->count();
                $data=['label'=>$role->name,'data'=>$count];
                $company_data[]=$data;
            }
        }

        return $this->render('index',[
              'allusercount'=>$usercount,
              'usercount_today'=>$usercount_today,
              'onlinecount'=>$onlinecount,
              'vipcountdata'=>$vip_data,
              'companycountdata'=>$company_data
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $siteconfig=ConfigCategory::getConfigbyalias('siteconfig');
        if ($model->load(Yii::$app->request->post())&& $model->login()) {

            if(!empty($siteconfig->oprecord->val)){
                $oprecord=new Oprecord();
                $oprecord->uid=Yii::$app->user->identity->id;
                $oprecord->ip=Yii::$app->request->userIP;
                $oprecord->logintime=date('Y-m-d H:i:s',time());
                $oprecord->save();
            }
            return $this->goBack();
        } else {
            return $this->renderPartial('sblogin', [
                'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->goHome();
    }

    public function actionSignup()
    {
        header("Content-type:text/html;charset=utf8");
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
                else{

                }
            }
            else{
                exit($model->errors);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    public function actionUpload(){
        $result=array('error'=>0,'msg'=>'','src'=>'','url'=>'');
        try{
            if(!empty($_FILES)){
                foreach($_FILES as $name=>$data){
                    $dir=Yii::$app->request->get('dir',"");
                    if(!$dir){
                        $dir="others";
                    }

                    if(Yii::$app->request->get('toback',0)){
                        if (!is_dir(Yii::getAlias("@webroot/uploads/{$dir}"))) {
                            mkdir(Yii::getAlias("@webroot/uploads/{$dir}"), 0777);
                        }
                        $save_result=Yii::$app->upload->upload(Yii::getAlias("@webroot/uploads/{$dir}"),Yii::getAlias("@web/uploads/{$dir}"),$name);
                        /*如果要存到后台*/
                    }
                    else{
                        $saveroot=str_replace("backend","frontend",Yii::getAlias("@webroot/uploads/{$dir}"));
                        if (!is_dir($saveroot)) {
                            mkdir($saveroot, 0777);
                        }
                        $urlroot=Yii::$app->furlManager->hostInfo.Yii::$app->furlManager->baseUrl."/uploads/{$dir}";
                        $save_result=Yii::$app->upload->upload($saveroot,$urlroot,$name);
                    }
                    if(!$save_result['error']){
                        $result['src'] =$save_result['src'];
                        $result['url'] =$save_result['src'];
                    }
                    else{
                        throw new Exception(print_r($save_result,true));
                    }
                }
            }
            else{
                throw new Exception("你没有选择任何文件上传!");
            }
            echo json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            echo  json_encode($result);
        }
    }
   /*
    *网站配置
    */
    public function actionConfig(){
        $model=\backend\models\ConfigCategory::getConfig('网站配置');
        $errors="";
        if(!$model){
            exit("配置分类不存在");
        }

        if(Yii::$app->request->isPost&&Yii::$app->request->post("config",null)){
              $config_data=Yii::$app->request->post("config",[]);
              foreach(Yii::$app->request->post("config") as $itemname=>$val){
                  $item=$model->{$itemname};
                  if(!empty($item)){
                      $item->val=$val;
                      if(!$item->save()){
                          $errors.=$model->{$itemname}->errors."<br/>";
                      }
                  }
              }
              $check_vals=["oprecord","open_signup",'check_nickname',"multiplelogin","logwhenadmin","uchangenickname","homepage_withroom"];

              foreach($check_vals as $name){
                   if(!empty($model->{$name})){
                       $item=$model->{$name};
                       if(!empty($config_data[$name])){
                           $item->val="1";
                           if(!$item->save()){
                               $errors.=$item->errors."</br>";
                           }
                       }
                       else{
                           $item->val="0";
                           if(!$item->save()){
                               $errors.=$item->errors."</br>";
                           }
                       }
                   }
                   /**循环设置**/
              }
        }

        return $this->render("siteconfig",['model'=>$model,'errors'=>$errors]);
    }

    public function actionChangeurl(){
        $pre_url="http://zhibo.hdtv168.com";
        $current_url=Yii::$app->furlManager->hostInfo;
        $items=\backend\models\Zhibo::find()->where(['like','logo',"{$pre_url}"])->all();
        foreach ($items as $item){
            $item->logo=str_replace($pre_url,$current_url,$item->logo);
            $item->save();
        }
        $items=\backend\models\ConfigItems::find()->where(['like','val',"{$pre_url}"])->all();
        foreach ($items as $item){
            $item->val=str_replace($pre_url,$current_url,$item->val);
            $item->save();
        }
        $items=\backend\models\Image::find()->where(['like','address',"{$pre_url}"])->all();
        foreach ($items as $item){
            $item->address=str_replace($pre_url,$current_url,$item->address);
            $item->save();
        }
    }
}

