package com.msg.push.socket

import akka.actor.Actor
import akka.io.Tcp
import akka.io.IO
import java.net.InetSocketAddress
import akka.actor.Props
import akka.actor.ActorRef

class TcpSoketServer(host: String, port: Int) extends Actor {
    private var reactors = Map[Int, ActorRef]()
    import Tcp._
    import context.system
    if (host.isEmpty()) {
        IO(Tcp) ! Bind(self, new InetSocketAddress(port))
    } else {
        IO(Tcp) ! Bind(self, new InetSocketAddress(host, port))
    }

    def receive = {
        case b @ Bound(localAddress) =>
        case CommandFailed(x: Bind) =>
            context stop self
        case c @ Connected(remote, local) =>
            val handler = context.actorOf(Props[ListeningHander])
            val connection = sender()
            connection ! Register(handler)
    }
}