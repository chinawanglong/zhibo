<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Zhibo;

/**
 * ZhiboSearch represents the model behind the search form about `backend\models\Zhibo`.
 */
class ZhiboSearch extends Zhibo
{
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status','show_msgtime','base_online','zan_num'], 'integer'],
            [['name', 'announcement', 'logo', 'allowrules','zhibo_tips'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Zhibo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'announcement', $this->announcement])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'allowrules', $this->allowrules]);

        return $dataProvider;
    }
}
