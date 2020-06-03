package com.msg.push.socketio

import com.corundumstudio.socketio.SocketIOClient
import akka.actor.ActorSystem
import akka.actor.Props
import akka.actor.ActorRef
import com.msg.common.model.TermUnSubAll
import com.msg.common.model.Stop
import com.msg.common.model.Sub
import com.msg.common.model.UnSub

/**
 * SocketIo服务
 * @param context
 */
class SocketIoService(context: ActorSystem) extends SocketIo {
    /**
     * id -> actor
     */
    private var reactors = Map[Int, ActorRef]()

    final def forResource(descriptor: Int, reactor: Option[ActorRef]) {
        try {
            reactor match {
                case Some(actor) => reactors += ((descriptor, actor))
                case None => reactors -= descriptor
            }
        } catch {
            case e: Throwable => e.printStackTrace()
        }
    }

    def doStartSub(msg: String, client: SocketIOClient) = {
        try {
            println("socketIo=" + msg)
            if (!msg.isEmpty() && msg.length > 0) {
                val m = msg.split(";")
                if (m.length == 4) {
                    if (m(0).equals("sub") && !m(1).contains("undefined")) {
                        val actorOption = reactors.get(client.hashCode())
                        var userActor: ActorRef = null
                        if (!actorOption.isEmpty) {
                            userActor = actorOption.get
                            if (m.length == 4 && !m(3).equals("undefined")) {
                                userActor ! Sub(m(1), m(2), m(3))
                            } else {
                                userActor ! Sub(m(1), m(2), "")
                            }
                        } else {
                            userActor = context.actorOf(Props(new SoketioUserActor(client)))
                            if (m.length == 4 && !m(3).equals("undefined")) {
                                userActor ! Sub(m(1), m(2), m(3))
                            } else {
                                userActor ! Sub(m(1), m(2), "")
                            }
                            forResource(client.hashCode(), Some(userActor))
                        }
                    } else if (m(0).equals("unsub") && !m(1).contains("undefined")) {
                        val actorOption = reactors.get(client.hashCode())
                        if (!actorOption.isEmpty) {
                            val sendActor: ActorRef = actorOption.get
                            if (m.length == 4 && !m(3).equals("undefined")) {
                                sendActor ! UnSub(m(1), m(2), m(3))
                            } else {
                                sendActor ! UnSub(m(1), m(2), "")
                            }
                        }
                    }
                }
            }
        } catch {
            case e: Throwable => e.printStackTrace()
        }
    }

    def unConnect(client: SocketIOClient) = {
        try {
            val actor = reactors.get(client.hashCode())
            if (!actor.isEmpty) {
                actor.get ! TermUnSubAll
                actor.get ! Stop
                forResource(client.hashCode(), None)
            }
        } catch {
            case e: Throwable => e.printStackTrace()
        }
    }
}