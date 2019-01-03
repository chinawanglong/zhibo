<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "expression".
 *
 * @property integer $id
 * @property string $name
 * @property string $src
 * @property string $alias
 * @property string $data
 * @property integer $type
 * @property integer $status
 */
class Expression extends \yii\db\ActiveRecord
{

    public $item_width=0;
    public $item_height=0;

    public static $types = [
        0 => '未知',
        1 => '表情包',
        2 => '彩条包'
    ];
    public static $statuss = [
        0 => '不可用',
        1 => '可用'
    ];
    public $imageFiles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expression';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','alias'], 'required'],
            [['item_width','item_height'],'number'],
            [['data','config'], 'string'],
            [['type','sort', 'status','zhiboid'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['src'], 'string', 'max' => 255],
            [['alias'], 'string', 'max' => 125],
            [['imageFiles'], 'image', 'skipOnEmpty' => true, 'maxFiles' => 100],
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
            'name' => '包名称',
            'src' => '包目录',
            'alias' => '别名',
            'item_width'=>'单表情/彩条宽度',
            'item_height'=>'单表情/彩条高度',
            'imageFiles' => '表情/彩条包文件',
            'data' => '包数据',
            'sort' => '序列',
            'type' => '类型',
            'status' => '状态',
            'zhiboid'=>'所属直播间'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            self::upload();
            $config_data=['item_width'=>0,'item_height'=>0];
            $config_data['item_width']=doubleval($this->item_width);
            $config_data['item_height']=doubleval($this->item_height);
            $this->config=json_encode($config_data);
            return true;
        } else {
            return false;
        }
    }

    public function afterFind(){
        if(!empty($this->config)&&$config_data=json_decode($this->config)){
            $this->item_width=!empty($config_data->item_width)?doubleval($config_data->item_width):0;
            $this->item_height=!empty($config_data->item_height)?doubleval($config_data->item_height):0;
        }
    }

    public function upload()
    {
        $result=['error'=>0,'msg'=>''];

        try {
            if(Yii::$app->request->isPost){
                $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
            }
            else{
                return "";
            }

            if ($this->validate() && $this->hasErrors()) {
                return "";
            }
            if (!empty($this->imageFiles)) {
                $expression_data = [];
                $saveroot = str_replace("backend", "frontend", Yii::getAlias("@webroot"));

                /*保存dir*/

                if($this->type==1){
                    $this->src = "/uploads/express/" . $this->alias;
                }
                else if($this->type==2){
                    $this->src = "/uploads/caitiao/" . $this->alias;
                }
                else{
                    $this->src = "/uploads/express/" . $this->alias;
                }
                $expression_dir = $saveroot . $this->src;
                if (!is_dir($expression_dir)) {
                    mkdir($expression_dir, 0777);
                }
                foreach ($this->imageFiles as $file) {
                    $expression_name_data = explode("_", $file->baseName);
                    $expression_alias = $expression_name_data[0];
                    $expression_name = !empty($expression_name_data[1]) ? $expression_name_data[1] : $expression_alias;
                    $expression_filename = rand(999, 9999) . time() . '.' . $file->extension;
                    if ($file->saveAs($expression_dir . "/" . $expression_filename)) {
                        $item = ['name' => '', 'alias' => '', 'filename' => ''];
                        $item['name'] = $expression_name;
                        $item['alias'] = $expression_alias;
                        $item['filename'] = $expression_filename;
                        $expression_data[] = $item;
                    }
                }

                /*保存data*/
                $this->data = json_encode($expression_data);
                $this->imageFiles = "";
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
