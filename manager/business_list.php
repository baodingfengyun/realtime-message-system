	<?php require 'validateSession.php';?>
			<div>
				<div style="border-bottom: 1px solid #eee;margin-bottom: 20px;">
					<ol class="breadcrumb" style="margin-bottom: 9px;">
					  <li class="active">应用列表</li>
					</ol>
				</div>
				<div class="row text-right">
					<div class="col-md-10 col-md-offset-2">
                		<div class="btn-group">
                			<input type="button" class="btn btn-primary btn-right" value="应用申请" data-toggle="modal" data-target="#apply_modal"/>
                		</div>
                	</div>
				</div>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>序号</th>
							<th>应用名称</th>
							<th>应用代码</th>
							<th>创建时间</th>
							<th>审核</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
				<?php 
				require_once ("business.php");
				$business = new business();
				$business->setValues();
				$cursor = $business->queryBusiness();
				$count = count($cursor);
				$seq = 1;				
				session_start();
				$session_user = $_SESSION["user"];
				$user=$session_user['email'];
				if($count >0){
					foreach ($cursor as $business) {
					    $checkStatus = $business["check_status"] == 0 ?"未审核":"已审核";
					    if($user=="elviszhang1@163.com"){
						    $curStatus=$business["check_status"];
							$code=$business["code"];
							if($curStatus==0){
								$checkStatus="<button type=\"button\" class=\"btn btn-default btn-xs check_btn\" curcode=\"$code\">审核</button>";
							}	
					    }									
						echo "<tr>"
								."<td>".$seq++."</td>"
								."<td>".$business["business"]."</td>"
								."<td>".$business["code"]."</td>"
								."<td>".$business["create_time"]."</td>"
								."<td>".$checkStatus."</td>"
								."<td>"
								 	."<button type=\"button\" class=\"btn btn-default btn-xs entity-manager\">"
								 		."实体管理"	
									."</button>"	
							    ."</td>"
							  ."</tr>";
					}
				}else{
					echo "<tr><td colspan=5 style=\"text-align:center;\">无相关记录</td></tr>";
				}
				?>		
					</tbody>
				</table>
			</div>
			
			<div class="modal fade" id="apply_modal">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h3 class="modal-title">应用申请</h3>
			      </div>
			      <div class="modal-body">
			        <form id="form1" class="form-horizontal">
			        <!--  
									<div class="form-group">
										<label for="applyer" class="col-sm-2 control-label"><span
											style="color: red">*</span>申请人</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="applyer"
												name="applyer" placeholder="申请人">
										</div>
									</div>
					-->
									<div id="alert_div_content" class='alert alert-danger alert-dismissible col-sm-10 col-sm-offset-2' role='alert' style='margin-bottom: 5px;display: none'>
										<button type='button' class='close' onclick="alert_close()">
										<span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span>
										</button>
										<strong id="alert_info"></strong>
									</div>
									<div class="form-group">
										<label for="business" class="col-sm-2 control-label"><span
											style="color: red">*</span>应用名称</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="businessName"
												name="businessName" placeholder="应用名称">
										</div>
									</div>
									<div class="form-group">
										<label for="topic" class="col-sm-2 control-label"><span
											style="color: red">*</span>应用代码 </label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="code" name="code"
												placeholder="应用名称全拼">
										</div>
									</div>
								</form>
			      </div>
			      <div class="modal-footer">
			        <button id="b_close" type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        <input id="apply"  type="button" class="btn btn-primary" value="申请"/>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<script>
			function alert_close(){
				$("#alert_div_content").hide();
			}
			$(document).ready(function(){
	        	$("#apply").click(function(){
	        		var bName = $("#businessName").val().trim();
					var codeReg = /^[a-zA-Z]*$/;  
					var code = $("#code").val().trim();
					var result = true;
					var result_info="";
					if(bName == ""){
						result_info="应用名称不能为空!";
						result = false;
						$('#businessName').focus();
					}
					else if(code == ""){
						result_info="应用代码不能为空!";
						result = false;
						$('#code').focus();
					}
					else if(!codeReg.test(code)){    
						result_info="应用代码必须为英文字母!"; 
			            result = false; 
			            $('#code').focus();
			        }
					if(!result){
						$("#alert_div_content").show();
						$("#alert_info").text(result_info);
						return;
					}
		        	var _data = $("form").serialize();
			       	$.ajax({
			       	  type: "post",  
			 			  url: "business.php",  
			 			  data: _data+"&method=save",
			 			  cache: false,  
			 		  	  success: function(msg) {
				 		  	if(msg == 0){
				 		  		$("#b_close").trigger("click");
				 		  		alert("保存成功");
				 		  		loadPage("business_list.php");
				 		  	}else if(msg == 1){
				 		  		alert("应用代码已存在");
				 		  	}else if(msg == 2){
				 		  		alert("保存失败");
				 		  	} 		  	
			 			  },
			 			  error: function(){
			 				$("#b_close").trigger("click");
			 				alert("系统异常");
			       	  	}
			       	})
			    });
		   
				$(".entity-manager").on("click",function(){
					var businessName = $(this).parent().parent().find("td").eq(1).text();
					var code = $(this).parent().parent().find("td").eq(2).text();
					loadPage("topic_list.php?code="+code+"&businessName="+encodeURIComponent(businessName));
				});
				
				$(".check_btn").on("click",function(){
				    var code=$(this).attr('curcode');
					$.ajax({
					 type: "post",  
		 			  url: "apply.php",  
		 			  data: "code="+code+"&method=updateStat",
		 			  cache: false,  
		 		  	  success: function(msg) {
		 		  		if(msg == 0){
		 		  			alert("操作成功");
		 		  			loadPage("business_list.php");
			  		  	  }else if(msg == 1){
			  		  		alert("记录不存在");
			  		  		loadPage("business_list.php");
			  		  	  }else{
			  		  		alert("操作失败");
			  		  	  }
		 			  },
		 			  error: function(){
		 				 alert("操作失败")
		       	  	}
				  });
				});
				
			})
			</script>