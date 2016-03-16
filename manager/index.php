<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/docs.min.css" rel="stylesheet">
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
			<a class="navbar-brand" style="color: #FFFFFF;" href="index.php">好雨消息推送平台</a>
		</div>
        <div id="navbar" class="navbar-collapse collapse">
          <?php include 'head.php'; ?>
       	</div>
	</div>
</nav>

    <!-- Page content of course! -->
<main class="bs-docs-masthead" id="content" role="main">
  <div class="container" style="background-color: #92aad3;">
    <span class="bs-docs-booticon bs-docs-booticon-lg">好雨消息推送平台</span>
    <p class="lead">是一款集成发布订阅、点对点通讯、pull拉取、定向通知功能，为用户提供及时、高效、稳定的消息交换推送服务产品</p>
  </div>
</main>
<div class="bs-docs-featurette" style="padding-top: 70px;">
  <div class="container">
    <h2 class="bs-docs-featurette-title">为用户提供多场景的消息交换服务</h2>
    <p class="lead">让消息交换变得更高效、更快捷、更稳定。让使用者变得更容易、更方便。</p>

    <hr class="half-rule">

    <div class="row">
      <div class="col-sm-4 div-block">
        <h3 style="color: #FFF;">支持多种场景</h3>
        <p>支持发布订阅、点对点通讯、pull拉取和定向通知</p>
      </div>
      <div class="col-sm-4 div-block">
        <h3 style="color: #FFF;">配置灵活多样</h3>
        <p>用户可以自定义实体；实体可以是主题或者是人并且是有状态的</p>
      </div>
      <div class="col-sm-4 div-block">
        <h3 style="color: #FFF;">消息高效、可靠</h3>
        <p>系统基于Actor事件模型，所有消息纯异步，无阻塞，支持1000w/分钟以上的消息推送，消息准确到达率99.99%</p>
      </div>
      <div class="col-sm-4 div-block">
        <h3 style="color: #FFF;">动态伸缩</h3>
        <p>系统中实体支持动态分区和扩容；并对消息进行实时存储，保证消息的容灾恢复</p>
      </div>
      <div class="col-sm-4 div-block">
        <h3 style="color: #FFF;">支持多终端接入</h3>
        <p>方便开发者使用；支持多终端接入，支持多种协议（socket、web）</p>
      </div>
      <div class="col-sm-4 div-block">
        <h3 style="color: #FFF;">统计功能</h3>
        <p>提供消息的即时统计，包括发送量、在线订阅数等</p>
      </div>
    </div>
  </div>
</div>
<footer class="bs-docs-footer" role="contentinfo">
  <div class="container">
    <p>好雨消息推送平台 版权所有 ©2015-2019</p>
  </div>
</footer>
</body>
</html>