<?php

namespace backend\controllers;

use Yii;
use backend\models\Popwindow;
use backend\models\PopwindowSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\ConfigCategory;
use yii\filters\AccessControl;

/**
 * PopwindowController implements the CRUD actions for Popwindow model.
 */
class PopwindowController extends Controller
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
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['change'],
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
     * Lists all Popwindow models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PopwindowSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        /****预留操作区域***/
        if ($action = Yii::$app->request->get('action')) {
            $return = ['error' => 0, 'msg' => ''];
            try {
                $id = Yii::$app->request->get('id', 0);
                switch ($action) {
                    case "change":
                        if ($id) {
                            $model = $this->findModel($id);
                            if(!$model){
                                throw new Exception('指定聊天记录不存在!');
                            }
                            if($model->showkf == 0){
                                $model->showkf=1;
                            }else{
                                $model->showkf=0;
                            }
                            $model->save();
                        }
                        break;
                    case
                    "delete":
                        if ($id) {
                            $model = $this->findModel($id);
                            if(!$model){
                                throw new Exception('指定弹窗不存在!');
                            }
                            $model->delete();
                            Yii::$app->session->setFlash('handle-success',$model->name.'弹窗删除成功！');
                        }
                        break;
                    default:
                }
                /****操作执行完毕可以返回****/
                return json_encode($return);
            } catch (Exception $e) {
                $return['error'] = 1;
                $return['msg'] = $e->getMessage();
                return json_encode($return);
            }
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }



    /**
     * Displays a single Popwindow model.
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
     * Creates a new Popwindow model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Popwindow;
        if (Yii::$app->request->isPost&&$model->load(Yii::$app->request->post())) {

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
     * Updates an existing Popwindow model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost&&$model->load(Yii::$app->request->post())) {

            if($model->save()){
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('error', print_r($model->errors,true));
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Finds the Popwindow model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Popwindow the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Popwindow::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
