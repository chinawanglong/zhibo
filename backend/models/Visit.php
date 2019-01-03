<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use Yii;


/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $data
 */
class Visit extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'visit';
    }

    public function rules()
    {
        return [
            [['reffer', 'os', 'resolution', 'browser_type','province', 'city', 'reffer_keywords','room_id', 'browser_version'],'safe'],
            [['uid',  'username', 'ip'],'required']
        ];
    }


    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'announcement', $this->announcement])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'allowrules', $this->allowrules]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名/昵称',
            'uid' => '用户id',
            'reffer' => '访客来源地址',
            'reffer_type' => '类型1推广2搜索',
            'reffer_keywords' => '搜索关键字',
            'room_id' => '房间号',
            'visit_out' => '下线时间',
            'ip' => 'ip地址',
            'province' => '省',
            'city' => '市',
            'browsertype' => '浏览器类型',
            'from_source' => '来源编码',
            'os' => '操作系统',
            'resolution' => '分辨率',
            'browser_version' => '浏览器版本',
            'created_at' => '创建时间'
        ];
    }


     public  function isCrawler() {
        $agent= strtolower($_SERVER['HTTP_USER_AGENT']);
        if (!empty($agent)) {
                $spiderSite= array(
                        "TencentTraveler",
                        "Baiduspider+",
                        "BaiduGame",
                        "Googlebot",
                        "msnbot",
                        "Sosospider+",
                        "Sogou web spider",
                        "ia_archiver",
                        "Yahoo! Slurp",
                        "YoudaoBot",
                        "Yahoo Slurp",
                        "MSNBot",
                        "Java (Often spam bot)",
                        "BaiDuSpider",
                        "Voila",
                        "Yandex bot",
                        "BSpider",
                        "twiceler",
                        "Sogou Spider",
                        "Speedy Spider",
                        "Google AdSense",
                        "Heritrix",
                        "Python-urllib",
                        "Alexa (IA Archiver)",
                        "Ask",
                        "Exabot",
                        "Custo",
                        "OutfoxBot/YodaoBot",
                        "yacy",
                        "SurveyBot",
                        "legs",
                        "lwp-trivial",
                        "Nutch",
                        "StackRambler",
                        "The web archive (IA Archiver)",
                        "Perl tool",
                        "MJ12bot",
                        "Netcraft",
                        "MSIECrawler",
                        "WGet tools",
                        "larbin",
                        "Fish search"
                );
                foreach($spiderSite as $val) {
                        $str = strtolower($val);
                        if (strpos($agent, $str) !== false) {
                                return true;
                        }
                }
        } else {
                return false;
        }
    }
    
     public function GetVisitReferrerType($url)
        {
            $url = trim($url);
            if ("" == $url)
            {
                return"0"; //没有来源           
            }
            elseif (strpos($url,"fromsource="))
            {
                return"1"; //推广链接            
            }
            elseif (strpos($url,"baidu.com"))
            {
                return"2"; // 百度搜索引擎            
            }
            elseif (strpos($url,"google.com"))
            {
                return"3"; // Google搜索引擎            
            }
            elseif (strpos($url,"sogou.com"))
            {
                return"4"; // 搜狗搜索引擎           
                 }
            elseif (strpos($url,"soso.com"))            {
                return"5"; // 搜搜搜索引擎            
            } 
            else {
                return"6"; // 其他浏览            
            }
        }


    public function behaviors()
    {
        return [
            [
                'class'=> TimestampBehavior::className(),
                'attributes' => [
                    # 创建之前
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],
                ],
                #设置默认值
                'value' => 'time'
            ]
        ];
    }
    
    

}
