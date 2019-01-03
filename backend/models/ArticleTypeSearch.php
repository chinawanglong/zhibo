<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ArticleType;

/**
 * ArticleTypeSearch represents the model behind the search form about `backend\models\ArticleType`.
 */
class ArticleTypeSearch extends ArticleType
{
    public function rules()
    {
        return [
            [['id', 'order', 'status', 'created_at', 'updated_at','zhiboid'], 'integer'],
            [['name', 'code'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ArticleType::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order' => $this->order,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
