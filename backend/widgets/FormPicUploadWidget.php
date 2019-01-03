<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-24
 * Time: 下午6:03
 */
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use kartik\base\InputWidget;

class FormPicUploadWidget extends InputWidget
{
    public $pics;
    public $id;
    public $callback;
    public $jscallback;

    public function init()
    {
        parent::init();
        //$this->id = $this->id;
        //$this->id = $this->hasModel()?Html::getInputId($this->model, $this->attribute) : $this->id;
        if(!$this->id&&$this->hasModel()){
            $this->id =Html::getInputName($this->model, $this->attribute);
        }
        else if(!$this->id){
            $this->id="pics";
        }

        if(!$this->pics){
            if($this->hasModel()&&!empty($this->model->{$this->attribute})&&is_string($this->model->{$this->attribute})&&unserialize($this->model->{$this->attribute})){
                $this->pics=unserialize($this->model->{$this->attribute});
            }
            else if(!empty($this->model->{$this->attribute})&&is_array($this->model->{$this->attribute})){
                $this->pics=$this->model->{$this->attribute};
            }
            else{
                $this->pics=[];
            }
        }

        /**init**/
    }

    public function run()
    {
        return $this->render("FormPicUploadWidget",[
            'model'=>$this->model,
            'attribute'=>$this->attribute,
            'id'=>$this->id,
            'pics'=>$this->pics,
            'callback'=>$this->callback,
            'jscallback'=>$this->jscallback
        ]);
    }
}