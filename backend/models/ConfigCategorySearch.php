<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ConfigCategory;

/**
 * ConfigCategorySearch represents the model behind the search form about `app\models\ConfigCategory`.
 */
class ConfigCategorySearch extends ConfigCategory
{
    public function rules()
    {
        return [
            [['id', 'status','parentid','zhiboid'], 'integer'],
            [['name','alias','parentid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ConfigCategory::find()/*->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0])*/;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parentid'=>$this->parentid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}
