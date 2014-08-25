<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) จัดการประเภทต้นทุนสัญญา</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
/*$(document).ready(function(){ 
	/*var ele=$('input[name="showall"]');  
	if($(ele).is(':checked')){}
	else{$(ele).attr ( "checked" ,"checked" );}
	showall('0');});*/
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function showall(no)
{	
	var ele=$('input[name="showall"]');  
	if(($(ele).is(':checked')))
	{  
		$(ele).attr ( "checked" ,"checked" );
		data = $.ajax({    
			url: "frm_addtypeloan.php?autoid="+no,
			async:false
		}).responseText;	
		$("#addtypeloan").html(data);	
	}
	else
	{	
		var data = "";	
		$("#addtypeloan").html(data);	
	}
}		
</script>
</head>
<body>
<center><h2>(THCAP) จัดการประเภทต้นทุนสัญญา</h2></center>
<br>
<div id="addtype" name="addtype">
	<fieldset><legend><B>จัดการประเภทต้นทุนสัญญา</B></legend>
	<div> <input type=checkbox name="showall" onChange=showall(<?php echo '0'?>)>แสดง/ซ่อน การเพิ่มประเภทต้นทุนสัญญา</div>
<div id="addtypeloan">
</div>
</fieldset><br>

<div id="ask_for_authorization">
	<?php //รายการการขออนุมัติประเภทต้นทุนเริ่มแรก
	include('frm_ask_for_authorization.php');?>
</div>
<div id="approve_limit">
	<?php //ประวัติการอนุมัติ 30 รายการล่าสุด
	include('frm_approve_limit.php');?>
</div>
</div>
</body>
</html>