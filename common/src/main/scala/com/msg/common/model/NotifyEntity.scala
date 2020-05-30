package com.msg.common.model

import java.util.{ArrayList, HashMap}

case class NotifyAttr(name: String, rfield: String, qfield: String, source: String)

case class NotifyInfo(name: String, content: String, status: Int, pars: ArrayList[NotifyAttr])

object NotifyEntity {
    private val notifyInfoMap = new HashMap[String, HashMap[String, NotifyInfo]]
    def addNotifyInfo(topic: String, notifyInfo: NotifyInfo) = {
        var map = notifyInfoMap.get(topic)
        if (map == null) {
            map = new HashMap[String, NotifyInfo]
            map.put(notifyInfo.name, notifyInfo)
            notifyInfoMap.put(topic, map)
        } else {
            map.put(notifyInfo.name, notifyInfo)
        }
    }

    def getNotifyInfo(topic: String): HashMap[String, NotifyInfo] = {
        notifyInfoMap.get(topic)
    }

    def removeNotifyInfo(topic: String, notifyInfo: NotifyInfo) = {
        val map = notifyInfoMap.get(topic)
        if (map != null) {
            map.remove(notifyInfo.name)
        }
    }
    def getAllNotifyInfo(): HashMap[String, HashMap[String, NotifyInfo]] = {
        notifyInfoMap
    }
}