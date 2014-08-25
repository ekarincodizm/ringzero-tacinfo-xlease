<?php
include("../../config/config.php");
$page=pg_escape_string($_GET['page']);
$page=pg_escape_string($_GET['page']);
$page_app=pg_escape_string($_GET['page_app']);
if($page==""){
	$page=0;
}
if($page_app==""){
	$page_app=0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
    <link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
	<link href="list_tab.css" rel="stylesheet" type="text/css" /> 
</head>
<script type="text/javascript">
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id);
	$('.list_tab_menu_app').load('../thcap_app_payment_debtor_creditors/list_tab_showgroup_app.php?tabid=0');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php',function(){
		list_tab_menu($('#page').val());
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup_app').load('../thcap_app_payment_debtor_creditors/tab_showgroup_app.php',function(){
		list_tab_menu_app($('#page_app').val());
		$('.list_tab_menu_app').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
	
	
});
function list_tab_menu(tab_id){
	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id);
}
function list_tab_menu_app(tab_id){
	$('.tab.active').removeClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu_app').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu_app').load('../thcap_app_payment_debtor_creditors/list_tab_showgroup_app.php?tabid='+tab_id);
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<center><h2>(THCAP) ชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า</h2></center>
<body >
<table  align="right"><tr><td>
<a onclick="javascript:popU('frm_add.php?contractID=&sendfrom_noconid=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1800,height=1000')" style="cursor:pointer;"><font color="#0000FF"><u>ชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า(สัญญที่ไม่ได้ผูก)</u></font></a>
</td></tr></table>
<br>
<br>
<input type="hidden" name="page" id="page" value="<?php echo $page;?>">
<input type="hidden" name="page_app" id="page_app" value="<?php echo $page_app;?>">
<div id="tab_showgroup" style="width:1150px;margin:0 auto;"></div>

</br>
<!--รายการ 30 ล่าลุด-->
<?php 
$frompage='thcap_payment_debtor_creditors_cost';
include('../thcap_app_payment_debtor_creditors/frm_history_limit.php');?>
</body>