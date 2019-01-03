<?php

namespace backend\controllers;

use Yii;
use backend\models\Chat;
use backend\models\ChatSearch;
use backend\components\Common;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ChatController implements the CRUD actions for Chat model.
 */
class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete'],
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
     * Lists all Chat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChatSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        /****预留操作区域***/
        if ($action = Yii::$app->request->get('action')) {
            $return = ['error' => 0, 'msg' => ''];
            try {
                $id = Yii::$app->request->get('id', 0);
                $ids = Yii::$app->request->get('ids', []);
                switch ($action) {
                    case "check":
                        if ($id) {
                            $model = $this->findModel($id);
                            if(!$model){
                                throw new Exception('指定聊天记录不存在!');
                            }
                            if ($model->status == 1) {
                                $model->status = 0;
                            } else {
                                $model->status = 1;
                            }
                            $model->check_uid = Yii::$app->user->identity->id;
                            if ($model->save()) {
                                Yii::$app->session->setFlash('handle-success', $model->content . ',审核通过！');
                            } else {
                                Yii::$app->session->setFlash('handle-error', $model->content . ',审核失败！');
                            }
                        }
                        if (!empty($ids) && is_array($ids)) {
                            Yii::$app->db->createCommand()->update('chat', ['status' => 1], ['id' => $ids])->execute();
                            Yii::$app->session->setFlash('handle-success', '消息批量审核成功！');
                        }
                        break;
                    case
                    "delete":
                        if ($id) {
                            $model = $this->findModel($id);
                            if(!$model){
                                throw new Exception('指定聊天记录不存在!');
                            }
                            $model->delete();
                            Yii::$app->session->setFlash('handle-success', $model->content . '的消息删除成功！');
                        }
                        if (!empty($ids) && is_array($ids)) {
                            $return = ['error' => 0, 'msg' => ''];
                            Yii::$app->db->createCommand()->delete('chat', ['id' => $ids])->execute();
                            Yii::$app->session->setFlash('handle-success', '消息批量删除成功！');
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
        //return $this->render('index', ['dataProvider' => $dataProvider,'searchModel' => $searchModel]);
        return Common::compress_html(
            $this->render('index', ['dataProvider' => $dataProvider,'searchModel' => $searchModel])
        );
    }


    /**
     * Finds the Chat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Chat::findOne($id)) !== null) {
            return $model;
        } else {
            return "";
        }
    }
}
