package com.msg.push.socketio;

public class RepData {
    String topic = "";
    String msg = "";
    
    public RepData(){
    }
    public RepData(String t,String m){
        this.topic=t;
        this.msg=m;
    }

    public String getTopic() {
        return topic;
    }

    public void setTopic(String topic) {
        this.topic = topic;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }
}