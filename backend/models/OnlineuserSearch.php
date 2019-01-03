<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Onlineuser;

/**
 * OnlineuserSearch represents the model behind the search form about `backend\models\Onlineuser`.
 */
class OnlineuserSearch extends Onlineuser
{
    public $username;
   /* public $roomrole;*/
    public function rules()
    {
        return [
            [['zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {

            $query = Onlineuser::find()->orderBy(['id'=>SORT_DESC])->andFilterWhere(['onlineuser.zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

            $query->joinWith(['user']);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            if (!($this->load($params) && $this->validate())) {
                return $dataProvider;
            }

            $query->andFilterWhere([
                'id' => $this->id,
                'uid' => $this->uid,
                'time' => $this->time,
            ]);

            $query->andFilterWhere(['like', 'ip', $this->ip])
                /*->andFilterWhere(['like', 'onlineuser.zhiboid', $this->zhiboid])*/
                ->andFilterWhere(['like', 'sort', $this->sort])
                ->andFilterWhere(['like', 'user.username', $this->username]);

            return $dataProvider;
    }
}
