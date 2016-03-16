package com.msg.push.ws

import akka.actor.Actor
import akka.actor.ActorLogging
import org.java_websocket.WebSocket
import scala.collection.JavaConversions._
import akka.actor.ActorRef
import org.java_websocket.handshake.ClientHandshake
import com.msg.push.actor.SubBaseActor
import com.msg.common.model.Msg
import java.util.ArrayList
import com.msg.push.util.Constants
import com.msg.common.model.RegAddrEntity

case class Open(ws: WebSocket, hs: ClientHandshake)
case class Close(ws: WebSocket, code: Int, reason: String, external: Boolean)
case class Error(ws: WebSocket, ex: Exception)

class PushWsUserActor(ws: WebSocket) extends SubBaseActor with ActorLogging {

    def remoteAddress: String = RegAddrEntity.getRandomAddress

    def receiveMsg(msg: Msg) = {
        if (ws.isOpen()) {
            ws.send(msg.t + Constants.SEC + msg.m)
        } else {
            log.info("ws is close")
        }
    }

    def receiveHistory(list: ArrayList[Msg]) = {
        if (ws.isOpen()) {
            list.foreach(msg => {
                ws.send(msg.t + Constants.SEC + msg.m)
            })
        }

    }

    def subError(msg: String) = {
        if (ws.isOpen()) {
            ws.send(msg)
        }
    }

    override def interval: Int = 5 * 60
}