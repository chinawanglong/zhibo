<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "Advertise".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $image
 * @property integer $status
 */
class Advertise extends \yii\db\ActiveRecord
{
    public $imageFile;

    public static $status=[
        0=>'不可用',
        1=>'可用'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status'], 'in','range'=>[1,0]],
            [['image','url'],'string'],
            [['name'], 'string', 'max' => 50],
            [['order'], 'integer'],
            ['name','unique','targetAttribute'=>'name'],
            /*[['url'], 'url'],*/
            [['imageFile'], 'image', /*'minWidth' => 190, 'maxWidth' => 190,'minHeight' => 90, 'maxHeight' => 90*/],
            [['zhiboid'], 'integer']
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
            'id' => '编号',
            'name' => '名称',
            'url' => '跳转地址',
            'image' => '图片',
            'status' => '状态',
            'order' => '排列顺序',
            'imageFile'=>'广告图像',
            'zhiboid'=>'所属直播间'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
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
                $savename="uploads/advertise/".$pre . '.' . $this->imageFile->extension;
                if($this->imageFile->saveAs($saveroot."/".$savename)){
                    $this->image=$urlroot."/".$savename;
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
