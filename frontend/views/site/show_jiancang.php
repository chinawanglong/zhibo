<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14
 * Time: 15:32
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Shouted;

$shouted_goods=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedGood::find()->asArray()->all(),'id','name');
$shouted_teachers=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedTeacher::find()->asArray()->all(),'id','name');
?>
    <div class="shouted-index">
        <div class="show_title" style="background: #bfbfbf;color:black">建仓提醒</div>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'start_time',
                [
                    'header'=>'<a>类型</a>',
                    'attribute'=>'type',
                    'value'=>function($model){
                        return !empty(Shouted::$types[$model->type])?Shouted::$types[$model->type]:"";
                    }
                ],
                'point',
                [
                    'attribute'=>'title',
                    'value'=>function($model) use($shouted_goods){
                        return !empty($shouted_goods[$model->title])?$shouted_goods[$model->title]:"";
                    },
                ],
                'desc',
                'start_point',
                'stoploss',
                'limited',
                'pingprice',
                'yli',
                'pingtime',
                [
                    'attribute'=>'mai_type',
                    'value'=>function($model){
                        return !empty(Shouted::$mai_types[$model->mai_type])?Shouted::$mai_types[$model->mai_type]:"";
                    }
                ],
                [
                    'attribute'=>'postname',
                    'value'=>function($model){
                        return !empty($shouted_teachers[$model->postname])?$shouted_teachers[$model->postname]:"";
                    },
                ],
            ],
            'showHeader'=>true,
            'filterPosition'=>false,
            'summaryOptions'=>['style'=>'display:none']
        ]); ?>

    </div>
    <style type="text/css">
        html{
            width: 100%;
            min-height: 100%;
            padding:50px 35px;
            padding-top: 120px;
            background: url("/images/dialogbillBg.png");
            background-size:100% 100%;
        }
        .show_title{
            display: none;
            padding: 0px 15px;
            border-bottom: 1px solid transparent;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            font-size:15px;
            background-color: #bfbfbf;
            height: 45px;
            line-height: 45px;
            color: black;
            font-weight: bold;
            margin-bottom: 30px;
        }
    </style>
<?= Html::cssFile("@web/css/bootstrap.css"); ?>