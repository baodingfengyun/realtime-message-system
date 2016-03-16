package com.msg.region.actor

import akka.actor.ActorLogging
import akka.actor.Actor
import akka.persistence.PersistentActor
import scala.collection.JavaConversions._
import scala.concurrent.duration._
import akka.persistence.SnapshotOffer
import akka.persistence.SnapshotMetadata
import akka.persistence.RecoveryCompleted
import com.msg.base.util.FstUtil
import akka.persistence.SnapshotSelectionCriteria
import com.msg.region.util.Constants
import java.util.Date
import akka.cluster.sharding.ClusterSharding
import akka.persistence.Persistence
import akka.actor.Terminated
import akka.actor.ActorRef
import akka.actor.ActorSystem
import com.msg.base.model._
import java.util.ArrayList
import java.util.HashMap
import com.msg.region.Configuration
import com.msg.region.util.RegionInfoMongoHelper

case class Check()
case class ReplyMe()
case class DelSeq(seq: Long)

class MsgActor(shardName: String) extends PersistentActor with ActorLogging {
	import context.dispatcher
	private var checkPeriod = 300
	context.system.scheduler.schedule(checkPeriod.seconds, checkPeriod.seconds, self, Check)
	private var sequeuNr = 0l
	private var lastSequeuNr = 0l
	private def oldSnapShotId = Persistence(context.system).persistenceId(self)
	override def snapshotSequenceNr: Long = sequeuNr
	private var msgState = new MsgState()
	private val msgRegion = ClusterSharding(context.system).shardRegion(shardName)
	private var lastTime = System.currentTimeMillis()
	private var msgNum = 0
	private var periodSum = 0

	//oldSnapShotId===/user/ExRegion/ExRegion/test
	override def persistenceId: String = {
		var pos = oldSnapShotId.lastIndexOf(shardName)
		if (pos > 0) {
			oldSnapShotId.substring(pos + 1 + shardName.size, oldSnapShotId.size).replace("/", "-")
		} else {
			oldSnapShotId
		}
	}
	private def topicName = persistenceId

