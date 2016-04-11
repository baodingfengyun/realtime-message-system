				  <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" style="font-weight: bold;">模板编辑</h4>
			      </div>
			      <div class="modal-body">
			      	<?php
						require 'validateSession.php';
						require_once ("template.php");
						$template= new template();
						$cursor = $template->detail($_POST ["code"],$_POST ["topicName"],$_POST ["name"]);
						
						foreach ($cursor['template'] as $key=>$template){
							
							
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
								style="color: red">*</span>模板名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="md_name" name="name" disabled="disabled"
									placeholder="名称必须为数字、字母、_或者-" value="<?php echo $key ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-3 control-label"><span
								style="color: red">*</span>模板内容</label>
							<div class="col-sm-9">
								<textarea id="md_content" name="content"  class="form-control" rows="5" 
								placeholder="模板内容"><?php echo $template["content"]?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="desc" class="col-sm-3 control-label">模板描述</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="md_desc" name="desc" 
									placeholder="模板描述" value="<?php echo $template['desc']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="desc" class="col-sm-3 control-label"><span
								style="color: red">*</span>状态</label>
							<div class="col-sm-9">
								<select class="form-control" id="status" name="status" >
									<option value="1" <?php if($template['status']==1) { ?>selected="selected" <?php } ?>>有效</option>
									<option value="0" <?php if($template['status']==0) { ?>selected="selected" <?php } ?>>无效</option>
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
					result_info = "模版名称不能为空!";
					result = false;
					$('#md_name').focus();
				}else if(!nameReg.test(name)){
					result_info = "名称必须为数字、字母、_或者-";
					result = false;
					$('#md_name').focus();
				}else if(content.length==0){
					result_info = "模版内容不能为空!";
					result = false;
					$('#md_content').focus();
				}
				if(!result){
					$("#md_alert_div").show();
					$("#md_alert_info").text(result_info);
					return;
				}
				$.ajax({
					url:"template.php",  
					data:param+"&code="+code+"&topicName="+topicName+"&name="+name,
					type:"post", 
					cache:false,  
		  		  	  success: function(msg) { 
			  		  	  if(msg == 0){
			  		  		alert("保存成功");
			  		  		loadPage("template_list.php?code="+code+"&topicName="+topicName+"&businessName="+encodeURIComponent(businessName));
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