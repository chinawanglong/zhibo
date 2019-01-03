<?php
namespace frontend\components;
use yii\helpers\ArrayHelper;
use Yii;
use yii\log\Logger;
class SocketServer
{

    public $ip;
    public $port;
    public $config;
    public $server;

    public $pidfile;

    public function __construct($servconfig) {

        if(!empty($servconfig['ip'])){
            $this->ip=$servconfig['ip'];
        }
        else{
            echo "You have not set the IP for socket server\n";
            Yii::warning("You have not set the IP for socket server\n","socketserver");
            exit;
        }

        if(!empty($servconfig['port'])){
            $this->port=$servconfig['port'];
        }
        else{
            echo "You have not set the port for socket server\n";
            exit;
        }

        if(empty($servconfig['config']) || !is_array($servconfig['config']) || count($servconfig['config'])==0){
            echo "The config is invalid !\n";
            exit;
        }
        else{
            $this->config=$servconfig['config'];
        }

        if(!empty($servconfig['zhibomainpidfile'])){
            $this->pidfile=$servconfig['zhibomainpidfile'];
        }
        else{
            echo "Please set the zhibomainpidfile in the config !\n";
            exit;
        }
    }

    /*
     * 判断是否是Comet连接
     */
    function isCometClient($client_id)
    {
        return strlen($client_id) === 32;
    }

    public function start(){
        $this->server = new \swoole_websocket_server($this->ip, $this->port);
        $this->server->set($this->config);
        $this->server->on('Start', array($this, 'onStart'));
        $this->server->on('Shutdown',array($this,'onShutDown'));
        $this->server->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->server->on('WorkerStop', array($this, 'onWorkerStop'));
        $this->server->on("Message",array($this,"onMessage"));
        $this->server->on("Open",array($this,"onOpen"));
        $this->server->on('Connect', array($this, 'onConnect'));
        $this->server->on('Receive', array($this, 'onReceive'));
        $this->server->on('Task', array($this, 'onTask'));
        $this->server->on('Finish', array($this, 'onFinish'));
        $this->server->on('Close', array($this, 'onClose'));
        $this->server->start();

    }


    /**
     * 主Reactor开始接收事件，客户端可以connect到Server
     */
    public function onStart( $serv ) {
        swoole_set_process_name("zhibo_service_{$this->port}");
        $log_str="主Reactor进程已启动,客户端可以connect到server \n 进程信息 PID:".getmypid()." ,PROCTITLE : zhibo_service";
        echo $log_str;
        Yii::info($log_str,"socketserver");
        file_put_contents($this->pidfile,$serv->master_pid);
    }


    /**
     * Server结束
     */
    public function onShutDown( $serv ) {
        $log_str="Server shutdown \n";
        echo $log_str;
        Yii::info($log_str,"socketserver");
        if(file_exists($this->pidfile)){
            unlink($this->pidfile);
        }
    }

    /**
     * Worder进程启动
     */
    public function onWorkerStart( $serv , $worker_id) {
        if($worker_id >= $serv->setting['worker_num']) {
            $log_str="Task 进程启动,Id:".$worker_id."\n";
        } else {
            $log_str="Worker 进程启动,Id:".$worker_id."\n";
        }
        echo $log_str;
        Yii::info($log_str,"socketserver");
    }
    public function onWorkerStop($serv, $worker_id){
        echo "Worker stop,id:".$worker_id."\n";
    }

    /*
     * onWorkerError
     */
    public function onWorderError(\swoole_websocket_server $serv, $worker_id, $worker_pid, $exit_code){
        $log_str="Worker 进程(ID=$worker_id,PID=$worker_pid)异常退出 EXIT_CODE:$exit_code\n";
        echo $log_str;
        Yii::info($log_str,"socketserver");
    }

    /**
     * @param $serv
     * @param $fd
     * @param $from_id
     */
    public function onConnect( $serv, $fd, $from_id ) {
        // echo "Client {$fd} connect\n";

    }

    /*
    *对于websocket有了onOpen就不调用onReceive
    */
    public function onReceive(\swoole_websocket_server $serv, $fd, $from_id, $str ) {

    }


    /*socket连接打开*/
    public function onOpen(\swoole_websocket_server $server, $request){

    }

    public function onMessage(\swoole_websocket_server $server, $frame){
        /*echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, "this is server");*/
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
        return "";
    }


    /**
     *
     * @param $serv
     * @param $task_id
     * @param $data
     */
    function onFinish($serv, $task_id, $str)
    {

    }

    public function onClose(\swoole_websocket_server $server,$fd ) {

    }


}