	def receiveCommand = {
		case mdata: MData =>
			try {
				if (mdata.isSave == Constants.STORE) {
					persist(mdata.d)(evt => { sequeuNr = sequeuNr + 1 })
				}
				mdata.d.c match {
					case Constants.MSG_SUB =>
						if (msgState.regions.get(topicName) == null) {
							msgState.regions.put(topicName, 0)
						}
						if (topicName.equals(mdata.d.t)) {
							if (msgState.regions.get(topicName) < Constants.REGION_MAXSIZE) {
								if (!msgState.subscriber.contains(sender.path.toString())) {
									statusNotify(mdata, Constants.ONLINE_STATUS)
									msgState.addSubscriber(sender.path.toString(), mdata.d.m)
									msgState.regions.put(mdata.r, msgState.regions.get(topicName) + 1)
								}
								sender ! SubOk(mdata.d.t)
								sendOnlineSubscriber(mdata, sender)
								log.info("[{}]---sub msgState.Subscriber size-76=[{}]", topicName, msgState.subscriber.size())
							} else {
								var flag = false
								var tellNum = 0
								msgState.regions.keySet().foreach(topickey => {
									if (!topickey.equals(topicName)) {
										//exclude had subed
										val curData = Data(mdata.d.t, Constants.MSG_UNSUB, mdata.d.m, System.currentTimeMillis())
										val unSubMdata = MData(mdata.s, topickey, curData, mdata.isSave)
										msgRegion tell (unSubMdata, sender)
										//resub again
										if (msgState.regions.get(topickey) <= Constants.REGION_MAXSIZE && tellNum == 0) {
											val newMdata = MData(mdata.s, topickey, mdata.d, mdata.isSave)
											msgRegion tell (newMdata, sender)
											msgState.regions.put(topickey, msgState.regions.get(topickey) + 1)
											flag = true
											tellNum = tellNum + 1
										}
									}
								})
								if (!flag) {
									val newTopicKey = mdata.r + Constants.REGION_TOPIC_CHILD + msgState.regions.size()
									msgState.regions.put(newTopicKey, 1)
									val newMdata = MData(mdata.s, newTopicKey, mdata.d, mdata.isSave)
									msgRegion tell (newMdata, sender)
								}
							}
						} else {
							if (!msgState.subscriber.keySet().contains(sender.path.toString())) {
								statusNotify(mdata, Constants.ONLINE_STATUS)
								msgState.addSubscriber(sender.path.toString(), mdata.d.m)
							}
							sender ! SubOk(mdata.d.t)
							sendOnlineSubscriber(mdata, sender)
							log.info("[{}]---sub msgState.Subscriber size--109=[{}]", topicName, msgState.subscriber.size())
						}
					case Constants.MSG_UNSUB =>
						if (topicName.equals(mdata.d.t)) {
							if (msgState.subscriber.contains(mdata.s)) {
								msgState.removeSubscriber(mdata.s)
								msgState.regions.put(topicName, msgState.regions.get(topicName) - 1)
								log.info("[{}]---unsub msgState.Subscriber size=[{}]", topicName, msgState.subscriber.size())
								statusNotify(mdata, Constants.OFFLINE_STATUS)
							} else {
								msgState.regions.keySet().foreach(topickey => {
									if (!topickey.equals(topicName)) {
										val newMdata = MData(mdata.s, topickey, mdata.d, mdata.isSave)
										msgRegion ! newMdata
									}
								})
							}
						} else {
							if (msgState.subscriber.contains(mdata.s)) {
								msgState.removeSubscriber(mdata.s)
								val curData = Data(topicName, Constants.MSG_UNSUB_NUM, mdata.d.m, System.currentTimeMillis())
								val newMdata = MData(mdata.s, mdata.d.t, curData, mdata.isSave)
								msgRegion ! newMdata
								log.info("[{}]---unsub msgState.Subscriber size=[{}]", topicName, msgState.subscriber.size())
							}
						}
					case Constants.MSG_HISTORY =>
						val datalist = findHistoryMsg(mdata.d.m)
						val tlist = new ArrayList[Msg]
						if (datalist != null) {
							datalist.foreach(data => {
								tlist.add(Msg(data.t, data.m, "", ""))
							})
						}
						sender ! History(mdata.d.t, FstUtil.s(tlist))
					case Constants.MSG_UNSUB_NUM =>
						msgState.regions.put(mdata.d.t, msgState.regions.get(mdata.d.t) - 1)
					case Constants.MSG_COMMON =>
						msgState.storeMsg(mdata.d)
						msgState.regions.keySet().foreach(topickey => {
							if (!topickey.equals(topicName)) {
								val newMdata = MData(mdata.s, topickey, mdata.d, Constants.UN_STORE)
								msgRegion ! newMdata
							}
						})
						msgState.subscriber.keySet().foreach(receiver => {
							val selection = context.system.actorSelection(receiver)
							selection ! Msg(mdata.d.t, mdata.d.m, receiver, "")
						})
						msgNum = msgNum + 1
					case _ =>
				}
			} catch {
				case t: Throwable => t.printStackTrace()
			}
			lastTime = System.currentTimeMillis()
		case deadMsg: DeadMsg =>
			msgState.removeSubscriber(deadMsg.rec)
			if (!topicName.equals(deadMsg.topic)) {
				val curData = Data(topicName, Constants.MSG_UNSUB_NUM, "", System.currentTimeMillis())
				val newMdata = MData(self.path.address.toString, deadMsg.topic, curData, Constants.UN_STORE)
				msgRegion ! newMdata
			}
			log.info("DeadLetter: " + deadMsg.topic + " not send to:" + deadMsg.rec)
		case Check =>
			if (!topicName.contains(Constants.REGION_TOPIC_CHILD)) {
				//在线订阅，消息统计
				updateSendSubNum()
				//生成快照 2小时
				periodSum = periodSum + checkPeriod
				if (periodSum / 60 >= Configuration.snapshotPeriod) {
					deleteSnapshots(SnapshotSelectionCriteria.Latest)
					Thread.sleep(1000)
					saveSnapshot(FstUtil.s(msgState))
					log.info("[{}]---create snapShot [{}]", persistenceId, snapshotSequenceNr)
					self ! DelSeq(sequeuNr - 1)
					periodSum = 0
				}
			} else {
				//reply parent 
				if (topicName.contains(Constants.REGION_TOPIC_CHILD)) {
					val parentTopic = topicName.split(Constants.REGION_TOPIC_CHILD)(0)
					msgRegion ! ReplySubNum(parentTopic, topicName, msgState.subscriber.size())
				}
			}
			//6小时内没有订阅也没有消息关掉
			if (msgState.subscriber.size() <= 0 && lastTime + 6 * Configuration.snapshotPeriod * 60 * 1000 < System.currentTimeMillis()) {
				log.info("[{}] actor stop", persistenceId)
				context.stop(self)
			}
		case ReplySubNum(ptopic, topic, num) =>
			msgState.regions.put(topic, num)
		case DelSeq(seq) =>
			try {
				deleteMessages(seq)
			} catch {
				case e: Throwable => e.printStackTrace()
			}
		case Connect(ack) =>
			sender ! Connect(ack)
		case VisitMsg(topic, start, end) =>
			if (start == -1 && end == -1) { //统计 在线人数             
				var onlineUser = 0
				val onLineInfo = new ArrayList
				msgState.regions.keySet().foreach(key => {
					onlineUser = onlineUser + msgState.regions.get(key)
				})
				msgState.subscriber.keySet().foreach(receiver => {
					msgState.subscriber.get(receiver)
				})
				val map = new HashMap[String, Any]
				map.put("subNum", onlineUser)
				map.put("msgNum", msgState.msgStore.size())
				sender ! History(topic, FstUtil.s(map))
			} else if (start == -2) { //统计 当前订阅人员        
				val onLineInfo = new ArrayList[String]
				msgState.subscriber.keySet().foreach(receiver => {
					onLineInfo.add(msgState.subscriber.get(receiver))
				})
				val map = new HashMap[String, Any]
				map.put("subScriber", onLineInfo)
				map.put("subNum", msgState.subscriber.size())
				sender ! History(topic, FstUtil.s(map))
			} else { //历史消息
				val datalist = findHistoryMsg(start + Constants.UNDERLINE + end)
				val tlist = new ArrayList[String]
				if (datalist != null) {
					datalist.foreach(data => {
						tlist.add(data.m)
					})
				}
				sender ! History(topic, FstUtil.s(tlist))
			}
		case x =>
	}

