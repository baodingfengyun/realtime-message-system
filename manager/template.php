<?php
class template{
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
			$collection = $mongo->getMongoDb('msg-center','msg_template');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$cursor = $collection->findOne($query);
			if(!empty($cursor['template'][$this->name])){
				return "1";
			}else{
				$createTime = date('Y-m-d H:i:s',time());
				$templateInfo = array();
				$templateInfo['create_time'] = $createTime;
				$templateInfo['content'] = $this->content;
				$templateInfo['desc'] = $this->desc;
				$templateInfo['status'] = (int)$this->status;
				if(!empty($this->content)){
					$search = '/\\$\\{(.+?)\\}/';
					preg_match_all($search,$this->content,$r);
					$param = array();
					foreach($r[1] as $value){
						$param[$value] = array("source" => "0","return_field" => "","query_field" => "");
					}
					if(!empty($param)){
						$templateInfo['param'] = $param;
					} 
				}
				if(empty($cursor)){
					$template = array();
					$template['create_time'] = $createTime;
					$template['template'] = array($this->name => $templateInfo);
					$template['entity_name'] = $code."_".$topicName;
					$collection->insert($template,array('safe'=>true));
				}elseif(empty($cursor['template'])){
					$collection->update ($query, array("\$set" => array ("template" => array($this->name => $templateInfo))));
				}elseif(!empty($cursor['template'])){
					$collection->update ($query, array("\$set" => array ("template.".$this->name => $templateInfo)));
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
			$collection = $mongo->getMongoDb('msg-center','msg_template');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "template" => true );
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
			$collection = $mongo->getMongoDb('msg-center','msg_template');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "template.".$name => true );
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
			$collection = $mongo->getMongoDb('msg-center','msg_template');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "template" => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['template'][$this->name])){
				$value = array();
				$value["create_time"] = $cursor['template'][$this->name]['create_time'];
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
						if(!empty($cursor['template'][$this->name]['param'][$n]['source'])){
							$source =$cursor['template'][$this->name]['param'][$n]['source'];
						}
						if(!empty($cursor['template'][$this->name]['param'][$n]['return_field'])){
							$return_field = $cursor['template'][$this->name]['param'][$n]['return_field'];
						}
						if(!empty($cursor['template'][$this->name]['param'][$n]['query_field'])){
							$query_field = $cursor['template'][$this->name]['param'][$n]['query_field'];
						}
						$param[$n] = array("source" => $source,"return_field" => $return_field,"query_field"=>$query_field);
					}
					if(!empty($param)){
						$value['param'] = $param;
					}
				}
				$collection->update ($query,array("\$set" => array("template.".$this->name => $value)));
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
			$collection = $mongo->getMongoDb('msg-center','msg_template');
			$query = array ();
			$query['entity_name'] = $code."_".$topicName;
			$filter = array( "template.".$name => true );
			$cursor = $collection->findOne($query,$filter);
			if(!empty($cursor['template'][$_post ["name"]]["param"])){
				$param = array();
				foreach ($cursor['template'][$_post ["name"]]["param"] as $key=>$attr){
					$source = $_post[$key."_source"];
					$return_field = $_post[$key."_return"];
// 					$query_field = $_post[$key."_query"];
					$query_field = $_post[$key."_return"];
					$param[$key] = array("source" => $source,"return_field" => $return_field,"query_field"=>$query_field);
				}
				$collection->update ($query,array("\$set" => array("template.".$this->name.".param" => $param)));
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
$template = new template();
$template->setValues();
switch ($method){
	case "save":
		echo $template->save($code, $topicName);
		break;
	case "modify":
		echo $template->modify($code, $topicName);
		break;
	case "save_params":
		echo $template->save_params($code, $topicName, $_POST);
	    break;
}

?>