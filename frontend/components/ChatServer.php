<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-5
 * Time: 下午2:49
 */
namespace frontend\components;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use backend\models\Zhibo;
use backend\models\Chat;
use backend\models\Onlineuser;
use backend\models\OnlineMinCount;
use backend\models\User;
use backend\models\Blacklist;
use backend\models\RoomRole;
use backend\models\ConfigCategory;
use backend\models\Shouted;
use frontend\components\Common;

class ChatServer extends SocketServer
{

    /**当前用户**/
    public $current_user;

    /*
     * 构造函数
     */
    public function __construct($servconfig)
    {
        parent::__construct($servconfig);
    }


    /**f
     * 主Reactor开始接收事件，客户端可以connect到Server
     */
    public function onStart($serv)
    {
        parent::onStart($serv);

        if (Onlineuser::deleteAll(['not like','ip','localhost'])) {
            $tablename = Onlineuser::tableName();
            Yii::$app->db->createCommand("ALTER TABLE {$tablename} AUTO_INCREMENT=1;")->execute();
            //清空聊天列表
        }
    }

    /*
     * 当工作进程启动的时候调用
     */
    public function onWorkerStart($serv, $worker_id)
    {
        parent::onWorkerStart($serv, $worker_id);
        /**每个work进程占用一个数据库连接,不使用全局连接**/
        Yii::$app->setComponents([
            'db' => Yii::$app->components['db'],
        ]);

        /*
         * 刷新缓存
         */
        /*Yii::$app->cache->flush();*/
        Yii::$app->db->open();

        if ($worker_id == 0) {
            $serv->tick(10000, function () {
                /**每10秒握手一次以防止自动断开**/
                $msg = ['cmd' => 'shakehand', 'time' => date('h:i:s', time())];
                $this->broadcastJson(0, $msg, true);
            });
            /****版权控制**/
            $serv->tick(3000, function () {
                return false;
                if (!empty($this->config['auth_code'])) {
                    $auth_code = $this->config['auth_code'];
                    try {
                        $auth_value = file_get_contents("http://demo.meilingzhibo.com/other/auth?code={$auth_code}");
                        if (trim($auth_value) == 2) {
                            Yii::$app->cache->set("auth_status", 0);
                            $this->broadcastJson(0, ['cmd' => 'tip', 'code' => "403", 'msg' => "授权失败,请联系美林官方客服!"], true);
                        } else {
                            Yii::$app->cache->set("auth_status", 1);
                        }
                    } catch (\Exception $e) {
                        Yii::$app->cache->set("auth_status", 1);
                    }
                } else {
                    Yii::$app->cache->set("auth_status", 1);
                }
            });
            /**系统提醒**/
            $serv->tick(3000, function () {
                $shouted_goods = \yii\helpers\ArrayHelper::map(\backend\models\ShoutedGood::find()->asArray()->all(), 'id', 'name');
                $shouted_teachers = \yii\helpers\ArrayHelper::map(\backend\models\ShoutedTeacher::find()->asArray()->all(), 'id', 'name');
                $added_shouted = Shouted::find()->where(['status' => 1, 'process' => 0])->all();
                foreach ($added_shouted as $i => $shouted) {
                    $shouted->process = 1;
                    $html = sprintf("<div class='handan_detail'><div style='font-size:14px; color:#0072ff;'><span style='font-weight:bold; color:#f30;'>即时策略：</span>单号：%s，<!--方向：%s，-->开仓交易商品【%s】<!--简述--></div><div class='show_detail'><a  class='viewbilldetail' data-value='%s' >查看详情</a></div></div>", $shouted->id, !empty(Shouted::$types[intval($shouted->type)]) ? Shouted::$types[intval($shouted->type)] : "", !empty($shouted_goods[intval($shouted->title)]) ? $shouted_goods[intval($shouted->title)] : "", $shouted->id);
                    $chat = new Chat();
                    $chat->fid = 0;
                    $chat->fromname = "系统消息";
                    $chat->lineid = 0;
                    $chat->type = "system";
                    $chat->content = $html;
                    $chat->ftime = date("Y-m-d H:i:s", time());
                    $chat->status = 1;
                    $chat->zhiboid = $shouted->zhiboid;
                    if ($shouted->save() && $chat->save()) {
                        $this->broadcastJson(0, ['cmd' => 'system', 'code' => "newhandan", 'zhiboid' => "{$shouted->zhiboid}", 'msg' => $html], true);
                    } else {
                        //$this->broadcastJson(0,['cmd' => 'system','code' => "newhandan",'msg' => print_r($chat->errors,true)],true);
                    }
                }

                $pingcang_shouted = Shouted::find()->where(['status' => 1, 'process' => 2])->all();
                foreach ($pingcang_shouted as $i => $shouted) {
                    $shouted->process = 3;
                    $html = sprintf("<div class='pingcang_detail'><div style='font-size:14px; color:#0072ff;'><span style='font-weight:bold; color:#f30;'>交易提示：</span>单号：%s，<!--方向：%s，-->平仓交易商品【%s】<!--简述--></div><div class='show_detail'><a  class='viewbilldetail' data-value='%s'>查看详情</a></div></div>", $shouted->id, !empty(Shouted::$types[intval($shouted->type)]) ? Shouted::$types[intval($shouted->type)] : "", !empty($shouted_goods[intval($shouted->title)]) ? $shouted_goods[intval($shouted->title)] : "", $shouted->id);
                    $chat = new Chat();
                    $chat->fid = 0;
                    $chat->fromname = "系统消息";
                    $chat->lineid = 0;
                    $chat->type = "system";
                    $chat->content = $html;
                    $chat->ftime = date("Y-m-d H:i:s", time());
                    $chat->status = 1;
                    $chat->zhiboid = $shouted->zhiboid;
                    if ($shouted->save() && $chat->save()) {
                        $this->broadcastJson(0, ['cmd' => 'system', 'code' => "handanpingcang", 'zhiboid' => "{$shouted->zhiboid}", 'msg' => $html], true);
                    } else {
                        //$this->broadcastJson(0,['cmd' => 'system','code' => "newhandan",'msg' => print_r($chat->errors,true)],true);
                    }
                }

                $current_shouted_teachers = \backend\models\ShoutedTeacher::find()->where(['if_current'=>1])->groupBy('zhiboid')->asArray()->all();
                foreach($current_shouted_teachers as $i=>$teacher){
                    $this->broadcastJson(0, ['cmd' => 'switchteacher', 'data' => "{$teacher['name']}", 'zhiboid' => "{$teacher['zhiboid']}", 'msg' => ''], true);
                }

            });

            /**机器人功能**/
            $robot_time_standard = abs(Zhibo::$robot_time_standard);
            $robot_time_standard = $robot_time_standard ? $robot_time_standard : 5;

            $serv->tick($robot_time_standard * 1000, function () use ($robot_time_standard) {
                $zhibos = Zhibo::find()->where(['status' => 1])->all();
                foreach ($zhibos as $i => $zhibo) {
                    if (!empty($zhibo->robot_time) && !empty($zhibo->robot_rate) && !empty($zhibo->robot_contents)) {
                        $rediskey = "zhibo_robot_last_{$zhibo->id}";
                        $last_robot_time = Yii::$app->cache->get($rediskey);
                        $before_time = $last_robot_time ? time() - $last_robot_time : 0;
                        $robot_time = abs($zhibo->robot_time);
                        if ($before_time && $before_time / $robot_time < 1) {
                            continue;
                        }
                        $zhibo_onlines = Onlineuser::find()->where(['zhiboid' => $zhibo->id, 'ip' => 'localhost'])->asArray()->all();
                        if(count($zhibo_onlines)==0){
                            continue;
                        }
                        $robot_contents = !empty($zhibo->robot_contents) ? explode('|', $zhibo->robot_contents) : [];
                        for ($i = 0; $i < $zhibo->robot_rate; $i++) {
                            $online_item = $zhibo_onlines[array_rand($zhibo_onlines, 1)];
                            $content_itemstr = $robot_contents[array_rand($robot_contents, 1)];
                            if (empty($online_item) || empty($content_itemstr)) {
                                continue;
                            }
                            $robot_chat = new Chat();
                            $robot_chat->lineid = 0;
                            $robot_chat->fid = intval($online_item['uid']);
                            $robot_chat->fromname = $online_item['temp_name'];
                            $robot_chat->toid = 0;
                            $robot_chat->toname = '';
                            $robot_chat->channal = 1;
                            $robot_chat->content = $content_itemstr;
                            $robot_chat->color = "";
                            $robot_chat->type = "text";
                            $robot_chat->ftime = date('Y-m-d H:i:s', time());
                            $robot_chat->zhiboid = $zhibo->id;
                            $robot_chat->status = 1;
                            if (!$robot_chat->save()) {
                                continue;
                            }
                            $resMsg = $this->getresMsg($robot_chat);
                            $this->broadcastJson(0, $resMsg);
                        }
                        Yii::$app->cache->set($rediskey, time());
                    }
                }
            });

            $serv->tick(60000, function () {
                $allroom_online_count = Onlineuser::findBySql('SELECT count(1) as count,zhiboid FROM onlineuser WHERE ip<>"localhost" GROUP BY zhiboid ')->asArray()->all();
                $online_min_count_model = new OnlineMinCount();
                $time = time();
                $time = $time-$time%60;
                foreach ($allroom_online_count as $attributes){
                    $attributes['create_at'] = $time;
                    $online_min_count_model->isNewRecord = true;
                    $online_min_count_model->setAttributes($attributes);
                    $online_min_count_model->save() && $online_min_count_model->id = 0;
                }
            });

            /***workerid=0代表第一个进程启动**/
        }
    }

