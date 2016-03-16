package com.msg.push.actor

import akka.actor.ActorLogging
import akka.actor.Actor
import com.msg.push.Configuration
import akka.actor.Props
import com.msg.push.socket.TcpSoketServer

class SocketCenter extends Actor with ActorLogging {

    override def preStart(): Unit = {
       context.system.actorOf(Props(new TcpSoketServer(Configuration.socketIp, Configuration.socketPort)), "SoketServer")
    }

    def receive = {
        case msg: String =>
            println("SocketCenter=" + msg)
        case _ =>
    }
}