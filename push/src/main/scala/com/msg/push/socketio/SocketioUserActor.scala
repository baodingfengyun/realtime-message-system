package com.msg.push.socketio

import java.util.ArrayList

import akka.actor.ActorLogging
import com.corundumstudio.socketio.SocketIOClient
import com.msg.common.model.{Msg, RegAddrEntity}
import com.msg.push.actor.SubBaseActor
import com.msg.push.util.Constants
import org.java_websocket.WebSocket
import org.java_websocket.handshake.ClientHandshake

import scala.collection.JavaConversions._

case class Open(ws: WebSocket, hs: ClientHandshake)
case class Close(ws: WebSocket, code: Int, reason: String, external: Boolean)
case class Error(ws: WebSocket, ex: Exception)

class SoketioUserActor(client: SocketIOClient) extends SubBaseActor with ActorLogging {

    def remoteAddress: String = RegAddrEntity.getRandomAddress

    def receiveMsg(msg: Msg) = {
        if (client.isChannelOpen()) {
            val repData = new RepData()
            repData.setTopic(msg.t)
            repData.setMsg(msg.m)
            client.sendEvent("message", repData);
        }
    }

    def receiveHistory(list: ArrayList[Msg]) = {
        list.foreach(msg => {
            if (client.isChannelOpen()) {
                val repData = new RepData()
                repData.setTopic(msg.t)
                repData.setMsg(msg.m)
                client.sendEvent("message", repData);
            }
        })
    }
    
    def subError(msg: String) = {
        if (client.isChannelOpen()) {
            val repData = new RepData()
            val msgs = msg.split(Constants.SEC)
            repData.setTopic(msgs(0))
            repData.setMsg(msgs(1))
            client.sendEvent("message", repData);
        }
    }

    override def interval: Int = 5 * 60
}