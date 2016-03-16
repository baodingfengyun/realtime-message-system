package com.msg.common.util

import java.util.HashMap

object JsonTest extends App {
    val map=new HashMap[String,String]()
    map.put("key", "value")
    println(JsonUtil.toJson(map))
}