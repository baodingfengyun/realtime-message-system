		<?php require 'validateSession.php';?>
		<div>
				<div style="border-bottom: 1px solid #eee;margin-bottom: 20px;">
					<ol class="breadcrumb" style="margin-bottom: 9px;">
					  <li><a href="javascript:loadPage('business_list.php');">应用列表</a></li>
					  <li><a href="javascript:loadPage('topic_list.php?businessName='+$('#businessName').val()+'&code='+$('#b_code').val());">实体管理</a></li>
					  <li class="active">定向通知模板</li>
					</ol>
				</div>
				<div class="row text-right">
					<div class="col-md-10 col-md-offset-2">
                		<div class="btn-group">
                			<input type="button" class="btn btn-primary btn-right" value="创建通知" data-toggle="modal" data-target="#notify_modal"/>
                		</div>
                	</div>
				</div>
				<input type="hidden" id="businessName" value="<?php echo $_GET["businessName"] ?>">
				<input type="hidden" id="b_code" value="<?php echo $_GET ["code"] ?>">
				<input type="hidden" id="topicName" value="<?php echo $_GET["topicName"] ?>">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>序号</th>
							<th>应用代码</th>
							<th>实体名称</th>
							<th>通知名称</th>
							<th>通知描述</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						require_once ("notify.php");
						$notify = new notify();
						$cursor = $notify->query($_GET ["code"],$_GET ["topicName"]);
						if(!empty($cursor['notify'])){
							$count = count($cursor['notify']);
							$seq = 1;
							if($count >0){
								foreach ($cursor['notify'] as $key=>$notify) {
									$status = $notify["status"] == 0 ?"无效":"有效";
										echo "<tr>"
												."<td>".$seq++."</td>"
												."<td>".$_GET ["code"]."</td>"
												."<td>".$_GET ["topicName"]."</td>"
												."<td>".$key."</td>"
												."<td>".$notify["desc"]."</td>"
                								."<td>".$status."</td>"
												."<td>"
													."<button type=\"button\" class=\"btn btn-default btn-xs\" style=\"margin-left:5px;\" onclick=\"show_modify(this)\">编辑</button>"
													."<button type=\"button\" class=\"btn btn-default btn-xs\" style=\"margin-left:5px;\" onclick=\"set_param(this)\">参数设置</button>"
													."<button type=\"button\" class=\"btn btn-default btn-xs\" style=\"margin-left:5px;\" onclick=\"detail(this)\">详情</button>"
												."</td>"
											."</tr>";
								}
							}else{
								echo "<tr><td colspan=7 style=\"text-align:center;\">无相关记录</td></tr>";
							}
						}else{
								echo "<tr><td colspan=7 style=\"text-align:center;\">无相关记录</td></tr>";
						}
						
					?>
					</tbody>
				</table>
			</div>
			<div class="modal fade" id="notify_modal">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" style="font-weight: bold;">创建通知</h4>
			      </div>
			      <div class="modal-body">
			        <form id="form1" class="form-horizontal" role="form">
			        	<input id="method" name="method" type="hidden" value="save">
			        	<div id="alert_div_content" class='alert alert-danger alert-dismissible col-sm-9 col-sm-offset-3' role='alert' style='margin-bottom: 5px;display: none'>
							<button type='button' class='close' onclick="alert_close()">
							<span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span>
							</button>
							<strong id="alert_info"></strong>
						</div>
						<div class="form-group">
							<label for="code" class="col-sm-3 control-label"><span
								style="color: red">*</span>应用代码</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="code" disabled="disabled"
									name="code" value="<?php echo $_GET ["code"];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="topicName" class="col-sm-3 control-label"><span
								style="color: red">*</span>实体名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="topicName" name="topicName" disabled="disabled"
									value="<?php echo $_GET ["topicName"];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label"><span
								style="color: red">*</span>通知名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="name" name="name" 
									placeholder="名称必须为数字、字母、_或者-">
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-3 control-label"><span
								style="color: red">*</span>通知内容</label>
							<div class="col-sm-9">
								<textarea id="content" name="content"  class="form-control" rows="5" 
								placeholder="通知内容为Json格式"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="desc" class="col-sm-3 control-label">描述</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="desc" name="desc" 
									placeholder="描述">
							</div>
						</div>
						<div class="form-group">
							<label for="desc" class="col-sm-3 control-label"><span
								style="color: red">*</span>状态</label>
							<div class="col-sm-9">
								<select class="form-control" id="status" name="status" >
									<option value="1" selected="selected">有效</option>
									<option value="0">无效</option>
								</select>
							</div>
						</div>
					</form>
			      </div>
			      <div class="modal-footer">
			        <button id="d_close" type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        <input type="button" id="submit1" class="btn btn-primary" value="创建"/>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			
			<div class="modal fade" id="notify_detail">
			 <div class="modal-dialog">
			    <div class="modal-content" id="detail_content">
			      
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			
			<script type="text/javascript">
			function alert_close(){
				$("#alert_div_content").hide();
			}
			$("#submit1").click(function(){
				var param = $("#form1").serialize();
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var businessName = $("#businessName").val(); 
				var name = $("#name").val().trim(); 
				var content = $("#content").val();
				var nameReg = /^[a-zA-Z0-9_-]*$/; 
				var result_info="";
				var result = true;
				if(name.length==0){
					result_info = "名称不能为空!";
					result = false;
					$('#name').focus();
				}else if(!nameReg.test(name)){
					result_info = "名称必须为数字、字母、_或者-";
					result = false;
					$('#name').focus();
				}else if(content.length==0){
					result_info = "通知内容不能为空!";
					result = false;
					$('#content').focus();
				}else if(content.length > 0){
					try{
						eval("("+content+")");
					}catch(e){
						result_info = "通知内容必须为Json格式!";
						result = false;
						$('#content').focus();
					}
				}
				if(!result){
					$("#alert_div_content").show();
					$("#alert_info").text(result_info);
					return;
				}
				$.ajax({
					url:"notify.php",  
					data:param+"&code="+code+"&topicName="+topicName,
					type:"post", 
					cache:false,  
		  		  	  success: function(msg) { 
			  		  	  if(msg == 0){
			  		  		alert("保存成功");
			  		  		loadPage("notify_list.php?code="+code+"&topicName="+topicName+"&businessName="+encodeURIComponent(businessName));
			  		  	  }else if(msg == 1){
				  		  	alert("名称已存在");
			  		  	  }else{
			  		  		alert("保存失败");
			  		  	  }
		  			  }, 
		  			  error: function(){
		  				alert("保存失败");
		        	  }
				});

			});

			function detail(_this){
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var notify_name = $(_this).parent().parent().find("td").eq(3).text();
				$.ajax({
					type: "post",  
		  			  url: "notify_detail.php",  
		  			  data: "code="+code+"&topicName="+encodeURIComponent(topicName)+"&name="+notify_name,
		  			  cache: false,  
		  		  	  success: function(msg) {  
			  		  	$("#detail_content").html(msg);
			  		  	$("#notify_detail").modal('show');
		  			  }, 
		  			  error: function(){
		        	  }
				});
			}

			function show_modify(_this){
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var notify_name = $(_this).parent().parent().find("td").eq(3).text();
				$.ajax({
					type: "post",  
		  			  url: "notify_modify.php",  
		  			  data: "code="+code+"&topicName="+encodeURIComponent(topicName)+"&name="+notify_name,
		  			  cache: false,  
		  		  	  success: function(msg) {  
			  		  	$("#detail_content").html(msg);
			  		  	$("#notify_detail").modal('show');
		  			  }, 
		  			  error: function(){
		        	  }
				});
			}
			
			function set_param(_this){
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var notify_name = $(_this).parent().parent().find("td").eq(3).text();
				$.ajax({
					type: "post",  
		  			  url: "notify_param.php",  
		  			  data: "code="+code+"&topicName="+encodeURIComponent(topicName)+"&name="+notify_name,
		  			  cache: false,  
		  		  	  success: function(msg) {  
			  		  	$("#detail_content").html(msg);
			  		  	$("#notify_detail").modal('show');
		  			  }, 
		  			  error: function(){
		        	  }
				});
			}
			</script>
