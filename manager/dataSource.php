<?php
class dataSource{
	public $name;
	public $address;
	public $check_time=1500;
	public $status;
	public $category;
	public $create_time;
	public $topic;
	
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
			$collection = $mongo->getMongoDb('msg-center','msg_queue');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$cursor = $collection->findOne($query);
			if(!empty($cursor['queue'][$this->name])){
				return "1";
			}else{
				$createTime = date('Y-m-d H:i:s',time());
				$dataSourceInfo = array();
				$dataSourceInfo['create_time'] = $createTime;
				$dataSourceInfo['address'] = $this->address;
				$dataSourceInfo['check_time'] = (int)$this->check_time;
				$dataSourceInfo['status'] = 1;
				$dataSourceInfo['category'] = (int)$this->category;
				$dataSourceInfo['topic'] = $this->topic;
				if(empty($cursor)){
					$queue = array();
					$queue['create_time'] = $createTime;
					$queue['queue'] = array($this->name => $dataSourceInfo);
					$queue['entity_name'] = $code."_".$topicName;
					$collection->insert($queue,array('safe'=>true));
				}elseif(empty($cursor['queue'])){
					$collection->update ($query, array("\$set" => array ("queue" => array($this->name => $dataSourceInfo))));
				}elseif(!empty($cursor['queue'])){
					$collection->update ($query, array("\$set" => array ("queue.".$this->name => $dataSourceInfo)));
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
			$collection = $mongo->getMongoDb('msg-center','msg_queue');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "queue" => true );
			$cursor = $collection->findOne($query,$filter);
			
			return $cursor;
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
		}
	}
	
	public function updateStat($code,$topicName,$stat){
		try{
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_queue');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "queue" => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['queue'][$this->name])){
				$value = array("\$set" => array ("queue.".$this->name.".status" => (int)$stat));
				$collection->update ($query,$value);
				return "0";
			}else{
				return "1";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	}
	
	public function modify($code,$topicName){
		try{
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_queue');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "queue" => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['queue'][$this->name])){
				$value = array();
				$value["create_time"] = $cursor['queue'][$this->name]['create_time'];
				$value["address"] = $this->address;
				$value["check_time"] = (int)$cursor['queue'][$this->name]['check_time'];
				$value["status"] = (int)$this->status;
				$value["category"] = (int)$this->category;
				$value["topic"] = $this->topic;
				$collection->update ($query,array("\$set" => array("queue.".$this->name => $value)));
				return "0";
			}else{
				return "1";
			}
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
			return "2";
		}
	}
	
	public function detail($code,$topicName){
		try {
			$result = null;
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','msg_queue');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "queue.".$this->name => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['queue'])){
				foreach ($cursor['queue'] as $key=>$dataSource) {
					$address = $dataSource["address"];
					$category = $dataSource["category"];
					$status = $dataSource["status"];
					$topic = $dataSource["topic"];
				}
				$result = array("name"=>$this->name);
			}
			return "{'name':'$this->name','address':'$address','category':'$category','status':'$status','topic':'$topic'}";
		}catch (MongoCursorException $e){
			//$this->error = $e->getMessage();
		}
	}
}

$code = $_POST['code'];
$topicName = $_POST['topicName'];
$method = $_POST['method'];
$dataSource = new dataSource();
$dataSource->setValues();
switch ($method){
	case "save":
		echo $dataSource->save($code, $topicName);
		break;
	case "updateStat":
		$stat = $_POST['stat'];
		echo $dataSource->updateStat($code, $topicName,$stat);
		break;
	case "modify":
		echo $dataSource->modify($code, $topicName);
		break;
	case "query":
		echo $dataSource->detail($code, $topicName);
		break;
}


?>