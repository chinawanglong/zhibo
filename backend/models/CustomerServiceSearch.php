<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomerService;

/**
 * CustomerServiceSearch represents the model behind the search form about `backend\models\CustomerService`.
 */
class CustomerServiceSearch extends CustomerService
{
    public function rules()
    {
        return [
            /*[['id'], 'integer'],*/
            [['name', 'account','status','begintime','endtime','zhiboid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerService::find()->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status'=>$this->status,
            /*'onlinetime'=>$this->onlinetime*/
        ]);
        $a = $this->begintime;
        $b = $this->endtime;
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'account', $this->account]);
        if($a && $b){
            if($a>$b){
                $query->select("*")->from('customer_service')->where(['<=', 'begintime', '24'])->andWhere(['<=', 'endtime', $b]);
                $anotherQuery = CustomerService::find();
                $anotherQuery->select('*')->from('customer_service')->where(['<=', 'begintime', $b])->andWhere(['<=', 'endtime', $b]);
                $query->union($anotherQuery);
            }elseif($b>$a){
                $query->andWhere(['>=', 'begintime', $a])->andWhere(['<=', 'endtime', $b]);
            }
        }elseif($a){
            $query->andWhere(['>=', 'begintime', $a]);
        }elseif($b){
            $query->andFilterWhere(['<=', 'endtime', $b]);
        }

        return $dataProvider;
    }
}
