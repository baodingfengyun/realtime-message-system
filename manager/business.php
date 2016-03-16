<?php
require 'validateSession.php';
class business {
	public $applyer;
	public $businessName;
	public $code;
	public $create_time;
	public $check_status;
	
	public function setValues() {
		foreach ( $this as $key => &$value ) {
			if (! empty ( $_POST [$key] ))
				$value = $_POST [$key];
		}
	}
	public function saveBusiness(){
		require_once ("mongoUtil.php");
		$mongo = new Mongo_Util ();
		$collection = $mongo->getMongoDb('msg-center','topic_info');
		session_start();
		$session_user = $_SESSION["user"];
		$record = array();//字段存储列表
		$record['applyer'] = $session_user['email'];
		$record['business'] = $this->businessName;
		$record['code'] = $this->code;	//业务名称英文简写
		$record['check_status'] = 1;	//业务名称英文简写
		//$record['check_status']=$check_status
		try {
			$count = $collection->find(array('code'=>$this->code))->count();
			if($count>0){
				return "1";
			}else{
				$createTime = date('Y-m-d H:i:s',time());
				$record['create_time'] = $createTime;
// 				$topicInfo = array ();
// 				$topicInfo ['is_store'] = 1;
// 				$topicInfo ['store_method'] = 2;
// 				$topicInfo ['store_num'] = 1;
// 				$topicInfo ['send_num'] = 0;
// 				$topicInfo ['sub_num'] = 0;
// 				$topicInfo ['join_method'] = "";
// 				$topicInfo ['key'] = md5 ( $this->code . "_notify*" );
// 				$topicInfo ['create_time'] = $createTime;
// 				$topicInfo ['status'] = 1;
// 				$topicInfo ['is_regx'] = 0;
// 				$topicInfo ['is_replace'] = 0;
// 				$topicInfo ['is_notify'] = 0;
// 				$topicInfo ['regx_param'] = "";
// 				$topicInfo ['template_regx'] = "";
// 				$topicInfo ['notify_regx'] = "";
				$record['entity'] = "";
				$collection->insert($record, array('safe'=>true));
				return "0";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
		
	}
	public function queryBusiness(){
		require_once ("mongoUtil.php");
		$mongo = new Mongo_Util ();
		$collection = $mongo->getMongoDb('msg-center','topic_info');
		session_start();
		$session_user = $_SESSION["user"];
		$user=$session_user['email'];
		$query = array( "applyer" =>  $user);
		$cursor = $collection->find($query);
		
// 		while( $cursor->hasNext() ) {
// 			var_dump( $cursor->getNext() );
// 		}
		return $cursor;
	}
	
}

$method = $_POST ["method"];
$business = new business();
$business->setValues();
switch ($method) {
	case "save" :
		echo $business->saveBusiness();
		break;
}

?>