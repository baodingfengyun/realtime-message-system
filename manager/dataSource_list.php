		<?php require 'validateSession.php';?>
		<div>
				<div style="border-bottom: 1px solid #eee;margin-bottom: 20px;">
					<ol class="breadcrumb" style="margin-bottom: 9px;">
					  <li><a href="javascript:loadPage('business_list.php');">应用列表</a></li>
					  <li><a href="javascript:loadPage('topic_list.php?businessName='+$('#businessName').val()+'&code='+$('#b_code').val());">实体管理</a></li>
					  <li class="active">数据源配置</li>
					</ol>
				</div>
				<div class="row text-right">
					<div class="col-md-10 col-md-offset-2">
                		<div class="btn-group">
                			<input type="button" class="btn btn-primary btn-right" value="创建数据源" id="create"/>
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
							<th>数据源名称</th>
							<th>URI</th>
							<th>类型</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						require_once ("dataSource.php");
						$dataSource = new dataSource();
						$cursor = $dataSource->query($_GET ["code"],$_GET ["topicName"]);
						if(!empty($cursor['queue'])){
							$count = count($cursor['queue']);
							$seq = 1;
							if($count >0){
								foreach ($cursor['queue'] as $key=>$dataSource) {
										$category = $dataSource["category"];
										$category_value="";
										if ($category==1){
											$category_value = "zeromq";
										}elseif($category==2){
											$category_value = "http";
										}
										$status = $dataSource['status']==0? "无效" : "有效";
										echo "<tr>"
												."<td>".$seq++."</td>"
												."<td>".$_GET ["code"]."</td>"
												."<td>".$_GET ["topicName"]."</td>"
												."<td>".$key."</td>"
												."<td>".$dataSource["address"]."</td>"
												."<td>".$category_value."</td>"
												."<td>".$status."</td>"
												."<td>"
													."<button attr=".$dataSource['status']." type=\"button\" class=\"btn btn-default btn-xs\" style=\"margin-left:5px;\" onclick=\"show_modify(this)\">编辑</button>"
												."</td>"
											."</tr>";
								}
							}else{
								echo "<tr><td colspan=8 style=\"text-align:center;\">无相关记录</td></tr>";
							}
						}else{
								echo "<tr><td colspan=8 style=\"text-align:center;\">无相关记录</td></tr>";
						}
						
					?>
					</tbody>
				</table>
		</div>
		
		<div class="modal fade" id="datasource_modal">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" style="font-weight: bold;" id="ds_title">创建数据源</h4>
			      </div>
			      <div class="modal-body">
			        <form id="form1" class="form-horizontal" role="form">
			        	<input type="hidden" id="method" name="method" value="save"/>
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
								style="color: red">*</span>数据源名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="name" name="name" 
									placeholder="数据源名称必须为数字、字母、_或者-">
							</div>
						</div>
						<div class="form-group">
							<label for="category" class="col-sm-3 control-label"><span
								style="color: red">*</span>数据源类型</label>
							<div class="col-sm-9">
								<select class="form-control" id="category" name="category">
									<option value="1" selected="selected">zeromq</option>
									<!-- <option value="2">jms</option> -->
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="address" class="col-sm-3 control-label"><span
								style="color: red">*</span>数据源URI</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="address" name="address" 
									placeholder="数据源URI">
							</div>
						</div>
						<div class="form-group">
							<label for="topic" class="col-sm-3 control-label"><span
								style="color: red">*</span>数据源主题</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="topic" name="topic" 
									placeholder="数据源主题">
							</div>
						</div>
						<div class="form-group">
							<label for="status" class="col-sm-3 control-label"><span
								style="color: red">*</span>数据源状态</label>
							<div class="col-sm-9">
								<select class="form-control" id="status" name="status">
									<option value="1" selected="selected">有效</option>
									<option value="0" >无效</option>
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
			
			<div class="modal fade" id="dataSource_modify">
			 <div class="modal-dialog" >
			    <div class="modal-content" id="detail_content">
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			
			<script type="text/javascript">
			function alert_close(){
				$("#alert_div_content").hide();
			}
			$("#create").click(function(){
				$("#ds_title").text("创建数据源");
				$("#name").val("");
				$("#address").val("");
				$("#topic").val("");
	  		  	$("#category").val("1");
	  		  	$("#status").val("1");
	  		    $("#name").removeAttr("disabled");
	  		  	$("#method").val("save");
	  		  	$("#submit1").val("创建");
	  		    $("#datasource_modal").modal('show');
			});
			$("#submit1").click(function(){
				var param = $("form").serialize();
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var businessName = $("#businessName").val(); 
				var name = $("#name").val().trim(); 
				var address = $("#address").val().trim(); 
				var method = $("#method").val().trim(); 
				var topic = $("#topic").val().trim(); 
				var nameReg = /^[a-zA-Z0-9_-]*$/; 
				var result_info="";
				var result = true;
				if(name.length==0){
					result_info = "数据源名称不能为空!";
					result = false;
					$('#name').focus();
				}else if(!nameReg.test(name)){
					result_info = "数据源名称必须为数字、字母、_或者-";
					result = false;
					$('#name').focus();
				}else if(address.length==0){
					result_info = "数据源地址不能为空!";
					result = false;
					$('#address').focus();
				}else if(topic.length==0){
					result_info = "数据源主题不能为空!";
					result = false;
					$('#topic').focus();
				}
				if(!result){
					$("#alert_div_content").show();
					$("#alert_info").text(result_info);
					return;
				}
				if(method == "modify"){
					param += "&name="+name;
				}
				$.ajax({
					url:"dataSource.php",  
					data:param+"&code="+code+"&topicName="+topicName+"&name",
					type:"post", 
					cache:false,  
		  		  	  success: function(msg) { 
			  		  	  if(msg == 0){
			  		  		alert("保存成功");
			  		  		loadPage("dataSource_list.php?code="+code+"&topicName="+topicName+"&businessName="+encodeURIComponent(businessName));
			  		  	  }else if(msg == 1){
				  		  	  if(method == "save"){
				  		  		alert("名称已存在");
				  		  	  }else if(method == "modify"){
				  		  		alert("记录不存在");
				  		  	  }
			  		  	  }else{
			  		  		alert("保存失败");
			  		  	  }
		  			  }, 
		  			  error: function(){
		  				alert("保存失败");
		        	  }
				});

			});
			function userOrNot(_this){
				var status = $(_this).attr("attr");
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var businessName = $("#businessName").val(); 
				var dsname = $(_this).parent().parent().find("td").eq(3).text();
				if(status == 1){
					status = 0;
				 }else{
					 status = 1;
				 }
				$.ajax({
					 type: "post",  
		 			  url: "dataSource.php",  
		 			  data: "code="+code+"&topicName="+encodeURIComponent(topicName)+"&stat="+status+"&method=updateStat"+"&name="+dsname,
		 			  cache: false,  
		 		  	  success: function(msg) {
		 		  		if(msg == 0){
		 		  			alert("操作成功");
		 		  			loadPage("dataSource_list.php?code="+code+"&topicName="+topicName+"&businessName="+encodeURIComponent(businessName));
			  		  	  }else if(msg == 1){
			  		  		alert("记录不存在");
			  		  	loadPage("dataSource_list.php?code="+code+"&topicName="+topicName+"&businessName="+encodeURIComponent(businessName));
			  		  	  }else{
			  		  		alert("操作失败");
			  		  	  }
		 			  },
		 			  error: function(){
		 				 alert("操作失败")
		       	  	}
				 });
			};

			function show_modify(_this){
				var code = $("#b_code").val();
				var topicName = $("#topicName").val(); 
				var dsname = $(_this).parent().parent().find("td").eq(3).text();
				$.ajax({
					type: "post",  
		  			  url: "dataSource.php",  
		  			  data: "code="+code+"&topicName="+encodeURIComponent(topicName)+"&stat="+status+"&method=query"+"&name="+dsname,
		  			  cache: false,  
		  		  	  success: function(msg) {
						var dataObj=eval("("+msg+")");
						$("#datasource_modal").modal('show');
						$("#ds_title").text("数据源编辑");
						$("#name").val(dataObj.name);
						$("#address").val(dataObj.address);
			  		  	$("#category").val(dataObj.category);
			  		    $("#topic").val(dataObj.topic);
			  		  	$("#status").val(dataObj.status);
			  		  	$("#method").val("modify");
			  			$("#submit1").val("修改");
			  			$("#name").attr("disabled","disabled");
		  			  }, 
		  			  error: function(){
		        	  }
				});
			};
			</script>