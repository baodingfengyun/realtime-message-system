package com.msg.region.http
import scala.concurrent.duration._
import akka.actor._
import akka.pattern.ask
import spray.routing.{ HttpService, RequestContext }
import spray.routing.directives.CachingDirectives
import spray.can.server.Stats
import spray.can.Http
import spray.httpx.marshalling.Marshaller
import spray.httpx.encoding.Gzip
import spray.util._
import spray.http._
import MediaTypes._
import CachingDirectives._
import spray.http.Uri.Path
import scala.collection.mutable.HashMap
import scala.collection.JavaConversions._
import spray.httpx.encoding.GzipCompressor

/**
 * 定义rest接口
 *
 */
trait HttpGetRouter extends HttpService {
    implicit def executionContext = actorRefFactory.dispatcher
    val visit = path("msg" / "data" / Segment / Segment / IntNumber / IntNumber)
    val statics = path("msg" / "statics" / Segment / Segment)
    val subscriber = path("msg" / "subscriber" / Segment / Segment)
    val post_topic_data = path("msg" / "push" / "data")
    val route = {
        get {
            pathSingleSlash {
                complete(index)
            } ~
                path("stats") {
                    complete {
                        actorRefFactory.actorSelection("/user/IO-HTTP/listener-0")
                            .ask(Http.GetStats)(1.second).mapTo[Stats]
                    }
                } ~
                visit { (topic, token, start, end) =>
                    ctx => doHistoryQuery(ctx, topic, token, start, end)
                } ~
                statics { (topic, token) =>
                    ctx => doStaticsQuery(ctx, topic, token)
                } ~
                subscriber { (topic, token) =>
                    ctx => doSubscriber(ctx, topic, token)
                }

        } ~
            post {
                post_topic_data {
                    decompressRequest() {
                        //parameters('topic.as[String], 'content.as[String]) { (topic, content) =>
                        formFields('topic, 'content, 'key) { (topic, content, key) =>
                            //println("receive msg=", topic, content, key)                            
                            ctx => doPushData(ctx, topic,content, key)
                        }
                    }
                }
            }
    }

    def doHistoryQuery(ctx: RequestContext, topic: String, token: String, start: Int, end: Int) = {
        ctx.responder ! "uncompleted"
    }

    def doStaticsQuery(ctx: RequestContext, topic: String, token: String) = {
        ctx.responder ! "uncompleted"
    }

    def doSubscriber(ctx: RequestContext, topic: String, token: String) = {
        ctx.responder ! "uncompleted"
    }

    def doPushData(ctx: RequestContext, topic: String, content: String, key: String) = {
        ctx.responder ! "uncompleted"
    }
    lazy val index =
        <html>
            <body>
                <h1>visit url</h1>
                <p>Defined resources:</p>
                <ul>
                    <li><a href="/stats">/stats</a></li>
					<li><a href="">/msg/data/topic/token/start/size</a></li>
					<li><a href="">/msg/push/data</a></li>
                </ul>
            </body>
        </html>
    implicit val statsMarshaller: Marshaller[Stats] =
        Marshaller.delegate[Stats, String](ContentTypes.`text/plain`) { stats =>
            "Uptime                : " + stats.uptime.formatHMS + '\n' +
                "Total requests        : " + stats.totalRequests + '\n' +
                "Open requests         : " + stats.openRequests + '\n' +
                "Max open requests     : " + stats.maxOpenRequests + '\n' +
                "Total connections     : " + stats.totalConnections + '\n' +
                "Open connections      : " + stats.openConnections + '\n' +
                "Max open connections  : " + stats.maxOpenConnections + '\n' +
                "Requests timed out    : " + stats.requestTimeouts + '\n'
        }
}