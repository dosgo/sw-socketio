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
		if(isset($serv->Connections[$fd])){
			$Connection= $serv->Connections[$fd];
			$Connection->_recvBuffer= $Connection->_recvBuffer.$data;
			if($Connection->protocol){
					$len=call_user_func_array(array($Connection->protocol,'input'),array($Connection->_recvBuffer,$Connection));
					if($len>0&&strlen($Connection->_recvBuffer)>=$len){
							$data=substr($Connection->_recvBuffer,0,$len);
							$Connection->consumeRecvBuffer($len);
							$data=call_user_func_array(array($Connection->protocol,'decode'),array($data,$Connection));
							if($Connection->onMessage){
									call_user_func_array($Connection->onMessage,array($Connection,$data));
							}
					}
		   }
	   }
    }
	function Free($serv,$fd){
		if(isset($serv->Connections[$fd])){
			$Connection= $serv->Connections[$fd];
			if($Connection->onClose){
				call_user_func($Connection->onClose,$Connection);
			}
			if($serv->Connections[$fd]->_recvBuffer){
				unset($serv->Connections[$fd]->_recvBuffer);
			}
			unset($serv->Connections[$fd]);
	    }
	}

}
