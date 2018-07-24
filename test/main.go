package main

import (
	"fmt"
	socket "github.com/webrtcn/go-socketio-client"
	"time"
	"os"
	"log"
)

var ConnNum=0;
var FailNum=0;
var TestNum=8000;
var Url="http://127.0.0.1:2020";

func main() {

	if len(os.Args)>1  {
		Url=os.Args[1];
	}
	log.Println("Url:"+Url+"\r\n");
	for j := 1; j <= TestNum; j++ {
		go client();
	}
	go echo();
	select {}
}

func echo(){

	for {
		fmt.Printf("TestNum:%dConnNum:%dFailNum:%d\r\n",TestNum,ConnNum,FailNum)
		time.Sleep(time.Second*1)
	}

}

func client(){
	options := &socket.SocketOption{
		ReconnectionDelay:    3,
		ReconnectionAttempts: 10,
	}
	s, err := socket.Connect(Url, options)
	if err != nil {
		return
	}
	s.On(socket.OnConnection, func() {
	//	fmt.Println("Connect to server successful.")
		//fmt.Println(s.GetSessionID())    //sid
		ConnNum++;
	})
	s.On(socket.OnMessage, func(msg string)  { //listen with ack message
		//fmt.Println(msg);
		s.Emit("message", msg)
	})
	s.On(socket.OnConnecting, func() {
		//fmt.Println("connecting to server")
	})
	s.On(socket.OnReconnectFailed, func() {
		fmt.Println("connect to server failed")
		FailNum++;
	})
	s.On(socket.OnDisConnection, func() {
		//fmt.Println("server disconnect.")
		ConnNum--;
	})
	if err != nil {
		fmt.Println(err)
	}
}