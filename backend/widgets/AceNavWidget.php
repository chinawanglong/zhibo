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

class AceNavWidget extends Widget
{

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render("acenav");
    }
}