<?php
use PHPSocketIO\SocketIO;
require_once __DIR__ . '/../phpsocketio/autoload.php';
$worker = new \swoole_server('0.0.0.0', 2020);		

$worker->set(array(
    'worker_num' => 6,    //worker process num
    'dispatch_mode'=>4, //这个重点，模式必须是4，无效
));
$io = new SocketIO();
$io->swbind($worker);//绑定swoole
$io->on('connection', function($socket){
     $socket->on('message', function ($data)use($socket){
         
         echo $data;
          $socket->send($data);  
     });
    // when the user disconnects.. perform this
    $socket->on('disconnect', function () use($socket) {
		
   });
   $socket->send("sdfsd");  
});

$worker->start();