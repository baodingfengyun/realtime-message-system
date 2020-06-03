package com.msg.push.actor

import akka.actor.ActorLogging
import akka.actor.Actor
import com.msg.push.socketio.SocketIoService
import com.msg.push.Configuration

/**
 * 定义SocketIoCenter actor
 */
class SocketIoCenter extends Actor with ActorLogging {

    override def preStart(): Unit = {
        val socketIoServer = new SocketIoService(context.system)
        socketIoServer.getSocketIoServer(Configuration.socketIoIp, Configuration.socketIoPort).start()
    }

    def receive = {
        case msg: String =>
            println("SocketIoCenter=" + msg)
        case _ =>
    }
}