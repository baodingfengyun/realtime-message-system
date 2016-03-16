package com.msg.push.util

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
import scala.collection.JavaConversions._

object RegionInfoMongoHelper {

    private val conf = ConfigFactory.load("mongdb-region.conf")
    val opt = new MongoOptions()
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
                    val checkStatus = dbobject.get("check_status").asInstanceOf[Int]
                    if (checkStatus == 1) {
                        val entityObj = dbobject.get("entity").asInstanceOf[DBObject]
                        if (entityObj != null && entityObj.keySet().size() > 0) {
                            val iterator = entityObj.keySet().iterator()
                            while (iterator.hasNext()) {
                                val curtopic = iterator.next()
                                val topic = code + "_" + curtopic
                                val obj = entityObj.get(curtopic).asInstanceOf[DBObject]
                                val status = obj.get("status").asInstanceOf[Int]
                                if (status == 1) {
                                    val status = obj.get("status").asInstanceOf[Int]
                                    val isStore = obj.get("is_store").asInstanceOf[Int]
                                    val key = obj.get("key").asInstanceOf[String]
                                    val storeMethod = obj.get("store_method").asInstanceOf[Int]
                                    val storeNum = obj.get("store_num").asInstanceOf[Int]
                                    val sendNum = obj.get("send_num").asInstanceOf[Int]
                                    val isRegx = obj.get("is_regx").asInstanceOf[Int]
                                    val regxParam = obj.get("regx_param").asInstanceOf[String]
                                    val isTemplate = obj.get("is_replace").asInstanceOf[Int]
                                    val isNotify = obj.get("is_notify").asInstanceOf[Int]
                                    val defaultTemp = obj.get("template_regx").asInstanceOf[String]
                                    val defaultNotify = obj.get("notify_regx").asInstanceOf[String]
                                    val broadStatus = obj.get("broad_status").asInstanceOf[Int]
                                    val topicInfo = TopicInfo(topic, code, key, isStore, storeMethod, sendNum, storeNum, isRegx, status, isTemplate, isNotify, regxParam, defaultTemp, defaultNotify,broadStatus)
                                    TopicEntity.addTopicInfo(topic, topicInfo)
                                    validTopicMap.add(topic)
                                } else {
                                    TopicEntity.removeTopicInfo(topic)
                                }
                            }
                        }
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

    private def getAllRegion(collectionName: String): ArrayList[DBObject] = {
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

    def initAllRegionInfo(collectionName: String) = {
        try {
            var dbObjectList = getAllRegion(collectionName)
            val addressList = new ArrayList[String]()
            if (dbObjectList != null && dbObjectList.size() > 0) {
                for (index <- 0 to dbObjectList.size() - 1) {
                    val dbobject = dbObjectList.get(index)
                    val name = dbobject.get("name").asInstanceOf[String]
                    val status = dbobject.get("status").asInstanceOf[Int]
                    if (status == 1) {
                        RegAddrEntity.addAddress(name)
                        addressList.add(name)
                    } else {
                        RegAddrEntity.removeAddress(name)
                    }
                }
            }
            RegAddrEntity.mergeAddress(addressList)
            //println("valid region address ="+RegAddrEntity.getAllRegionAddress().toString())
        } catch {
            case e: Throwable => e.printStackTrace()
        }
    }
}