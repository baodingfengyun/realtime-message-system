package com.msg.push.ws

import java.util.ArrayList

import akka.actor.ActorLogging
import com.msg.common.model.{Msg, RegAddrEntity}
import com.msg.push.actor.SubBaseActor
import com.msg.push.util.Constants
import org.java_websocket.WebSocket
import org.java_websocket.handshake.ClientHandshake

import scala.collection.JavaConversions._

case class Open(ws: WebSocket, hs: ClientHandshake)
case class Close(ws: WebSocket, code: Int, reason: String, external: Boolean)
case class Error(ws: WebSocket, ex: Exception)

/**
 * 基于WebSocket的玩家推送actor
 * @param ws
 */
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