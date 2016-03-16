 <?php
 require 'validateSession.php';
	class apply {
		public $code;
		public $check_status;
		
		public function setValues() {
			foreach ( $this as $key => &$value ) {
				if (! empty ( $_POST [$key] ))
					$value = $_POST [$key];
			}
		}
		
		public function updateStat($code,$_stat){
			session_start();
			$session_user = $_SESSION["user"];
			$user=$session_user['email'];
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','topic_info');
			
			$record = array (); // 字段存储列表
			$record ['code'] = $code; // 业务名称英文简写
			$value = array("\$set" => array ("check_status" =>1));				
			try {
				$collection->update ($record,$value);
				return "0";
			} catch ( MongoCursorException $e ) {
				return "2";
			}			
		}
	}
	
	$method = $_GET ["method"];
	$code = $_GET ["code"];
	$topic = new apply();
	$topic->setValues();
	switch ($method) {
		case "updateStat":
			echo $topic->updateStat($code,$stat);
			break;
	}	
	
	
?>