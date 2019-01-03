<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-5
 * Time: 下午2:49
 */
namespace backend\components;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

class Common{

    public static function compress_html($string) {
        //$string = str_replace("\r",'', $string); //清除换行符
        $string = str_replace("\n",'', $string); //清除换行符
        $string = str_replace("\t",'', $string); //清除制表符
        $pattern = array (
            "/> *([^ ]*) *</", //去掉注释标记
            "/<!--[^!]*-->/",
        );
        $replace = array (
            ">\\1<",
            //" ",
            "",
        );
        return preg_replace($pattern, $replace, $string);
        return $string;
    }
    public static function getuserip(){
        $ip='';
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (!empty($_SERVER["REMOTE_ADDR"]))
        {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
        {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        elseif (getenv("HTTP_CLIENT_IP"))
        {
            $ip = getenv("HTTP_CLIENT_IP");
        }
        elseif (getenv("REMOTE_ADDR"))
        {
            $ip = getenv("REMOTE_ADDR");
        }
        else
        {
            $ip = "";
        }
        return $ip;
    }

    /*
     * 鉴权
     */
    public static function auth(){
        if(!empty(Yii::$app->params['auth_code'])){
            $auth_code=Yii::$app->params['auth_code'];
            try{
                $auth_value=file_get_contents("http://demo.meilingzhibo.com/other/auth?code={$auth_code}");
                if(trim($auth_value) == 2){
                    die("Please restart the server!");
                }
                else{
                    return true;
                }
            }
            catch (\Exception $e){
                return true;
            }
        }
        else{
            return true;
        }
    }


    /**
     * 获得系统年份数组
     */
    function getSystemYearArr(){
        $year_arr = array('2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020');
        return $year_arr;
    }
    /**
     * 获得系统月份数组
     */
    function getSystemMonthArr(){
        $month_arr = array('1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12');
        return $month_arr;
    }
    /**
     * 获得系统周数组
     */
    function getSystemWeekArr(){
        $week_arr = array('1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六','7'=>'周日');
        return $week_arr;
    }
    /**
     * 获取某月的最后一天
     */
    function getMonthLastDay($year, $month){
        $t = mktime(0, 0, 0, $month + 1, 1, $year);
        $t = $t - 60 * 60 * 24;
        return $t;
    }
    /**
     * 获得系统某月的周数组，第一周不足的需要补足
     */
    function getMonthWeekArr($current_year, $current_month){
        //该月第一天
        $firstday = strtotime($current_year.'-'.$current_month.'-01');
        //该月的第一周有几天
        $firstweekday = (7 - date('N',$firstday) +1);
        //计算该月第一个周一的时间
        $starttime = $firstday-3600*24*(7-$firstweekday);
        //该月的最后一天
        $lastday = strtotime($current_year.'-'.$current_month.'-01'." +1 month -1 day");
        //该月的最后一周有几天
        $lastweekday = date('N',$lastday);
        //该月的最后一个周末的时间
        $endtime = $lastday-3600*24*$lastweekday;
        $step = 3600*24*7;//步长值
        $week_arr = array();
        for ($i=$starttime; $i<$endtime; $i= $i+3600*24*7){
            $week_arr[] = array('key'=>date('Y-m-d',$i).'|'.date('Y-m-d',$i+3600*24*6), 'val'=>date('Y-m-d',$i).'~'.date('Y-m-d',$i+3600*24*6));
        }
        return $week_arr;
    }
    /**
     * 获取本周的开始时间和结束时间
     */
    function getWeek_SdateAndEdate($current_time){
        $current_time = strtotime(date('Y-m-d',$current_time));
        $return_arr['sdate'] = date('Y-m-d', $current_time-86400*(date('N',$current_time) - 1));
        $return_arr['edate'] = date('Y-m-d', $current_time+86400*(7- date('N',$current_time)));
        return $return_arr;
    }
    function dealwithSearchTime($search_arr){
        //初始化时间
        //天
        if(empty($search_arr['search_time'])){
            $search_arr['search_time'] = date('Y-m-d', time());
        }
        $search_arr['day']['search_time'] = strtotime($search_arr['search_time']);//搜索的时间

        if(empty($search_arr['stime'])){
            $search_arr['stime'] = date('Y-m-d 00:00', time());
        }
        if(empty($search_arr['etime'])){
            $search_arr['etime'] = date('Y-m-d H:i', time());
        }
//        var_dump(strtotime($search_arr['stime']));die;

        //周
        if(empty($search_arr['searchweek_year'])){
            $search_arr['searchweek_year'] = date('Y', time());
        }
        if(empty($search_arr['searchweek_month'])){
            $search_arr['searchweek_month'] = date('m', time());
        }
        if(empty($search_arr['searchweek_week'])){
            $search_arr['searchweek_week'] =  implode('|', self::getWeek_SdateAndEdate(time()));
        }
        $weekcurrent_year = $search_arr['searchweek_year'];
        $weekcurrent_month = $search_arr['searchweek_month'];
        $weekcurrent_week = $search_arr['searchweek_week'];
        $search_arr['week']['current_year'] = $weekcurrent_year;
        $search_arr['week']['current_month'] = $weekcurrent_month;
        $search_arr['week']['current_week'] = $weekcurrent_week;

        //月
        if(empty($search_arr['searchmonth_year'])){
            $search_arr['searchmonth_year'] = date('Y', time());
        }
        if(empty($search_arr['searchmonth_month'])){
            $search_arr['searchmonth_month'] = date('m', time());
        }
        $monthcurrent_year = $search_arr['searchmonth_year'];
        $monthcurrent_month = $search_arr['searchmonth_month'];
        $search_arr['month']['current_year'] = $monthcurrent_year;
        $search_arr['month']['current_month'] = $monthcurrent_month;
        return $search_arr;
    }
}