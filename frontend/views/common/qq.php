<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 11:47
 */
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\ConfigCategory;

$qq_model=ConfigCategory::getConfigbyalias('qq_config');
$qq_model_attribute=\backend\models\ConfigItems::find()->where(['name'=>'qq_config_val','categoryid'=>$qq_model->id,'zhiboid'=>intval(Yii::$app->session->get("zhiboid"))])->one();
$qq_config_val=!empty($qq_model_attribute->val)?json_decode($qq_model_attribute->val,true):[];
if(!empty($qq_config_val['contents'])){
    foreach ($qq_config_val['contents'] as $i=>$str){
        $qq_config_val['contents'][$i]=explode(",",$str);
    }
}
$qq_list_str=json_encode($qq_config_val);
?>
<iframe style="display:none;" id="qq_iframe" name="qq_iframe" class="qq_iframe" src=""></iframe>
<script type="text/javascript">
    $(function () {
        /****随机QQ*****/
        function  random_customer(){
            /*var qq_list_val=<?=$qq_list_str;?>;
             var qq_list=[];
             if(qq_list_val['selected'] && qq_list_val['contents'][qq_list_val['selected']]){
             qq_list=qq_list_val['contents'][qq_list_val['selected']];
             }

             //随机
             var qq_i = Math.floor(Math.random() * qq_list.length);
             var src = $.format("tencent://message/?uin={0}&Site=&menu=yes", qq_list[qq_i]);
             $('.qq_iframe').attr('src', src);
             return false;
             */

            var qq_list=$(".main-content .main .main-top .chat-video .left-area .chat-area .chat-handle-area .custom-area .custom-item");
            var qq_i = Math.floor(Math.random() * qq_list.length);
            var qq_item=qq_list.eq(qq_i);
            var src = qq_item.attr("href");
            if(room_info.isMobile){
                //var src=$.format("http://wpa.qq.com/msgrd?v=3&uin={0}&site=qq&menu=yes", qq_list[qq_i]);
                //src=$.format("mqqwpa://im/chat?chat_type=wpa&uin={0}&version=1&src_type=web&web_src=oicqzone.com","757045849");
                //$('.qq_iframe').attr('src', src);
                //window.open(src);
                window.location.href = src;
            }
            else{
                $('.qq_iframe').attr('src', src);
            }

            return false;
            /*点击客服*/
        };
        window.random_customer=random_customer;

        $("body").delegate(".custom-item,.toqq", "click", random_customer);

        //setTimeout(random_customer,1000);
    });
</script>