	def receiveRecover = {
		case SnapshotOffer(m: SnapshotMetadata, s: Array[Byte]) =>
			lastSequeuNr = m.sequenceNr
			sequeuNr = m.sequenceNr
			msgState = FstUtil.d(s).asInstanceOf[MsgState]
			log.info("[{}] restore from snapshot", persistenceId)
		case mdata: Data =>
			msgState.storeMsg(mdata)
			log.info("[{}] restore from journal [{}]", persistenceId, mdata)
		case RecoveryCompleted =>
			log.info("[{}] recovery completed", persistenceId)
		case x =>
			log.info("[{}] recovery msg=[{}]", persistenceId, x)
	}

	//历史消息查询
	private def findHistoryMsg(info: String): java.util.List[Data] = {
		var datalist: java.util.List[Data] = null
		try {
			val limits = info.split(Constants.UNDERLINE)
			var start = 0
			var end = 0
			if (!limits(0).isEmpty()) {
				start = limits(0).toInt - 1
				if (start < 0) {
					start = 0
				}
			}
			if (!limits(1).isEmpty()) {
				end = limits(1).toInt
			}
			val totalsize = msgState.msgStore.size()
			if (totalsize >= start) {
				if (totalsize >= end) {
					datalist = msgState.msgStore.subList(start, end)
				} else {
					datalist = msgState.msgStore.subList(start, totalsize)
				}
			}
		} catch {
			case t: Throwable =>
		}
		datalist
	}
	//更新发送消息及订阅量
	private def updateSendSubNum() = {
		try {
			var onlineUser = 0
			msgState.regions.keySet().foreach(key => {
				onlineUser = onlineUser + msgState.regions.get(key)
			})
			var curTopicName = topicName
			var topicInfo = TopicEntity.getTopicInfo(curTopicName)
			if (topicInfo == null) {
				val topics = curTopicName.split(Constants.HORIZONTAL_LINE)
				if (topics.length >= 2) {
					curTopicName = topics(0) + Constants.HORIZONTAL_LINE
					topicInfo = TopicEntity.getTopicInfo(curTopicName)
				}
			}
			if (topicInfo != null) {
				RegionInfoMongoHelper.updateSendSubNum(Constants.TOPIC_INFO, curTopicName, msgNum, onlineUser)
				msgNum = 0
			}
		} catch {
			case t: Throwable =>
		}
	}

