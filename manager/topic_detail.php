	  <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="font-weight: bold;">实体详情</h4>
      </div>
      <div class="modal-body">
        	<table class="table table-striped table-hover">
				<?php
				require 'validateSession.php';
				require_once ("topic.php");
				$topic = new topic();
				$cursor = $topic->topicDetail($_POST ["code"],$_POST ["name"]);
				
				foreach ($cursor['entity'] as $key=>$topic){
					$regx = $topic["is_regx"] == 0 ? "否":"是";
					$store = $topic["is_store"] == 0 ? "否":"是";
					$stat = $topic["status"] == 0 ? "失效":"有效";
					$smethod = $topic["store_method"] == 1 ? "条数":"天数";
					$is_replace = $topic["is_replace"] == 1 ? "是":"否";
					if($topic["is_notify"] == 0){
						$is_notify = "在线推送";
					}else if($topic["is_notify"] == 1){
						$is_notify = "定向通知";
					}else if($topic["is_notify"] == 2){
						$is_notify = "全部";
					}
					$broad = $topic["broad_status"] == 0 ? "否":"是";
				// 	$jmethod = explode(",",$topic["join_method"]);
				// 	$jname ="";
				// 	foreach ($jmethod as $value){
				// 		if($jname != ""){
				// 			$jname = $jname.",";
				// 		}
				// 		if($value == "1"){
				// 			$jname = $jname."发布/订阅";
				// 		}
				// 		if($value == "2"){
				// 			$jname = $jname."点对点通讯";
				// 		}
				// 		if($value == "3"){
				// 			$jname = $jname."信息拉取";
				// 		}
				// 		if($value == "4"){
				// 			$jname = $jname."通知推送";
				// 		}
				// 	}
					echo "<tr>".
							"<td>组织代码</td>"
							."<td>".$_POST ["code"]."</td>"
						 ."</tr>"
						 ."<tr>".
							"<td>实体名称</td>"
							."<td>".$key."</td>"
						 ."</tr>"
						 ."<tr>"
							."<td>Token</td>"
							."<td>".$topic["key"]."</td>"
						 ."</tr>"
						 ."<tr>"
							."<td>交换方式</td>"
							."<td>".$is_notify."</td>"
						 ."</tr>";
					if($topic["is_notify"] == 0 || $topic["is_notify"] == 2){
						echo "<tr>"
								."<td>模式匹配</td>"
								."<td>".$regx."</td>"
							 ."</tr>"
							 ."<tr>"
								."<td>匹配参数</td>"
								."<td>".$topic['regx_param']."</td>"
							 ."</tr>"
							 ."<tr>"
								."<td>模板渲染</td>"
								."<td>".$is_replace."</td>"
							 ."</tr>"
							 ."<tr>"
								."<td>模板名称匹配</td>"
								."<td>".$topic["template_regx"]."</td>"
							 ."</tr>";
					}
					if($topic["is_notify"] == 1 || $topic["is_notify"] == 2){
						echo "<tr>"
								."<td>定向通知匹配</td>"
								."<td>".$topic["notify_regx"]."</td>"
							 ."</tr>";
					}
					echo "<tr>"
							."<td>持久化</td>"
							."<td>".$store."</td>"
						 ."</tr>";
					if($topic["is_store"] == 1){
						echo "<tr>"
								."<td>持久化类型</td>"
								."<td>".$smethod."</td>"
							 ."</tr>"
							 ."<tr>"
								."<td>持久化".$smethod."</td>"
								."<td>".$topic["store_num"]."</td>"
							 ."</tr>"
							 ."<tr>"
								."<td>发送条数</td>"
								."<td>".$topic["send_num"]."</td>"
							 ."</tr>"
							 ."<tr>"
								."<td>订阅条数</td>"
								."<td>".$topic["sub_num"]."</td>"
							 ."</tr>";
					}
					echo "<tr>"
							."<td>状态</td>"
							."<td>".$stat."</td>"
						 ."</tr>"
				 		 ."<tr>"
				 			."<td>状态通知</td>"
				 			."<td>".$broad."</td>"
				 		 ."</tr>";
				}
				
				?>
				</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>




