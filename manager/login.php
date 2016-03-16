<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/signin.css" rel="stylesheet">
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


    <div class="container" style="padding: 20px;">
      <form class="form-signin" role="form" method="post" action="user.php">
        <h3 class="form-signin-heading">用户登录</h3>
        <?php 
			$info = $_GET['info'];
			if(!empty($info)){
				echo "<div class='alert alert-danger alert-dismissible' role='alert' style='margin-bottom: 5px;'>";
				echo "<button type='button' class='close' data-dismiss='alert'>";	
				echo "<span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span>";	
				echo "</button><strong>账号或密码错误，请重新输入</strong></div>";
			};
		?>
		<input type="hidden" name="method" value="login"> 
        <input type="email" name="email" class="form-control" placeholder="请输入邮箱地址" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="密码" required>
        <div class="checkbox">
          <label style="padding-left: 20px;">
            <input type="checkbox" name="remembe_me" value="remember-me"> 下次自动登录
          </label>
          <a href="javascript:window.location.href='register.php';" style="margin-right: 10px;float: right; cursor:pointer;">用户注册</a>
        </div>
        <button class="btn btn-lg-6 btn-primary btn-block" type="submit">登录</button>
      </form>
    </div> <!-- /container -->

</body>
</html>
