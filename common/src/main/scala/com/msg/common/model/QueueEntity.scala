package com.msg.common.model

import java.util.HashMap

/**
 * 队列信息
 * @param topic     主题
 * @param address   地址
 * @param category  类别
 * @param status    状态
 * @param checkTime 检测时间戳
 */
case class QueueInfo(topic: String, address: String, category: Int, status: Int, checkTime: Int)

/**
 * 队列实体
 */
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