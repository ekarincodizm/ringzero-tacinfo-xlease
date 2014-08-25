<?php
include("../../../config/config.php");
$page=$_GET['page'];
if($page==""){
	$page=0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ติดตามหนี้เบื้องต้น</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link href="list_tab.css" rel="stylesheet" type="text/css" /> 
<script language="javascript">
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id);
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php',function(){
		list_tab_menu($('#page').val());
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id);
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body bgcolor="">

<form name="frm" method="post">
<input type="hidden" name="page" id="page" value="<?php echo $page;?>">
<table width="850" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
   <tr>
        <td align="center">
				<table align="center" width="100%">
					<tr>
						<td align="left">
							<div style="padding-top:35px;"></div>
							<font color="red" size="5px;"><b>(THCAP) ติดตามหนี้เบื้องต้น</b></font>
						</td>
					</tr>
				</table>
				<div id="tab_showgroup" style="width:1150px;margin:0 auto;"></div>
			</td>
		</tr>
</table>		
</form>
</body>