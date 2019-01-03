<?php

namespace backend\controllers;

use Yii;
use backend\models\Advertise;
use backend\models\AdvertiseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * AdvertiseController implements the CRUD actions for Advertise model.
 */
class AdvertiseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view','create','update','delete','changeads'],
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
            \backend\components\ControllerBehavior::className()
        ];
    }

    /**
     * Lists all Advertise models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdvertiseSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionChangeads($id)
    {
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
     * Displays a single Advertise model.
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
     * Creates a new Advertise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advertise;
        if (Yii::$app->request->isPost&&$model->load(Yii::$app->request->post())) {//post发送的数据
            if($model->save()){
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('error', print_r($model->errors,true));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Advertise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost&&$model->load(Yii::$app->request->post())) {//post发送的数据
            if($model->save()){
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('error', print_r($model->errors,true));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /*
       * 删除不需要的指定文件
       * 必须为绝对路径
       *
       * */
    /**
     * Deletes an existing Advertise model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model){
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Advertise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advertise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Advertise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
