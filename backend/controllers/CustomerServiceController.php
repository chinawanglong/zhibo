<?php

namespace backend\controllers;

use Yii;
use backend\models\CustomerService;
use backend\models\CustomerServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CustomerServiceController implements the CRUD actions for CustomerService model.
 */
class CustomerServiceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view','create','update','delete','change','setupkefu'],
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
     * Lists all CustomerService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerServiceSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CustomerService model.
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
     * Creates a new CustomerService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerService;

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else {
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
     * Updates an existing CustomerService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())&&$model->save()) {

        }
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    public function actionChange()
    {
        $id=Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if($model->status == 0){
            $model->status=1;
        }else{
            $model->status=0;
        }
        if($model->save()){
            echo 1;
        }else{
            echo false;
        }

    }
    /**
     * Deletes an existing CustomerService model.
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
     * Finds the CustomerService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = CustomerService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('指定页面不存在!');
        }
    }
    /*
     * 设置客服
     */
    public function actionSetupkefu(){

        $roomid = Yii::$app->session->get("zhiboid");
        $qq_model=\backend\models\ConfigCategory::getConfigbyalias('qq_config');
        $qq_model_attribute=\backend\models\ConfigItems::find()->where(['name'=>'qq_config_val','categoryid'=>$qq_model->id,'zhiboid'=>$roomid])->one();
        if(empty($qq_model_attribute)){
            $qq_model_attribute=new \backend\models\ConfigItems();
            $qq_model_attribute->name="qq_config_val";
            $qq_model_attribute->zh_name="QQ列表值";
            $qq_model_attribute->desc="QQ列表值";
            $qq_model_attribute->categoryid=$qq_model->id;
            $qq_model_attribute->status=1;
            $qq_model_attribute->save();
        }

        $default_val = [
            'selected'=>'',
            'contents'=>[
                'qq_list_one'=>'',
                'qq_list_two'=>'',
                'qq_list_three'=>''
            ]
        ];
        $qq_config_val=!empty($qq_model_attribute->val) ? json_decode($qq_model_attribute->val) : $default_val;
        if(Yii::$app->request->isPost){
            $qq_config_val=Yii::$app->request->post("qq_config");
            $qq_model_attribute->val=!empty($qq_config_val) ? json_encode($qq_config_val) : json_encode($default_val);
            $qq_model_attribute->save();
        }
        return $this->render("setupkefu",[
            "qq_config_val"=>$qq_config_val
        ]);
    }
}
