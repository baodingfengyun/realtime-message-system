package com.msg.region.actor

import akka.cluster.sharding.ShardRegion
import com.msg.common.model.{MData, ReplySubNum, VisitMsg}
import com.msg.region.Configuration


object MsgOject {

    val extractEntityId: ShardRegion.ExtractEntityId = {
        case msg @ MData(sender, receiver, msginfo, isSave) => (receiver, msg)
        case reply @ ReplySubNum(parentTopic, topic, num) => (parentTopic, reply)
        case visitMsg @ VisitMsg(topic, start, end) => (topic, visitMsg)
    }
    val extractShardId: ShardRegion.ExtractShardId = msg => msg match {
        case MData(sender, receiver, msginfo, isSave) => (receiver.hashCode % Configuration.modNum).toString
        case ReplySubNum(parentTopic, topic, num) => (parentTopic.hashCode % Configuration.modNum).toString
        case VisitMsg(topic, start, end) => (topic.hashCode() % Configuration.modNum).toString()
    }
}
