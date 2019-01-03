<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Expression;

/**
 * ExpressionSearch represents the model behind the search form about `backend\models\Expression`.
 */
class ExpressionSearch extends Expression
{
    public function rules()
    {
        return [
            [['id', 'type', 'sort','status','zhiboid'], 'integer'],
            [['name', 'src', 'alias', 'data'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Expression::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'src', $this->src])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
