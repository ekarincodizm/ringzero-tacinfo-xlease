<?php
include("../../config/config.php");
include("../function/nameMonth.php");

$selectMonth = $_GET["month"]; // เดือนที่เลือก
$selectYear = $_GET["year"]; // ปีที่เลือก
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานดอกเบี้ยประจำเดือน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link href="list_tab.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&month='+'<?php echo $selectMonth; ?>'+'&year='+'<?php echo $selectYear;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?month='+'<?php echo $selectMonth; ?>'+'&year='+'<?php echo $selectYear;?>',function(){
		list_tab_menu('0');
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&month='+'<?php echo $selectMonth; ?>'+'&year='+'<?php echo $selectYear;?>');
}

function popU(U,N,T){
	newWindow = window.open(U, N, T);
}
</script>
	
</head>
<body>

<center>
<h2>แสดงรายงานดอกเบี้ยประจำเดือนตามปีลูกหนี้</h2>
</center>

<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>ช่วงเวลาที่เลือกแสดง</B></legend>
				<table  width="100%" >
						<tr align="center" >						
							<td>
								<span  id="d1" >
									เดือน :
									<select name="month"><option><?php echo $selectMonth; ?></option></select>
									&nbsp;&nbsp;&nbsp;
								</span>
								<span  id="d2" >
									ปี :
									<select name="year"><option><?php echo $selectYear; ?></option></select>
								</span>
							</td>					
						</tr>
				</table>
			</fieldset>
			<div id="tab_showgroup"></div>
		</td>
	</tr>
</table>
</body>
</html>