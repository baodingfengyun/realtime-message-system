package com.msg.region.actor

import akka.actor.{Actor, ActorLogging, DeadLetter, OneForOneStrategy, Props}
import akka.actor.SupervisorStrategy._
import akka.cluster.sharding.{ClusterSharding, ClusterShardingSettings}
import akka.io.IO
import com.msg.region.Configuration
import com.msg.region.http.DataGetService
import spray.can.Http

class MsgExchangeActor extends Actor with ActorLogging {

	ClusterSharding(context.system).start(
			Configuration.guardianName,
			Props(new MsgActor(Configuration.guardianName)),
			ClusterShardingSettings(context.system), 
			MsgOject.extractEntityId,
			MsgOject.extractShardId
			)

	val initActor = context.actorOf(Props[InitRegionBaseInfo], name = "initTopic")
	//log.info("initTopic actor=" + initActor)

	val receiveActor = context.actorOf(Props(new ReceiveMsg(Configuration.guardianName)), name = "receivedata")
	//log.info("receivedata actor=" + receiveActor)

	val listener = context.system.actorOf(Props[DeadLetterListener], "deadLetter")
	context.system.eventStream.subscribe(listener, classOf[DeadLetter])
	//log.info("listener actor=" + listener)

	val handler = context.system.actorOf(Props(new DataGetService(Configuration.guardianName)), name = "handler")
	implicit val system = context.system
	IO(Http) ! Http.Bind(handler, interface = Configuration.httpOutputIp, port = Configuration.httpOutputPort)

	override val supervisorStrategy =
		OneForOneStrategy() {
			case e: NullPointerException =>
				log.error("[{}] create NullPointerException [{}]", self, e.getLocalizedMessage()); Restart
			case e: Exception =>
				log.error("[{}] create NullPointerException [{}]", self, e.getLocalizedMessage()); Restart
		}
	def receive = {
		case x => log.info("x=" + x)
	}
}