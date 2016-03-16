package com.msg.region.actor

import akka.actor.Actor
import akka.actor.ActorRef
import akka.actor.Props
import akka.cluster.sharding.ClusterSharding
import com.msg.common.model._
import com.msg.region.util.Constants
import akka.actor.ActorLogging
import java.util.Date
import com.msg.common.util.Md5

class ReceiveMsg(shardName: String) extends Actor with ActorLogging {

    val msgRegion = ClusterSharding(context.system).shardRegion(shardName)

    def receive = {
        case Sub(topic, key, info) =>
            if (Constants.checkKeyValid(topic, key)) {
                val data = MData(sender.path.toString(), topic, Data(topic, Constants.MSG_SUB, info, System.currentTimeMillis()), Constants.UN_STORE)
                msgRegion tell (data, sender)
            } else {
                log.info(topic + "-Sub-" + key)
                sender ! Error(topic + "::::invalid topic for 'Sub' cmd")
            }
        case UnSub(topic, key, info) =>
            if (Constants.checkKeyValid(topic, key)) {
                val data = MData(sender.path.toString(), topic, Data(topic, Constants.MSG_UNSUB, info, System.currentTimeMillis()), Constants.UN_STORE)
                msgRegion tell (data, sender)
            } else {
                log.info(topic + "-UnSub-" + key)
                sender ! Error(topic + "::::invalid topic for 'UnSub' cmd")
            }
        case ListMsg(topic, key, start, end) =>
            if (Constants.checkKeyValid(topic, key)) {
                val data = MData(sender.path.toString(), topic, Data(topic, Constants.MSG_HISTORY, start + Constants.UNDERLINE + end, System.currentTimeMillis()), Constants.UN_STORE)
                msgRegion tell (data, sender)
            } else {
                log.info(topic + "-ListMsg-" + key)
                sender ! Error(topic + "::::invalid topic for 'ListMsg' cmd")
            }
        case Msg(topic, msg, _, key) =>
            if (Constants.checkKeyValid(topic, key)) {
                val data = MData(sender.path.toString(), topic, Data(topic, Constants.MSG_COMMON, msg, System.currentTimeMillis()), Constants.STORE)
                msgRegion ! data
            } else {
                log.info(topic + "-Msg-" + key)
                sender ! Error(topic + "::::invalid topic for 'Msg' cmd")
            }
        case _ =>
    }
}