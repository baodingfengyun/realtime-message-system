	  <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="font-weight: bold;">模板详情</h4>
      </div>
      <div class="modal-body" >
		<table class="table table-striped table-hover">
		<?php
		require 'validateSession.php';
		require_once ("template.php");
		$template= new template();
		$cursor = $template->detail($_POST ["code"],$_POST ["topicName"],$_POST ["name"]);
		
		foreach ($cursor['template'] as $key=>$template){
			echo "<tr>".
					"<td>应用代码</td>"
					."<td>".$_POST ["code"]."</td>"
				 ."</tr>"
				 ."<tr>".
					"<td>实体名称</td>"
					."<td>".$_POST ["topicName"]."</td>"
				 ."</tr>"
				 ."<tr>"
					."<td>模版名称</td>"
					."<td>".$key."</td>"
				 ."</tr>"
				 ."<tr>"
					."<td>模板内容</td>"
					."<td><textarea style='width:100%;background-color:#FFF;' rows='5' disabled='disabled'>".$template["content"]."</textarea></td>"
				 ."</tr>"
				 ."<tr>"
					."<td>模板描述</td>"
					."<td>".$template['desc']."</td>"
				 ."</tr>"
				 ."<tr>"
					."<td>创建时间</td>"
					."<td>".$template['create_time']."</td>"
				 ."</tr>";
		}
		?>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>


