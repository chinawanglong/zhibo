<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Blacklist;

/**
 * BlacklistSearch represents the model behind the search form about `\backend\models\Blacklist`.
 */
class BlacklistSearch extends Blacklist
{
    public function rules()
    {
        return [
            [['id', 'uid', 'check_uid','zhiboid'], 'integer'],
            [['temp_name', 'ip', 'datetime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Blacklist::find()->orderBy(['id'=>SORT_DESC])->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'datetime' => $this->datetime,
            'check_uid' => $this->check_uid,
            'zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0
        ]);

        $query->andFilterWhere(['like', 'temp_name', $this->temp_name])->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
