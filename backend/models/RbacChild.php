<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-9
 * Time: 下午4:57
 */
namespace backend\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
class RbacChild extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $primaryKey="child";
    public static function tableName()
    {
        return '{{%auth_item_child}}';
    }
}