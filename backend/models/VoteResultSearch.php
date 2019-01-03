<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VoteResultSearch represents the model behind the search form about `backend\models\VoteResult`.
 */
class VoteResultSearch extends VoteResult
{
    public function rules()
    {
        return [
            [['id', 'voteid', 'uid', 'result', 'created_at', 'updated_at', 'zhiboid'], 'integer'],
            [['info'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = VoteResult::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'voteid' => $this->voteid,
            'uid' => $this->uid,
            'result' => $this->result,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'info', $this->info]);

        return $dataProvider;
    }
}
