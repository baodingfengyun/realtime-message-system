package com.msg.push.actor

import akka.actor.ActorLogging
import akka.actor.Actor
import com.msg.push.Configuration
import akka.actor.Props
import com.msg.push.socket.TcpSocketServer

/**
 * 定义 SocketCenter actor
 */
class SocketCenter extends Actor with ActorLogging {
    val CENTER_NAME = "SocketServer"

    override def preStart(): Unit = {
       context.system.actorOf(Props(new TcpSocketServer(Configuration.socketIp, Configuration.socketPort)), CENTER_NAME)
    }

    def receive = {
        case msg: String =>
            println(CENTER_NAME + "=" + msg)
        case _ =>
    }
}