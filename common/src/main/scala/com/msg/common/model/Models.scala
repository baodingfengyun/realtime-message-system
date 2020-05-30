package com.msg.common.model

/**
 * 定义所有的消息及格式
 */

trait OkoMessage extends Serializable

/**
 * 注册
 * @param topic 主题
 * @param key   唯一key
 * @param info  内容
 */
case class Sub(topic: String, key: String, info: String) extends OkoMessage

/**
 * 注册成功
 * @param topic 主题
 */
case class SubOk(topic: String) extends OkoMessage

/**
 * 消息
 * @param t 目标
 * @param m 信息
 * @param r 来源
 * @param key 唯一key
 */
case class Msg(t: String, m: String, r: String, key: String) extends OkoMessage

/**
 * 回复注册数量
 * @param parentTopic 父主题
 * @param topic 子主题
 * @param num 数量
 */
case class ReplySubNum(parentTopic: String, topic: String, num: Int) extends OkoMessage

/**
 * 错误
 * @param msg 错误信息
 */
case class Error(msg: String) extends OkoMessage

/**
 * 取消注册
 * @param topic 主题
 * @param key 唯一key
 * @param info 信息
 */
case class UnSub(topic: String, key: String, info: String) extends OkoMessage

/**
 * 死亡消息
 * @param topic 主题
 * @param rec ？
 */
case class DeadMsg(topic: String, rec: String) extends OkoMessage

/**
 * 连接
 * @param ack ACK回复内容
 */
case class Connect(ack: String) extends OkoMessage

/**
 * 消息列表
 * @param topic 主题
 * @param key 唯一key
 * @param start 起始位置
 * @param end 截止位置
 */
case class ListMsg(topic: String, key: String, start: Int, end: Int) extends OkoMessage

/**
 * 访问消息
 * @param topic 主题
 * @param start 起始位置
 * @param end 截止位置
 */
case class VisitMsg(topic: String, start: Int, end: Int) extends OkoMessage

/**
 * 数据
 * @param t
 * @param c
 * @param m
 * @param time 时间戳
 */
case class Data(t: String, c: String, m: String, time: Long) extends OkoMessage

/**
 *
 * @param s
 * @param r
 * @param d
 * @param isSave
 */
case class MData(s: String, r: String, d: Data, isSave: Int) extends OkoMessage

/**
 * 历史记录
 * @param t
 * @param byte
 */
case class History(t: String, byte: Array[Byte]) extends OkoMessage

/**
 * 统计信息
 * @param topic 主题
 * @param num 数量
 */
case class Statics(topic: String, num: Int) extends OkoMessage

/**
 * 在线数量
 * @param statics 统计
 * @param topic 主题
 * @param num 数量
 */
case class OnlineNum(statics: String, topic: String, num: Int) extends OkoMessage

/**
 * 定期发送消息
 * @param topic 主题
 * @param msg 内容
 */
case class TermSendMsg(topic: String, msg: String)

/**
 * 取消所有的定期消息
 */
case class TermUnSubAll()

/**
 * 停止
 */
case class Stop()

