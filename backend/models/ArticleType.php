<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\Article;

/**
 * This is the model class for table "article_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $order
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleType extends \yii\db\ActiveRecord
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
        return 'article_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['order', 'status', 'created_at', 'updated_at','zhiboid'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['code'], 'string', 'max' => 255],
            [['role'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '栏目名称',
            'code' => '代号',
            'order' => '排列顺序',
            'status' => '状态',
            'role'=>'角色设置',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'zhiboid'=>'所属直播间'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!empty($_POST["ArticleType"]["role"])){
                $allow_rules=serialize($_POST["ArticleType"]["role"]);
                $this->role= $allow_rules;
            }
            $this->zhiboid = !empty(Yii::$app->session->get('zhiboid')) ? Yii::$app->session->get('zhiboid'): 0;
            return true;
        } else {
            return false;
        }
    }

    /*
     * 获得文章
     */
    public function getArticles(){
        return $this->hasMany(Article::className(),['typeid'=>'id']);
    }
}
