package com.msg.common.model

import java.util.HashSet
import scala.concurrent.forkjoin.ThreadLocalRandom
import java.util.ArrayList
import java.util.HashMap

case class TemplateAttr(name: String, rfield: String, qfield: String, source: String)

case class TemplateInfo(name: String, content: String, status: Int, pars: ArrayList[TemplateAttr])

object TemplateEntity {
    private val templateInfoMap = new HashMap[String, HashMap[String, TemplateInfo]]
    def addTemplateInfo(topic: String, templateInfo: TemplateInfo) = {
        var map = templateInfoMap.get(topic)
        if (map == null) {
            map = new HashMap[String, TemplateInfo]
            map.put(templateInfo.name, templateInfo)
            templateInfoMap.put(topic, map)
        } else {
            map.put(templateInfo.name, templateInfo)
        }
    }

    def getTemplateInfo(topic: String): HashMap[String, TemplateInfo] = {
        templateInfoMap.get(topic)
    }

    def removeTemplateInfo(topic: String, templateInfo: TemplateInfo) = {
        val map = templateInfoMap.get(topic)
        if (map != null) {
            map.remove(templateInfo.name)
        }
    }
    def getAllTemplate(): HashMap[String, HashMap[String, TemplateInfo]] = {
        templateInfoMap
    }
}