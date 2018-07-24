 # phpsocketio for swoole
  A server side alternative implementation of socket.io in PHP based on Swoole.
  
 # Notice
 Only support socket.io v1.3.0 or greater.  
 Modification based on https://github.com/walkor/phpsocket.io
 
# Install
composer require dosgo/sw-socketio

# Test
  php  test\echotest.php   socket.io-server   
  go run test\main.go   socker.io-client
  
  memory_limit 512M  
  worker_num 1 
  150000 client
  
  
