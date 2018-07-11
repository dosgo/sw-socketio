<?php

namespace  Swoole;


/**
 * TcpConnection.
 */
class TcpConnection 
{
  
	public static $maxPackageSize = 10485760;
		
	public $_recvBuffer='';
    /**
     * Construct.
     *
     * @param resource $socket
     * @param string   $remote_address
     */
    public function __construct($serv,$socket)
    {
       $this->serv=$serv;
	   $this->fd=$socket;
    }
 
	
    /**
     * Sends data on the connection.
     *
     * @param string $send_buffer
     * @param bool  $raw
     * @return void|bool|null
     */
    public function send($send_buffer,$raw = false)
    {
		if($this->protocol&&$raw==false){
			$send_buffer=call_user_func_array(array($this->protocol,'encode'),array($send_buffer,$this));
		}
		//没有握手会打包失败，导致发送报错（所以判断一下）
		strlen($send_buffer)>0 && $this->serv->send($this->fd,$send_buffer);
    }

    /**
     * Get remote IP.
     *
     * @return string
     */
    public function getRemoteIp()
    {
		return $this->serv->connection_info($this->fd)['remote_ip'];
    }

    /**
     * Get remote port.
     *
     * @return int
     */
    public function getRemotePort()
    {
       return $this->serv->connection_info($this->fd)['remote_port'];
    }
	





   

  

    /**
     * Close connection.
     *
     * @param mixed $data
     * @param bool $raw
     * @return void
     */
    public function close()
    {
		return $this->serv->close($this->fd);
    }

    /**
     * Get the real socket.
     *
     * @return resource
     */
    public function getSocket()
    {
        return $this->fd;
    } 
	
	
	
	
    /**
     * Remove $length of data from receive buffer.
     *
     * @param int $length
     * @return void
	 * SamLuo
     */
    public function consumeRecvBuffer($length)
    {
        $this->_recvBuffer = substr($this->_recvBuffer, $length);
    }
}
