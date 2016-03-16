<?php require 'validateSession.php';?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/common.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.11.0.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<title>好雨消息推送平台</title>
<style type="text/css">
.navbar-inverse {
/*  background-color: RGB(3,111,177);  */
 background-color: RGB(64,147,216); 
}
.navbar-inverse .navbar-nav>li>a{
color: #fff;
}
.navbar-inverse .navbar-nav>li>a:hover,
.navbar-inverse .navbar-nav>li>a:focus
{
background-color: #5d7895;
}
.navbar-inverse .navbar-nav>.open>a, 
.navbar-inverse .navbar-nav>.open>a:hover, 
.navbar-inverse .navbar-nav>.open>a:focus {
color: #fff;
background-color: #5d7895;
}

</style>
</head>
<script type="text/javascript">
$(document).ready(function(){
	if($_GET['sdk']==1){
		loadPage("sdk.php");
		$("#sdk").parent().children("li").attr("class", "");
		$("#sdk").attr("class", "active");
	}else{
		loadPage("business_list.php");
	}
    $(".col-sm-3 ul li").on("click", function () {
    		$(this).parent().children("li").attr("class", "");//将所有选项置为未选中
            $(this).attr("class", "active"); //设置当前选中项为选中样式
            var id = $(this).attr("id");
            if(id=="buisness"){
            	loadPage("business_list.php");
            }else if(id=="sdk"){
            	loadPage("sdk.php");
            }
    });
    $("#alert").on('show.bs.modal', function(e) {
        $(this).css({
            'margin-top': function () {
                return ($(this).height()/ 2) +document.documentElement.scrollTop;
            }
        });
    });
    
});
function loadPage(_page){
	  $.ajax({  
		  type: "get",  
		  url: _page,  
		  cache: false,  
	  	  success: function(msg) {  
	  		  $("#main").html(msg);
		  }
	  }); 
}
function showAlert(_text,_callback){
	$("#alert").modal('show');
	$("#desc_div").text(_text);
	if(_callback != undefined){
		$("#alert").on('hide.bs.modal', function (e) {
			_callback();
		})
	}
}
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();

</script>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" style="color: #FFFFFF;" href="index.php">好雨消息推送平台</a>
			</div>
	        <div id="navbar" class="navbar-collapse collapse">
	          <?php include 'head.php'; ?>
        	</div>
		</div>
	</nav>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li id="buisness" class="active"><a href="#">应用申请</a></li>
					<li id="sdk"><a href="#">SDK下载</a></li>
				</ul>
			</div>

			<div id="main" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			</div>
		</div>
	</div>
	
	
<div class="modal fade bs-example-modal-sm" id="alert">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">提示：</h3>
      </div>
      <div class="modal-body">
       		<div id="desc_div" style="vertical-align:middle;font-weight: bold;text-align:center;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>
