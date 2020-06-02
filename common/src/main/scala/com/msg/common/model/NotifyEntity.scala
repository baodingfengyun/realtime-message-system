package com.msg.common.model

import java.util.{ArrayList, HashMap}

/**
 * 通知属性
 * @param name   属性名字
 * @param rfield R字段
 * @param qfield Q字段
 * @param source 起源
 */
case class NotifyAttr(name: String, rfield: String, qfield: String, source: String)

/**
 * 通知信息
 * @param name    信息名字
 * @param content 内容
 * @param status  状态
 * @param pars    属性列表
 */
case class NotifyInfo(name: String, content: String, status: Int, pars: ArrayList[NotifyAttr])

/**
 * 通知实体，使用的是非线程安全的容器ArrayList和HashMap
 */
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