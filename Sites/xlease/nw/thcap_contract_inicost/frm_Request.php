<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$app_date = Date('Y-m-d H:i:s');
$tab_id=pg_escape_string($_GET['tab_id']);//ประเภทสัญญา
$Strsort=pg_escape_string($_GET['sort']);
$Strorder=pg_escape_string($_GET['order']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่ต้นทุนสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="styles/list_tab.css" rel="stylesheet" type="text/css" />

<script language=javascript>
//สร้าง tab
$(function(){
	if(document.getElementById("id").value==""){
		var tab_id = '0';//ทั้งหมด
	}
	else{
		var tab_id = document.getElementById("id").value;
	}
	$('.list_tab_menu').load('list_tabcost.php?tabid='+tab_id+'&sort=<?php echo $Strsort; ?>&order=<?php echo $Strorder; ?>');
	//ดึง tab ขึ้นมาแสดง
	$('#tab_cost').load('tab_cost.php?',function(){
		list_tab_menu(tab_id);
	});
});
function list_tab_menu(tab_id){	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');	
	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').load('list_tabcost.php?tabid='+tab_id+'&sort=<?php echo $Strsort ?>&order=<?php echo $Strorder ?>');
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>
<form name="frm" method="POST">
<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header" align="center"><h1>(THCAP) ใส่ต้นทุนสัญญา</h1></div>
		<div class="wrapper">
			<input type="hidden" name="id" id="id" value="<?php echo $tab_id; ?>">				
				<div id="tab_cost"></div>
</form>
<div>
	<?php
	//รายการรออนุมัติต้นทุนสัญญาของสัญญา
	include "frm_Approve.php";
	?>
</div>
<div>
	<?php 
	//ประวัติการอนุมัติ
	include"show_history.php";
	?>
</div>
</body>
</html>