package com.msg.base.model

import java.util.HashSet
import scala.concurrent.forkjoin.ThreadLocalRandom
import java.util.ArrayList
import java.util.HashMap

case class TopicInfo(topic: String, code: String, key: String, isStore: Int, storeMethod: Int, sendNum: Int, storeNum: Int, isRegx: Int, status: Int, isTemplate: Int, swapType: Int, regMethod: String, defaultTemp: String, defaultNotify: String,broadStatus:Int)

object TopicEntity {

    private val topicInfoMap = new HashMap[String, TopicInfo]

    def addTopicInfo(topic: String, topicInfo: TopicInfo) = {
        topicInfoMap.put(topic, topicInfo)
    }

    def getTopicInfo(topic: String): TopicInfo = {
        topicInfoMap.get(topic)
    }

    def removeTopicInfo(topic: String) = {
        topicInfoMap.remove(topic)
    }
    def containsTopic(topic: String): Boolean = {
        topicInfoMap.keySet().contains(topic)
    }
    def getTopicSendNum(topic: String): Int = {
        if (topicInfoMap.keySet().contains(topic)) {
            topicInfoMap.get(topic).sendNum
        } else {
            0
        }
    }
    def removeAll = {
        topicInfoMap.clear()
    }
    def getAllTopics: HashMap[String, TopicInfo] = {
        topicInfoMap
    }
}