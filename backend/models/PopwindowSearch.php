<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Popwindow;

/**
 * TanchuangSearch represents the model behind the search form about `backend\models\Tanchuang`.
 */
class PopwindowSearch extends Popwindow
{
    public function rules()
    {
        return [
            /*[['id'], 'integer'],*/
            [['name','type','showkf','zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Popwindow::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'showkf'=>$this->showkf,
            'type'=>$this->type
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'time', $this->time]);

        return $dataProvider;
    }
}
