package com.msg.push.actor

import akka.actor.ActorLogging
import akka.actor.Actor
import com.msg.push.socketio.SoketIoService
import com.msg.push.Configuration

class SocketIoCenter extends Actor with ActorLogging {

    override def preStart(): Unit = {
        val soketioServer = new SoketIoService(context.system)
        soketioServer.getSoketIoServer(Configuration.soketIoIp, Configuration.soketIoPort).start()
    }

    def receive = {
        case msg: String =>
            println("SocketIoCenter=" + msg)
        case _ =>
    }
}