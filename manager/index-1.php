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
/*  background-color: RGB(3,111,177); */
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
.carousel-caption {
	text-shadow :0 1px 2px rgba(0,0,0,0);
}

.carousel-control .icon-next, .carousel-control .slide-right{
display: inline-block;
position: absolute;
top: 50%;
right: 25%;
z-index: 5;
background: url(img/slide-right.png) no-repeat 0 0;
width: 48px;
height: 48px;
}
.carousel-control .icon-next, .carousel-control .slide-left{
display: inline-block;
position: absolute;
top: 50%;
left: 25%;
z-index: 5;
background: url(img/slide-left.png) no-repeat 0 0;
width: 48px;
height: 48px;
}
.carousel-indicators .active{
background-color: blue;
}
</style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed"
				data-toggle="collapse" data-target="#navbar" aria-expanded="false"
				aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span><span
					class="icon-bar"></span>
			</button>
			<a class="navbar-brand" style="color: #FFFFFF;" href="#">好雨消息推送平台</a>
		</div>
        <div id="navbar" class="navbar-collapse collapse">
          <?php include 'head.php'; ?>
       	</div>
	</div>
</nav>

    <div id="carousel-example-captions" class="carousel slide" data-ride="carousel" data-interval="false">
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-captions" style="border: 1px solid blue;" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-captions" style="border: 1px solid blue;" data-slide-to="1"></li>
        <li data-target="#carousel-example-captions" style="border: 1px solid blue;" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img src="img/bg.png" style="height: 100%;" alt="产品介绍">
          <div class="carousel-caption" style="padding-bottom: 220px;color:blue;text-align: left;">
          	  <h1>产品介绍</h1>
			  <h2 style="line-height:50px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;好雨消息推送平台是一款具有发布订阅、点对点通讯、pull拉取、定向通知功能，为用户提供及时、高效、稳定的消息交换推送服务产品</h2>
          </div>
        </div>
        <div class="item">
          <img src="img/bg.png" style="height: 100%" alt="功能说明2">
          <div class="carousel-caption row" style="padding-bottom: 130px;text-align:left;color:blue;">
            <h1 class="col-sm-12">特色功能</h1>
            <div class="col-sm-1" style="font-size: 24px;text-align: right;padding-right: 5px;">1、</div><div class="col-sm-11" style="font-size: 24px;padding-left: 5px;">支持发布订阅、点对点通讯、pull拉取和定向通知</div>
            <div class="col-sm-1" style="font-size: 24px;text-align: right;padding-right: 5px;">2、</div><div class="col-sm-11" style="font-size: 24px;padding-left: 5px;">用户可以自定义实体，每个实体可以是主题或者是人。每个实体可以自定义数据源和模板。</div>
            <div class="col-sm-1" style="font-size: 24px;text-align: right;padding-right: 5px;">3、</div><div class="col-sm-11" style="font-size: 24px;padding-left: 5px;">该系统基于Actor事件模型，所有消息纯异步，无阻塞，支持1000w/分钟以上的消息推送，消息准确到达率99.99%</h3></div>
            <div class="col-sm-1" style="font-size: 24px;text-align: right;padding-right: 5px;">4、</div><div class="col-sm-11" style="font-size: 24px;padding-left: 5px;">系统中实体支持动态分区和扩容；并对消息进行实时存储，保证消息的容灾恢复</div>
            <div class="col-sm-1" style="font-size: 24px;text-align: right;padding-right: 5px;">5、</div><div class="col-sm-11" style="font-size: 24px;padding-left: 5px;">方便开发者使用；支持多终端接入，支持多种协议（socket、web）</div>
            <div class="col-sm-1" style="font-size: 24px;text-align: right;padding-right: 5px;">6、</div><div class="col-sm-11" style="font-size: 24px;padding-left: 5px;">提供消息的即时统计，包括发送量、在线订阅数等</div>
          </div>
        </div>
        <div class="item">
          <img src="img/bg.png" style="height: 100%;background-color: #EFEFEF;" alt="使用场景">
          <div class="carousel-caption" style="padding-bottom: 90px;color:blue;" align="center">
          	  <h1>常见模式</h1>
			  <img src="img/stage.png" alt="">
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#carousel-example-captions" role="button" data-slide="prev">
        <span class="slide-left"></span>
      </a>
      <a class="right carousel-control" href="#carousel-example-captions" role="button" data-slide="next">
        <span class="slide-right"></span>
      </a>
    </div>

</body>
</html>