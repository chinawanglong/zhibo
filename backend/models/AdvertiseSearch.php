<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Advertise;

/**
 * AdvertiseSearch represents the model behind the search form about `backend\models\Advertise`.
 */
class AdvertiseSearch extends Advertise
{
    public function rules()
    {
        return [
            [['status','zhiboid'], 'integer'],
            [['name', 'url'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Advertise::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
