<?php

namespace backend\controllers;

use Yii;
use backend\models\Image;
use backend\models\ImageSearch;
use backend\models\UploadForm;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\components\Upload;
use yii\filters\AccessControl;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'choice', 'delete', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get', 'post'],
                ],
            ],
            \backend\components\ControllerBehavior::className()
        ];
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImageSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * Creates a new Image model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Image;

        if (Yii::$app->request->isPost&&$model->load(Yii::$app->request->post())) {
            if($model->isdefault){
                Yii::$app->db->createCommand()->update('image', ['isdefault' => 0], '')->execute();
            }

            if($model->save()){
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('error', print_r($model->errors,true));
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
     * Updates an existing Image model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $zhiboid=!empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0;
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost&&$model->load(Yii::$app->request->post())) {
            if($model->isdefault){
                Yii::$app->db->createCommand()->update('image', ['isdefault' => 0],['zhiboid'=>$zhiboid])->execute();
            }

            if(!$model->save()){
                Yii::$app->session->setFlash('error', print_r($model->errors,true));
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }



    public function actionChoice()
    {
        $return = ['error' => 0, 'msg' => ''];
        try {
            $id = Yii::$app->request->post('id', 0);
            $zhiboid=!empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0;
            $model = Image::findOne($id);
            if(!$model){
                throw new Exception('图片不存在!');
            }
            Yii::$app->db->createCommand()->update('image', ['isdefault' => 0],['zhiboid'=>$zhiboid])->execute();
            $model->isdefault=1;
            if(!$model->save()){
                throw new Exception(implode(",",$model->errors));
            }
            return json_encode($return);
        }
        catch(Exception $e){
            $return['error'] = 1;
            $return['msg'] = $e->getMessage();
            return json_encode($return);
        }
    }

    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $return = ['error' => 0, 'msg' => ''];
        try {
            $id = Yii::$app->request->get('id', 0);
            $model = $this->findModel($id);
            if(!$model){
                throw new Exception('图片不存在!');
            }
            $model->delete();
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
