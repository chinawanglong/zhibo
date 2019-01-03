<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Umobile;

/**
 * UmobileSearch represents the model behind the search form about `backend\models\Umobile`.
 */
class UmobileSearch extends Umobile
{
    public function rules()
    {
        return [
            [['id', 'type', 'zhiboid'], 'integer'],
            [['mobile', 'info', 'time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Umobile::find()->orderBy(['id'=>SORT_DESC])->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'info', $this->info]);

        return $dataProvider;
    }
}
