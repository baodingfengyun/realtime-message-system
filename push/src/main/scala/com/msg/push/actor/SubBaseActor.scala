package com.msg.push.actor

import java.util.{ArrayList, HashMap}

import akka.actor.{Actor, ActorRef}
import com.msg.common.model._
import com.msg.common.util.FstUtil

import scala.collection.JavaConversions._
import scala.concurrent.duration._

/**
 * 一个带名字的心跳空元组对象
 */
case class Check()

/**
 * 定义客户端基本功能（字段和方法）
 */
trait SubBaseService {
    def remoteAddress: String
    def receiveMsg(msg: Msg)
    def receiveHistory(list: ArrayList[Msg])
    def subError(msg: String)
    def interval: Int = 3 * 60
    def doOtherCmd(x: Any, sender: ActorRef) = {}
}

/**
 * 客户端基本actor抽象
 */
abstract class SubBaseActor extends Actor with SubBaseService {
    import context.dispatcher

    // 定时心跳检测
    context.system.scheduler.schedule(interval.seconds, interval.seconds, self, Check)

    /** actor关注的topic */
    var topicMap = new HashMap[String, ActorRef]
    /** actor关注的topicInfo */
    var topicInfoMap = new HashMap[String, String]
    /** actor关注的topicCheckTime */
    var topicCheckTimeMap = new HashMap[String, Long]
    /** actor关注的topicCheckNum */
    var topicCheckNumMap = new HashMap[String, Int]
    var validKey = ""

    /**
     * actorSelection方法会返回ActorSelection选择路径，而不会返回ActorRef引用。
     * 使用ActorSelection对象可以向该路径指向的Actor对象发送消息。
     * 然而，请注意，与使用ActorRef引用的方式相比，通过这种方式发送消息的速度较慢并且会占用更多资源。
     * 但是，actorSelection方法仍旧是一个优秀的工具，因为它可以执行查询由通配符代表的多个Actor对象的操作，
     * 从而使你能够向ActorSelection选择路径指向的任意个Actor对象广播消息。
     */

    // Akka 框架中的所有Actor对象都必须扩展akka.actor.Actor特征。你编写的Actor对象至少要支持receive代码块。
    def receive = {
        case TermSendMsg(topic, msg) =>
            // 接收到TermSendMsg后，给remoteAddress的actor发送新封装的Msg
            context.system.actorSelection(remoteAddress) ! Msg(topic, msg, "", validKey)
        case Sub(topic, key, info) =>
            validKey = key
            // 转发Sub
            context.system.actorSelection(remoteAddress) ! Sub(topic, key, info)
            topicMap.put(topic, null)
            topicInfoMap.put(topic, info)
        case ListMsg(topic, key, start, end) =>
            validKey = key
            // 转发ListMsg
            context.system.actorSelection(remoteAddress) ! ListMsg(topic, key, start, end)
        case UnSub(topic, key, info) =>
            validKey = key
            // 转发UnSub
            context.system.actorSelection(remoteAddress) ! UnSub(topic, key, info)
            topicMap.remove(topic)
            topicInfoMap.remove(topic)
        case TermUnSubAll =>
            // 遍历topicMap，转发UnSub
            topicMap.keySet().foreach(topic => {
                context.system.actorSelection(remoteAddress) ! UnSub(topic, validKey, topicInfoMap.get(topic))
            })
            topicMap.clear()
        case SubOk(topic) =>
            // 注册成功
            topicMap.put(topic, sender)
            topicCheckTimeMap.put(topic, System.currentTimeMillis())
            topicCheckNumMap.put(topic, 0)
        case Check =>
            // 心跳
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
            // 反序列化
            try {
                val obj = FstUtil.d(byte).asInstanceOf[ArrayList[Msg]]
                receiveHistory(obj)
            } catch {
                case t: Throwable =>
            }
        case Connect(topic) =>
            // 连接
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
            // 其余的
            doOtherCmd(x, sender)
    }

}