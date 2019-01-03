<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Oprecord;

/**
 * OprecordSearch represents the model behind the search form about `backend\models\Oprecord`.
 */
class OprecordSearch extends Oprecord
{
    public function rules()
    {
        return [
            [['uid', 'ip', 'zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Oprecord::find()->orderBy(['id'=>SORT_DESC])->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'uid'=>$this->uid,
            'logintime' => $this->logintime,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
