<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Votetype;

/**
 * VotetypeSearch represents the model behind the search form about `backend\models\Votetype`.
 */
class VotetypeSearch extends Votetype
{
    public function rules()
    {
        return [
            [[/*'interval', 'minlimit',*/ 'allowyou', 'status', 'zhiboid'], 'integer'],
            [['name', 'options','valdata',/*'btime', 'etime',*/ 'changes'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Votetype::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'interval' => $this->interval,
            'minlimit' => $this->minlimit,
            'allowyou' => $this->allowyou,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'options', $this->options])
            ->andFilterWhere(['like', 'btime', $this->btime])
            ->andFilterWhere(['like', 'etime', $this->etime])
            ->andFilterWhere(['like', 'changes', $this->changes]);

        return $dataProvider;
    }
}
