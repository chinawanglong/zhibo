<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Navigation;

/**
 * NavigationSearch represents the model behind the search form about `backend\models\Navigation`.
 */
class NavigationSearch extends Navigation
{
    public function rules()
    {
        return [
            [['id', 'type', 'order', 'status'], 'integer'],
            [['text', 'code', 'zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Navigation::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'order' => $this->order,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
