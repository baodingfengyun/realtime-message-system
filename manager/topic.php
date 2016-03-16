 <?php
 require 'validateSession.php';
	class topic {
		public $name;
		public $is_store;
		public $store_method;
		public $store_num;
		public $send_num;
		public $sub_num;
		public $join_method;
		public $key;
		public $create_time;
		public $valid_time;
		public $status;
		public $is_regx;
		public $is_replace;
		public $is_notify;
		public $regx_param;
		public $template_regx;
		public $notify_regx;
		public $broad_status;
		
		public function setValues() {
			foreach ( $this as $key => &$value ) {
				if (! empty ( $_POST [$key] ))
					$value = $_POST [$key];
			}
		}
		
		public function saveTopic($code,$jmethod) {
			$methods="";
			if(!empty($jmethod)){
				foreach ($jmethod as $key => $value) {
					if($methods == ""){
						$methods = $value;
					}else{
						$methods = $methods.",".$value;
					}
				}
			}
			
			if((int)$this->is_regx == 1){
				$this->name = explode("-",$this->name)[0]."-";
			}
			//else{
				//$this->name = explode("-",$this->name)[0];
			//}
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb ( 'msg-center', 'topic_info' );
						
			$createTime = date('Y-m-d H:i:s',time());
			$record = array (); // 字段存储列表
			$record ['code'] = $code; // 业务名称英文简写
			try {
				$cursor = $collection->findOne(array("code" => $code),array("entity" =>true));
				//return var_export($count['entity']['dddd'],true);
				if(!empty($cursor['entity'][$this->name])){
					return "1";
				}else{
					$topicInfo = array ();
					$topicInfo ['is_store'] = (int)$this->is_store;
					$topicInfo ['store_method'] = (int)$this->store_method;
					$topicInfo ['store_num'] = (int)$this->store_num;
					$topicInfo ['send_num'] = 0;
					$topicInfo ['sub_num'] = 0;
					$topicInfo ['join_method'] = empty($methods) ? "" : $methods;
					$topicInfo ['key'] = md5 ( $code . "_" .$this->name );
					$topicInfo ['create_time'] = $createTime;
					$topicInfo ['status'] = 1;
					$topicInfo ['is_regx'] = (int)$this->is_regx;
					$topicInfo ['is_replace'] = (int)$this->is_replace;
					$topicInfo ['is_notify'] = (int)$this->is_notify;
					$topicInfo ['regx_param'] = empty($this->regx_param) ? "" : $this->regx_param;
					$topicInfo ['template_regx'] = empty($this->template_regx) ? "" : $this->template_regx;
					$topicInfo ['notify_regx'] = empty($this->notify_regx) ? "" : $this->notify_regx;
					$topicInfo ['broad_status'] = (int)$this->broad_status;
					if(!empty($cursor['entity'])){
						$collection->update ($record, array("\$set" => array ("entity.".$this->name => $topicInfo)));
					}else{
						$collection->update ($record, array("\$set" => array ("entity" => array($this->name => $topicInfo))));
					}
					if((int)$this->is_notify == 2 && (int)$this->is_regx==1){
						$topicInfo ['key'] = md5 ( $code . "_" .$this->name."*" );
						$collection->update ($record, array("\$set" => array ("entity.".$this->name."*" => $topicInfo)));
					}
					return "0";
				}
			} catch ( MongoCursorException $e ) {
					
				// $this->error = $e->getMessage();
				return "2";
			}
		}
		
		public function queryTopic($code){
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','topic_info');
			$query = array( "code" => $code );
			$filter = array( "entity" => true );
			$cursor = $collection->findOne($query,$filter);
			
			return $cursor;
		}
		
		public function updateStat($code,$_stat){
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','topic_info');
			
			$cursor = $collection->findOne(array("code" => $code),array("entity" =>true));
			if(!empty($cursor['entity'][$this->name])){
				$record = array (); // 字段存储列表
				$record ['code'] = $code; // 业务名称英文简写
				$value = array("\$set" => array ("entity.".$this->name.".status" => (int)$_stat));
				
				try {
					$collection->update ($record,$value);
					return "0";
				} catch ( MongoCursorException $e ) {
				
					// $this->error = $e->getMessage();
					return "2";
				}
			}else{
				return "1";
			}
		}
		
		public function topicDetail($code,$name){
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','topic_info');
				
			$record = array (); // 字段存储列表
			$record ['code'] = $code; // 业务名称英文简写
			
			$searchObj = array();
			try {
				$searchObj = $collection->findOne($record,array('entity.'.$name =>true));
				return $searchObj;
			} catch ( MongoCursorException $e ) {
					
				// $this->error = $e->getMessage();
			}
			return $searchObj;
		}
		
		public function show_modify($code,$name){
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb('msg-center','topic_info');
			
			$record = array (); // 字段存储列表
			$record ['code'] = $code; // 业务名称英文简写
				
			$searchObj = array();
			try {
				$searchObj = $collection->findOne($record,array('entity.'.$name =>true));
				if(!empty($searchObj['entity'])){
					foreach ($searchObj['entity'] as $key=>$topic) {
						$is_store = $topic["is_store"];
						$store_method = $topic["store_method"];
						$store_num = $topic["store_num"];
						$send_num = $topic["send_num"];
						$sub_num = $topic["sub_num"];
						$join_method = $topic["join_method"];
						$key = $topic["key"];
						$create_time = $topic["create_time"];
						$status = $topic["status"];
						$is_regx = $topic["is_regx"];
						$is_replace = $topic["is_replace"];
						$is_notify = $topic["is_notify"];
						$regx_param = $topic["regx_param"];
						$template_regx = $topic["template_regx"];
						$notify_regx = $topic["notify_regx"];
						$broad_status = $topic["broad_status"];
					}
				}
				return "{'name':'$name','is_store':'$is_store','store_method':'$store_method','store_num':'$store_num',
				'send_num':'$send_num','sub_num':'$sub_num','join_method':'$join_method','key':'$key','create_time':'$create_time',
				'status':'$status','is_regx':'$is_regx','is_replace':'$is_replace','is_notify':'$is_notify','regx_param':'$regx_param',
				'template_regx':'$template_regx','notify_regx':'$notify_regx','broad_status':'$broad_status'}";;
			} catch ( MongoCursorException $e ) {
				return "";
				// $this->error = $e->getMessage();
			}
		}
		
		public function modify($code,$name){
			require_once ("mongoUtil.php");
			$mongo = new Mongo_Util ();
			$collection = $mongo->getMongoDb ( 'msg-center', 'topic_info' );
			
			$createTime = date('Y-m-d H:i:s',time());
			$record = array (); // 字段存储列表
			$record ['code'] = $code; // 业务名称英文简写
			try {
				$cursor = $collection->findOne(array("code" => $code),array("entity" =>true));
				//return var_export($count['entity']['dddd'],true);
				if(empty($cursor['entity'][$name])){
					return "1";
				}else{
					$topicInfo = array ();
					$topicInfo ['is_store'] = (int)$this->is_store;
					$topicInfo ['store_method'] = (int)$this->store_method;
					$topicInfo ['store_num'] = (int)$this->store_num;
					$topicInfo ['send_num'] = (int)$cursor['entity'][$name]['send_num'];
					$topicInfo ['sub_num'] = (int)$cursor['entity'][$name]['sub_num'];
					$topicInfo ['join_method'] = empty($methods) ? "" : $methods;
					$topicInfo ['key'] = md5 ( $code . "_" .$name );
					$topicInfo ['create_time'] = $createTime;
					$topicInfo ['status'] = (int)$this->status;
					$topicInfo ['is_regx'] = (int)$this->is_regx;
					$topicInfo ['is_replace'] = (int)$this->is_replace;
					$topicInfo ['is_notify'] = (int)$this->is_notify;
					$topicInfo ['regx_param'] = empty($this->regx_param) ? "" : $this->regx_param;
					$topicInfo ['template_regx'] = empty($this->template_regx) ? "" : $this->template_regx;
					$topicInfo ['notify_regx'] = empty($this->notify_regx) ? "" : $this->notify_regx;
					$topicInfo ['broad_status'] = (int)$this->broad_status;
					$collection->update ($record, array("\$set" => array ("entity.".$name => $topicInfo)));
					if(!empty($cursor['entity'][$name."*"])){
					   if((int)$this->is_notify != 2 || (int)$this->status == 0){
							$topicInfo['status'] = 0;
							$topicInfo ['key'] = md5 ( $code . "_" .$name."*" );
							$collection->update ($record, array("\$set" => array ("entity.".$name."*" => $topicInfo)));
						}else{
							$topicInfo ['key'] = md5 ( $code . "_" .$name."*" );
							$collection->update ($record, array("\$set" => array ("entity.".$name."*" => $topicInfo)));
						}
					}					
					return "0";
				}
			} catch ( MongoCursorException $e ) {
					
				// $this->error = $e->getMessage();
				return "2";
			}
		}
	}
	
	$method = $_POST ["method"];
	$code = $_POST ["code"];
	$stat = $_POST ["stat"];
	$name = $_POST ["name"];
	$jmethod = $_POST ["join_method"];
	$topic = new topic();
	$topic->setValues();
	switch ($method) {
		case "save" :
			echo $topic->saveTopic($code,$jmethod);
			break;
		case "updateStat":
			echo $topic->updateStat($code,$stat);
			break;
		case "show_modify":
			echo $topic->show_modify($code,$name);
			break;
		case "modify":
			echo $topic->modify($code,$name);
			break;
			
	}	
	
	
?>