    /**
     * @param $serv
     * @param $fd
     * @param $from_id
     * 在websocket连接过来时候onOpen和onConnect都会调用
     */
    public function onConnect($serv, $fd, $from_id)
    {

    }

    public function onOpen(\swoole_websocket_server $server, $request)
    {

    }

    public function onMessage(\swoole_websocket_server $server, $frame)
    {
        //echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        /*在接受到消息的时候做一些连接和缓存*/

        if (!empty(Yii::$app->db->pdo) && !Common::pdo_ping(Yii::$app->db->pdo)) {
            Yii::$app->db->close();
            Yii::$app->db->open();
            $log_str = "Mysql is not active.....\n";
            //Yii::info($log_str,"socketserver");
        }
        if ($frame->data) {
            /***获取数据***/
            $data = json_decode($frame->data, true);
            /***标记直播ID***/
            $online = Onlineuser::find()->where(['fd' => $frame->fd])->one();
            if ($online && !empty($online->zhiboid)) {
                $data['roomid'] = $online->zhiboid;
            }

            if (empty($data['cmd'])) {
                $this->sendMessage($frame->fd, 101, "invalid command");
                return;
            }
            $func = 'cmd_' . $data['cmd'];
            if (method_exists($this, $func)) {
                $this->$func($frame->fd, $data);
            } else {
                $this->sendMessage($frame->fd, 102, "command $func no support.");
                return;
            }

        }
    }

    /**
     * 发送错误信息
     * @param $serv 服务器
     * @param $task_id 任务进程id
     * @param $from_id 工作进程id
     * @param $data  传给task任务的数据
     */
    function onTask($serv, $task_id, $from_id, $data)
    {
        if (!empty(Yii::$app->db->pdo) && !Common::pdo_ping(Yii::$app->db->pdo)) {
            Yii::$app->db->close();
            Yii::$app->db->open();
            $log_str = "Mysql is not active.....\n";
            //Yii::info($log_str,"socketserver");
        }
        $req = unserialize($data);
        if ($req) {
            switch ($req['cmd']) {
                case 'broadcast':
                    $exclude = empty($req['exclude']) ? 0 : $req['exclude'];
                    $msg = $req['msg'];
                    foreach ($this->server->connections as $fd) {
                        //echo "broadcase $fd\n";
                        if (!is_array($exclude) && intval($exclude) == $fd) {
                            continue;
                        } else if (is_array($exclude) && in_array($fd, $exclude)) {
                            continue;
                        }
                        $this->send($fd, $msg);
                    }
                    break;
                case 'posttoadmin':
                    $msg = json_encode($req['msg']);
                    $fds = $this->getRoleFd();
                    $exclude = empty($req['exclude']) ? 0 : $req['exclude'];
                    foreach ($fds as $fd) {
                        if (!is_array($exclude) && intval($exclude) == $fd) {
                            continue;
                        } else if (is_array($exclude) && in_array($fd, $exclude)) {
                            continue;
                        }
                        if ($fd) {
                            $this->send($fd, $msg);
                        }
                    }
                    break;
                default:
                    break;
            }
        }
        $serv->finish('task ok');
        return;
    }

