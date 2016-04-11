<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/common.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.11.0.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<title>好雨消息推送平台</title>
<style type="text/css">
.navbar-inverse {
/*  background-color: RGB(3,111,177);  */
 background-color: RGB(64,147,216); 
}
.navbar-inverse .navbar-nav>li>a{
color: #fff;
}
.navbar-inverse .navbar-nav>li>a:hover,
.navbar-inverse .navbar-nav>li>a:focus
{
background-color: #5d7895;
}
.navbar-inverse .navbar-nav>.open>a, 
.navbar-inverse .navbar-nav>.open>a:hover, 
.navbar-inverse .navbar-nav>.open>a:focus {
color: #fff;
background-color: #5d7895;
}
.font-span{
 font-weight:lighter;
 border-radius:3px; 
 padding:4px 6px; 
 background-color: #428bca;
 border-color: #357ebd;
 color:#FFF;
}
</style>
<script type="text/javascript">
$(document).ready(function(){

	$('.collapse').on('shown.bs.collapse', function () {
		$("html,body").animate({scrollTop: $(this).offset().top-90}, 0); 
	})
});
</script>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" style="color: #FFFFFF;" href="index.php">好雨消息推送平台</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
	          <?php include 'head.php'; ?>
	       	</div>
		</div>
	</nav>
	<div class="container-fluid">
		<h3 class="page-header" style="margin-top: 28px;">使用手册</h3>
		<div class="alert alert-info" role="alert" style="line-height: 30px;"><strong>说明：</strong>用户在使用服务之前，首先需要创建账户。创建并认证通过后，登录账户进行以下操作。</div>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingZero">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapseZero" aria-expanded="true" aria-controls="collapseOne">
		          		第一部分：系统介绍
		        </a>
		      </h4>
		    </div>
		    <div id="collapseZero" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingZero">
		      <div class="panel-body">
		        <div style="line-height: 40px;"><strong>系统结构图</strong></div>
				<a><img alt="" src="img/structure.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>系统组成</strong></div>
				<a><img alt="" src="img/module.png" width="700"></a>				
		      </div>
		    </div>
		  </div>
		
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		          		第二部分：应用申请
		        </a>
		      </h4>
		    </div>
		    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		      <div class="panel-body">
		        <div style="line-height: 40px;"><strong>第一步：用户登陆后首先看到的是应用申请页面，首先需要点击右上放的<span class="font-span">应用申请</span>创建一个应用。</strong></div>
				<a><img alt="" src="img/business_list.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第二步：点击右上放的<span class="font-span">应用申请</span>创建一个应用。</strong></div>
				<a><img alt="" src="img/apply.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第三步：应用创建完成后，点击应用下的<span class="font-span">实体管理</span>。</strong></div>
				<a><img alt="" src="img/business_list1.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第四步：进入实体管理页面，点击右上方的<span class="font-span">创建实体</span>,进行创建实体操作。</strong></div>
				<a><img alt="" src="img/topic_list.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第五步：创建实体页面。</strong></div>
				<div style="line-height: 40px; margin-top: 10px;">
				<strong><span class="font-span">交换方式</span>分为在线推送、定向通知和全部。</strong><br>
				<strong>在线推送</strong>  一般用于订阅/发布,点对点通讯，信息拉取
				具体操作详见<a href="javascript:$('#collapseOne').collapse('hide');$('#collapseFive').collapse('show');"><strong>第五部分：模板定义</strong></a>。
				</div>
				<a><img alt="" src="img/create_topic.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第六步：实体创建完成后,页面会返回<span class="font-span">Token</span>,用户就可以使用<span class="font-span">应用代码</span>、<span class="font-span">实体名称</span>、<span class="font-span">Token</span>值进行消息发布操作了。</strong></div>
				<a><img alt="" src="img/topic_list1.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第七步：实体管理页面，点击操作按钮弹出下拉菜单，还提供了<span class="font-span">编辑</span>、<span class="font-span">详情</span>功能</strong></div>
				<a><img alt="" src="img/topic_list2.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第八步：实体管理，点击<span class="font-span">编辑</span>弹出编辑窗口。</strong>
				<br>编辑页面下，<strong>实体名称</strong>和<strong>模式匹配</strong>不可修改。
				</div>
				<a><img alt="" src="img/topic_edit.png" width="700"></a>
				<div style="line-height: 40px; margin-top: 10px;"><strong>第九步：实体管理，点击<span class="font-span">详情</span>弹出详细信息。</strong></div>
				<a><img alt="" src="img/topic_detail.png" width="700"></a>
		      </div>
		    </div>
		  </div>
		  
		   <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
		          	第三部分：实际操作
		        </a>
		      </h4>
		    </div>
		    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
		      <div class="panel-body">
		      		<div style="line-height: 40px;"><strong>一：<a style="text-decoration:underline;" href="main.php?sdk=1" target="_blank">下载SDK。</a></strong></div>
		      		
		      		<div style="line-height: 40px;"><strong>二： WebSocket使用。</strong></div>
		      			<p>1、下载js代码。ie9及以下版本需要下载<a href="down/WebSocketMain.swf.zip">WebSocketMain.swf。</a></p>
		      			<p>2、js代码调用。</p>
		      			<p>消息订阅方法：extPushWebSocketConnect().sendCmd(topic,type,token,identifier)。</p>
		      			<p>参数说明：topic ：应用代码+"_"+实体名称；type ：sub/unsub ，即订阅/取消；token: 实体token；identifier:订阅者标识（没有传空字符）。</p>
		      			<div class="panel  panel-default" style="padding: 10px;line-height: 30px;">
		      			var connect = new extPushWebSocketConnect(); <br>
						connect.init(new extPushWebSocketClient()); //初始化<br>
						connect.sendCmd("goodrain_demo", "sub", "b05b6e6d3d35d41220aac1b971f30d4d","123"); //消息订阅与取消<br>
						</div>
						<p>3、消息发布。</p>
						<p>使用post方式发送到http://服务ip:端口/msg/push/data地址。</p>
						<p>相关参数为：topic ：应用代码+"_"+实体名称；token ：实体token；content ：消息内容。</p>
						<p>4、消息接收。</p>
						<div class="panel  panel-default" style="padding: 10px;line-height: 30px;">
							extPushWebSocketClient.prototype = { <br>
								&nbsp;&nbsp;onMessage : function(data) { <br>
									&nbsp;&nbsp;&nbsp;&nbsp;alert(data.topic);  //消息发送的topic <br>
	 								&nbsp;&nbsp;&nbsp;&nbsp;alert(data.msg);   //消息的发送内容 <br>
								&nbsp;&nbsp;} <br>
							} <br>
						</div>
		      </div>
		    </div>
		  </div>
		  
		
		<!--<div class="alert alert-success" role="alert" style="line-height: 30px;margin-top: 40px;margin-bottom: 40px;">
      		<strong>提示：</strong>消息发布功能操作演示，见<a href="/demo/demo.html"><strong>在线演示</strong></a>页面！
		</div>-->
	</div>
</body>
</html>