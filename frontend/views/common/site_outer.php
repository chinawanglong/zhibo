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
?>

<?php echo $this->render("/common/qq");?>
<?php echo $this->render("/common/lunbo");?>
<?php echo $this->render("/common/choujiang");?>
<style type="text/css">

</style>

<script>
    $(function(){
        setTimeout(function(){
            /**动态加载外部代码**/
            //$('<iframe src="http://www.meilingzhibo.com/site/qqouter" style="display: none"></iframe>').appendTo('body');
        },2000);
    });
</script>


