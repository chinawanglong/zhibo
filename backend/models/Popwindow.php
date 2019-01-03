<?php

namespace backend\models;

use Yii;
use backend\models\ConfigCategory;
use yii\behaviors\TimestampBehavior;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tanchuang".
 *
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property integer $uid
 * @property string $desc
 * @property string $time
 */
class Popwindow extends \yii\db\ActiveRecord
{
    public $imageFile;
    public static $showkf=[
        1=>'显示',
        0=>'隐藏'
    ];
    public static $types=[
        1 => '客服弹窗',
        2 => '投资弹窗',
        3 => '操作弹窗',
        4 => '注册弹窗',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'popwindow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','type'], 'required'],
            [['type', 'time','interval', 'kfnum', 'showkf', 'status', 'created_at', 'updated_at', 'zhiboid','pwidth','pheight'], 'integer'],
            [['boffset'], 'double'],
            [['name', 'img'], 'string', 'max' => 255],
            [['link'],'string','max'=>550],
            /*自定义规则*/
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/','message'=>'此项只能含有字母数字！'],
            ['name','unique','targetAttribute'=>'name','message'=>'弹窗名称已经被使用，请重新输入！'],
            [['time'], 'default','value'=>'0'],
            [['time'], 'match', 'pattern' => '/^[0-9]+$/','message'=>'执行时间必须为数字格式！'],
            [['showkf'],'in','range'=>[0,1]],
            [['boffset'], 'default','value'=>0],
            [
                'imageFile',
                'image',
                'minWidth' => 100, 'maxWidth' => 2000,
                'minHeight' => 100, 'maxHeight' => 2000,
            ]
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
            'id' => '编号',
            'name' => '名称',
            'img' => '弹窗图片',
            'type' => '弹窗类型',
            'link'=>'弹窗链接',
            'time' => '页面加载后多久显示',
            'interval'=>'是否循环',
            'imageFile' => '弹窗图片背景',
            'showkf'=>'是否显示客服',
            'boffset'=>'客服距离弹窗底部的位置',
            'kfnum'=>'客服显示的个数',
            'status'=>'是否显示',
            'zhiboid'=>'所属直播间',
            'pwidth'=>'弹窗宽度',
            'pheight'=>'弹窗高度'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            print_r($this->imageFile);
            exit;
            self::upload();
            return true;
        } else {
            return false;
        }
    }

    public function upload()
    {
        $result=['error'=>0,'msg'=>''];

        try {
            if(Yii::$app->request->isPost){
                $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
            }
            else{
                return "";
            }

            if ($this->validate() && $this->hasErrors()) {
                return "";
            }
            if (!empty($this->imageFile)) {
                $saveroot=str_replace("backend","frontend",Yii::getAlias("@webroot"));
                $urlroot=Yii::$app->furlManager->hostInfo.Yii::$app->furlManager->baseUrl;
                $pre = rand(999,9999).time();
                $savename="uploads/popwindow/".$pre. '.' . $this->imageFile->extension;
                if($this->imageFile->saveAs($saveroot."/".$savename)){
                    $this->img=$urlroot."/".$savename;
                }
                return $result;
            } else {
                return "";
            }
        } catch (Exception $e) {
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return $result;
        }
    }
}
