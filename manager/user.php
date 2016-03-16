<?php
class user {
	public $cname;
	public $password;
	public $confirm;
	public $ename;
	public $tel;
	public $email;
	public $create_time;
	
	public function setValues() {
		foreach ( $this as $key => &$value ) {
			if (! empty ( $_POST [$key] ))
				$value = $_POST [$key];
		}
	}
	
	public function register(){
		require_once ("mongoUtil.php");
		$mongo = new Mongo_Util ();
		$collection = $mongo->getMongoDb('msg-center','user_info');
		
		try {
			$count = $collection->find(array('email'=>$this->email))->count();
			
			if($count>0){
				return "1";
			}else{
				if(!empty($this->email) && !empty($this->cname) && !empty($this->password) 
						&& !empty($this->password) && !empty($this->tel)){
					$record = array();
					$record['cname'] = $this->cname;
					$record['password'] = $this->password;
					$record['tel'] = $this->tel;
					$record['email'] = $this->email;
					$record['create_time'] = date('y-m-d H:i:s',time());
						
					$collection->insert($record, array('safe'=>true));
					return "0";
				}
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	}
	
	public function login($remembe_me){
		require_once ("mongoUtil.php");
		$mongo = new Mongo_Util ();
		$collection = $mongo->getMongoDb('msg-center','user_info');
		try {
			$record = $collection->findOne(array('email'=>$this->email,'password'=>$this->password));
			if(!empty($record)){
				Session_Start();
				$_SESSION["user"]=$record;
				if($remembe_me == "remember-me"){
					$expire = time() + 86400;
					setcookie('id',$record['email'],$expire);
    				setcookie('pass',$record['password'],$expire);
				}
				return "0";
			}else{
				return "1";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	}
	
	public function autoLogin($email,$password){
		require_once ("mongoUtil.php");
		$mongo = new Mongo_Util ();
		$collection = $mongo->getMongoDb('msg-center','user_info');
		try {
			$record = $collection->findOne(array('email'=>$email,'password'=>$password));
			if(!empty($record)){
				Session_Start();
				$_SESSION["user"]=$record;
				return "0";
			}else{
				return "1";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	}
	
	public function logout(){
		session_start();
		session_unset();
		session_destroy();
		setcookie("id", "", time()-3600);
		setcookie("pass", "", time()-3600);
	}

}

$method = $_POST ["method"];
$remembe_me = $_POST ["remembe_me"];
$user = new user();
$user->setValues();
switch ($method){
	case "register":
		echo $user->register();
		break;
	case "login":
		$result = $user->login($remembe_me);
		$url = "login.php";
		if($result == "0"){
			$url = "main.php";  
		}else{
			$url = "login.php?info=error";
		}
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
		break;
	default:
		$method = $_GET ["method"];
		echo $method;
		if($method == "logout"){
			$result = $user->logout();
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.location.href='login.php'";
			echo "</script>";
			break;
		}
}

?>