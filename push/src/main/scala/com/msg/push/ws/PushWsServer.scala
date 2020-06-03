package com.msg.push.ws

import java.net.InetSocketAddress
import java.text.SimpleDateFormat
import java.util.Date

import akka.actor.{ActorRef, ActorSystem, Props}
import com.msg.common.model.{Stop, Sub, TermUnSubAll, UnSub}
import org.java_websocket.WebSocket
import org.java_websocket.framing.CloseFrame
import org.java_websocket.handshake.ClientHandshake
import org.java_websocket.server.WebSocketServer

/**
 * 基于WebSocket的推送服务器
 *
 * @param wsUri
 * @param port
 * @param context
 */
class PushWsServer(wsUri: String, port: Int, context: ActorSystem) extends WebSocketServer(new InetSocketAddress(port)) {
    private var reactors = Map[Int, ActorRef]()
    private var joinNum = 0

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
    final override def onMessage(ws: WebSocket, msg: String) {
        try {
            println("websocket=" + msg)
            if (!msg.isEmpty() && msg.length > 0) {
                val m = msg.split(";")
                if (m.length <= 4) {
                    if (m(0).equals("sub") && !m(1).contains("undefined")) {
                        val actorOption = reactors.get(ws.hashCode())
                        var userActor: ActorRef = null
                        if (!actorOption.isEmpty) {
                            userActor = actorOption.get
                            if (m.length == 4 && !m(3).equals("undefined")) {
                                userActor ! Sub(m(1), m(2), m(3))
                            } else {
                                userActor ! Sub(m(1), m(2), "")
                            }
                        } else {
                            userActor = context.actorOf(Props(new PushWsUserActor(ws)))
                            if (m.length == 4 && !m(3).equals("undefined")) {
                                userActor ! Sub(m(1), m(2), m(3))
                            } else {
                                userActor ! Sub(m(1), m(2), "")
                            }
                            forResource(ws.hashCode(), Some(userActor))
                        }
                        joinNum = joinNum + 1
                    } else if (m(0).equals("unsub") && !m(1).contains("undefined")) {
                        val actorOption = reactors.get(ws.hashCode())
                        if (!actorOption.isEmpty) {
                            val sendActor: ActorRef = actorOption.get
                            if (m.length == 4 && !m(3).equals("undefined")) {
                                sendActor ! UnSub(m(1), m(2), m(3))
                            } else {
                                sendActor ! UnSub(m(1), m(2), "")
                            }
                            joinNum = joinNum - 1
                        }
                    }
                }
            }
        } catch {
            case e: Throwable => println("onMessage=" + e.getLocalizedMessage()); e.printStackTrace()
        }
    }
    final override def onOpen(ws: WebSocket, hs: ClientHandshake) {
        try {
            if (ws != null && hs.getResourceDescriptor().equals(wsUri)) {
                println("websoket joinNum=" + joinNum + "=onOpen:" + sdf.format(new Date()))
            } else {
                ws.close(CloseFrame.REFUSE)
            }
        } catch {
            case e: Throwable => println("onOpen=" + e.getLocalizedMessage()); e.printStackTrace()
        }
    }
    final override def onClose(ws: WebSocket, code: Int, reason: String, external: Boolean) {
        try {
            val actor = reactors.get(ws.hashCode())
            if (!actor.isEmpty) {
                actor.get ! TermUnSubAll
                actor.get ! Stop
                forResource(ws.hashCode(), None)
                joinNum = joinNum - 1
            }
        } catch {
            case e: Throwable => println("onClose=" + e.getLocalizedMessage()); e.printStackTrace()
        }
    }
    final override def onError(ws: WebSocket, ex: Exception) {
        try {
            if (ws != null) {
                val actor = reactors.get(ws.hashCode())
                if (!actor.isEmpty) {
                    actor.get ! TermUnSubAll
                    actor.get ! Stop
                    forResource(ws.hashCode(), None)
                    joinNum = joinNum - 1
                    println("joinNum=" + joinNum + "=onError:" + sdf.format(new Date()) + " host=" + ws.getRemoteSocketAddress().getAddress().getHostAddress())
                    ex.printStackTrace()
                }
            }
        } catch {
            case e: Throwable => println("onError=" + e.getLocalizedMessage()); e.printStackTrace()
        }
    }
    def sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
}