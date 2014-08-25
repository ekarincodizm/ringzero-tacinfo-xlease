<?php
session_start();
include("../../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];

$tab_id=pg_escape_string($_GET['tab_id']);
$Strsort=pg_escape_string($_GET['sort']);
$Strorder=pg_escape_string($_GET['order']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) UPLOAD เอกสารสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="styles/list_tab.css" rel="stylesheet" type="text/css" />

<script language=javascript>
//ค้นหาเลขที่สัญญา
function KeyData(){
	$("#conid").autocomplete({
		source: "s_idall.php",
        minLength:1
    });
}
//สร้าง tab
$(function(){
	if(document.getElementById("id").value==""){
		var tab_id = 'ALL';//ทั้งหมด
	}
	else{
		var tab_id = document.getElementById("id").value;
	}
	$('.list_tab_menu').load('list_upload.php?tabid='+tab_id+'&sort=<?php echo $Strsort; ?>&order=<?php echo $Strorder; ?>');
	//ดึง tab ขึ้นมาแสดง
	$('#include').load('tab_upload.php?',function(){
		list_tab_menu(tab_id);
	});
});
function list_tab_menu(tab_id){	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');	
	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').load('list_upload.php?tabid='+tab_id+'&sort=<?php echo $Strsort ?>&order=<?php echo $Strorder ?>');
}

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<style>
#include
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
#search
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
}
</style>
</head>
<body>
	<div class="header" align="center">
		<h1>(THCAP) UPLOAD เอกสารสัญญา</h1>
	</div>
	
	<div id="search">
		<form method="post" action="detail_contract.php">
		<table align="center" >
			<tr>
				<td align="center">
				<label><b>Upload เพิ่มเติม/แก้ไข ค้นหาเลขที่สัญญา: </b></label><input type="text" name="conid" id="conid" size="40" onkeyup="KeyData();" onblur="KeyData();" />
				<input type="hidden" name="valuechk" id="valuechk" >
				<input type="submit" id="search" value="ค้นหา"/>
				</td>
			</tr>
		</table>
		</form>
	</div>
	
	<input type="hidden" name="id" id="id" value="<?php echo $tab_id; ?>">				
	<div id="include"></div>
	<div id="include"><?php include("wait_appv.php"); ?></div> <!-- รายการรอตรวจสอบเอกสาร upload!-->
</body>
</html>