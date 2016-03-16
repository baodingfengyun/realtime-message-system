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
	<div class="container-fluid">
		<div class="row-fluid">
			<div id="main"
				class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 main">
				<div>
					<h3 class="page-header">用户注册</h3>
					<form id="form1" class="form-horizontal" role="form">
							<div id="alert_div_content" class='alert alert-danger alert-dismissible col-sm-10 col-sm-offset-2' role='alert' style='margin-bottom: 5px; display: none'>
								<button type='button' class='close' onclick="alert_close()">
								<span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span>
								</button>
								<strong id="alert_info"></strong>
							</div>
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label"><span
								style="color: red">*</span>注册账号</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="email"
									name="email" placeholder="邮箱账号">
							</div>
						</div>
						<div class="form-group">
							<label for="cname" class="col-sm-2 control-label"><span
								style="color: red">*</span>用户名称</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="cname" name="cname"
									placeholder="用户名称">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-2 control-label"><span
								style="color: red">*</span>登陆密码</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="password"
									name="password" placeholder="数字或字母大小写下划线，长度不少于8位">
							</div>
						</div>
						<div class="form-group">
							<label for="confirm" class="col-sm-2 control-label"><span
								style="color: red">*</span>密码确认</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="confirm"
									name="confirm" placeholder="数字或字母大小写下划线，长度不少于8位">
							</div>
						</div>
						<div class="form-group">
							<label for="tel" class="col-sm-2 control-label"><span
								style="color: red">*</span>联系方式</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tel"
									name="tel" placeholder="用户手机号">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="reset" class="btn btn-default" value="重置" /> <input
									type="button" id="submit1" class="btn btn-primary" value="注册" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">

	function alert_close(){
		$("#alert_div_content").hide();
	}
	
	$("#submit1").click(function() {
		var email = $("#email").val();
		var cname = $("#cname").val();
		var password = $("#password").val();
		var confirm =  $("#confirm").val();
		var tel = $("#tel").val();
		var emailReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var passwordReg = /^[a-zA-Z0-9]{8,}$/;
		var telReg = /^1[3|5|7|8|][0-9]{9}$/
		var result = true;
		var result_info = "";
 		if(email.length==0){
 			result_info = "请输入注册账号!";
			result =false;
			$('#email').focus();
		}else if(!emailReg.test(email)){
			result_info = "邮箱地址格式输入错误!";
			result =false;
			$('#email').focus();
		}else if(cname.length==0){
			result_info = "用户名称不能为空!";
			result =false;
			$('#cname').focus();
		}else if(password.length==0){
			result_info = "密码不能为空!";
			result =false;
			$('#password').focus();
		}else if(!passwordReg.test(password)){
			result_info = "密码格式错误!";
			result =false;
			$('#password').focus();
		}else if(confirm.length==0){
			result_info = "密码确认不能为空!";
			result =false;
			$('#confirm').focus();
		}else if(confirm != password){
			result_info = "密码确认不一致!";
			result =false;
			$('#confirm').focus();
		}else if(tel.length==0){
			result_info = "手机号不能为空!";
			result =false;
			$('#tel').focus();
		}else if(!telReg.test(tel)){
			result_info = "手机号输入错误!";
			result =false;
			$('#tel').focus();
		}
		if(!result){
			$("#alert_div_content").show();
			$("#alert_info").text(result_info);
			return;
		}
		
		var _data = $("form").serialize();
       	$.ajax({
       	  type: "post",  
 			  url: "user.php",  
 			  data: _data+"&method=register",
 			  cache: false,  
 		  	  success: function(msg) {
	 		  	if(msg == 0){
	 		  		alert("注册成功");
	 		  		window.location.href="login.php";
	 		  	}else if(msg == 1){
	 		  		alert("账号已存在");
	 		  	}else if(msg == 2){
	 		  		alert("注册失败");
	 		  	} 		  	
 			  },
 			  error: function(){
 				alert("系统异常");
       	  	}
       	})
	});
</script>
</html>