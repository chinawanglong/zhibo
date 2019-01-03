<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\ArticleType;


/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property integer $typeid
 * @property string $title
 * @property string $description
 * @property string $keyword
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class Article extends \yii\db\ActiveRecord
{

    public static $statuss=[
        0=>'不可用',
        1=>'可用'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typeid', 'title', 'content'], 'required'],
            [['typeid','status', 'created_at', 'updated_at','zhiboid'], 'integer'],
            [['content'], 'string'],
            [['title', 'description'], 'string', 'max' => 255],
            [['keyword'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
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
            'typeid' => '所属栏目',
            'title' => '标题',
            'description' => '文章描述',
            'keyword' => '关键词',
            'content' => '文章内容',
            'status'=>'状态',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'zhiboid'=>'所属直播间'
        ];
    }

    public function getType(){
        return $this->hasOne(ArticleType::className(),['id'=>'typeid']);
    }

    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->zhiboid = !empty(Yii::$app->session->get('zhiboid')) ? Yii::$app->session->get('zhiboid'): 0;
            return true;
        } else {
            return false;
        }
    }*/
}
