<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Course;

/**
 * CourseSearch represents the model behind the search form about `backend\models\Course`.
 */
class CourseSearch extends Course
{
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status','zhiboid'], 'integer'],
            [['title','content'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Course::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

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

        $query->andFilterWhere(['like', 'title', $this->content]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
