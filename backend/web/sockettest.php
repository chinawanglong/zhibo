<?php
$host="127.0.0.1";
$port=1234;//设置超时时间
set_time_limit(0);
//创建一个Socket
$socket=socket_create(AF_INET,SOCK_STREAM,0)or die("Couldnotcreatesocket\n");//绑定Socket到端口
$result=socket_bind($socket,$host,$port)or die("Couldnotbindtosocket\n");//开始监听链接
$result=socket_listen($socket,3)or die("Couldnotsetupsocketlistener\n");//acceptincomingconnections
//另一个Socket来处理通信
$spawn=socket_accept($socket)or die("Couldnotacceptincomingconnection\n");//获得客户端的输入
$input=socket_read($spawn,1024)or die("Couldnotreadinput\n");//清空输入字符串
$input=trim($input);//处理客户端输入并返回结果
$output=strrev($input)."\n";
socket_write($spawn,$output,strlen($output))or die("Couldnotwriteoutput\n");//关闭
socket_close($spawn);
socket_close($socket);