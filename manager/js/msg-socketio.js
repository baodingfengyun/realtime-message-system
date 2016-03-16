//var websocket_uri = "ws://211.151.108.22:6061/websocket,ws://211.151.108.19:6061/websocket"
//var socketIo_uri = "http://211.151.108.22:6062,http://211.151.108.19:6062"
var websocket_uri = "ws://10.1.4.89:6061/websocket,ws://10.1.4.89:6061/websocket"
var socketIo_uri = "http://10.1.4.89:6062,http://10.1.4.89:6062"

function extPushSocketIoClient(client) {

}

function extPushSocketIoConnect(client, topic, cmd, key, info) {
	this.requestUrl = socketIo_uri.split(","); // 扩充服务器
	this.socketStore = ''; // socketIo对象存储
	this.linkIndex = 0 // parseInt(Math.random() * 1); //下标值，连接地址随机0, 1
}

extPushSocketIoConnect.prototype = {
	// 连接初始化
	init : function(client, topic, cmd, key, info) {
		this.socketStore = '';
		var self = this, url = this.requestUrl[this.linkIndex];
		this.socketStore = io.connect(url, {
			'reconnection delay' : 2000,
			'force new connection' : true
		});
		this.socketStore.on('message', function(data) {
			if (data.msg == "connectOK" && data.topic == "") {
				if (topic != undefined && topic != "undefined") {
					if (info == undefined) {
						info = ""
					}
					self.sendCmd(topic, cmd, key, info)
				}
			} else {
				client.onMessage(data)
			}
		});
		this.socketStore.on('connect', function(client) {
			// if (!$.browser.msie) {
			// console.log("extPush:connect");
			// }
		});
		this.socketStore.on('disConnect', function(client) {
			// if (!$.browser.msie) {
			// console.log("extPush:disConnect");
			// }
		});
		this.socketStore.on('error', function() {
			// if (!$.browser.msie) {
			// console.log("extPush:error");
			// }
			this.socketStore.close()
		})
		this.windowCloseCheck();
		this.keepWebSocketLive(client, topic, cmd, key, info)
	},
	sendCmd : function(topic, cmd, key, info) {
		if (info == undefined) {
			info = ""
		}
		var self = this;
		var jsonObject = {
			'@class' : 'com.msg.push.socketio.RepData',
			'topic' : topic,
			'msg' : cmd + ";" + topic + ";" + key + ";" + info
		};
		self.socketStore.emit('subtopic', jsonObject);
	},
	closeWebSocket : function() {
		var self = this;
		self.socketStore.close();
	},
	keepWebSocketLive : function(client, topic, cmd, key, info) {
		var self = this;
		clearInterval(window.sockeyTryAgain);
		clearTimeout(window.socketJoinSucc);
		clearTimeout(window.resetCheckFlag);
		window.sockeyTryAgain = setInterval(function() {
			if (self.socketStore.readyState == 0
					|| self.socketStore.readyState == 2
					|| self.socketStore.readyState == 3
					|| self.socketStore.bufferedAmount > 0) {
				self.closeWebSocket();
				self.init(client, topic, cmd, key,info)
			} else {
				self.sendCmd("check", "1", "1",info)
			}
		}, 1000 * 12 * 5);
	},
	windowCloseCheck : function() {
		var self = this;
		// if ($.browser.msie) {
		// window.onbeforeunload = onbeforeunload_handler;
		// function onbeforeunload_handler() {
		// self.closeWebSocket();
		// }
		// }
	}
};