    /**
     *
     * @param $serv
     * @param $task_id
     * @param $str
     */
    function onFinish($serv, $task_id, $str)
    {
        //echo "Task(ID:$task_id) excute ok,result is ".$str."\n";
    }


    /**
     * 当客户端关闭连接时候
     * @param $server
     * @param $fd
     * @param $from_id
     */
    public function onClose(\swoole_websocket_server $server, $fd)
    {
        if (!empty(Yii::$app->db->pdo) && !Common::pdo_ping(Yii::$app->db->pdo)) {
            Yii::$app->db->close();
            Yii::$app->db->open();
            $log_str = "Mysql is not active.....\n";
            //Yii::info($log_str,"socketserver");
        }
        //echo "fd:$fd closed\n";
        $onlineInfos = Onlineuser::find()->where(['fd' => $fd])->all();
        foreach ($onlineInfos as $onlineInfo) {
            if ($onlineInfo->uid) {

                Onlineuser::deleteAll(['uid' => $onlineInfo->uid]);
            } else {
                Onlineuser::deleteAll(['temp_name' => $onlineInfo->temp_name]);
            }
        }

        if (empty($onlineInfo)) {
            return;
        }
        $resMsg = array(
            'cmd' => 'offline',
            'lineid' => $onlineInfo->id,
            'zhiboid' => $onlineInfo->zhiboid,
            'fd' => $fd,
            'uid' => $onlineInfo->uid,
            'temp_name' => $onlineInfo->temp_name
        );
        //将下线消息发送给所有人
        $this->broadcastJson($fd, $resMsg);
    }

    /**
     * 发送错误信息
     * @param $client_id
     * @param $code
     * @param $msg
     */
    function sendMessage($client_id, $code, $msg)
    {
        $this->sendJson($client_id,
            array(
                'cmd' => 'tip',
                'code' => $code,
                'msg' => $msg
            )
        );
    }

    /**
     * 发送JSON数据
     * @param $client_id
     * @param $array
     */
    function sendJson($client_id, $array)
    {
        $data = json_encode($array);
        if ($this->send($client_id, $data) == false) {
            $this->close($client_id);
        }
    }

    /**
     * 发送String数据
     * @param $fd
     * @param $data
     */
    function send($fd, $data)
    {
        try {
            if ($this->server->connection_info($fd)) {
                $this->server->push($fd, $data);
                return true;
            } else {
                return false;
            }
        } catch (\yii\base\ErrorException $e) {
            //Yii::info($e->getMessage(),"socketserver");
            return true;
        }
    }
    /*****************下面的代码为广播事件执行代码**********/
    /**
     * 广播JSON数据
     * @param $client_id
     * @param $array
     */
    function broadcastJson($exclude, $array, $easy = false)
    {
        $msg = json_encode($array);
        $this->broadcast($exclude, $msg, $easy);
    }

    /*如果是急切加载那么就直接广播否则会使用task*/
    function broadcast($exclude = 0, $msg, $easy = false)
    {
        if (empty($easy)) {
            $task = ['cmd' => 'broadcast', 'exclude' => $exclude, 'msg' => $msg];
            $this->server->task(serialize($task));
        } else {
            foreach ($this->server->connections as $fd) {
                //echo "broadcase $fd\n";
                if (!is_array($exclude) && intval($exclude) == $fd) {
                    continue;
                } else if (is_array($exclude) && in_array($fd, $exclude)) {
                    continue;
                }
                $this->send($fd, $msg);
            }
        }
    }


    /************************************************业务处理函数***************************************/


    /**
     * 登录
     * @param $client_id
     * @param $msg
     */
    function cmd_login($fd, $msg)
    {
        $roomid = !empty($msg['roomid']) ? strval($msg['roomid']) : "";
        $uid = !empty($msg['uid']) ? intval($msg['uid']) : 0;
        $ip = !empty($msg['ip']) ? $msg['ip'] : "";
        $temp_name = !empty($msg['temp_name']) ? $msg['temp_name'] : "";

        //回复给登录用户的信息样板
        $resMsg = array(
            'cmd' => 'login',
            'fd' => $fd,
            'uid' => $uid,
            'temp_name' => '',
            'zhiboid' => $roomid
        );


        //登录，把会话存起来,同时删除之前的有相同uid,temp_name,和fd的在线信息
        $online = new Onlineuser();
        $online->fd = $fd;
        $online->zhiboid = $roomid;

        Onlineuser::deleteAll(['fd' => $fd]);

        $other_accounts = [];
        if (!empty($uid)) {
            Onlineuser::deleteAll(['uid' => $uid]);
            $online->uid = intval($uid);

            /***把所有账户添加进去***/

            /**如果是管理员,先添加自己**/
            $online_user = User::findOne($uid);
            if ($online_user) {
                $online_user_roomrole = $online_user->room_role;
                if ($online_user_roomrole && $online_user_roomrole->isAdmin) {
                    $other_accounts[] = [
                        'from' => $online_user->id,
                        'fromname' => $online_user->ncname,
                        'from_roleid' => $online_user->roomrole,
                        'from_pic' => $online_user->img,
                    ];
                }
            }
            /***再添加一个游客**/
            /*$guest_role = RoomRole::getConfigbyalias("guest");
            $temp_name = "游客-" . Onlineuser::getuniqname(5);
            $other_accounts[]=[
                'from'=>0,
                'fromname'=>$temp_name,
                'from_roleid'=>$guest_role->id
            ];*/
            /*
             * 再添加其他账号
             */
            if ($online_user) {
                $childusers = $online_user->childusers;
                foreach ($childusers as $item) {
                    $other_accounts[] = [
                        'from' => $item->id,
                        'fromname' => $item->ncname,
                        'from_roleid' => $item->roomrole,
                        'from_pic' => $item->img,
                    ];
                }
            }
        } else {
            $online->uid = 0;
            $online->temp_name = !empty($temp_name) ? $temp_name : ("游客-" . Onlineuser::getuniqname(5));
            Onlineuser::deleteAll(['temp_name' => $online->temp_name]);
        }
        $online->ip = empty($ip) ? "" : $ip;
        $online->time = date('Y-m-d H:i:s', time());
        if ($online->save()) {
            $this->current_user = $online;

            /**同时将其他账户加入在线列表**/
            if (count($other_accounts) > 0) {
                $resMsg['otheraccounts'] = [];
                foreach ($other_accounts as $item) {
                    if (!empty($uid) && $item['from'] == $uid) {
                        $resMsg['otheraccounts'][] = [
                            'from' => $item['from'],
                            'fromname' => $item['fromname'],
                            'from_roleid' => $item['from_roleid'],
                            'from_pic' => $item['from_pic']
                        ];
                    } else {
                        $newonline = new Onlineuser();
                        $newonline->fd = $fd;
                        $newonline->zhiboid = $roomid;
                        $newonline->ip = $online->ip;
                        $newonline->time = $online->time;
                        $newonline->uid = $item['from'];
                        $newonline->temp_name = $item['fromname'];
                        if ($newonline->save()) {
                            $resMsg['otheraccounts'][] = [
                                'from' => $item['from'],
                                'fromname' => $item['fromname'],
                                'from_roleid' => $item['from_roleid'],
                                'from_pic' => $item['from_pic']
                            ];
                        }
                    }
                    /**逐个添加**/
                }
            }
        } else {
            return false;
        }

        //告知用户socket登录成功，可以执行下一步动作
        $resMsg['temp_name'] = $online->temp_name;
        $this->sendJson($fd, $resMsg);

        //广播给其他人用户的登录动态

        //如果是游客，并且设置不加载游客的话那么不广播用户登录
        $zhibo = Zhibo::findOne($roomid);
        if (!$online->uid && $zhibo && !$zhibo->loadguest) {
            return;
        }
        $loginMsg = array(
            'cmd' => 'newUser',
            'zhiboid' => $online->zhiboid,
            'data' => '',
        );
        if ($online) {
            /*确保此时广播的用户信息和实际进行登陆的是同一个用户*/
            $loginMsg['data'] = $online->getUserinfo();
        }
        $this->broadcastJson(0, $loginMsg);
    }

