<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Chat;

/**
 * ChatSearch represents the model behind the search form about `backend\models\Chat`.
 */
class ChatSearch extends Chat
{
    public function rules()
    {
        return [
            [['fid','toid', 'status','check_uid'], 'integer'],
            [['content','check_uid','fromname','toname','color','zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Chat::find()->andFilterWhere(['chat.zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'user' => function($query) { $query->from(['fromuser' => 'user']); },
            'touser' => function($query) { $query->from(['touser' => 'user']); },
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        /*$query->andFilterWhere(['like', 'chat.fromname1', $this->fromname]);
        $query->orFilterWhere(['LIKE', 'fromuser.ncname', $this->fromname]);

        $query->orFilterWhere(['LIKE', 'touser.ncname', $this->toname]);
        $query->andFilterWhere(['like', 'chat.toname', $this->toname]);
        */
        if($this->fromname){
            $query->andWhere("chat.fromname like :fromname or fromuser.ncname like :fromname",[':fromname'=>"%".$this->fromname."%"]);
        }

        if($this->toname){
            $query->andWhere("chat.toname like :toname or touser.ncname like :toname",[':toname'=>"%".$this->toname."%"]);
        }

        $query->andFilterWhere([
            'chat.fid' => $this->fid,
            'chat.status' => $this->status,
            'check_uid'=>$this->check_uid
        ]);
        $query->andFilterWhere(['like', 'content', $this->content])
              ->andFilterWhere(['like', 'chat.zhiboid', $this->zhiboid]);
        return $dataProvider;
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['user.ncname','touser.ncname']);
    }
}
