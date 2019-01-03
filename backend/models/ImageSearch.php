<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Image;

/**
 * ImageSearch represents the model behind the search form about `backend\models\Image`.
 */
class ImageSearch extends Image
{
    public function rules()
    {
        return [
           /* [['id'], 'integer'],*/
            [['name', 'isdefault','zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Image::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            /*'pagination' => [
                'pagesize' => '2',
            ],*/
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'isdefault' => $this->isdefault,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
        ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