    /**
     * 获取历史聊天记录
     */
    function cmd_getHistory($fd, $msg)
    {
        $task['cmd'] = 'getHistory';
        $task['fd'] = $fd;
        $task['from'] = $msg['from'];
        $lastid = !empty($msg['lastid']) && abs($msg['lastid']) > 0 ? abs($msg['lastid']) : 0;
        $page = !empty($msg['page']) && abs($msg['page']) > 0 ? abs($msg['page']) : 1;
        $limit = !empty($msg['limit']) && abs($msg['limit']) > 0 ? abs($msg['limit']) : 100;
        $task['lastid'] = $lastid;
        $task['offset'] = ($page - 1) * $limit;
        $task['limit'] = $limit;
        $task['roomid'] = !empty($msg['roomid']) ? $msg['roomid'] : 0;


        $historys = $this->getHistory($task);
        $response = array('cmd' => 'getHistory', 'history' => $historys);

        //直接发送给客户端
        $this->sendJson($fd, $response);
    }

    /*
     * 加载历史私聊
     */
    function cmd_getAllPrivateHistory($fd, $msg)
    {
        $task['cmd'] = 'getAllPrivateHistory';
        $task['fd'] = $fd;
        $task['from'] = $msg['from'];
        $task['fromname'] = $msg['fromname'];
        /***私聊的提交信息***/
        $otherid = !empty($msg['otherid']) && abs($msg['otherid']) > 0 ? abs($msg['otherid']) : 0;
        $othername = !empty($msg['othername']) ? $msg['othername'] : "";
        $lastid = !empty($msg['lastid']) && abs($msg['lastid']) > 0 ? abs($msg['lastid']) : 0;
        $task['otherid'] = $otherid;
        $task['othername'] = $othername;
        $task['lastid'] = $lastid;
        $task['roomid'] = !empty($msg['roomid']) ? $msg['roomid'] : 0;

        $historys = $this->getPrivateHistory($task);
        $response = array('cmd' => 'getAllPrivateHistory', 'data' => $historys);

        //直接发送给客户端
        $this->sendJson($fd, $response);
        //echo "send private history ok \n";
    }

