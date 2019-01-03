<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-21
 * Time: 下午4:32
 */
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use backend\models\RbacModel;
use backend\models\RbacChild;
use backend\models\RbacAssignment;

/**
 * Site controller
 */
class RbacController extends Controller{

    public $auth;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['topadmin'],
                    ]
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        $this->auth=Yii::$app->authManager;
        return parent::beforeAction($action);
    }
    public function actionManage(){
        $auth=$this->auth;
        $auth_data=[];
        $all_roles=$auth->getRoles();
        foreach($all_roles as $i=>$role){
            $query = RbacAssignment::find()->where(['item_name'=>$role->name]);
            $dataProvider = new ActiveDataProvider([
                'pagination' => [
                    'pagesize' => '15',
                ],
                'query' => $query,
            ]);
            $dataProvider->pagination->pageParam = $role->name.'-page';
            $dataProvider->sort->sortParam = $role->name.'-sort';
            $role_item=['role'=>$role,'permissions'=>[],'assigns'=>$dataProvider];
            $role_item['permissions']=$auth->getPermissionsByRole($role->name);
            $auth_data[]=$role_item;
        }

        return $this->render("manage",['roles'=>$auth_data,'syspermissions'=>$auth->getPermissions(),"sysrules"=>$auth->getRules()]);
    }
    /**
     *
     * @return json*
     */
    public function actionAddrole(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $role_data=[];
        $auth=$this->auth;
        try{
            if(empty($post_data['role'])|| !is_array($post_data['role']) || count($post_data['role'])==0){
                throw new Exception("提交的数据不能为空!");
            }
            else{
                $role_data=$post_data['role'];
                $role_name=$role_data['name'];
                $role_desc=$role_data['description'];
                $role_permissions=!empty($role_data['permissions'])?$role_data['permissions']:"";
                $parent_role="";
                if(!$role_name){
                    throw new Exception("角色名不能为空!");
                }
                if(!$role_desc){
                    throw new Exception("角色描述不能为空!");
                }
                if(!$auth->getRole($role_name)){
                    $role=$auth->createRole($role_name);
                    $role->description=$role_desc;
                    $auth->add($role);
                    if(!empty($role_data['permissions'])){
                         foreach($role_data['permissions'] as $i=>$permissionname){
                             if($auth->getPermission($permissionname)){
                                 $permission=$auth->getPermission($permissionname);
                                 $auth->addChild($role,$permission);
                             }
                         }
                        /**若附加了permission**/
                    }
                }
                else{
                    throw new Exception("角色已存在");
                }

                if(!empty($role_permissions)&&is_array($role_permissions)&&count($role_permissions)>0){
                    foreach($role_permissions as $i=>$name){
                        if(!$name){
                            continue;
                        }
                        if(!$permission=$auth->getPermission($name)){
                            throw new Exception("$name 权限不存在!");
                        }
                        if(!$auth->hasChild($role,$permission)){
                            $auth->addChild($role,$permission);
                        }
                    }
                }

                if(!empty($role_data['parentname'])){
                    if(!($parent_role=$auth->getRole($role_data['parentname']))){
                        $auth->remove($role);
                        throw new Exception("父角色不存在!");
                    }
                    else{
                        $auth->addChild($parent_role,$role);
                    }
                }

                return json_encode($result);
            }
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
        /**addrole**/
    }

    /*
     * 添加权限
     * return json
     */
    public function actionAddpermission(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $auth=$this->auth;
        $permission_data=[];
        try{
            if(empty($post_data['permission'])|| !is_array($post_data['permission']) || count($post_data['permission'])==0){
                throw new Exception("提交的数据不能为空!");
            }
            else{
                $permission_data=$post_data['permission'];
                $permission_name=$permission_data['name'];
                $permission_description=!empty($permission_data['description'])?$permission_data['description']:"";
                $role_name=!empty($permission_data['rolename'])?$permission_data['rolename']:"";
                if(!$permission_name&&(empty($permission_data['others']) || !is_array($permission_data['others']) || count($permission_data['others'])<=0)){
                      throw new Exception("没有提供要添加的任何权限!");
                }
                if($permission_name){
                    if(!$auth->getPermission($permission_name)){
                        $permission=$auth->createPermission($permission_name);
                        $permission->description=$permission_description;
                        $data=['category'=>''];
                        if(!empty($permission_data['category'])){
                             $data['category']=$permission_data['category'];
                        }
                        $permission->data=json_encode($data);
                        if(!empty($permission_data['rule'])&&$auth->getRule($permission_data['rule'])){
                            $permission->ruleName=$permission_data['rule'];
                        }
                        $auth->add($permission);
                    }
                    else{
                        throw new Exception("权限已经存在");
                    }
                    /**如果存在权限名**/
                }
                if($role_name){
                    $role=$auth->getRole($role_name);
                    if(!empty($permission)){
                        $auth->addChild($role,$permission);
                    }
                    if(!empty($permission_data['others'])&&is_array($permission_data['others'])&&count($permission_data['others'])>0){
                          foreach($permission_data['others'] as $i=>$name){
                              if(!$name){
                                  continue;
                              }
                              if(!$permission=$auth->getPermission($name)){
                                  throw new Exception("$name 权限不存在!");
                              }
                              if(!$auth->hasChild($role,$permission)){
                                  $auth->addChild($role,$permission);
                              }
                          }
                    }
                }
                if(empty($permission)){
                    throw new Exception("没有添加任何权限!");
                }
                return json_encode($result);
            }
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
        /**addpermission**/
    }
    /*
     * 添加权限
     * return json
     */
    public function actionAddrule(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $auth=$this->auth;
        $permission_data=[];
        try{
            if(empty($post_data['rule'])|| !is_array($post_data['rule']) || count($post_data['rule'])==0){
                throw new Exception("提交的数据不能为空!");
            }
            else{
                $rule_data=$post_data['rule'];
                $rule_class=$rule_data['class'];
                if($rule_class){
                    if(class_exists($rule_class)){
                        $rule=new $rule_class();
                        if(!$rule->name || !method_exists($rule,"execute")){
                            throw new Exception("规则类不符合定义!");
                        }
                        if(!$auth->getRule($rule->name)){
                            $auth->add($rule);
                        }
                    }
                    else{
                        throw new Exception("规则类不存在!");
                    }
                }

                if(!empty($rule_data['permission'])&&$rule){
                    $permission_name=$rule_data['permission'];
                    if($permission=$auth->getPermission($permission_name)){
                        $permission->ruleName=$rule->name;
                        $auth->update($permission->name,$permission);
                    }
                    else{
                        throw new Exception("权限不存在!");
                    }
                    /**如果存在权限名**/
                }
                if(!$rule){
                    throw new Exception("你没有添加任何规则");
                }
                return json_encode($result);
            }
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
        /**addpermission**/
    }
    /*
     *删除权限，提供权限名，执行时判断是否有删除权限的权限
     */
    public function actionDeletepermission(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $auth=$this->auth;
        $permission_data=[];
        try{
            if(empty($post_data['data'])){
                throw new Exception("权限信息不能为空!");
            }
            else{
                $pdata=preg_split("/_/",$post_data['data']);
                $rolename=trim($pdata[0]);
                $permissionname=trim($pdata[1]);

                if(!$permission=$auth->getPermission($permissionname)){
                    throw new Exception("权限不存在!");
                }
                if($rolename=='systemtype'){
                    $auth->remove($permission);
                    /*如果操作的是系统权限*/
                }
                else{
                    if(!$role=$auth->getRole($rolename)){
                        throw new Exception("角色不存在!");
                    }
                    if(!$auth->hasChild($role,$permission)){
                        throw new Exception($role->name."角色没有声明该权限!");
                    }
                    $auth->removeChild($role,$permission);
                }
                /*如果信息存在*/
            }
            return json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
    }

    /*
     *删除权限，提供权限名，执行时判断是否有删除权限的权限
     */
    public function actionDeleterole(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $auth=$this->auth;
        try{
            if(empty($post_data['data'])){
                throw new Exception("角色信息不能为空!");
            }
            else{
                $rolename=trim($post_data['data']);
                if(!$role=$auth->getRole($rolename)){
                    throw new Exception("角色不存在!");
                }
                $auth->remove($role);
            }
            return json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
    }
    public function actionDeleterule(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $auth=$this->auth;
        try{
            if(empty($post_data['data'])){
                throw new Exception("规则信息不能为空!");
            }
            else{
                $rulename=trim($post_data['data']);
                if(!$rule=$auth->getRule($rulename)){
                    throw new Exception("规则不存在!");
                }
                $auth->remove($rule);
            }
            return json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
    }
    public function actionUpdatepermission(){
        $result=['error'=>0,'msg'=>''];
        $post_data=Yii::$app->request->post();
        $auth=$this->auth;
        try{
            if(empty($post_data['roleid'])){
                throw new Exception("角色信息不能为空!");
            }
            else{
                $rolename=trim($post_data['roleid']);
                if(!$role=$auth->getRole($rolename)){
                    throw new Exception("角色不存在!");
                }
                $permissions=$auth->getPermissionsByRole($rolename);
                if(!empty($permissions)&&is_array($permissions)){
                    foreach($permissions as $i=>$permission){
                        $auth->removeChild($role,$permission);
                    }
                    //首先移除所有权限,进行下面的更新
                }
                if(!empty($post_data['permissions'])&&is_array($post_data['permissions'])){
                     foreach($post_data['permissions'] as $i=>$permissionname){
                         if($permission=$auth->getPermission($permissionname)){
                            $auth->addChild($role,$permission);
                         }
                     }
                }
            }
            echo json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return json_encode($result);
        }
    }
}