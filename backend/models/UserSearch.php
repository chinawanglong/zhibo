<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\User;

/**
 * UserSearch represents the model behind the search form about `backend\models\User`.
 */
class UserSearch extends User
{
    public function rules()
    {
        return [
            [['id', 'role','teacher','roomrole', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ncname','username', 'auth_key', 'password_hash', 'password_reset_token','mobile','email','zhiboid','info','agentid'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find()/*->where(['not like','mobile','robot'])*/->orderBy(['id'=>SORT_DESC]);

        if(!in_array("topadmin",Yii::$app->user->identity->rbacroles)){
            $query->andFilterWhere(['zhiboid' => !empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0]);
            /*如果不是顶级角色*/
        }
        else if(!empty($this->zhiboid)){
            $query->andFilterWhere(['zhiboid' => $this->zhiboid ]);
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pagesize' => '10',
            ],
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'teacher'=>$this->teacher,
            'roomrole' => $this->roomrole,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'ncname', $this->ncname])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like','mobile',$this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like','zhiboid',$this->zhiboid]);

        return $dataProvider;
    }
}
