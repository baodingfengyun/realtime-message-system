package com.msg.push.actor

import akka.actor.Actor
import akka.actor.ActorSelection
import com.msg.common.model._
import java.util.HashMap
import akka.actor.ActorRef
import scala.concurrent.forkjoin.ThreadLocalRandom
import java.util.HashSet
import java.util.ArrayList
import com.msg.common.util.FstUtil
import scala.concurrent.duration._
import scala.collection.JavaConversions._

case class Check()

trait SubBaseService {
    def remoteAddress: String
    def receiveMsg(msg: Msg)
    def receiveHistory(list: ArrayList[Msg])
    def subError(msg: String)
    def interval: Int = 3 * 60
    def doOtherCmd(x: Any, sender: ActorRef) = {}
}

abstract class SubBaseActor extends Actor with SubBaseService {
    import context.dispatcher

    context.system.scheduler.schedule(interval.seconds, interval.seconds, self, Check)

    var topicMap = new HashMap[String, ActorRef]
    var topicInfoMap = new HashMap[String, String]
    var topicCheckTimeMap = new HashMap[String, Long]
    var topicCheckNumMap = new HashMap[String, Int]
    var validKey = ""

    def receive = {
        case TermSendMsg(topic, msg) =>
            context.system.actorSelection(remoteAddress) ! Msg(topic, msg, "", validKey)
        case Sub(topic, key, info) =>
            validKey = key
            context.system.actorSelection(remoteAddress) ! Sub(topic, key, info)
            topicMap.put(topic, null)
            topicInfoMap.put(topic, info)
        case ListMsg(topic, key, start, end) =>
            validKey = key
            context.system.actorSelection(remoteAddress) ! ListMsg(topic, key, start, end)
        case UnSub(topic, key, info) =>
            validKey = key
            context.system.actorSelection(remoteAddress) ! UnSub(topic, key, info)
            topicMap.remove(topic)
            topicInfoMap.remove(topic)
        case TermUnSubAll =>
            topicMap.keySet().foreach(topic => {
                context.system.actorSelection(remoteAddress) ! UnSub(topic, validKey, topicInfoMap.get(topic))
            })
            topicMap.clear()
        case SubOk(topic) =>
            topicMap.put(topic, sender)
            topicCheckTimeMap.put(topic, System.currentTimeMillis())
            topicCheckNumMap.put(topic, 0)
        case Check =>
            var curTime = interval
            if (curTime < 120) curTime = 120
            topicMap.keySet().foreach(topic => {
                if (topicCheckNumMap.get(topic) >= 2 || topicMap.get(topic) == null) {
                    self ! Sub(topic, validKey, topicInfoMap.get(topic))
                } else {
                    if (topicCheckTimeMap.get(topic) + curTime * 1000 < System.currentTimeMillis()) {
                        topicMap.get(topic) ! Connect(topic)
                        topicCheckNumMap.put(topic, topicCheckNumMap.get(topic) + 1)
                    }
                }
            })
        case History(topic, byte) =>
            try {
                val obj = FstUtil.d(byte).asInstanceOf[ArrayList[Msg]]
                receiveHistory(obj)
            } catch {
                case t: Throwable =>
            }
        case Connect(topic) =>
            topicCheckTimeMap.put(topic, System.currentTimeMillis())
            topicCheckNumMap.put(topic, topicCheckNumMap.get(topic) - 1)
        case Error(msg) =>
            subError(msg)
        case msg: Msg =>
            receiveMsg(msg)
            topicCheckTimeMap.put(msg.t, System.currentTimeMillis())
        case Stop =>
            context.stop(self)
        case x =>
            doOtherCmd(x, sender)
    }

}