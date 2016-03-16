package com.msg.push.socketio;

import java.text.SimpleDateFormat;
import java.util.Date;

import com.corundumstudio.socketio.AckRequest;
import com.corundumstudio.socketio.Configuration;
import com.corundumstudio.socketio.SocketIOClient;
import com.corundumstudio.socketio.SocketIOServer;
import com.corundumstudio.socketio.listener.ConnectListener;
import com.corundumstudio.socketio.listener.DisconnectListener;
import com.corundumstudio.socketio.listener.DataListener;

public abstract class SoketIo {
    SocketIOServer server = null;

    abstract void doStartSub(String msg, SocketIOClient client);

    abstract void unConnect(SocketIOClient client);

    private int joinNum = 0;

    private SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

    public SocketIOServer getSoketIoServer(String host, int port) {
        if (server == null) {
            server = init(host, port);
        }
        return server;
    }

    private SocketIOServer init(String host, int port) {
        Configuration config = new Configuration();
        if (!host.isEmpty()) {
            config.setHostname(host);
        }
        config.setPort(port);

        server = new SocketIOServer(config);
        server.addEventListener("subtopic", RepData.class,
                new DataListener<RepData>() {
                    public void onData(SocketIOClient client, RepData arg1,
                            AckRequest arg2) throws Exception {
                        doStartSub(arg1.msg, client);
                    }
                });
        server.addConnectListener(new ConnectListener() {
            public void onConnect(SocketIOClient client) {
                RepData repData = new RepData();
                repData.setTopic("");
                repData.setMsg("connectOK");
                client.sendEvent("message", repData);
                joinNum = joinNum + 1;
                System.out.println("SocketIOServer joinNum=" + joinNum + "=onOpen:"
                        + sdf.format(new Date()));
            }
        });
        server.addDisconnectListener(new DisconnectListener() {
            public void onDisconnect(SocketIOClient client) {
                unConnect(client);
                joinNum = joinNum - 1;
            }
        });
        return server;
    }
}
