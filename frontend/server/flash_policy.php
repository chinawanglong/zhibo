<?php

$config = array_merge(
    require(dirname(__DIR__) . '/config/params.php'),
    require(dirname(__DIR__ ). '/config/params-local.php')
);
if(empty($config) || empty($config['chatserver'])){
    echo "The policy config is invalid !";
    exit;
}

$chatconfig=$config['chatserver'];
$flashconfig=[];
$ip="";
$port="";
$pidfile="";
if(!empty($chatconfig['flaship'])){
    $ip=$chatconfig['flaship'];
}
else{
    echo "You have not set the  flash ip for  socket server !\n";
    exit;
}

if(!empty($chatconfig['flashport'])){
    $port=$chatconfig['flashport'];
}
else{
    echo "You have not set the flash port for socket server !\n";
    exit;
}

if(empty($chatconfig['flashconfig']) || !is_array($chatconfig['flashconfig']) || count($chatconfig['flashconfig'])==0){
    echo "The flashconfig is invalid !\n";
    exit;
}
else{
    $flashconfig=$chatconfig['flashconfig'];
}

if(!empty($chatconfig['zhibopolicypidfile'])){
    $pidfile=$chatconfig['zhibopolicypidfile'];
}
else{
    echo "Please set the zhibopolicypidfile in the config !\n";
    exit;
}

$serv = new swoole_server($ip, $port);
$serv->set($flashconfig);

$serv->on('connect', function ($serv, $fd, $from_id){
    echo "Flash client@[$fd:$from_id]: Connect.\n";
});
$serv->on('start',function($server) use ($port){
    swoole_set_process_name("zhibo_flashservice__{$port}");
    $console_str="Flash server start\n";
    global  $pidfile;
    file_put_contents($pidfile,$server->master_pid);
});

$serv->on('workerstart',function($serv , $worker_id){
    $console_str="Flash Worker 进程启动,Id:".$worker_id."\n";
    echo $console_str;
});

$serv->on('workerstop',function($serv , $worker_id){
    $console_str="Flash Worker 进程关闭,Id:".$worker_id."\n";
    echo $console_str;
});

$serv->on('shutdown',function($server) {
    global  $pidfile;
    if(file_exists($pidfile)){
        unlink($pidfile);
    }
});

$serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
    echo "Flash receive client[$fd:]: $data\n";
    $serv->send($fd, "<cross-domain-policy><allow-access-from domain='*' to-ports='*' /></cross-domain-policy>\n\0");
});

$serv->on('close', function ($serv, $fd, $from_id) {
    echo "Flash client@[$fd:$from_id]: Close.\n";
});
$serv->start();

