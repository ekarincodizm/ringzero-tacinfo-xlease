<?php
include("../../config/config.php");
if($page_app==""){
	$page_app=0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการอนุมัติชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
    <link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
	<link href="list_tab.css" rel="stylesheet" type="text/css" /> 

<style type="text/css">
.sortable {
	color: #000000;
	cursor:pointer;
	text-decoration:underline;
}
</style>
<script type="text/javascript">
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu_app').load('list_tab_showgroup_app.php?tabid=0&all=1');
		
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup_app').load('tab_showgroup_app.php?all=1',function(){
		list_tab_menu_app($('#page_app').val());
		$('.list_tab_menu_app').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
	
});

function list_tab_menu_app(tab_id){	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');
	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu_app').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu_app').load('list_tab_showgroup_app.php?tabid='+tab_id+'&all=1');
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<center><h1>ประวัติการอนุมัติชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า</h1></center>
<input type="hidden" name="page_app" id="page_app" value="<?php echo $page_app;?>">
<div id="tab_showgroup_app" style="width:100%;margin:0 auto;"></div>
</body>

