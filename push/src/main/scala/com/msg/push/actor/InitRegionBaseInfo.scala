package com.msg.push.actor

import akka.actor.Actor
import scala.concurrent.duration._
import akka.actor.ActorLogging
import com.msg.push.util.RegionInfoMongoHelper
import com.msg.push.util.Constants

class InitRegionBaseInfo extends Actor with ActorLogging {
    import context.dispatcher
    context.system.scheduler.schedule(1.milliseconds, 60.seconds, self, "init")
    var num = 0
    def receive = {
        case "init" =>
            RegionInfoMongoHelper.initAllRegionInfo(Constants.REGION_INFO)
            //RegionInfoMongoHelper.initTopicInfo(Constants.TOPIC_INFO)
        case _ =>
    }
}

