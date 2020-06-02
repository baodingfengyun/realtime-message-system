package com.msg.common.model

import java.util.{ArrayList, HashMap}

/**
 * 模板属性
 * @param name    模板属性
 * @param rfield  R字段
 * @param qfield  Q字段
 * @param source  来源
 */
case class TemplateAttr(name: String, rfield: String, qfield: String, source: String)

/**
 * 模板信息
 * @param name     主题
 * @param content  内容
 * @param status   状态
 * @param pars     属性列表
 */
case class TemplateInfo(name: String, content: String, status: Int, pars: ArrayList[TemplateAttr])

/**
 * 模板实体
 */
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