<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ConfigCategory;



class RoomRoleSearch extends RoomRole
{

    public $parentname;
    public $searchquery;

    public function rules()
    {
        return [

        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$parentname="")
    {
        $query = RoomRole::find();
        $this->searchquery=$query;
        $query->select("child.*");
        $query->from("config_category as child,config_category as parent")->Where('parent.id=child.parentid')->andWhere(['like','parent.alias',$parentname]);
        /*$query2=RoomRole::find();
        $query2->select("*");
        $query2->where(['like','alias',$parentname]);
        $query2->union($query);*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'child.id' => $this->id,
            'child.parentid'=>$this->parentid,
            'child.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'child.name', $this->name])->andFilterWhere(['like', 'child.alias', $this->alias]);
        return $dataProvider;
    }
}