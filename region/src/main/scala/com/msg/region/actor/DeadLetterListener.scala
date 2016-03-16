package com.msg.region.actor

import akka.actor.Actor
import akka.actor.ActorLogging
import akka.actor.DeadLetter
import com.msg.common.model.Msg
import com.msg.common.model.DeadMsg
import com.msg.region.util.RegionInfoMongoHelper
import com.msg.region.util.Constants

class DeadLetterListener extends Actor with ActorLogging {
	def receive = {
		case d: DeadLetter => {
			if (d.sender.toString.contains("ExRegion")) {
				d.message match {
					case msg: Msg =>
						d.sender ! DeadMsg(msg.t, msg.r)
					case _ =>
				}
			}
			if (d.recipient.toString().contains("MsgExchangeSystem")) {
				val address = d.recipient.toString().split("-")
				if (address.length > 2) {
					val add = address(1).replace("%3A", ":").replace("%2F", "/").replace("%40", "@")
					val region = add + "/user/exchange/receivedata"
					RegionInfoMongoHelper.deleteRegion(Constants.REGION_INFO, region)
				}
			}
		}
	}
}