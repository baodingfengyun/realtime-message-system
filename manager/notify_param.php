				  <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" style="font-weight: bold;">通知参数设置</h4>
			      </div>
			      <div class="modal-body">
			      	<form id="form2" class="form-horizontal" role="form">
			      		<input type="hidden" id="param_code" value="<?php echo $_POST ['code']?>">
				       	<input type="hidden" id="param_topicName" value="<?php echo $_POST ['topicName']?>">
				       	<input type="hidden" id="param_name" value="<?php echo $_POST ['name']?>">
			      	<table class="table table-bordered">
			      		<thead>
			      			<tr>
				       		<th width="20%">参数名</th>
				       		<th width="80%">设置</th>
				       		</tr>
				       	</thead>
				       	<tbody>
					      	<?php
								require 'validateSession.php';
								require_once ("notify.php");
								$notify= new notify();
								$cursor = $notify->detail($_POST ["code"],$_POST ["topicName"],$_POST ["name"]);
								if(!empty($cursor['notify'][$_POST ["name"]]["param"])){
									foreach ($cursor['notify'][$_POST ["name"]]["param"] as $key=>$attr){
							?>
						       		<tr>
						       		<td rowspan="2"><?php echo $key ?></td>
						       		<td><label for="source" class="col-sm-3 control-label">数据源 ：</label>
						       			<select class="col-sm-8" name="<?php echo $key?>_source">
						       				<option value="0" <?php if($attr['source'] == "0")echo "selected=\"selected\"";?> >Json消息</option>
						       				<!--<option value="1" <?php if($attr['source'] == "1")echo "selected=\"selected\"";?>>RT用户信息表</option>
						       				<option value="2" <?php if($attr['source'] == "2")echo "selected=\"selected\"";?>>RT微信用户表</option>
						       				<option value="3" <?php if($attr['source'] == "3")echo "selected=\"selected\"";?>>RT彩种中英文关系</option>-->
						       				<option value="4" <?php if($attr['source'] == "4")echo "selected=\"selected\"";?>>系统时间</option>
						       			</select>
						       		</td>
						       		</tr>
						       		<tr>
						       		<td><label for="field" class="col-sm-3 control-label">返回字段：</label>
						       			<input class="col-sm-8" type="text" name="<?php echo $key?>_return" value="<?php echo $attr['return_field'] ?>"
						       				placeholder="根据查询条件，指定返回字段"/></td>
						       		</tr>
						       		<!-- <td><label for="field" class="col-sm-3 control-label">查询条件：</label>
						       			<input class="col-sm-8" type="text" name="<?php echo $key?>_query" value="<?php echo $attr['query_field'] ?>"
						       			placeholder="根据json消息属性，指定查询条件"/></td>  -->
						       		</tr>
							<?php 
									}
								}else{
							?>
								<tr><td colspan=2 style="text-align:center;">无相关参数</td></tr>
							<?php 
								}						
							?>
							
						</tbody>
					</table>
					</form>
			      </div>
			      <div class="modal-footer">
			        <button id="d_close" type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        <input type="button" id="save_params" class="btn btn-primary" value="保存"/>
			      </div>
			<script type="text/javascript">
				$("#save_params").click(function(){
					var param = $("#form2").serialize();
					var code = $("#param_code").val();
					var topicName = $("#param_topicName").val();
					var name = $("#param_name").val();
					$.ajax({
						url:"notify.php",  
						data:param+"&code="+code+"&topicName="+topicName+"&name="+name+"&method=save_params",
						type:"post", 
						cache:false,  
		  		  	    success: function(msg) { 
		  		  	    if(msg == 0){
			  		  		alert("保存成功");
			  		  	    $("#notify_detail").modal('hide');
			  		  	  }else if(msg == 1){
				  		  	alert("记录不存在");
			  		  	  }else{
			  		  		alert("保存失败");
			  		  	  }
		  		  	    }

					});
				})
			</script>