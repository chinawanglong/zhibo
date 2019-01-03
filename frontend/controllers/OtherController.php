<?php
namespace frontend\controllers;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\Controller;
/**
 * Other controller
 */
class OtherController extends Controller
{
    public function actionAuth(){
        try{
            if(!empty($_GET['code'])){
                $code=trim($_GET['code']);
                $code_array=[
                    'mahuadong'=>'mahuadong_0511',
                    'wujun'=>'wujunzhibo_3214',
                    'lingchanglong'=>'lingchanglong_0515',
                    'liugang'=>'liugang_0622',
                    'liugang_2017'=>'liugang_2017',
                    'null1017'=>'null1017',
                    'liguanghui1017'=>'liguanghui1017',
                    'chali1017'=>'chali1017',
                    'mahuadong_0511'=>'mahuadong_0511',
                    'shjr20171220'=>'shjr20171220',
                    'wanyuan2017'=>'wanyuan2017',
                    'cjpd2017'=>'cjpd2017',
                    'jywc20180111'=>'jywc20180111'
                ];
                if(in_array($code,$code_array)){
                    return "1";
                }
            }
            return "2";
        }
        catch (ErrorException $e){
            return "0";
        }
    }
}