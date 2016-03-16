package com.msg.base.model

import java.util.ArrayList
import java.util.HashMap

trait OkoMessage extends Serializable

case class Sub(topic: String, key: String, info: String) extends OkoMessage

case class SubOk(topic: String) extends OkoMessage

case class Msg(t: String, m: String, r: String, key: String) extends OkoMessage

case class ReplySubNum(parentTopic: String, topic: String, num: Int) extends OkoMessage

case class Error(msg: String) extends OkoMessage

case class UnSub(topic: String, key: String, info: String) extends OkoMessage

case class DeadMsg(topic: String, rec: String) extends OkoMessage

case class Connect(ack: String) extends OkoMessage

case class ListMsg(topic: String, key: String, start: Int, end: Int) extends OkoMessage

case class VisitMsg(topic: String, start: Int, end: Int) extends OkoMessage

case class Data(t: String, c: String, m: String, time: Long) extends OkoMessage

case class MData(s: String, r: String, d: Data, isSave: Int) extends OkoMessage

case class History(t: String, byte: Array[Byte]) extends OkoMessage

case class Statics(topic: String, num: Int) extends OkoMessage

case class OnlineNum(statics: String, topic: String, num: Int) extends OkoMessage

case class TermSendMsg(topic: String, msg: String)

case class TermUnSubAll()

case class Stop()

