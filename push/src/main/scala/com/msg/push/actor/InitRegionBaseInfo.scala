package com.msg.push.actor

import akka.actor.Actor
import scala.concurrent.duration._
import akka.actor.ActorLogging
import com.msg.push.util.RegionInfoMongoHelper
import com.msg.push.util.Constants

/**
 * 定义 InitRegionBaseInfo actor
 */
class InitRegionBaseInfo extends Actor with ActorLogging {
    val COMMAND_INIT = "init"
    import context.dispatcher
    // 定义一个60秒的定时器，给自己发init
    context.system.scheduler.schedule(1.milliseconds, 60.seconds, self, COMMAND_INIT)
    var num = 0
    def receive = {
        case COMMAND_INIT =>
            RegionInfoMongoHelper.initAllRegionInfo(Constants.REGION_INFO)
            //RegionInfoMongoHelper.initTopicInfo(Constants.TOPIC_INFO)
        case _ =>
    }
}

