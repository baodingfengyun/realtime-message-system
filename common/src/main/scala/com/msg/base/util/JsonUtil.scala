package com.msg.base.util

import com.fasterxml.jackson.databind.{ DeserializationFeature, ObjectMapper }
import com.fasterxml.jackson.module.scala.DefaultScalaModule
import com.fasterxml.jackson.module.scala.experimental.ScalaObjectMapper
import scala.collection.mutable.HashMap

object JsonUtil{
	val mapper = new ObjectMapper() with ScalaObjectMapper
	mapper.registerModule(DefaultScalaModule)
	mapper.configure(DeserializationFeature.FAIL_ON_UNKNOWN_PROPERTIES, false)

	def toJson(value: Map[Symbol, Any]): String = {
		toJson(value map { case (k, v) => k.name -> v })
	}

	def toJson(value: Any): String = {
		if(value==null){
			mapper.writeValueAsString("")
		}else{
			mapper.writeValueAsString(value)
		}
	}

	def toListMap[V](json: String)(implicit m: Manifest[V]) = fromJson[Map[String, List[V]]](json)
	
	def toMap[V](json: String)(implicit m: Manifest[V]) = fromJson[Map[String, V]](json)
	
	def toBsonMap[V](json: Array[Byte])(implicit m: Manifest[V]) = fromArryByteJson[Map[String, V]](json)

	def fromArryByteJson[T](json: Array[Byte])(implicit m: Manifest[T]): T = {
		mapper.readValue[T](json)
	}
	
	def fromJson[T](json: String)(implicit m: Manifest[T]): T = {
		mapper.readValue[T](json)
	}

//	val originalMap = Map("a" -> List(1, 2), "b" -> List(3, 4, 5), "c" -> List())
//	val json = JsonUtil.toJson(originalMap)
//	// json: String = {"a":[1,2],"b":[3,4,5],"c":[]}
//	val map = JsonUtil.toMap[Seq[Int]](json)
//	// map: Map[String,Seq[Int]] = Map(a -> List(1, 2), b -> List(3, 4, 5), c -> List())
//	val mutableSymbolMap = JsonUtil.fromJson[collection.mutable.Map[Symbol, Seq[Int]]](json)
	// mutableSymbolMap: scala.collection.mutable.Map[Symbol,Seq[Int]] = Map('b -> List(3, 4, 5), 'a -> List(1, 2), 'c -> List())

	/*
 * (Un)marshalling nested case classes
 */
	/*val jeroen = Person("Jeroen", 26)
	val martin = Person("Martin", 54)
    var tempMap=HashMap[String,Person]()
    tempMap.put("test", jeroen)
    tempMap.put("test1", martin)
	val groupJson = JsonUtil.toJson(tempMap)
	println("groupJson="+groupJson)*/
	// groupJson: String = {"name":"Scala ppl","persons":[{"name":"Jeroen","age":26},{"name":"Martin","age":54}],"leader":{"name":"Martin","age":54}}

	//val group = JsonUtil.fromJson[Group](groupJson)
	// group: Group = Group(Scala ppl,List(Person(Jeroen,26), Person(Martin,54)),Person(Martin,54))

}