<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Shouted;

/**
 * ShoutedSearch represents the model behind the search form about `backend\models\Shouted`.
 */
class ShoutedSearch extends Shouted
{
    public function rules()
    {
        return [
            [['id', 'postuid', 'type', 'mai_type', 'status', 'zhiboid','process'], 'integer'],
            [['title', 'desc', 'content', 'point', 'start_time', 'end_time', 'start_point', 'end_point', 'stoploss', 'limited', 'pingprice', 'yli', 'pingtime', 'postname'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Shouted::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $query->orderBy(['id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'postuid' => $this->postuid,
            'type' => $this->type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'pingtime' => $this->pingtime,
            'mai_type' => $this->mai_type,
            'status' => $this->status,
            'process'=>$this->process
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'point', $this->point])
            ->andFilterWhere(['like', 'start_point', $this->start_point])
            ->andFilterWhere(['like', 'end_point', $this->end_point])
            ->andFilterWhere(['like', 'stoploss', $this->stoploss])
            ->andFilterWhere(['like', 'limited', $this->limited])
            ->andFilterWhere(['like', 'pingprice', $this->pingprice])
            ->andFilterWhere(['like', 'yli', $this->yli])
            ->andFilterWhere(['like', 'postname', $this->postname]);

        return $dataProvider;
    }
}
