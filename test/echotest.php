<?php
ini_set('memory_limit','64M');
use PHPSocketIO\SocketIO;
require_once __DIR__ . '../phpsocketio/autoload.php';
$worker = new \swoole_server('0.0.0.0', 2020);		
$worker->set(array(
    'worker_num' => 1,    //worker process num
    'dispatch_mode'=>4, 
));
$io = new SocketIO();
$io->swbind($worker);
$io->on('connection', function($socket){
     $socket->on('message', function ($data)use($socket){
          $socket->send($data);  
     });
    // when the user disconnects.. perform this
    $socket->on('disconnect', function () use($socket) {
		
   });
   $socket->send("hello");  
});
$worker->start();
