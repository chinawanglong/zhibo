<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $data
 */
class Image extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','address','data'], 'string'],
            [['isdefault','zhiboid'], 'integer'],
            [['name','isdefault'], 'required'],
            ['name','unique','targetAttribute'=>'name'],
            [['data'], 'default','value'=>date('Y-m-d H:i:s')],
            [['isdefault'], 'in', 'range' => [0,1]],
            [['imageFile'], 'image', 'skipOnEmpty' => true],
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
            'name' => '背景标示',
            'address' => '背景图地址',
            'data' => '上传时间',
            'isdefault'=>'当前背景',
            'imageFile'=>'背景图片',
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
                $savename="uploads/deskback/".$pre . '.' . $this->imageFile->extension;
                if($this->imageFile->saveAs($saveroot."/".$savename)){
                    $this->address=$urlroot."/".$savename;
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
