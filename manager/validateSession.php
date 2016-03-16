<?php
	Session_Start();
	$session_user=$_SESSION["user"];
	if(empty($session_user)){
		if($_COOKIE['id']&&$_COOKIE['pass']){
			require_once ("user.php");
			$user = new user();
			$result = $user->autoLogin($_COOKIE['id'], $_COOKIE['pass']);
			if($result == 0){
				$session_user=$_SESSION["user"];
				return;
			}
		}
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='login.php'";
		echo "</script>";
	}
?>