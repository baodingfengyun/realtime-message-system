package com.msg.region.http

import akka.actor._
import akka.cluster.sharding.ClusterSharding
import com.msg.common.model.{History, Msg, VisitMsg}
import com.msg.common.util.{FstUtil, JsonUtil}
import com.msg.region.util.Constants
import spray.http.MediaTypes._
import spray.http._
import spray.httpx.encoding.Gzip
import spray.routing.RequestContext

case class RetrievalTimeout()

class DataGetService(shardName: String) extends Actor with HttpGetRouter with ActorLogging {
    val postRegion = ClusterSharding(context.system).shardRegion(shardName)
    def actorRefFactory = context
    def receive = runRoute(route)
    override def doHistoryQuery(ctx: RequestContext, topic: String, token: String, start: Int, end: Int): Unit = {
        log.info("request uri=[{}]", ctx.request.uri)
        if (start < 0 || end < 0 || start + end <= 0) {
            ctx.responder ! entityResponse("")
        } else {
            if (Constants.checkKeyValid(topic, token)) {
                context.actorOf {
                    Props {
                        new Actor with ActorLogging {
                            val startTime = System.currentTimeMillis()
                            def receive = {
                                case history: History =>
                                    val json = JsonUtil.toJson(FstUtil.d(history.byte))
                                    val utfJson = new String(json.getBytes("UTF-8"), "ISO-8859-1")
                                    ctx.responder ! entityResponse(utfJson)
                                    log.info("request [{}] cost time:[{}]", ctx.request.uri, System.currentTimeMillis() - startTime)
                                    context.stop(self)
                                case x =>
                                    context.stop(self)
                            }
                            postRegion ! VisitMsg(topic, start, end)
                        }
                    }
                }
            } else {
                //ctx.responder ! HttpResponse(status = 200, entity = HttpEntity(ContentType(MediaTypes.`application/json`, HttpCharsets.`UTF-8`), "Request Is Illegal"))
                ctx.responder ! entityResponse("Request Is Illegal")
            }
        }
    }

    override def doStaticsQuery(ctx: RequestContext, topic: String, token: String): Unit = {
        log.info("request uri=[{}]", ctx.request.uri)
        if (Constants.checkKeyValid(topic, token)) {
            context.actorOf {
                Props {
                    new Actor with ActorLogging {
                        val startTime = System.currentTimeMillis()
                        def receive = {
                            case history: History =>
                                val json = JsonUtil.toJson(FstUtil.d(history.byte))
                                val utfJson = new String(json.getBytes("UTF-8"), "ISO-8859-1")
                                ctx.responder ! entityResponse(utfJson)
                                log.info("request [{}] cost time:[{}]", ctx.request.uri, System.currentTimeMillis() - startTime)
                                context.stop(self)
                            case x =>
                                context.stop(self)
                        }
                        postRegion ! VisitMsg(topic, -1, -1)
                    }
                }
            }
        } else {
            //ctx.responder ! HttpResponse(status = 200, entity = HttpEntity(ContentType(MediaTypes.`application/json`, HttpCharsets.`UTF-8`), "Request Is Illegal"))
            ctx.responder ! entityResponse("Request Is Illegal")
        }
    }

    override def doSubscriber(ctx: RequestContext, topic: String, token: String): Unit = {
        log.info("request uri=[{}]", ctx.request.uri)
        if (Constants.checkKeyValid(topic, token)) {
            context.actorOf {
                Props {
                    new Actor with ActorLogging {
                        val startTime = System.currentTimeMillis()
                        def receive = {
                            case history: History =>
                                val json = JsonUtil.toJson(FstUtil.d(history.byte))
                                val utfJson = new String(json.getBytes("UTF-8"), "ISO-8859-1")
                                ctx.responder ! entityResponse(utfJson)
                                log.info("request [{}] cost time:[{}]", ctx.request.uri, System.currentTimeMillis() - startTime)
                                context.stop(self)
                            case x =>
                                context.stop(self)
                        }
                        postRegion ! VisitMsg(topic, -2, -2)
                    }
                }
            }
        } else {
            //ctx.responder ! HttpResponse(status = 200, entity = HttpEntity(ContentType(MediaTypes.`application/json`, HttpCharsets.`UTF-8`), "Request Is Illegal"))
            ctx.responder ! entityResponse("Request Is Illegal")
        }
    }

    override def doPushData(ctx: RequestContext, topic: String, content: String, key: String) = {
        context.system.actorSelection("/user/exchange/receivedata") ! Msg(topic, content, "", key)
        ctx.responder ! entityResponse("ok")
    }

    def entityResponse(json: String): HttpResponse = {
        Gzip.encode(HttpResponse(status = 200, entity = HttpEntity(`application/json`, json)))
    }
}