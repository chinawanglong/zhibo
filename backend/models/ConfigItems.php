<?php

namespace backend\models;

use Yii;
use backend\models\ConfigItemval;

/**
 * This is the model class for table "config_items".
 *
 * @property integer $id
 * @property string $name
 * @property integer $categoryid
 * @property integer $status
 */
class ConfigItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $status=[
        0=>'失效',
        1=>'生效'
    ];
    public static function tableName()
    {
        return 'config_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','zh_name','categoryid'], 'required'],
            [['categoryid', 'status','zhiboid'], 'integer'],
            [['name','val','zh_name','desc'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \backend\components\ZhiboidBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zh_name'=>'配置属性名称',
            'name' => '别名',
            'desc'=>'描述',
            'categoryid' => '配置项ID',
            'val'=>'配置值',
            'status' => '配置项状态',
            'zhiboid'=>'所属直播间'
        ];
    }
    public static function findByName($name){
        return self::find()->where(['name'=>$name])->one();
    }
    public function afterSave($insert,$changeAttributes){
        if($insert){

        }
        else{

        }
        parent::afterSave($insert,$changeAttributes);
        return true;
    }
    public function getCategory(){
        return $this->hasOne(ConfigCategory::className(),['id'=>'categoryid']);
    }
}
