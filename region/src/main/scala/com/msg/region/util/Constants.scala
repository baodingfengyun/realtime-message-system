package com.msg.region.util

import java.text.SimpleDateFormat
import java.util.HashMap

import com.msg.common.model.{TopicEntity, TopicInfo}
import com.msg.common.util.{JsonUtil, Md5}

object Constants {
    def sdf = new SimpleDateFormat("yyyy-mm-dd HH:mm:ss")
    val MSG_SUB = "sub"
    val MSG_RESUB = "resub"
    val MSG_UNSUB = "unsub"
    val MSG_COMMON = "common"
    val MSG_HISTORY = "history"
    val MSG_UNSUB_NUM = "unsubnum"
    val REGION_MAXSIZE = 500000
    val REGION_TOPIC_CHILD = "_child_"
    val DEFAULT_NUM = 20
    val STATICS = "static"
    val TOPIC_INFO = "topic_info"
    val REGION_INFO = "region_info"
    val HORIZONTAL_LINE = "-"
    val UNDERLINE = "_"
    val UN_STORE = 0
    val STORE = 1
    val STORE_METHOD_NUM = 1
    val STORE_METHOD_DAY = 2
    val ONLINE_STATUS = "1"
    val OFFLINE_STATUS = "0"

    def checkKeyValid(topic: String, key: String): Boolean = {
        var validTag = false
        val topicInfo = TopicEntity.getTopicInfo(topic)
        if (topicInfo != null) {
            validTag = Md5.md5Hash(topic).equals(key)
        } else {
            val topics = topic.split(Constants.HORIZONTAL_LINE)
            if (topics.length >= 2) {
                val newTopic = topics(0) + Constants.HORIZONTAL_LINE
                val regTopicInfo = TopicEntity.getTopicInfo(newTopic)
                if (regTopicInfo != null) {
                    validTag = Md5.md5Hash(newTopic).equals(key)
                }
            }
        }
        validTag
    }

    def createJson(dataMap: HashMap[String, Any]): String = {
        JsonUtil.toJson(dataMap)
    }

    def getTopicInfo(topicName: String): TopicInfo = {
        var topicInfo = TopicEntity.getTopicInfo(topicName)
        if (topicInfo == null) {
            val topics = topicName.split(Constants.HORIZONTAL_LINE)
            if (topics.length >= 2) {
                val newTopic = topics(0) + Constants.HORIZONTAL_LINE
                topicInfo = TopicEntity.getTopicInfo(newTopic)
            }
        }
        topicInfo
    }
}