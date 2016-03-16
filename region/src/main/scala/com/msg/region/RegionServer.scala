package com.msg.region

import com.typesafe.config.ConfigFactory
import akka.actor.ActorSystem
import akka.actor.Props
import akka.event.Logging
import akka.actor.ExtendedActorSystem
import com.msg.region.actor.MsgExchangeActor

object RegionServer extends App {
	if (!args.isEmpty) System.setProperty("akka.remote.netty.tcp.port", args(0))
	val config = ConfigFactory.load("region-config.conf")
	implicit val system = ActorSystem("MsgExchangeSystem", config)
	Configuration.initConfig(system)
	val log = Logging(system, "")
	val topActor = system.actorOf(Props[MsgExchangeActor], name = "exchange")
	log.info(" start MsgExchangeServer..........")
}

object Configuration {
	private val config = ConfigFactory.load("file-config.conf")
	config.checkValid(ConfigFactory.defaultReference)
	var guardianName = ""
	var httpOutputIp = ""
	var httpOutputPort = 0
	var modNum = 0
	var snapshotPeriod = 60

	def initConfig(system: ActorSystem) = {
		guardianName = system.settings.config.getString("akka.contrib.cluster.sharding.guardian-name")
		httpOutputIp = system.settings.config.getString("region.http.http_output_ip")
		httpOutputPort = system.settings.config.getInt("region.http.http_output_port")
		modNum = system.settings.config.getInt("akka.contrib.cluster.sharding.mod-num")
		snapshotPeriod = system.settings.config.getInt("akka.persistence.snapshot-store.period")
	}
}