<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\User;
use backend\models\UserSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['changepwd','view','delete'],
                        'allow' => true,
                        'roles' => ['supperadmin'],
                    ],
                    [
                        'actions' => ['update','create','make-robot-user'],
                        'allow' => true,
                        'roles' => ['supperadmin'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['supperadmin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            \backend\components\ControllerBehavior::className()
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionChangepwd($id)
    {
        $model = User::findOne($id);
        if(Yii::$app->request->isPost){
            $pass = $_POST['password3'];
            $model->setPassword($pass);
            $model->generatePasswordResetToken();
            if($model->save(false)){
                Yii::$app->user->logout();
                echo '修改密码成功';
            }else{
                echo '修改密码出错';
            }
        }else{
            echo '数据出错！！';
        }
    }
    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => 'register']);
        $auth=Yii::$app->authManager;
        if ($model->load(Yii::$app->request->post())) {
            $preroles=[];
            if(!empty($model->role)){
                $preroles=$model->role;
            }
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->role=serialize([]);
            if($model->save()){
                if(!empty($preroles)){
                    $roles=[];
                    foreach($preroles as $role){
                        if(!empty($role)&&$auth->getRole($role)&&!$auth->getAssignment($role,$model->id)){
                            $auth->assign($auth->getRole($role),$model->id);
                            $roles[]=$role;
                        }
                    }
                    $model->role=serialize($roles);
                    $model->save();
                }
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
                $parentuser_id=intval(Yii::$app->request->get('dataid'));
                $model->parentid=$parentuser_id;
                return $this->render('create', [
                   'model' => $model,
                ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        /**非顶级管理员不可以更改顶级管理员的权限**/
        if(!in_array("topadmin",Yii::$app->user->identity->rbacroles) && in_array("topadmin",$model->rbacroles)){
            return "<script type='text/javascript'>alert('没有权限更改!');window.history.back(-1);</script>";
        }

        if(Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $userinfo=!empty($post['User'])?$post['User']:[];
        }
        if(!empty($userinfo['password'])){
            $model->scenario='updatewithpwd';
        }
        else{
            $model->scenario="update";
        }
        $preroles=[];
        if($model->role){
            $preroles=unserialize($model->role);
        }
        $auth=Yii::$app->authManager;

        if ($model->load(Yii::$app->request->post())) {

            /**更新用户权限,每次只要用户提交了更新的数据都重新赋权限**/
            $result=$auth->revokeAll($model->id);
            if($model->role){
                $roles=[];
                foreach($model->role as $role){
                    if(!empty($role)&&$auth->getRole($role)&&!$auth->getAssignment($role,$model->id)){
                        if($role == "topadmin" && !in_array("topadmin",Yii::$app->user->identity->rbacroles)){
                            continue;
                        }
                        $auth->assign($auth->getRole($role),$model->id);
                        $roles[]=$role;
                    }
                }
                $model->role=serialize($roles);
            }

            $model->validate();
            if(!$model->hasErrors()&&$model->password){
                $model->setPassword($model->password);
            }
            if($model->save()){
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            $model->role=($model->role?unserialize($model->role):[]);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        /**非顶级管理员不可以更改顶级管理员的权限**/
        if(!in_array("topadmin",Yii::$app->user->identity->rbacroles) && in_array("topadmin",$model->rbacroles)){
            return "<<script type='text/javascript'>alert('没有权限删除 !');window.history.back(-1);</script>";
        }

        if($id ==Yii::$app->user->id){
            return "<<script type='text/javascript'>alert('你不可以删除自己!');window.history.back(-1);</script>";
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