    //接受聊天信息
    function cmd_message($fd, $msg)
    {
        $auth_status = Yii::$app->cache->get("auth_status");

        if (empty($auth_status)) {
            //return $this->sendMessage($fd, 110, "授权失败,无法发言,请联系官方客服!");
        }
        if (strlen(ArrayHelper::getValue($msg, 'data', '')) > 1024)//单条信息不能超过1K
        {
            $this->sendMessage($fd, 102, 'message max length is 1024');
            return;
        }
        /***获得站点配置信息***/
        $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
        /***当前登录的用户***/
        $onlineuser = Onlineuser::find()->where(['fd' => $fd])->one();
        if (!empty($msg['from'])) {
            $onlineuser = Onlineuser::find()->where(['fd' => $fd, 'uid' => $msg['from']])->one();
        } else if (!empty($msg['fromname'])) {
            $onlineuser = Onlineuser::find()->where(['fd' => $fd, 'temp_name' => $msg['fromname']])->one();
        }
        if (empty($onlineuser)) {
            return $this->sendMessage($fd, 106, '系统错误,刷新浏览器试试' . $fd);
        }
        if (!empty($onlineuser->user->room_role)) {
            $userrole = $onlineuser->user->room_role;
        } else {
            $userrole = RoomRole::getConfigbyalias("guest");
        }

        /**查看用户状态**/
        if (!empty($onlineuser->user)) {
            $user = $onlineuser->user;
            if (!empty($siteconfig->check_nickname->val) && $user->status == 2) {
                return $this->sendMessage($fd, 114, "你的昵称还未被审核通过,请稍后再试!");
            }
            if ($user->status != 1) {
                return $this->sendMessage($fd, 115, "你当前被限制!");
            }
        }

        /***屏蔽黑名单用户的所有消息***/
        if (!empty($onlineuser->uid)) {
            $blackitem = Blacklist::find()->where(['uid' => $onlineuser->uid])->one();
        } else if (!empty($onlineuser->temp_name)) {
            $blackitem = Blacklist::find()->where(['uid' => 0, 'temp_name' => $onlineuser->temp_name])->one();
        }
        if (!empty($blackitem)) {
            return $this->sendMessage($fd, 113, "你当前被禁");
        }


        /*******聊天权限控制******/
        if (ArrayHelper::getValue($msg, 'channal') != 2) {
            if (empty($userrole->enable_publish_chat->val)) {
                $this->sendMessage($fd, 109, "你没有公聊权限");
                return;
            }
        } else if (ArrayHelper::getValue($msg, 'channal') == 2) {
            if (empty($userrole->private_chat->val)) {
                $this->sendMessage($fd, 112, "你没有私聊权限");
                return;
            }
        }


        /***查看有没有被禁言***/
        if (!empty($onlineuser->jinyan) && (strtotime($onlineuser->jinyan) > time())) {
            return $this->sendMessage($fd, 107, '您当前被禁言');
        }

        /****检查是否自己对自己聊****/
        if (in_array(ArrayHelper::getValue($msg, 'channal', 0), [1, 2])) {
            if (!empty($msg['from']) && !empty($msg['to']) && (intval($msg['from']) == intval($msg['to']))) {
                $repeated = true;
                /*member用户信息不能重复*/
            } else if (empty($msg['from']) && empty($msg['to']) && !empty($msg['fromname']) && !empty($msg['toname']) && ($msg['fromname'] == $msg['toname'])) {
                $repeated = true;
                /*游客信息不能重复*/
            }
            if (!empty($repeated)) {
                $this->sendMessage($fd, 101, '自己不能和自己对话!');
                return;
            }
        }

        /****检查是否开启审核****/
        if (!empty($msg['enable_check'])) {
            /****检查角色是否需要审核****/
            if ($userrole->speeking_check && $userrole->speeking_check->val) {
                $msg['needcheck'] = 1;
            } else {
                $msg['needcheck'] = 0;
            }
        } else {
            $msg['needcheck'] = 0;
        }

        /****间隔控制******/
        if (ArrayHelper::getValue($msg, 'channal') != 2) {
            /*公聊间隔只针对公聊不针对彩条*/
            $rediskey = !empty($msg['from']) ? intval($msg['from']) : (!empty($msg['fromname']) ? HtmlPurifier::process($msg['fromname'], ['HTML.Allowed' => '']) : "游客");

            $rediskey = base64_encode($rediskey) . 'lasttime';
            $lasttime = 0;
            //$lasttime=Yii::$app->redis->hget($rediskey,'lasttime');
            $lasttime = Yii::$app->cache->get($rediskey);

            if (ArrayHelper::getValue($msg, "type", "text") == "text") {
                if ($userrole) {
                    $interval_time = !empty($userrole->publish_chat_time->val) ? intval($userrole->publish_chat_time->val) : 0;
                }
                if ($lasttime && $interval_time && ($leavetime = ($lasttime + $interval_time) - time()) > 0) {
                    $this->sendMessage($fd, 110, "你的发言间隔为" . $interval_time . "秒,还剩" . $leavetime . "秒!");
                    return;
                }
                /*公聊间隔控制*/
            } else if (ArrayHelper::getValue($msg, "type") == "caitiao") {
                if ($userrole) {
                    $interval_time = !empty($userrole->color_interval->val) ? intval($userrole->color_interval->val) : 0;
                }
                if ($lasttime && $interval_time && ($leavetime = ($lasttime + $interval_time) - time()) > 0) {
                    $this->sendMessage($fd, 111, "你的彩条间隔是" . $interval_time . "秒,还剩" . $leavetime . "秒!");
                    return;
                }
                /*彩条间隔*/
            }

            /****存储上次聊天时间****/
            Yii::$app->cache->set($rediskey, time(), 3600);
            /*间隔控制*/
        }


        /***保存聊天信息***/
        $msgmodel = $this->saveChat($fd, $msg);
        $msgid = $msgmodel->id;
        if (!$msgid) {
            $this->sendMessage($fd, 102, "消息发送失败!");
            return;
        }
        /***获得组装的消息格式***/
        $resMsg = $this->getresMsg($msgmodel);

        /***表示群发***/
        if (ArrayHelper::getValue($msg, 'channal') != 2) {
            if (ArrayHelper::getValue($msg, 'channal') == 1) {
                if (!empty($resMsg['to'])) {
                    $toonline = Onlineuser::find()->where(['uid' => intval($resMsg['to'])])->one();
                } else if (!empty($resMsg['toname'])) {
                    $toonline = Onlineuser::find()->where(['temp_name' => $resMsg['toname']])->one();
                }
                if (empty($toonline)) {
                    $this->sendMessage($fd, '105', '对方现在不在线!');
                }
                /*如果是对谁说*/
            }

            /***看是否需要审核***/
            if (!empty($msg['needcheck'])) {
                //需要审核,将消息广播给具有审核权限的人
                $task = ['cmd' => 'posttoadmin', 'fd' => $fd, 'msg' => $resMsg, 'exclude' => [$fd]];
                $this->server->task(serialize($task));
                return;
            } else {
                //广播
                $this->broadcastJson($fd, $resMsg);
                return;
            }

        } else if (ArrayHelper::getValue($msg, 'channal') == 2) {

            if (!empty($msg['to'])) {
                $toonlineinfo = Onlineuser::find()->where(['uid' => intval($msg['to'])])->one();
                if (!empty($toonlineinfo)) {
                    $fd2 = $toonlineinfo->fd;
                }
            } else if (!empty($msg['toname'])) {
                $toonlineinfo = Onlineuser::find()->where(['temp_name' => $msg['toname']])->orderBy(['id' => SORT_DESC])->one();
                if (!empty($toonlineinfo)) {
                    $fd2 = $toonlineinfo->fd;
                }
            }
            if (!empty($fd2) && $fd2 != $fd) {
                //发送私聊消息体
                //echo "jike.................";
                $resMsg['cmd'] = 'fromMsg';
                $this->sendJson($fd2, $resMsg);
            } else if (empty($fd2)) {
                $this->sendMessage($fd, '103', '对方已不在线!');
            }
            /*若是私聊*/
        }
    }


