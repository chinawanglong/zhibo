<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Teacherzan;

/**
 * TeacherzanSearch represents the model behind the search form about `backend\models\Teacherzan`.
 */
class TeacherzanSearch extends Teacherzan
{
    public function rules()
    {
        return [
            [['id','tid', 'uid'], 'integer'],
            [['name', 'time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Teacherzan::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
