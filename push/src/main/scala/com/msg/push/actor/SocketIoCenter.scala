package com.msg.push.actor

import akka.actor.ActorLogging
import akka.actor.Actor
import com.msg.push.socketio.SocketIoService
import com.msg.push.Configuration

class SocketIoCenter extends Actor with ActorLogging {

    override def preStart(): Unit = {
        val soketioServer = new SocketIoService(context.system)
        soketioServer.getSocketIoServer(Configuration.soketIoIp, Configuration.soketIoPort).start()
    }

    def receive = {
        case msg: String =>
            println("SocketIoCenter=" + msg)
        case _ =>
    }
}