package com.msg.region.actor

import akka.actor.{Actor, ActorLogging}
import com.msg.region.util.{Constants, RegionInfoMongoHelper}

import scala.concurrent.duration._

class InitRegionBaseInfo extends Actor with ActorLogging {
    import context.dispatcher
    context.system.scheduler.schedule(1.milliseconds, 60.seconds, self, "init")
    def receive = {
        case "init" =>
            RegionInfoMongoHelper.initTopicInfo(Constants.TOPIC_INFO)
        case _ =>
    }
}

