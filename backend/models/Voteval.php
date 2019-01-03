<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "voteval".
 *
 * @property integer $id
 * @property integer $vid
 * @property string $valdata
 * @property string $begintime
 * @property string $endtime
 */
class Voteval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voteval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vid', 'valdata', 'begintime', 'endtime'], 'required'],
            [['vid'], 'integer'],
            [['begintime', 'endtime'], 'safe'],
            [['valdata'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'vid' => '投票ID',
            'valdata' => '投票值',
            'begintime' => '开始时间',
            'endtime' => '结束时间',
        ];
    }

    public function getVotetype(){
        return $this->hasOne(Votetype::className(),['id'=>'vid']);
    }
}
