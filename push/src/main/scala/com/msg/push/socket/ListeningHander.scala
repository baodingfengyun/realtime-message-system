package com.msg.push.socket

import akka.actor.Actor
import akka.io.Tcp
import akka.util.ByteString
import com.msg.base.actor.TerminalSubBaseActor
import akka.actor.ActorLogging
import com.msg.base.model.Msg
import java.util.ArrayList
import akka.actor.ActorRef
import akka.io.Tcp._
import com.msg.base.model.Sub
import com.msg.base.model.UnSub
import com.msg.base.model.Stop
import com.msg.base.util.CommonUtil
import com.msg.base.model.TermUnSubAll
import com.msg.base.model.History
import com.msg.base.model.ListMsg
import com.msg.base.model.RegAddrEntity
import scala.collection.JavaConversions._
import com.msg.push.util.Constants

class ListeningHander extends TerminalSubBaseActor with ActorLogging {
    private var reactors = Map[String, ActorRef]()

    final def forResource(descriptor: String, reactor: Option[ActorRef]) {
        try {
            reactor match {
                case Some(actor) => reactors += ((descriptor, actor))
                case None => reactors -= descriptor
            }
        } catch {
            case e: Throwable => e.printStackTrace()
        }
    }

    def remoteAddress: String = RegAddrEntity.getRandomAddress

    def receiveMsg(msg: Msg) = {
        if (!reactors.get(msg.t).isEmpty) {
            reactors.get(msg.t).get ! Write(ByteString.apply(msg.t + Constants.SEC + msg.m + "\n", "GBK"))
        }
    }

    def receiveHistory(list: ArrayList[Msg]) = {
        /* list.foreach(msg => {
            if (!reactors.get(msg.t).isEmpty) {
                reactors.get(msg.t).get ! Write(ByteString.apply(msg.m))
            }
        })*/
    }

    def subError(msg: String) = {
        val msgs = msg.split(Constants.SEC)
        log.info("msg error:" + msg)
        reactors.get(msgs(0)).get ! Write(ByteString.apply(msg))
    }

    override def doOtherCmd(x: Any, sender: ActorRef) = {
        x match {
            case Received(data) =>
                var msg = data.utf8String
                msg = msg.replace("\n", "")
                println("socket=" + msg)
                try {
                    val m = msg.split(";")
                    if (m.length == 4) {
                        if (m(0).equals("sub")) {
                            val actorOption = reactors.get(m(1))
                            if (actorOption.isEmpty) {
                                forResource(m(1), Some(sender))
                            }
                            self ! Sub(m(1), m(2), m(3))
                        } else if (m(0).equals("unsub")) {
                            val actorOption = reactors.get(m(1))
                            if (!actorOption.isEmpty) {
                                self ! UnSub(m(1), m(2), m(3))
                            }
                        }
                    }
                } catch {
                    case e: Throwable => e.printStackTrace()
                }
            case PeerClosed =>
                self ! TermUnSubAll
                self ! Stop
            case ErrorClosed(_) =>
                self ! TermUnSubAll
                self ! Stop
            case x =>
                log.info("socket x=" + x)
        }
    }
    override def interval: Int = 5 * 60
}