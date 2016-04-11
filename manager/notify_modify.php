				  <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" style="font-weight: bold;">编辑通知</h4>
			      </div>
			      <div class="modal-body">
			      	<?php
						require 'validateSession.php';
						require_once ("notify.php");
						$notify= new notify();
						$cursor = $notify->detail($_POST ["code"],$_POST ["topicName"],$_POST ["name"]);
						
						foreach ($cursor['notify'] as $key=>$notify){
							
							
					?>
			        <form id="md_form" class="form-horizontal" role="form">
			        	<input id="method" name="method" type="hidden" value="modify">
			        	<div id="md_alert_div" class='alert alert-danger alert-dismissible col-sm-9 col-sm-offset-3' role='alert' style='margin-bottom: 5px;display: none'>
							<button type='button' class='close' onclick="md_alert_close()">
							<span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span>
							</button>
							<strong id="md_alert_info"></strong>
						</div>
						<div class="form-group">
							<label for="code" class="col-sm-3 control-label"><span
								style="color: red">*</span>应用代码</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" disabled="disabled"
									name="code" value="<?php echo $_POST ["code"];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="topicName" class="col-sm-3 control-label"><span
								style="color: red">*</span>实体名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="topicName" disabled="disabled"
									value="<?php echo $_POST ["topicName"];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label"><span
								style="color: red">*</span>通知名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="md_name" name="name" disabled="disabled"
									placeholder="名称必须为数字、字母、_或者-" value="<?php echo $key ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-3 control-label"><span
								style="color: red">*</span>通知内容</label>
							<div class="col-sm-9">
								<textarea id="md_content" name="content"  class="form-control" rows="5" 
								placeholder="通知内容"><?php echo $notify["content"]?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="desc" class="col-sm-3 control-label">描述</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="md_desc" name="desc" 
									placeholder="描述" value="<?php echo $notify['desc']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="desc" class="col-sm-3 control-label"><span
								style="color: red">*</span>状态</label>
							<div class="col-sm-9">
								<select class="form-control" id="status" name="status" >
									<option value="1" <?php if($notify['status']==1) { ?>selected="selected" <?php } ?>>有效</option>
									<option value="0" <?php if($notify['status']==0) { ?>selected="selected" <?php } ?>>无效</option>
								</select>
							</div>
						</div>
					</form>
					<?php 
					}
					?>
			      </div>
			      <div class="modal-footer">
			        <button id="d_close" type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        <input type="button" id="modify" class="btn btn-primary" value="修改"/>
			      </div>
			<script type="text/javascript">
			function md_alert_close(){
				$("#md_alert_div").hide();
			}
			$("#modify").click(function(){
				var param = $("#md_form").serialize();
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var businessName = $("#businessName").val(); 
				var name = $("#md_name").val().trim(); 
				var content = $("#md_content").val();
				var nameReg = /^[a-zA-Z0-9_-]*$/; 
				var result_info="";
				var result = true;
				if(name.length==0){
					result_info = "通知名称不能为空!";
					result = false;
					$('#md_name').focus();
				}else if(!nameReg.test(name)){
					result_info = "名称必须为数字、字母、_或者-";
					result = false;
					$('#md_name').focus();
				}else if(content.length==0){
					result_info = "通知内容不能为空!";
					result = false;
					$('#md_content').focus();
				}else if(content.length > 0){
					try{
						eval("("+content+")");
					}catch(e){
						result_info = "通知内容必须为Json格式!";
						result = false;
						$('#md_content').focus();
					}
				}
				if(!result){
					$("#md_alert_div").show();
					$("#md_alert_info").text(result_info);
					return;
				}
				$.ajax({
					url:"notify.php",  
					data:param+"&code="+code+"&topicName="+topicName+"&name="+name,
					type:"post", 
					cache:false,  
		  		  	  success: function(msg) { 
			  		  	  if(msg == 0){
			  		  		alert("保存成功");
			  		  		loadPage("notify_list.php?code="+code+"&topicName="+topicName+"&businessName="+encodeURIComponent(businessName));
			  		  	  }else if(msg == 1){
				  		  	alert("记录不存在");
			  		  	  }else{
			  		  		alert("保存失败");
			  		  	  }
		  			  }, 
		  			  error: function(){
		  				alert("保存失败");
		        	  }
				});

			});
			</script>