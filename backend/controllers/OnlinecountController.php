<?php

namespace backend\controllers;

use Yii;
use backend\models\Visit;
use backend\models\Zhibo;
use backend\models\OnlineMinCount;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\Common;

/**
 * ZhiboController implements the CRUD actions for Zhibo model.
 */
class OnlinecountController extends Controller
{
    private $search_arr;

    /*
     * 加载访客页面
     */
    public function actionMinutecountIframe(){
        return $this->render('minutecountiframe');
    }

    /**
     * Lists all Zhibo models.
     * @return mixed
     */
    public function actionMinutecount()
    {
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        $this->search_arr = Common::dealwithSearchTime($this->search_arr);
        //获得系统年份
        $year_arr = Common::getSystemYearArr();
        //获得系统月份
        $month_arr = Common::getSystemMonthArr();
        //获得本月的周时间段
        $week_arr = Common::getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);

        $is_super_admin = in_array('topadmin',Yii::$app->user->identity->rbacroles);
        $zhibo = $is_super_admin?Zhibo::find()->all():[];
        if(!empty($this->search_arr['zhiboid'])){
            $zhiboid = (int)$this->search_arr['zhiboid'];
        }else{
            $is_super_admin = in_array('topadmin',Yii::$app->user->identity->rbacroles);
            $zhiboid = $is_super_admin?null: Yii::$app->session->get("zhiboid");
        }
        if(empty($this->search_arr['search_type'])){
            $this->search_arr['search_type'] = 'day';
        }
        if(empty($this->search_arr['ranges'])){
            $this->search_arr['ranges'] = 1;
        }
        if($this->search_arr['search_type'] == 'day'){
            $stime = $this->search_arr['day']['search_time'];
            $etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
        }

        if($this->search_arr['search_type'] == 'week'){
            $current_weekarr = explode('|', $this->search_arr['week']['current_week']);
            $stime = strtotime($current_weekarr[0]);
            $etime = strtotime($current_weekarr[1])+86400-1;
        }
        if($this->search_arr['search_type'] == 'month'){
            $stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01");
            $etime = Common::getMonthLastDay($this->search_arr['month']['current_year'],$this->search_arr['month']['current_month'])+86400-1;
        }
        if($this->search_arr['search_type'] == 'range'){
            $stime = strtotime($this->search_arr['stime']);
            $etime = strtotime($this->search_arr['etime']);
        }
        if($zhiboid){
            $data = OnlineMinCount::findBySql('SELECT  create_at*1000+3600000*8 as minutes,sum(`count`) FROM online_min_count WHERE zhiboid='.$zhiboid.' and create_at>'.$stime." and create_at<".$etime." GROUP BY minutes ")->asArray()->all();
        }else{
            $data = OnlineMinCount::findBySql('SELECT  create_at*1000+3600000*8 as minutes,sum(`count`) FROM online_min_count WHERE create_at>'.$stime." and create_at<".$etime." GROUP BY minutes ")->asArray()->all();
        }



        foreach($data as &$value){
            $value = array_values($value);
        }
        $json = str_replace('"','',json_encode($data));

        return $this->renderPartial('minutecount', [
            'zhibo' => $zhibo,
            'is_super_admin'=>$is_super_admin,
            'year_arr' => $year_arr,
            'month_arr' => $month_arr,
            'week_arr' => $week_arr,
            'search_arr' => $this->search_arr,
            'json'=>$json
        ]);
    }
}
