<?php

namespace  Swoole;

class   WorkermanApi
{
 
    function WorkerConnect($serv,$fd){
        $serv->Connections[$fd]=new TcpConnection($serv,$fd);
        $serv->io->engine->onConnect($serv->Connections[$fd]);
    }
    function WorkerMessage($serv,$fd,$data){
         //此处模拟workerman的自定义协议回调 input decode
        $Connection= $serv->Connections[$fd];
        $Connection->_recvBuffer= $Connection->_recvBuffer.$data;
        if($Connection->protocol){
                $len=call_user_func_array(array($Connection->protocol,'input'),array($Connection->_recvBuffer,$Connection));
                if($len>0&&strlen($Connection->_recvBuffer)>=$len){
                        $data=substr($Connection->_recvBuffer,0,$len);
                        $Connection->consumeRecvBuffer($len);
                        if($Connection->protocol){
                                $data=call_user_func_array(array($Connection->protocol,'decode'),array($data,$Connection));
                        }
                        if($Connection->onMessage){
                                call_user_func_array($Connection->onMessage,array($Connection,$data));
                        }
                }
       }
    }

}
