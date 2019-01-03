<?php

namespace backend\controllers;

use Yii;
use backend\models\Onlineuser;
use backend\models\OnlineuserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OnlineuserController implements the CRUD actions for Onlineuser model.
 */
class OnlineuserController extends Controller
{
    public function behaviors()
    {
        return [
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
     * Lists all Onlineuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OnlineuserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }



    /**
     * Finds the Onlineuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Onlineuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Onlineuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
