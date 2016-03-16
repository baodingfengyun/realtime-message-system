package com.msg.push

import akka.actor.ActorSystem
import akka.actor.Props
import akka.io.IO
import akka.io.Tcp
import java.net.InetSocketAddress
import com.typesafe.config.ConfigFactory
import com.msg.push.actor.DeadLetterListener
import akka.actor.DeadLetter
import com.msg.push.actor.WebsoketCenter
import com.msg.push.actor.SocketIoCenter
import com.msg.push.actor.InitRegionBaseInfo
import com.msg.push.actor.SocketCenter

object PushServer extends App {
    implicit lazy val system = ActorSystem("NewEventSystem", ConfigFactory.load("push-config.conf"))
    if (!args.isEmpty) System.setProperty("akka.remote.netty.tcp.port", args(0))

    //初始化远程地址
    system.actorOf(Props[InitRegionBaseInfo], "initAddress")

    val listener = system.actorOf(Props[DeadLetterListener], "deadLetter")
    system.eventStream.subscribe(listener, classOf[DeadLetter])

    //定义新的websoket 通过极推送服务
    system.actorOf(Props[WebsoketCenter], "websoket")

    //定义新的socketIo 通过极推送服务
    //system.actorOf(Props[SocketIoCenter], "socketio")

    //定义新的socketIo 通过极推送服务
    //system.actorOf(Props[SocketCenter], "socket")
}

object Configuration {
    private val config = ConfigFactory.load("push-config.conf")
    config.checkValid(ConfigFactory.defaultReference)
    //new websocket
    val newWebsocketUri = config.getString("websocket.wsuri")
    val newWebsocketPort = config.getInt("websocket.port")
    //socket Io
    val soketIoIp = config.getString("socketio.host")
    val soketIoPort = config.getInt("socketio.port")   
    //socket
    val socketIp = config.getString("socket.host")
    val socketPort = config.getInt("socket.port")    
}