    //审核完消息后再将此条消息广播一遍
    function cmd_check($fd, $msg)
    {
        //查询消息体
        $msgs = Chat::findOne($msg['msgid']);
        //不要广播给发送者
        $from = $msg['sfid'];//在线id
        $s = Onlineuser::findOne($from);//查找发送者
        if ($s) {//还在线的
            $sid = $s->fd;
        } else {//已经不在线或者刷新了
            if (!empty($msgs->fid)) {//非游客
                $sy = Onlineuser::find()->where(['uid' => $msgs->fid])->orderBy(['id' => SORT_DESC])->one();
                if (!empty($sy)) {
                    $sid = $sy->fd;
                } else {
                    $sid = '';
                }
            } else {//游客
                $sy = Onlineuser::find()->where(['temp_name' => $msgs->fromname])->orderBy(['id' => SORT_DESC])->one();
                if (!empty($sy)) {
                    $sid = $sy->fd;
                } else {
                    $sid = '';
                }
            }
        }
        $sendmsg = $this->getresMsg($msgs);
        $sendmsg = json_encode($sendmsg);
        foreach ($this->server->connections as $other) {
            if ($fd != $other && $other != $sid)//审核者和发送者不会再次接收到
            {
                $this->send($other, $sendmsg);
            }
        }
    }

    /*******操作用户的指令*******/
    function cmd_handleuser($fd, $msg)
    {
        $handle = trim(ArrayHelper::getValue($msg, 'handle'));
        $uid = ArrayHelper::getValue($msg, 'uid');
        $uname = ArrayHelper::getValue($msg, 'uname');
        $tfd = 0;
        /**管理在线信息**/
        $adminonline = Onlineuser::find()->where(['fd' => $fd])->one();
        if (empty($adminonline) || empty($adminonline->user)) {
            return $this->sendMessage($fd, "", "没有权限!");
        }
        if (trim($handle) == "unable_speaking" && empty($adminonline->user->room_role->unable_speaking->val)) {
            return $this->sendMessage($fd, "", "没有将用户禁言的权限!");
        } else if (trim($handle) == "enable_speaking" && empty($adminonline->user->room_role->unable_speaking->val)) {
            return $this->sendMessage($fd, "", "没有将用户解除禁言的权限!");
        } else if (trim($handle) == "shot_off_room" && empty($adminonline->user->room_role->shot_off_room->val)) {
            return $this->sendMessage($fd, "", "没有将用户踢出房间的权限!");
        } else if (trim($handle) == "addblack" && empty($adminonline->user->room_role->addblack->val)) {
            return $this->sendMessage($fd, "", "没有将用户加入黑名单的权限!");
        }
        /**用户在线信息**/
        if (!empty($uid)) {
            $toonline = Onlineuser::find()->where(['uid' => $uid])->one();
        } else {
            $toonline = Onlineuser::find()->where(['temp_name' => $uname])->one();
        }
        if (empty($toonline)) {
            throw new Exception('该用户已不在线');
        }
        $tfd = intval($toonline->fd);
        if ($tfd) {
            if ($handle == "unable_speaking") {
                $forbid_endtime = strtotime($toonline->jinyan);
                $msgs = [
                    'cmd' => 'unable_speaking',
                    'msg' => '您已经被管理员禁言！',
                    'forbid_endtime' => $forbid_endtime
                ];
                $this->sendJson($tfd, $msgs);
            } else if ($handle == "enable_speaking") {
                $msgs = [
                    'cmd' => 'enable_speaking',
                    'msg' => '您已经被管理员解除禁言！',
                ];
                $this->sendJson($tfd, $msgs);
            } else if ($handle == "shot_off_room") {
                $msgs = [
                    'cmd' => 'kickout',
                    'msg' => '您已经被管理员踢出房间！'
                ];
                $this->sendJson($tfd, $msgs);
                $this->sendMessage($fd, "", "已成功将用户踢出房间!");
            } else if ($handle == "addblack") {
                $msgs = [
                    'cmd' => 'addblack',
                    'msg' => '你已经被加入黑名单！'
                ];
                $this->sendJson($tfd, $msgs);
            }
            return;
        } else {
            $this->sendMessage($fd, "", "用户已经不在房间!");
        }
    }

    /*******操作用户的指令*******/
    function cmd_handlemsg($fd, $msg)
    {
        $handle = trim(ArrayHelper::getValue($msg, 'handle'));
        $msgid = ArrayHelper::getValue($msg, 'msgid');
        $roomid = ArrayHelper::getValue($msg, 'roomid');
        /**管理在线信息**/
        $adminonline = Onlineuser::find()->where(['fd' => $fd])->one();
        if (empty($adminonline) || empty($adminonline->user)) {
            return $this->sendMessage($fd, "", "没有权限!");
        }
        if (trim($handle) == "check_msg" && empty($adminonline->user->room_role->check_msg->val)) {
            return $this->sendMessage($fd, "", "没有审核聊天的权限!");
        } else if (trim($handle) == "delete_msg" && empty($adminonline->user->room_role->delete_msg->val)) {
            return $this->sendMessage($fd, "", "没有删除聊天的权限!");
        }
        if ($handle == "check_msg") {
            $chat = Chat::findOne($msgid);
            $online = $chat->online;
            if ($online) {
                $chat_fd = $online->fd;
            } else {
                $chat_fd = 0;
            }
            if ($chat) {
                $broaddata = $this->getresMsg($chat);
                /**不再发给审核人和消息发送者**/
                return $this->broadcastJson([$fd, $chat_fd], $broaddata);
            }
        } else if ($handle == "delete_msg") {
            $broaddata = [
                'cmd' => 'delete_msg',
                'msgid' => $msgid,
                'zhiboid' => $roomid
            ];
            return $this->broadcastJson(0, $broaddata);
        }
    }

    /*
     * 喜欢直播间
     */
    function cmd_likezhibo($fd, $msg)
    {
        $roomid = intval(trim(ArrayHelper::getValue($msg, 'roomid')));
        $like_num = intval(ArrayHelper::getValue($msg, 'like_num'));
        $zhibo = Zhibo::findOne($roomid);
        if (empty($zhibo)) {
            return;
        }
        $zhibo->zan_num += $like_num;
        $zhibo->save();
        $broaddata = [
            'cmd' => 'likezhibo',
            'zan_num' => $zhibo->zan_num,
            'zhiboid' => $roomid
        ];
        return $this->broadcastJson(0, $broaddata);
    }

