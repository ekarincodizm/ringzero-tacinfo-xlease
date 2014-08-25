<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายงานค้างชำระหนี้อื่นๆ</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#panel").load("list_contract.php");

    $('#type').change(function(){
		var aaaa = $('#type option:selected').attr('value');
		if(aaaa == 'con'){
			$("#panel").load("list_contract.php");
		}else{
			$("#panel").load("list_type.php");
		}
    });
	
	
	

});
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(255, 255, 255);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body bgcolor='#EEEEEE'>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">

<div id="warppage" style="width:460px;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:25px; padding-left:10px; padding-top:10px; padding-right:10px;">(THCAP) รายงานค้างชำระหนี้อื่นๆ<hr /></div>
<div id="contentpage" style="height:auto;">
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <b></b>
	รูปแบบการแสดงรายงาน : 
    <select id="type">
		<option value="con" selected>แสดงแบบรายสัญญา</option>
		<option value="type">แสดงแบบรายประเภทหนี้</option>	
	</select>
    <input name="button" type="button" onclick="window.close();" value="ปิด" style="height:40px; width:80px;"/>
	<div class="style5" style="width:auto; height:100px; padding-left:10px;">
	  </div>
  </div>
</div>

</div>
<div id="footerpage"></div>
</div>
</div>
<div id="panel" style="padding-top: 1px;"></div>
</body>
</html>
