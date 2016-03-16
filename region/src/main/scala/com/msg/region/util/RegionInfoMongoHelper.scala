package com.msg.region.util

import com.mongodb.MongoException
import com.typesafe.config.ConfigFactory
import com.mongodb.MongoOptions
import com.mongodb.ServerAddress
import com.mongodb.casbah.MongoConnection
import com.mongodb.casbah.MongoDB
import scala.util.Try
import com.mongodb.casbah.commons.MongoDBObject
import java.util.ArrayList
import com.mongodb.DBObject
import org.bson.BSON
import com.mongodb.BasicDBList
import com.msg.base.model.RegAddrEntity
import com.msg.base.model.TopicInfo
import com.msg.base.model.QueueInfo
import com.msg.base.model.QueueEntity
import com.msg.base.model.TemplateEntity
import com.msg.base.model.TemplateInfo
import com.msg.base.model.TopicEntity
import java.util.HashMap
import scala.collection.JavaConversions._
import java.text.SimpleDateFormat
import java.util.Date

object RegionInfoMongoHelper {

	private val conf = ConfigFactory.load("mongdb-region.conf")
	val opt = new MongoOptions()
	opt.setSocketKeepAlive(true)
	val hosts = conf.getString("region_mongo.host")
	val ports = conf.getString("region_mongo.port")
	val hostArr = hosts.split(",")
	val portArr = ports.split(",")
	var addArr = Set.empty[ServerAddress]
	for (index <- 0 to hostArr.length - 1) {
		addArr += new ServerAddress(hostArr(index), portArr(index).toInt)
	}
	private val mongoConn = MongoConnection(addArr.toList, opt)
	private val dbname = conf.getString("region_mongo.db")

	private def mongoDb(dbname: String): MongoDB = {
		val db = mongoConn.getDB(dbname)
		db
	}

	private def getAllTopic(collectionName: String): ArrayList[DBObject] = {
		var result: ArrayList[DBObject] = new ArrayList[DBObject]
		try {
			val collection = mongoDb(dbname)(collectionName)
			var cursor = collection.find()
			while (cursor.hasNext) {
				result.add(cursor.next)
			}
		} catch {
			case e: Throwable => e.printStackTrace()
		}
		result
	}

	def initTopicInfo(collectionName: String) = {
		try {
			var dbObjectList = getAllTopic(collectionName)
			if (dbObjectList != null && dbObjectList.size() > 0) {
				val validTopicMap = new ArrayList[String]
				for (index <- 0 to dbObjectList.size() - 1) {
					val dbobject = dbObjectList.get(index)
					val code = dbobject.get("code").asInstanceOf[String]
					val checkStatus = dbobject.get("check_status").asInstanceOf[Long].toInt
					if (checkStatus == 1) {
						val entityObj = dbobject.get("entity").asInstanceOf[DBObject]
						if (entityObj != null && entityObj.keySet().size() > 0) {
							val iterator = entityObj.keySet().iterator()
							while (iterator.hasNext()) {
								val curtopic = iterator.next()
								val topic = code + "_" + curtopic
								val obj = entityObj.get(curtopic).asInstanceOf[DBObject]
								val status = obj.get("status").asInstanceOf[Long].toInt
								if (status == 1) {
									val isStore = obj.get("is_store").asInstanceOf[Long].toInt
									val key = obj.get("key").asInstanceOf[String]
									val storeMethod = obj.get("store_method").asInstanceOf[Long].toInt
									val storeNum = obj.get("store_num").asInstanceOf[Long].toInt
									val sendNum = obj.get("send_num").asInstanceOf[Long].toInt
									val isRegx = obj.get("is_regx").asInstanceOf[Long].toInt
									val regxParam = obj.get("regx_param").asInstanceOf[String]
									val isTemplate = obj.get("is_replace").asInstanceOf[Long].toInt
									val isNotify = obj.get("is_notify").asInstanceOf[Long].toInt
									val defaultTemp = obj.get("template_regx").asInstanceOf[String]
									val defaultNotify = obj.get("notify_regx").asInstanceOf[String]
									val broadStatus = obj.get("broad_status").asInstanceOf[Long].toInt
									val topicInfo = TopicInfo(topic, code, key, isStore, storeMethod, sendNum, storeNum, isRegx, status, isTemplate, isNotify, regxParam, defaultTemp, defaultNotify, broadStatus)
									TopicEntity.addTopicInfo(topic, topicInfo)
									validTopicMap.add(topic)
								} else {
									TopicEntity.removeTopicInfo(topic)
								}
							}
						}
					} else {
						println(code + " uncheck")
					}
				}
				if (validTopicMap != null && validTopicMap.size() != TopicEntity.getAllTopics.size()) {
					val oldMap = TopicEntity.getAllTopics
					oldMap.keySet().foreach(key => {
						if (!validTopicMap.contains(key)) {
							TopicEntity.removeTopicInfo(key)
						}
					})
				}
			} else {
				TopicEntity.removeAll
			}
		} catch {
			case e: Throwable => e.printStackTrace()
		}
	}

	def updateSendSubNum(collectionName: String, topic: String, sendNum: Int, subNum: Int) = {
		//db.topic_info.update({"code":"trade"},{'$set':{'topic.topic.send_num':1}})
		try {
			val collection = mongoDb(dbname)(collectionName)
			val mytopic = topic.split(Constants.UNDERLINE)
			val query = MongoDBObject("code" -> mytopic(0))
			val update = MongoDBObject("$inc" -> MongoDBObject("entity." + mytopic(1).trim() + ".send_num" -> sendNum), "$set" -> MongoDBObject("entity." + mytopic(1).trim() + ".sub_num" -> subNum))
			collection.findAndModify(query, update)
		} catch {
			case e: Throwable => e.printStackTrace()
		}
	}
	
	def saveRegion(collectionName: String, name: String, status: Int) = {
        try {
            val collection = mongoDb(dbname)(collectionName)
            val query = MongoDBObject("name" -> name)
            val num = collection.find(query).count
            if (num > 0) {
                val update = MongoDBObject("name" -> name, "status" -> status, "create_time" -> sdf.format(new Date()))
                collection.update(query, update)
            } else {
                val save = MongoDBObject("name" -> name, "status" -> status, "create_time" -> sdf.format(new Date()))
                collection.save(save, com.mongodb.WriteConcern.ACKNOWLEDGED)
            }
        } catch {
            case e: Throwable => e.printStackTrace()
        }
    }
	
	def deleteRegion(collectionName: String, name: String)={
		try {
            val collection = mongoDb(dbname)(collectionName)
            val query = MongoDBObject("name" -> name)
            val num = collection.remove(query, com.mongodb.WriteConcern.ACKNOWLEDGED)
        } catch {
            case e: Throwable => e.printStackTrace()
        }
	}
	
	private val sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
}