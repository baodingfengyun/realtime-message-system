package com.msg.common.model

import java.util.HashMap

case class QueueInfo(topic: String, address: String, category: Int, status: Int, checkTime: Int)

object QueueEntity {
    private val queueInfoMap = new HashMap[String, HashMap[String, QueueInfo]]
    def addQueueInfo(topic: String, queueInfo: QueueInfo) = {
        var map = queueInfoMap.get(topic)
        if (map == null) {
            map = new HashMap[String, QueueInfo]
            map.put(queueInfo.address + queueInfo.topic, queueInfo)
            queueInfoMap.put(topic, map)
        } else {
            map.put(queueInfo.address + queueInfo.topic, queueInfo)
        }
    }

    def getQueueInfo(topic: String): HashMap[String, QueueInfo] = {
        queueInfoMap.get(topic)
    }

    def removeQueueInfo(topic: String, queueInfo: QueueInfo) = {
        val map = queueInfoMap.get(topic)
        if (map != null) {
            map.remove(queueInfo.address + queueInfo.topic)
        }
    }

    def getAllQueue(): HashMap[String, HashMap[String, QueueInfo]] = {
        queueInfoMap
    }
}