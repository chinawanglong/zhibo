<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-2
 * Time: 上午10:09
 */
namespace backend\controllers;

use backend\models\ConfigItems;
use backend\models\RoomRole;
use backend\models\RoomRoleSearch;
use Yii;
use backend\models\ConfigCategory;
use backend\models\ConfigCategorySearch;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\components\Upload;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
/**
 * ConfigCategoryController implements the CRUD actions for ConfigCategory model.
 */
class RoomRoleController extends Controller{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['topadmin'],
                    ],
                    [
                        'actions' => ['getall','update','addone'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex(){



        $guestmodel=RoomRoleSearch::find()->where(['alias'=>'guest']);
        $vipmodel=RoomRoleSearch::find()->where(['alias'=>'vip']);
        $companymodel=RoomRoleSearch::find()->where(['alias'=>'company']);
        $searchModel2 = new RoomRoleSearch();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->getQueryParams(),"vip");
        $dataProvider2->pagination->pageParam = 'vip-page';
        $dataProvider2->sort->sortParam = 'vip-sort';

        $searchModel3 = new RoomRoleSearch();
        $dataProvider3 = $searchModel3->search(Yii::$app->request->getQueryParams(),'company');
        $dataProvider3->pagination->pageParam = 'company-page';
        $dataProvider3->sort->sortParam = 'company-sort';
        //权限总览
        $query1=RoomRoleSearch::find()->where(['alias'=>'guest']);;
        $query2=$vipmodel;
        $query3=$companymodel;
        $query2_1=$searchModel2->searchquery;
        $query3_1=$searchModel3->searchquery;
        $query1->union($query2);
        $query1->union($query2_1);
        $query1->union($query3);
        $query1->union($query3_1);

        $allroles_dataprovider=Yii::$app->db->cache(function($db) use($query1){
            return  $query1->all();
        });

        $allroomroleinfo=$this->renderPartial('list_roomrole',['allroles'=>$allroles_dataprovider]);

        return $this->render('index', [
            'guestmodel' => $guestmodel->one(),
            'vipmodel' => $vipmodel->one(),
            'companymodel' => $companymodel->one(),
            'dataProvider2' => $dataProvider2,
            'searchModel2' => $searchModel2,
            'dataProvider3' => $dataProvider3,
            'searchModel3' => $searchModel3,
            'allroomroleinfo'=>$allroomroleinfo
        ]);
    }
    public function actionUpdate(){
        /**142**/
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $role_data=[];
        try{

            if(!empty($post_data['guest'])){
                $role_data=$post_data['guest'];
                $role=RoomRole::findOne($role_data['id']);
                $int_vals=["publish_chat_time","color_interval",'watch_live','enable_publish_chat','speeking_check','see_online_num','can_upload_img'];
                $string_vals=['role_pic'];
                $all_vals=ArrayHelper::merge($int_vals,$string_vals);
                foreach($all_vals as $attribute_name){
                    $role_attribute=$role->{$attribute_name};
                    if(in_array($attribute_name,$int_vals)){
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    else if(in_array($attribute_name,$string_vals)){
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    else{
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    if(!$role_attribute || ($role_attribute && $role_attribute->categoryid!=$role->id)){
                        $role_attribute=new ConfigItems();
                        $role_attribute->name=$attribute_name;
                        $role_attribute->zh_name=$attribute_name;
                        $role_attribute->desc=$attribute_name;
                        $role_attribute->categoryid=$role->id;
                        $role_attribute->val=$attribute_val;
                        $role_attribute->status=1;
                        if(!$role_attribute->save()){
                            throw new ErrorException(print_r($role_attribute->errors,true));
                        }
                    }
                    else{
                        $role_attribute->val=$attribute_val;
                        if(!$role_attribute->save()){
                            throw new ErrorException(print_r($role_attribute->errors,true));
                        }
                    }

                }
            }
            else if(!empty($post_data['vip'])){
                $role_data=$post_data['vip'];
                $role=RoomRole::findOne($role_data['id']);
                $int_vals=['watch_live','enable_publish_chat','private_chat','publish_chat_time',"lookup_singalservice",'speeking_check','see_online_num','can_upload_img','able_speaking','prevent_shot_off_room'];
                $string_vals=['role_pic'];
                $all_vals=ArrayHelper::merge($int_vals,$string_vals);
                foreach($all_vals as $attribute_name){
                    $role_attribute=$role->{$attribute_name};
                    if(in_array($attribute_name,$int_vals)){
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    else if(in_array($attribute_name,$string_vals)){
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    else{
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    if(!$role_attribute || ($role_attribute && $role_attribute->categoryid!=$role->id)){
                        $role_attribute=new ConfigItems();
                        $role_attribute->name=$attribute_name;
                        $role_attribute->zh_name=$attribute_name;
                        $role_attribute->desc=$attribute_name;
                        $role_attribute->categoryid=$role->id;
                        $role_attribute->val=$attribute_val;
                        $role_attribute->status=1;
                        if(!$role_attribute->save()){
                            throw new ErrorException(print_r($role_attribute->errors,true));
                        }
                    }
                    else{
                        $role_attribute->val=$attribute_val;
                        if(!$role_attribute->save()){
                            throw new ErrorException(print_r($role_attribute->errors,true));
                        }
                    }
                    /**foreach循环**/
                }
                //如果是更新用户名字
                $role->name=!empty($role_data['name'])?$role_data['name']:"";
                $role->save();
            }
            else if(!empty($post_data['company'])){
                $role_data=$post_data['company'];
                $role=RoomRole::findOne($role_data['id']);
                $int_vals=["check_msg","delete_msg","shot_off_room","unable_speaking","addblack","publish_chat_time","color_interval","private_chat",'watch_live',
                    'enable_publish_chat',"lookup_singalservice",'speeking_check','see_online_num','can_upload_img','feiping'];
                $string_vals=['role_pic'];
                $all_vals=ArrayHelper::merge($int_vals,$string_vals);
                foreach($all_vals as $attribute_name){

                    $role_attribute=$role->{$attribute_name};
                    if(in_array($attribute_name,$int_vals)){
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    else if(in_array($attribute_name,$string_vals)){
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    else{
                        $attribute_val=ArrayHelper::getValue($role_data,$attribute_name,"");
                    }
                    if(!$role_attribute || ($role_attribute && $role_attribute->categoryid!=$role->id)){
                        $role_attribute=new ConfigItems();
                        $role_attribute->name=$attribute_name;
                        $role_attribute->zh_name=$attribute_name;
                        $role_attribute->desc=$attribute_name;
                        $role_attribute->categoryid=$role->id;
                        $role_attribute->val=$attribute_val;
                        $role_attribute->status=1;
                        if(!$role_attribute->save()){
                            throw new ErrorException(print_r($role_attribute->errors,true));
                        }
                    }
                    else{
                        $role_attribute->val=$attribute_val;
                        if(!$role_attribute->save()){
                            throw new ErrorException(print_r($role_attribute->errors,true));
                        }
                    }
                    /**foreach**/
                }
                //如果是更新用户名字
                $role->name=!empty($role_data['name'])?$role_data['name']:"";
                $role->save();
            }
            return json_encode($result);
        }
        catch(ErrorException $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
    }

    /****添加一个角色****/
    public function  actionAddone(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $role_data=[];
        try{


            if(!empty($post_data['role'])){
                $role_data=$post_data['role'];
                $role=new RoomRole();
                $parentrole="";
                if(!$role_data['name']){
                    throw new ErrorException("角色名称不能为空");
                }
                if(!$role_data['alias']){
                    throw new ErrorException("角色别名不能为空");
                }
                if(!$role_data['auth']['role_pic']){
                    throw new ErrorException("角色图片不能为空");
                }
                $role->name=$role_data['name'];
                $role->alias=$role_data['alias'];


                if(!empty($role_data['parentid'])){
                    $role->parentid=$role_data['parentid'];
                    $parentrole=RoomRole::findOne($role->parentid);
                }
                else{
                    $parentrole=RoomRole::findOne(['alias'=>'room_role']);
                    $role->parentid=$parentrole->id;
                }

                if(!$role->save()){
                    throw new ErrorException(print_r($role->errors,true));
                }

                /****存储权限信息****/
                $check_vals=array(
                    'watch_live','see_online_num','enable_publish_chat','private_chat','speeking_check','lookup_singalservice','prevent_shot_off_room','able_speaking',
                    'unable_speaking','addblack','shot_off_room','can_upload_img','check_msg','delete_msg');
                $str_vals=array('role_pic','publish_chat_time','color_interval');
                if(!empty($role_data['auth'])){
                    foreach($role_data['auth'] as $authname=>$authval){
                          $attribute_name=$authname;
                          $attribute_desc=!empty($parentrole->{$authname})?$parentrole->{$authname}->desc:$authname;
                          if(in_array($authname,$check_vals)){
                              $attribute_val=$authval>0?"1":"0";
                          }
                          else if(in_array($authname,$str_vals)){
                              $attribute_val=$authval?$authval:"";
                          }
                          else{
                              $attribute_val=$authval;
                          }
                          $role_attribute=new ConfigItems();
                          $role_attribute->name=$attribute_name;
                          $role_attribute->zh_name=$attribute_desc;
                          $role_attribute->desc=$attribute_desc;
                          $role_attribute->categoryid=$role->id;
                          $role_attribute->val=$attribute_val;
                          $role_attribute->status=1;
                          if(!$role_attribute->save()){
                              throw new ErrorException(print_r($role_attribute->errors,true));
                          }
                    }
                    /**如果存在角色权限信息**/
                }
                /**如果存在角色信息**/
            }
            else{
                throw new ErrorException("数据不能为空！");
            }
            return json_encode($result);
        }
        catch(ErrorException $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
        /*******/
    }
}