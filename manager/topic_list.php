		<?php require 'validateSession.php';?>
		<div>
				<div style="border-bottom: 1px solid #eee;margin-bottom: 20px;">
					<ol class="breadcrumb" style="margin-bottom: 9px;">
					  <li><a href="javascript:loadPage('business_list.php');">应用列表</a></li>
					  <li class="active">实体管理</li>
					</ol>
				</div>
				<div class="row text-right">
					<div class="col-md-10 col-md-offset-2">
                		<div class="btn-group">
                			<input type="button" id="create_topic" class="btn btn-primary btn-right" value="创建实体"/>
                		</div>
                	</div>
				</div>
				<input type="hidden" id="busName" value="<?php echo $_GET["businessName"] ?>">
				<input type="hidden" id="b_code" value="<?php echo $_GET ["code"] ?>">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>序号</th>
							<th>组织代码</th>
							<th>实体名称</th>
							<th>Token</th>
							<th>交换方式</th>
							<th>发送量</th>
							<th>订阅量</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						require_once ("topic.php");
						$topic = new topic();
						$cursor = $topic->queryTopic($_GET ["code"]);
						if(!empty($cursor['entity'])){
							$count = count($cursor['entity']);
							$seq = 1;
							if($count >0){
								foreach ($cursor['entity'] as $key=>$topic) {
									if(strpos($key,"*") === false){
										$st_value = "失效";
										$butt_value = "启用";
										if($topic["status"] != 0){
											$st_value = "有效";
											$butt_value = "禁用";
										}
										if($topic["is_notify"] == 0){
											$is_notify = "在线推送";
										}else if($topic["is_notify"] == 1){
											$is_notify = "定向通知";
										}else if($topic["is_notify"] == 2){
											$is_notify = "全部";
										}
										echo "<tr>"
												."<td>".$seq++."</td>"
												."<td>".$_GET ["code"]."</td>"
												."<td>".$key."</td>"
												."<td>".$topic["key"]."</td>"
												."<td>".$is_notify."</td>"
												."<td>".$topic["send_num"]."</td>"
												."<td>".$topic["sub_num"]."</td>"
												."<td>".$st_value."</td>"
												."<td>"
							?>
											<div class="btn-group">
											  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="padding: 0;">
											    <img alt="" src="img/operation.png" height="20"><span class="caret"></span>
											  </button>
											  <ul class="dropdown-menu pull-right" role="menu">
											  	<li><a href="javascript:show_modify('<?php echo $key ?>');">编辑</a></li>
											    <li><a href="javascript:detail('<?php echo $key ?>');">详情</a></li>
											    <li class="divider"></li>
											  </ul>
											</div>
										
										<?php	"</td>"
											."</tr>";
									}
								}
							}else{
								echo "<tr><td colspan=9 style=\"text-align:center;\">无相关记录</td></tr>";
							}
						}else{
								echo "<tr><td colspan=9 style=\"text-align:center;\">无相关记录</td></tr>";
						}
					?>	
					</tbody>
				</table>
			</div>
			
			<div class="modal fade" id="topic_modal">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 id="topic_head" class="modal-title" style="font-weight: bold;">创建实体</h4>
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
							<label for="businessName" class="col-sm-3 control-label"><span
								style="color: red">*</span>组织名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="businessName" disabled="disabled"
									name="businessName" value="<?php echo $_GET ["businessName"];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="code" class="col-sm-3 control-label"><span
								style="color: red">*</span>组织代码</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="code" disabled="disabled"
									name="code" value="<?php echo $_GET ["code"];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label"><span
								style="color: red">*</span>实体名称</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="name" name="name"
									placeholder="英文或数字表示，首字母为英文">
							</div>
						</div>
						<div class="form-group">
							<label for="is_notify" class="col-sm-3 control-label"><span
								style="color: red">*</span>交换方式</label>
							<div class="col-sm-9">
								<select class="form-control" id="is_notify"
									name="is_notify" >
									<option value="0" selected="selected">在线推送</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="is_regx_div">
							<label for="is_regx" class="col-sm-3 control-label"><span
								style="color: red">*</span>模式匹配</label>
							<div class="col-sm-9">
								<select class="form-control" id="is_regx" name="is_regx">
									<option value="0" selected="selected">否</option>
								</select>
							</div>
						</div>
						<div id="regx_param_div" class="form-group" style="display: none;">
							<label for="regx_param" class="col-sm-3 control-label"><span
								style="color: red">*</span>匹配格式</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="regx_param" name="regx_param"
									placeholder="格式为：${消息json中的参数}；eg：${uid}">
							</div>
						</div>
						<div class="form-group" id="is_replace_div">
							<label for="is_replace" class="col-sm-3 control-label"><span
								style="color: red">*</span>推送模板</label>
							<div class="col-sm-9">
								<select class="form-control" id="is_replace"
									name="is_replace" >
									<option value="0" selected="selected">否</option>
								</select>
							</div>
						</div>
						<div id="template_regx_div" class="form-group" style="display: none;">
							<label for="template_regx" class="col-sm-3 control-label"><span
								style="color: red">*</span>推送模板匹配</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="template_regx"
									name="template_regx" placeholder="格式：${json消息中的参数}-${json消息中的参数}；多个模板用分号隔开,匹配一个停止" >
							</div>
						</div>
						<div id="notify_regx_div" class="form-group" style="display: none;">
							<label for="notify_regx" class="col-sm-3 control-label"><span
								style="color: red">*</span>定向通知匹配</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="notify_regx"
									name="notify_regx" placeholder="格式：${json消息中的参数}-${json消息中的参数}；多个模板用分号隔开,匹配一个停止" >
							</div>
						</div>
						<div class="form-group">
							<label for="is_store" class="col-sm-3 control-label"><span
								style="color: red">*</span>持久化</label>
							<div class="col-sm-9">
								<select class="form-control" id="is_store" name="is_store" onchange="isStore(this);">
									<option value="0" selected="selected">否</option>
									<option value="1">是</option>
								</select>
							</div>
						</div>
						<div id="store_method_div" class="form-group" style="display: none;">
							<label for="store_method" class="col-sm-3 control-label">持久化方式</label>
							<div class="col-sm-9">
								<select class="form-control" id="store_method"
									name="store_method" onchange="storeMethod(this);">
									<option value="1" selected="selected">按条数</option>
									<option value="2">按天</option>
								</select>
							</div>
						</div>
						<div id="store_num_div" class="form-group" style="display: none;">
							<label id="store_num_lab" for="store_num" class="col-sm-3 control-label">持久化条数</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="store_num"
									name="store_num" placeholder="条数" value="20">
							</div>
						</div>
						<div class="form-group">
							<label for="status" class="col-sm-3 control-label"><span
								style="color: red">*</span>状态</label>
							<div class="col-sm-9">
								<select class="form-control" id="status" name="status" >
									<option value="1" selected="selected">有效</option>
									<option value="0">无效</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="broad_status" class="col-sm-3 control-label"><span
								style="color: red">*</span>状态广播</label>
							<div class="col-sm-9">
								<select class="form-control" id="broad_status" name="broad_status" >
									<option value="0" selected="selected">否</option>							
								</select>
							</div>
						</div>
					</form>
			      </div>
			      <div class="modal-footer">
			        <button id="b_close" type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        <input type="button" id="submit1" class="btn btn-primary" value="创建"/>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			
			
			
			
			<div class="modal fade" id="topic_detail">
			  <div class="modal-dialog">
			    <div class="modal-content" id="detail_content">
			      
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			
			<script>
			function alert_close(){
				$("#alert_div_content").hide();
			}
			$("#create_topic").click(function(){
				$("#topic_head").text("创建实体");
				$("#name").val("");
				$("#is_regx").val("0");
				$("#regx_param").val("");
				$("#is_store").val("0");
				$("#store_method").val("1");
				$("#store_num").val("20");
				$("#is_replace").val("0");
				$("#template_regx").val("");
				$("#is_notify").val("0");
				$("#is_replace_div").show();
				$("#is_regx_div").show();
				$("#notify_regx").val("");
				$("#regx_param_div").hide();
				$("#store_method_div").hide();
				$("#store_num_div").hide();
				$("#template_regx_div").hide();
				$("#notify_regx_div").hide();
				$("#status").val("1");
				$("#alert_div_content").hide();
				$("#method").val("save");
				$("#name").removeAttr("disabled");
				$("#is_regx").removeAttr("disabled");
				$("#topic_modal").modal('show');
			});
			$("#name").blur(function(){
				var regx=$("#is_regx").val();
				var name = $(this).val();
				//name = name.split("-")[0];
				//if(regx == '1'){
				//	name=  name+"-" ;
				//}
				$("#name").val(name);
			});
			$("#is_notify").change(function(){ 
				var value=$(this).children('option:selected').val();
				if(value == '1'){
					$("#notify_regx_div").show();
					$("#is_regx_div").hide();
					$("#is_regx").val("0");
					$("#regx_param_div").hide();
					$("#regx_param").val("");
					$("#is_replace_div").hide();
					$("#is_replace").val("0");
					$("#template_regx_div").hide();
					$("#template_regx").val("");
				}else if(value == '0'){
					$("#notify_regx_div").hide();
					$("#notify_regx").val("");
					$("#is_regx_div").show();
					$("#is_replace_div").show();
				}else{
					$("#notify_regx_div").show();
					$("#is_regx_div").show();
					$("#is_replace_div").show();
				}
			});
			$("#is_replace").change(function(){ 
				var value=$(this).children('option:selected').val();
				if(value == '1'){
					$("#template_regx_div").show();
				}else{
					$("#template_regx_div").hide();
				}
			});
			$("#is_regx").change(function(){ 
				var regx=$(this).children('option:selected').val();
				var name = $("#name").val().trim();
				name = name.split("-")[0];
				if(regx == '1'){
					name=  name+"-" ;
					$("#regx_param_div").show();
				}else{
					$("#regx_param_div").hide();
				}
				$("#name").val(name);
			});

			$("#submit1").click(function(){
// 	            var join_method =[];    
// 	            $('input[name="join_method[]"]:checked').each(function(){
// 	            	join_method.push($(this).val());  
// 	            });
				
	        	var _data = $("#form1").serialize();
	        	var code = $("#b_code").val();
				var busName = $("#busName").val();
				var tName = $("#name").val().trim().trim();		
				var method = $("#method").val().trim(); 
				var store_method = $("#store_method").val().trim();
				var store_num = $("#store_num").val().trim();
				var is_store = $("#is_store").val().trim();
				var is_regx = $("#is_regx").val().trim();
				var is_replace = $("#is_replace").val().trim();
				var is_notify = $("#is_notify").val().trim();
				var template_regx = $("#template_regx").val().trim();
				var notify_regx = $("#notify_regx").val().trim(); 
				var regx_param = $("#regx_param").val().trim();
				var nameReg = /^[a-zA-Z]+[a-zA-Z0-9]*[-]?$/; 
				var numReg = /^[0-9]*$/;
				var regxReg = /^\${[a-zA-Z0-9_]+}$/;
// 				var tem_regx = /^(((\${[a-zA-Z0-9_]+})|([a-zA-Z0-9_]+))+((-\${([a-zA-Z0-9_])+})*|(-[a-zA-Z0-9_]*))(;)?)+$/;
				var tem_regx = /^((\${[a-zA-Z0-9_]+}(-\${[a-zA-Z0-9_]+})*)|[a-zA-Z0-9_]+)$/;
				var result_info="";
				var result = true;
				if(tName.length==0){
					$('#name').focus();
					$("#alert_div_content").show();
					$("#alert_info").text("实体名称不能为空!");
					return;
				}
				if(!nameReg.test(tName)){
					$('#name').focus();
					$("#alert_div_content").show();
					$("#alert_info").text("实体名称必须为英文或数字表示，首字母为英文!");
					return;
// 				}else if(join_method.length==0){
// 					result_info = "请选择使用场景!";
// 					result = false;
// 					$('#join_method').focus();
				}
				if(is_notify == "0" || is_notify == "2"){
					if(is_regx == "1" && !regxReg.test(regx_param)){
						$('#regx_param').focus();
						$("#alert_div_content").show();
						$("#alert_info").text("匹配格式必须为：${消息json中的参数}；eg：${uid}");
						return;
					}else if(is_replace == "1" && template_regx.length==0){
						$('#template_regx').focus();
						$("#alert_div_content").show();
						$("#alert_info").text("模板名称匹配不能为空");
						return;
					}else if(is_replace == "1"){
						var regxs = template_regx.split(";");
						for(var i in regxs){
							if(!tem_regx.test(regxs[i])){
								result_info = "推送模板匹配格式有误";
								result = false;
								$('#template_regx').focus();
								$("#alert_div_content").show();
								$("#alert_info").text(result_info);
								return;
							}
						}
					}
				}
				if(is_notify == "1" || is_notify == "2"){
					if(notify_regx.length==0){
						$('#notify_regx').focus();
						$("#alert_div_content").show();
						$("#alert_info").text("定向通知匹配不能为空");
						return;
					}else{
						var regxs = notify_regx.split(";");
						for(var i in regxs){
							if(!tem_regx.test(regxs[i])){
								$('#notify_regx').focus();
								$("#alert_div_content").show();
								$("#alert_info").text("定向通知匹配格式有误");
								return;
							}
						}
					}
				}
				if(is_store == "1"){
					if(store_num.length == 0){
						if(store_method == "1"){
							result_info = "持久化条数不能为空!";
						}else{
							result_info = "持久化条天数不能为空!";
						}
						$("#alert_div_content").show();
						$("#alert_info").text(result_info);
						$('#store_num').focus();
						return;
					}else if(!numReg.test(store_num)){
						if(store_method == "1"){
							result_info = "持久化条数必须为数字!";
						}else{
							result_info = "持久化条必须为数字!";
						}
						$("#alert_div_content").show();
						$("#alert_info").text(result_info);
						$('#store_num').focus();
						return;
					}
				}
				
				if(method == "modify"){
					_data += "&name="+tName+"&is_regx="+is_regx;
				}
				$.ajax({
		        	  type: "post",  
		  			  url: "topic.php",  
		  			  data: _data+"&code="+code,
		  			  cache: false,  
		  		  	  success: function(msg) { 
			  		  	  if(msg == 0){
			  		  		alert("保存成功");
			  		  		loadPage("topic_list.php?code="+code+"&businessName="+encodeURIComponent(busName));
			  		  	  }else if(msg == 1){
				  		  	  if(method=="save"){
				  		  		alert("实体名称已存在");
				  		  	  }else if(method=="modify"){
				  		  		alert("实体名称不存在");
				  		  	  }
			  		  	  }else{
			  		  		alert("保存失败");
			  		  	  }
		  			  }, 
		  			  error: function(){
		  				alert("保存失败");
		        	  }
		        	})
	        });


			function isStore(_this){
				var store = $(_this).val();
				if(store == "1"){
					$("#store_method_div").show();
					$("#store_num_div").show();
				}else{
					$("#store_method_div").hide();
					$("#store_num_div").hide();
				}
			};
			
			function storeMethod(_this){
				var method = $(_this).val();
				if(method == "1"){
					$("#store_num_lab").text("持久化条数");
					$("#store_num").attr("placeholder","条数");
					$("#store_num").val("20");
				}else{
					$("#store_num_lab").text("持久化天数");
					$("#store_num").attr("placeholder","天数");
					$("#store_num").val("1");
				}
			};
	       
			function doEnOrDis(topic_name,stat){
				 var code = $("#b_code").val();
				 var busName = $("#busName").val();
				 
// 				 var topic_name = $(_this).parent().parent().find("td").eq(2).text();
// 				 var stat = $(_this).attr("attr");
				 if(stat == 1){
					 stat = 0;
				 }else{
					 stat = 1;
				 }
				 $.ajax({
					 type: "post",  
		 			  url: "topic.php",  
		 			  data: "code="+code+"&name="+encodeURIComponent(topic_name)+"&stat="+stat+"&method=updateStat",
		 			  cache: false,  
		 		  	  success: function(msg) {
		 		  		if(msg == 0){
		 		  			alert("操作成功");
		 		  			loadPage("topic_list.php?code="+code+"&businessName="+encodeURIComponent(busName));
			  		  	  }else if(msg == 1){
			  		  		alert("记录不存在");
			  		  		loadPage("topic_list.php?code="+code+"&businessName="+encodeURIComponent(busName));
			  		  	  }else{
			  		  		alert("操作失败");
			  		  	  }
		 			  },
		 			  error: function(){
		 				 alert("操作失败")
		       	  	}
				 });
			 };
			 function detail(topic_name){
				var code = $("#b_code").val();
// 				var topic_name = $(_this).parent().parent().find("td").eq(2).text();
				$.ajax({
					type: "post",  
		  			  url: "topic_detail.php",  
		  			  data: "code="+code+"&name="+encodeURIComponent(topic_name),
		  			  cache: false,  
		  		  	  success: function(msg) {  
			  		  	$("#detail_content").html(msg);
			  		  	$("#topic_detail").modal('show');
		  			  }, 
		  			  error: function(){
		        	  }
				})
			 };
			 function dataSource(topic_name){
				var businessName = $("#busName").val();
				var code = $("#b_code").val();
// 				var topic_name = $(_this).parent().parent().find("td").eq(2).text();
				loadPage("dataSource_list.php?code="+code+"&topicName="+topic_name+"&businessName="+encodeURIComponent(businessName));
			 }
			 function template(topic_name){
				var businessName = $("#busName").val();
				var code = $("#b_code").val();
// 				var topic_name = $(_this).parent().parent().find("td").eq(2).text();
				loadPage("template_list.php?code="+code+"&topicName="+topic_name+"&businessName="+encodeURIComponent(businessName));
			 }
			 function notify(topic_name){
				var businessName = $("#busName").val();
				var code = $("#b_code").val();
// 				var topic_name = $(_this).parent().parent().find("td").eq(2).text();
				loadPage("notify_list.php?code="+code+"&topicName="+topic_name+"&businessName="+encodeURIComponent(businessName));
			 }
			function show_modify(topic_name){
				var businessName = $("#busName").val();
				var code = $("#b_code").val();
				$.ajax({
					type: "post",  
		  			  url: "topic.php",
		  			  data: "code="+code+"&name="+topic_name+"&method=show_modify",
		  			  cache: false,  
		  		  	  success: function(msg) { 
			  		  	  if(msg == ""){
								alert("系统错误");
			  		  	  }else{
		  		  			var dataObj=eval("("+msg+")"); 
		  		  			$("#topic_head").text("编辑实体");
			  		  		$("#name").val(dataObj.name);
							$("#is_regx").val(dataObj.is_regx);
							$("#regx_param").val(dataObj.regx_param);
							$("#is_store").val(dataObj.is_store);
							$("#store_method").val(dataObj.store_method);
							$("#store_num").val(dataObj.store_num);
							$("#is_replace").val(dataObj.is_replace);
							$("#template_regx").val(dataObj.template_regx);
							$("#is_notify").val(dataObj.is_notify);
							$("#notify_regx").val(dataObj.notify_regx);
							$("#status").val(dataObj.status);
							$("#broad_status").val(dataObj.broad_status);
							if(dataObj.is_regx == 0){
								$("#regx_param_div").hide();
							}else{
								$("#regx_param_div").show();
							}
							if(dataObj.is_store == 1){
								$("#store_method_div").show();
								$("#store_num_div").show();
							}else{
								$("#store_method_div").hide();
								$("#store_num_div").hide();
							}
							if(dataObj.is_replace == 1){
								$("#template_regx_div").show();
							}else{
								$("#template_regx_div").hide();
							}
							if(dataObj.is_notify == 0){
								$("#is_regx_div").show();
								$("#is_replace_div").show();
								$("#notify_regx_div").hide();
							}else if(dataObj.is_notify == 1){
								$("#is_regx_div").hide();
								$("#is_replace_div").hide();
								$("#notify_regx_div").show();
							}else if(dataObj.is_notify == 2){
								$("#notify_regx_div").show();
								$("#is_regx_div").show();
								$("#is_replace_div").show();
							}
							if(dataObj.store_method == 1){
								$("#store_num_lab").text("持久化条数");
								$("#store_num").attr("placeholder","条数");
							}else{
								$("#store_num_lab").text("持久化天数");
								$("#store_num").attr("placeholder","天数");
							}
							$("#alert_div_content").hide();
							$("#submit1").val("编辑");
							$("#method").val("modify");
							$("#name").attr("disabled","disabled");
							$("#is_regx").attr("disabled","disabled");
							$("#topic_modal").modal('show');
			  		  	  }
		  			  }, 
		  			  error: function(){
		  				alert("系统错误");
		        	  }
				});
			}
			</script>