    /*
     * 获得历史聊天记录
     */
    function getHistory($req)
    {
        if (!$req) {
            $req = ['lastid' => 0, 'offset' => 0, 'limit' => 10];
        }
        $historys = [];
        //获得所有管理者的fd,根据请求者身份显示不同的聊天内容
        $rolefds = $this->getRoleFd();
        if (is_array($rolefds) && in_array($req['fd'], $rolefds, true)) {
            //查询全部的聊天
            $temp_chat_query = Chat::find()->with('user', 'user.onlineinfo')->where(['or', 'channal=1', 'channal=0']);
        } else {
            //只加载审核过的,即state=1的聊天
            $temp_chat_query = Chat::find()->with('user', 'user.onlineinfo')->where(['status' => 1])->andWhere(['or', 'channal=1', 'channal=0']);
        }

        /**过滤消息**/
        if (!empty($req['roomid'])) {
            $temp_chat_query->andWhere(['zhiboid' => $req['roomid']]);
        }

        if (!empty($req['lastid'])) {
            $temp_chat_query->andWhere(['<', 'id', intval($req['lastid'])]);
        } else {
            $temp_chat_query->offset($req['offset']);
        }
        $temp_chat_query->limit($req['limit'])->orderBy(['id' => SORT_DESC]);

        $history_query = Chat::find()->from(['temp' => $temp_chat_query])->orderBy([
            'id' => SORT_ASC,
        ]);
        //echo $temp_chat_query->createCommand()->getRawSql();

        $history_msgs = $history_query->all();
        foreach ($history_msgs as $i => $msg) {
            $historys[] = $this->getresMsg($msg);
        }
        return $historys;
    }

    /**获得私聊的历史聊天记录**/
    function getPrivateHistory($req)
    {
        if (!$req) {
            return [];
        }
        $historys = [];
        $alllimit = 6;//所有查询的记录数目查询
        $singlelimit = 6;
        $uid = ArrayHelper::getValue($req, 'from');
        $uname = ArrayHelper::getValue($req, 'fromname');
        $otherid = ArrayHelper::getValue($req, 'otherid');
        $othername = ArrayHelper::getValue($req, 'othername');
        $selfcode = $uid ? $uid : $uname;
        if ($uid) {
            $temp_chat_query = Chat::find()
                ->where(['status' => 1, 'channal' => 2])->andWhere(['or', ['fid' => $uid], ['toid' => $uid]]);
        } else if (!$uid && $uname) {
            $temp_chat_query = Chat::find()
                ->where(['status' => 1, 'channal' => 2])->andWhere(['or', ['toid' => 0, 'toname' => $uname], ['fid' => 0, 'fromname' => $uname]]);
        }

        /**过滤消息**/
        if (!empty($req['roomid'])) {
            $temp_chat_query->andWhere(['zhiboid' => $req['roomid']]);
        }

        if ($otherid || $othername) {
            if ($otherid) {
                $temp_chat_query->andWhere(['or', ['fid' => $otherid], ['toid' => $otherid]]);
            } else if ($othername) {
                $temp_chat_query->andWhere(['or', ['toid' => 0, 'toname' => $othername], ['fid' => 0, 'fromname' => $othername]]);
            }
            if (!empty($req['lastid'])) {
                $temp_chat_query->andWhere(['<', 'id', intval($req['lastid'])]);
            }
            $temp_chat_query->limit($singlelimit);
            /**说明是加载私聊**/
        } else {
            $temp_chat_query->limit($alllimit);
            /**说明是加载公聊**/
        }

        $temp_chat_query->orderBy(['id' => SORT_DESC]);

        $history_query = Chat::find()->from(['temp' => $temp_chat_query])->orderBy([
            'temp.id' => SORT_ASC,
        ]);

        //查询所有聊天
        $history_msgs = $history_query->all();
        $guestrole = RoomRole::getConfigbyalias('guest');

        foreach ($history_msgs as $i => $msg) {
            /*
             * 其中uid,uname,roleid唯一标记对方信息,
             * endmsgid用来之后的用户排序,实现新联系的用户显示在最前面
             */
            $chatul_item = ['uid' => 0, 'uname' => '', 'roleid' => '', 'endmsgid' => 0, 'historys' => []];
            $msgcode_from = !empty($msg['fid']) ? ArrayHelper::getValue($msg, 'fid') : ArrayHelper::getValue($msg, 'fromname');
            $msgcode_to = !empty($msg['toid']) ? ArrayHelper::getValue($msg, 'toid') : ArrayHelper::getValue($msg, 'toname');
            $chat_index = "";
            $chat_user = "";
            if (trim($selfcode) == trim($msgcode_from)) {
                $chatul_item['uid'] = ArrayHelper::getValue($msg, 'toid');
                $chat_user = $msg->touser;
                if ($chat_user) {
                    $chatul_item['uname'] = $chat_user->ncname;
                    $chatul_item['roleid'] = ArrayHelper::getValue($chat_user, "room_role.id");
                } else {
                    $chatul_item['uname'] = ArrayHelper::getValue($msg, 'toname');
                    $chatul_item['roleid'] = $guestrole->id;
                }
                $chat_index = crc32($msgcode_to);
            } else {
                $chatul_item['uid'] = ArrayHelper::getValue($msg, 'fid');
                $chatul_item['uname'] = ArrayHelper::getValue($msg, 'fromname');
                $chat_user = $msg->user;
                if ($chat_user) {
                    $chatul_item['uname'] = $chat_user->ncname;
                    $chatul_item['roleid'] = ArrayHelper::getValue($chat_user, "room_role.id");
                } else {
                    $chatul_item['uname'] = ArrayHelper::getValue($msg, 'fromname');
                    $chatul_item['roleid'] = $guestrole->id;
                }
                $chat_index = crc32($msgcode_from);
            }
            $resMsg = $this->getresMsg($msg);
            if (!ArrayHelper::getValue($historys, $chat_index)) {
                $historys[$chat_index] = $chatul_item;
            }
            $historys[$chat_index]['endmsgid'] = ArrayHelper::getValue($resMsg, 'msgid');
            $historys[$chat_index]['historys'][] = $resMsg;
        }
        ArrayHelper::multisort($historys, ['endmsgid'], [SORT_DESC]);
        return $historys;
    }

