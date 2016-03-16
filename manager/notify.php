<?php
class notify{
	public $create_time;
	public $name;
	public $content;
	public $desc;
	public $status;

	public function setValues() {
		foreach ( $this as $key => &$value ) {
			if (! empty ( $_POST [$key] ))
				$value = $_POST [$key];
		}
	}

	public function save($code,$topicName){
		try{
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_notify');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$cursor = $collection->findOne($query);
			if(!empty($cursor['notify'][$this->name])){
				return "1";
			}else{
				$createTime = date('Y-m-d H:i:s',time());
				$notifyInfo = array();
				$notifyInfo['create_time'] = $createTime;
				$notifyInfo['content'] = $this->content;
				$notifyInfo['desc'] = $this->desc;
				$notifyInfo['status'] = (int)$this->status;
				if(!empty($this->content)){
					$search = '/\\$\\{(.+?)\\}/';
					preg_match_all($search,$this->content,$r);
					$param = array();
					foreach($r[1] as $value){
						$param[$value] = array("source" => "0","return_field" => "","query_field" => "");
					}
					if(!empty($param)){
						$notifyInfo['param'] = $param;
					}
				}
				if(empty($cursor)){
					$notify = array();
					$notify['create_time'] = $createTime;
					$notify['notify'] = array($this->name => $notifyInfo);
					$notify['entity_name'] = $code."_".$topicName;
					$collection->insert($notify,array('safe'=>true));
				}elseif(empty($cursor['notify'])){
					$collection->update ($query, array("\$set" => array ("notify" => array($this->name => $notifyInfo))));
				}elseif(!empty($cursor['notify'])){
					$collection->update ($query, array("\$set" => array ("notify.".$this->name => $notifyInfo)));
				}
				return "0";
			}
		}catch ( MongoCursorException $e ) {
			// $this->error = $e->getMessage();
			return "2";
		}
	}
	public function query($code,$topicName){
		try {
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_notify');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "notify" => true );
			$cursor = $collection->findOne($query,$filter);
				
			return $cursor;
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
		}
	}
	public function detail($code,$topicName,$name){
		try {
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_notify');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "notify.".$name => true );
			$cursor = $collection->findOne($query,$filter);
	
			return $cursor;
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
		}
	}
	public function modify($code,$topicName){
		try{
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_notify');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "notify" => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['notify'][$this->name])){
				$value = array();
				$value["create_time"] = $cursor['notify'][$this->name]['create_time'];
				$value["content"] = $this->content;
				$value["desc"] = $this->desc;
				$value["status"] = (int)$this->status;
				if(!empty($this->content)){
					$search = '/\\$\\{(.+?)\\}/';
					preg_match_all($search,$this->content,$r);
					$param = array();
					foreach($r[1] as $n){
						$source = "";
						$return_field = "";
						$query_field = "";
						if(!empty($cursor['notify'][$this->name]['param'][$n]['source'])){
							$source =$cursor['notify'][$this->name]['param'][$n]['source'];
						}
						if(!empty($cursor['notify'][$this->name]['param'][$n]['return_field'])){
							$return_field = $cursor['notify'][$this->name]['param'][$n]['return_field'];
						}
						if(!empty($cursor['notify'][$this->name]['param'][$n]['query_field'])){
							$query_field = $cursor['notify'][$this->name]['param'][$n]['query_field'];
						}
						$param[$n] = array("source" => $source,"return_field" => $return_field,"query_field"=>$query_field);
					}
					if(!empty($param)){
						$value['param'] = $param;
					}
				}
				$collection->update ($query,array("\$set" => array("notify.".$this->name => $value)));
				return "0";
			}else{
				return "1";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	}

	public function save_params($code, $topicName, $_post){
		try{
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_notify');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "notify.".$name => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['notify'][$_post ["name"]]["param"])){
				$param = array();
				foreach ($cursor['notify'][$_post ["name"]]["param"] as $key=>$attr){
					$source = $_post[$key."_source"];
					$return_field = $_post[$key."_return"];
// 					$query_field = $_post[$key."_query"];
					$query_field = $_post[$key."_return"];
					$param[$key] = array("source" => $source,"return_field" => $return_field,"query_field"=>$query_field);
				}
				$collection->update ($query,array("\$set" => array("notify.".$this->name.".param" => $param)));
				return "0";
			}else{
				return "1";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	
	}
}

$code = $_POST['code'];
$topicName = $_POST['topicName'];
$method = $_POST['method'];
$notify = new notify();
$notify->setValues();
switch ($method){
	case "save":
		echo $notify->save($code, $topicName);
		break;
	case "modify":
		echo $notify->modify($code, $topicName);
		break;
	case "save_params":
		echo $notify->save_params($code, $topicName, $_POST);
	    break;
}

?>