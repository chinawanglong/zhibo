<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use backend\models\Zhibo;
use backend\models\ZhiboSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\Upload;
use yii\filters\AccessControl;

/**
 * ZhiboController implements the CRUD actions for Zhibo model.
 */
class ZhiboController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view','create','update','delete'],
                        'allow' => true,
                        'roles' => ['topadmin'],
                    ],
                    [
                        'actions' => ['setup'],
                        'allow' => true,
                        'roles' => ['topadmin','supperadmin'],
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
     * Lists all Zhibo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ZhiboSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Zhibo model.
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
     * Creates a new Zhibo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Zhibo;
        header("Content-type:text/html;charset=utf8");

        if ($model->load(Yii::$app->request->post())) {
            /**上传图片**/
            $allow_rules=serialize($_POST["Zhibo"]["allowroles"]);
            $model->allowroles= $allow_rules;
            if(!empty($_FILES['logo_attr'])){
                $save_result=Yii::$app->upload->upload(Yii::getAlias("@web/uploads/img/"),'logo_attr');
                if(!$save_result['error']){
                    $img_src =$save_result['src'];
                    $model->logo=$img_src;
                }
                else{
                    $model->addError("logo_attr",$save_result['msg']);
                }
            }
            else{
                $model->addError("logo_attr","LOGO不可以为空!");
            }

            if($model->save()){

                /*新建课程表*/
                $course=new \backend\models\Course();
                $course->title="课程表";
                $course->content="课程表";
                $course->zhiboid=$model->id;
                $course->status=1;
                $course->save();

                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Zhibo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $allow_roles=serialize($_POST["Zhibo"]["allowroles"]);
            $model->allowroles= $allow_roles;
            if(!empty($_FILES['logo_attr'])&&$_FILES['logo_attr']['error']!=4){
                $save_result=Yii::$app->upload->upload(Yii::getAlias("@web/uploads/img/"),'logo_attr');
                if(!$save_result['error']){
                  $img_src =$save_result['src'];
                  $model->logo=$img_src;
                }
                else{
                  header("Content-type:text/html;charset=utf8");
                  print_r($save_result);
                  exit();
                }
            }

            if($model->save()){
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
            $model->allowroles=($model->allowroles?unserialize($model->allowroles):[]);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSetup(){
        $zhiboid = !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):1;
        $model = Zhibo::findOne($zhiboid);
        if ($model->load(Yii::$app->request->post())) {
            $allow_roles=serialize($_POST["Zhibo"]["allowroles"]);
            $model->allowroles= $allow_roles;
            if(!empty($_FILES['logo_attr'])&&$_FILES['logo_attr']['error']!=4){
                $save_result=Yii::$app->upload->upload(Yii::getAlias("@web/uploads/img/"),'logo_attr');
                if(!$save_result['error']){
                    $img_src =$save_result['src'];
                    $model->logo=$img_src;
                }
                else{
                    header("Content-type:text/html;charset=utf8");
                    print_r($save_result);
                    exit();
                }
            }

            if($model->save()){
                return $this->redirect(['setup', 'id' => $model->id]);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            $model->allowroles=($model->allowroles?unserialize($model->allowroles):[]);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSetupvideo(){
        $model=\backend\models\ConfigCategory::getConfig('视频设置');
        header("Content-type:text/html;charset=utf8");
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
                        print_r($item->errors);
                        exit;
                    }
                }
            }
            $check_vals=[];

            foreach($check_vals as $name){
                if(!empty($model->{$name})){
                    $item=$model->{$name};
                    if(!empty($config_data[$name])){
                        $item->val="1";
                        if(!$item->save()){
                            print_r($item->errors);
                        }
                    }
                    else{
                        $item->val="0";
                        if(!$item->save()){
                            print_r($item->errors);
                        }
                    }
                }
                /**循环设置**/
            }
        }

        return $this->render("setupvideo",['model'=>$model]);
    }

    /**
     * Deletes an existing Zhibo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Zhibo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Zhibo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Zhibo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