	private def statusNotify(mdata: MData, status: String) = {
		val topicInfo = Constants.getTopicInfo(topicName)
		if (topicInfo != null && topicInfo.broadStatus == 1) {
			val dataMap = new HashMap[String, Any]
			dataMap.put("category", "subStatus")
			dataMap.put("topic", topicName)
			dataMap.put("from", "system")
			dataMap.put("name", mdata.d.m)
			dataMap.put("status", status)
			val broadMsg = Constants.createJson(dataMap)
			//TODO状态通知
			msgState.subscriber.keySet().foreach(receiver => {
				val selection = context.system.actorSelection(receiver)
				selection ! Msg(mdata.d.t, broadMsg, receiver, "")
			})
		}
	}

	private def sendOnlineSubscriber(mdata: MData, sender: ActorRef) = {
		val topicInfo = Constants.getTopicInfo(topicName)
		if (topicInfo != null && topicInfo.broadStatus == 1) {
			val onLineInfo = new ArrayList[String]
			msgState.subscriber.keySet().foreach(receiver => {
				onLineInfo.add(msgState.subscriber.get(receiver))
			})
			val dataMap = new HashMap[String, Any]
			dataMap.put("category", "subScriber")
			dataMap.put("topic", topicName)
			dataMap.put("from", "system")
			dataMap.put("data", onLineInfo)
			val broadMsg = Constants.createJson(dataMap)
			sender ! Msg(mdata.d.t, broadMsg, sender.path.toString(), "")
		}
	}

}

case class MsgState(events: List[String] = Nil) {
	val msgStore = new ArrayList[Data]
	val subscriber = new HashMap[String, String]
	val regions = new HashMap[String, Int]
	def addSubscriber(sender: String, info: String) = {
		subscriber.put(sender, info)
	}
	def removeSubscriber(sender: String) = {
		subscriber.remove(sender)
	}
	def storeMsg(event: Data) = {
		var topicInfo = TopicEntity.getTopicInfo(event.t)
		if (topicInfo == null) {
			val topics = event.t.split(Constants.HORIZONTAL_LINE)
			if (topics.length >= 2) {
				topicInfo = TopicEntity.getTopicInfo(topics(0) + Constants.HORIZONTAL_LINE)
			}
		}
		if (topicInfo != null && topicInfo.isStore == Constants.STORE) {
			if (topicInfo.storeMethod == Constants.STORE_METHOD_NUM) {
				if (msgStore.size() >= topicInfo.storeNum) {
					msgStore.remove(msgStore.size() - 1)
				}
				msgStore.add(0, event)
			} else if (topicInfo.storeMethod == Constants.STORE_METHOD_DAY) {
				msgStore.add(event)
				val old = msgStore.get(msgStore.size() - 1)
				if (old.time + topicInfo.storeNum * 24 * 60 * 60 * 1000 < event.time) {
					msgStore.remove(msgStore.size() - 1)
				}
			}
		}
	}
}