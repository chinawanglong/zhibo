<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Voteval;

/**
 * VotevalSearch represents the model behind the search form about `backend\models\Voteval`.
 */
class VotevalSearch extends Voteval
{
    public function rules()
    {
        return [
            [['vid'], 'integer'],
            [[/*'valdata',*/ 'begintime', 'endtime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Voteval::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            if(!empty($params['id'])){
                $query->andFilterWhere([
                    'vid' => $params['id'],
                ]);
            }
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vid' => $this->vid,
            /*'begintime' => $this->begintime,
            'endtime' => $this->endtime,*/
        ]);

        $query->andFilterWhere(['like', 'valdata', $this->valdata])->andWhere(['>=','begintime',$this->begintime])->andWhere(['<=','endtime',$this->endtime]);
        return $dataProvider;
    }
}
