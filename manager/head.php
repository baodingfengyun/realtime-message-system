<ul class="nav navbar-nav navbar-right">
<li><a href="index.php">产品介绍</a></li>
<li><a href="instructions.php">使用手册</a></li>
<!--<li><a href="demo/demo.html">在线演示</a></li>-->
<li><a href="main.php">主题定义</a></li>
<?php
Session_Start();
$session_user=$_SESSION["user"];
if(empty($session_user)){
	echo "<li class='dropdown'>";
	echo " <a href='javascript:window.location.href=\"login.php\";'>登录 </a></li>";
	echo "<li class='dropdown'>";
	echo "<a href='javascript:window.location.href=\"register.php\";'>注册</a></li>";
}else{
	echo "<li class='dropdown' id='li_id' >";
	echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>";
	echo $session_user['cname'].",您好 <span class='caret'></span>";
	echo "</a><ul id='ul_id' class='dropdown-menu' role='menu'><li><a href='user.php?method=logout'>登出</a></li></ul></li>";
}
?>
</ul>
<script>
$("#ul_id").attr("style","min-width:"+$("#li_id").width()+"px;");
</script>
