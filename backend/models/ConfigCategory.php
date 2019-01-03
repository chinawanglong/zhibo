<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use backend\models\ConfigItems;

/**
 * This is the model class for table "config_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 */
class ConfigCategory extends \yii\db\ActiveRecord
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
        return 'config_category';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','alias'], 'required'],
            [['parentid','status','zhiboid'], 'integer'],
            [['name','alias'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['add'] = ['name'];
        return $scenarios;
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
            'parentid'=>'父配置',
            'name' => '配置项名称',
            'alias'=>'配置项别名',
            'status' => '配置状态',
            'zhiboid'=>'所属直播间'
        ];
    }
    public static  function getConfig($name){
        return static::find()->where(['name'=>trim($name)])->one();
    }
    public static function getConfigbyalias($alias){
        return static::find()->where(['alias'=>trim($alias)])->one();
    }
    public function getItems(){
        return $this->hasMany(ConfigItems::className(),['categoryid'=>'id']);
    }
    public function getParent(){
        return $this->hasOne(ConfigCategory::className(),['id'=>'parentid'])->from(ConfigCategory::tableName().' parent');
    }
    public function __get($name){

        if($this->getScenario()=="default"){
            try{
                $val=parent::__get($name);
                if($val){
                    return $val;
                }
            }
            catch(Exception $e){
                $item=ConfigItems::find()->where(['name'=>$name,'categoryid'=>$this->id])->one();
                if($item){
                    return $item;
                }
                else if($this->parent){
                    return $this->parent->{$name};
                }
                return $item;
            }
            /**仅用于default场景**/
        }
        else{
            return parent::__get($name);
        }
    }
}
