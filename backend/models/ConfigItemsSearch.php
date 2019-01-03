<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ConfigItems;

/**
 * ConfigItemsSearch represents the model behind the search form about `app\models\ConfigItems`.
 */
class ConfigItemsSearch extends ConfigItems
{
    public function rules()
    {
        return [
            [['id', 'categoryid', 'status','zhiboid'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ConfigItems::find()/*->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0])*/;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'categoryid' => $this->categoryid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