    function saveChat($fd, $msg)
    {
        $channal = !empty($msg['channal']) ? $msg['channal'] : 0;
        $model = new Chat();
        //发送者的fd
        $line_model = Onlineuser::find()->where(['fd' => $fd])->one();
        if (!empty($msg['from'])) {
            $line_model = Onlineuser::find()->where(['fd' => $fd, 'uid' => $msg['from']])->one();
        } else if (!empty($msg['fromname'])) {
            $line_model = Onlineuser::find()->where(['fd' => $fd, 'temp_name' => $msg['fromname']])->one();
        }
        if (!empty($line_model)) {
            $model->lineid = $line_model->id;
        } else {
            $model->lineid = 0;
        }
        $model->fid = intval($line_model->uid);
        if (!$model->fid) {
            $model->fromname = !empty($msg['fromname']) ? HtmlPurifier::process($msg['fromname'], ['HTML.Allowed' => '']) : "";
        }
        //以在线用户的userid或则temp_name作为标记唯一的一个用户
        $model->toid = !empty($msg['to']) ? intval($msg['to']) : 0;
        if (!$model->toid) {
            $model->toname = !empty($msg['toname']) ? HtmlPurifier::process($msg['toname'], ['HTML.Allowed' => '']) : "";
        }
        $model->channal = $channal;
        $model->content = HtmlPurifier::process($msg['data'], ['HTML.Allowed' => '']);
        $model->color = !empty($msg['color']) ? HtmlPurifier::process($msg['color'], ['HTML.Allowed' => '']) : "";

        $model->type = !empty($msg['type']) ? $msg['type'] : 0;
        $model->ftime = date('Y-m-d H:i:s', time());
        $model->zhiboid = !empty($msg['roomid']) ? intval($msg['roomid']) : 0;
        if ($msg['channal'] == 2) {
            /*私聊信息的状态,不需要审查直接就是1*/
            $model->status = 1;
        } else {
            $model->status = empty($msg['needcheck']) ? 1 : 0;
            /*根据是否审核设置状态*/
        }
        if (!$model->save()) {
            var_dump($model->errors);
        }
        return $model;
    }

    /**根据消息model组装回复消息**/
    function getresMsg($msgmodel)
    {
        $resMsg = [
            'cmd' => 'fromMsg',
            "msgid" => "",
            "channal" => '',
            "from" => 0,//发送者id
            "fromname" => "",
            "from_roleid" => "",
            "from_pic" => "",//发送者图片
            "from_rolename" => "",//发送者角色名称
            "to" => "",//接受者id
            "toname" => "",
            "to_roleid" => "",
            "to_pic" => "",//接收者图片
            "to_rolename" => "",//接受者角色名称
            "type" => '',
            "color" => '',
            "data" => "",
            "time" => "",
            "ischeck" => 0,
            "roomid" => 0,
            "zhiboid" => 0
        ];
        //组装消息格式
        $resMsg['msgid'] = $msgmodel->id;
        $resMsg['channal'] = $msgmodel->channal;
        $resMsg['type'] = $msgmodel->type;
        $resMsg['color'] = HtmlPurifier::process($msgmodel->color, ['HTML.Allowed' => '']);
        if (!in_array($msgmodel->type, ['system', 'systip'])) {
            $resMsg['data'] = HtmlPurifier::process($msgmodel->content, ['HTML.Allowed' => '']);
        } else {
            $resMsg['data'] = $msgmodel->content;
        }
        if ($msgmodel->ftime && (date("Y-m-d", strtotime($msgmodel->ftime)) == date("Y-m-d", time()))) {
            $resMsg['time'] = date("H:i:s", strtotime($msgmodel->ftime));
        } else if ($msgmodel->ftime && (date("Y", strtotime($msgmodel->ftime)) == date("Y", time()))) {
            $resMsg['time'] = date("m-d H:i:s", strtotime($msgmodel->ftime));
        } else if ($msgmodel->ftime) {
            $resMsg['time'] = date("Y-m-d H:i:s", strtotime($msgmodel->ftime));
        }
        //游客角色
        $guestmodel = RoomRole::find()->where(['alias' => 'guest'])->one();

        //发送者信息
        $resMsg['from'] = $msgmodel->fid;
        if ($msgmodel->fid && $msgmodel->user) {
            $resMsg['fromname'] = HtmlPurifier::process($msgmodel->user->ncname ? $msgmodel->user->ncname : $msgmodel->user->username, ['HTML.Allowed' => '']);
            $resMsg['from_roleid'] = $msgmodel->user->roomrole;
            $resMsg['from_pic'] = $msgmodel->user->img;
        } else {
            $resMsg['fromname'] = HtmlPurifier::process($msgmodel->fromname, ['HTML.Allowed' => '']);
            $resMsg['from_roleid'] = $guestmodel->id;
        }
        $fromrole = RoomRole::findOne($resMsg['from_roleid']);
        if ($fromrole) {
            $resMsg['from_rolename'] = $fromrole->name ? $fromrole->name : '';
        }

        //接受者信息
        $resMsg['to'] = $msgmodel->toid;
        if (empty($msgmodel->toid)) {
            $resMsg['toname'] = HtmlPurifier::process($msgmodel->toname, ['HTML.Allowed' => '']);
            $resMsg['to_roleid'] = $guestmodel->id;
        } else if ($msgmodel->touser) {
            $resMsg['toname'] = HtmlPurifier::process($msgmodel->touser->ncname ? $msgmodel->touser->ncname : $msgmodel->touser->username, ['HTML.Allowed' => '']);
            $resMsg['to_roleid'] = $msgmodel->touser->roomrole;
            $resMsg['to_pic'] = $msgmodel->touser->img;
        }
        //接收者图片
        $torole = RoomRole::findOne($resMsg['to_roleid']);
        if ($torole) {
            $resMsg['to_rolename'] = $torole->name ? $torole->name : '';
        }

        $resMsg['ischeck'] = $msgmodel->status == 1 ? 1 : 0;
        $resMsg['roomid'] = !empty($msgmodel->zhiboid) ? intval($msgmodel->zhiboid) : 0;
        $resMsg['zhiboid'] = $resMsg['roomid'];
        return $resMsg;
    }

    //获取具有审核权限的fd集合数组
    function getRoleFd()
    {
        $fds = [];
        $admins = Onlineuser::getOnlineadmin();
        if (is_array($admins)) {
            $fds = ArrayHelper::map($admins, 'uid', 'fd');
        }
        return $fds;
    }

    //通过uid查找fd
    function uid_to_fd($uid)
    {
        $fdval = Onlineuser::find()->where(['uid' => $uid])->one();
        if ($fdval) {
            return $fdval->fd;
        }
    }
}