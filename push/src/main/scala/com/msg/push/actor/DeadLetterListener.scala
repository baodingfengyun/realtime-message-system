package com.msg.push.actor

import akka.actor.Actor
import akka.actor.ActorLogging
import akka.actor.DeadLetter
import com.msg.common.model.Msg
import com.msg.common.model.DeadMsg

/**
 * 定义 DeadLetterListener actor
 * 基于模式匹配的逻辑
 */
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
		}
	}
}