<div>
				<h1 class="page-header">创建主题</h1>
				<div class="panel panel-default"
					style="padding: 10px 15px 10px 15px;">
					<form id="form1" class="form-horizontal" role="form">
						<div class="form-group">
							<label for="applyer" class="col-sm-2 control-label"><span
								style="color: red">*</span>申请人</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="applyer"
									name="applyer" placeholder="申请人">
							</div>
						</div>
						<div class="form-group">
							<label for="business" class="col-sm-2 control-label"><span
								style="color: red">*</span>业务名称</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="business"
									name="business" placeholder="业务名称">
							</div>
						</div>
						<div class="form-group">
							<label for="topic" class="col-sm-2 control-label"><span
								style="color: red">*</span>主题名</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name"
									placeholder="主题名,主题名">
							</div>
						</div>
						<div class="form-group">
							<label for="join_method" class="col-sm-2 control-label"><span
								style="color: red">*</span>接入方式</label>
							<div class="col-sm-10">
								<select class="form-control" id="join_method" name="join_method">
									<option value="1">websocket</option>
									<option value="2">socket-io</option>
									<option value="3">Socket</option>
									<option value="4">Http</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="is_store" class="col-sm-2 control-label"><span
								style="color: red">*</span>持久化</label>
							<div class="col-sm-10">
								<select class="form-control" id="is_store" name="is_store">
									<option value="0">否</option>
									<option value="1">是</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="is_store" class="col-sm-2 control-label">持久化方式</label>
							<div class="col-sm-10">
								<select class="form-control" id="store_method"
									name="store_method">
									<option value="1">按条数</option>
									<option value="2">按天</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="store_num" class="col-sm-2 control-label">持久化条数(天数)</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="store_num"
									name="store_num">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="button" id="submit1" class="btn btn-primary" value="创建"/>
							</div>
						</div>
					</form>
				</div>
			</div>
		<script>
			$(document).ready(function(){
				$("#submit1").click(function(){
		        	var _data = $("form").serialize();
		        	$.ajax({
		        	  type: "post",  
		  			  url: "topic.php",  
		  			  data: _data+"&method=save",
		  			  cache: false,  
		  		  	  success: function(msg) {  
		  		  		  alert("保存成功");
		  			  },
		  			  error: function(){
		  				alert("保存失败");
		        	  }
		        	})
		        })
			})
			</script>