<?php 
//แสดงข้อมูลโดย Group ตามปีที่ทำสัญญา
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$type = $_GET["type"]; // ประเภท
$Sdate = $_GET["Sdate"]; // วันที่เริ่ม
$Edate = $_GET["Edate"]; // วันที่สิ้นสุด
$month = $_GET["month"]; // เดือนที่เลือก
$year = $_GET["year"]; // ปีที่เลือก
$whereContract = $_GET["whereContract"]; // เลขที่สัญญา
$selectStyle = $_GET["selectStyle"]; // รูปแบบการแสดง

$nameMonthTH = nameMonthTH($month);
if($whereContract==""){
	$txtcontractID="แสดงทุกเลขที่สัญญา";
}else{
	$txtcontractID="เลขที่สัญญา $whereContract";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) รายงานตั้งหนี้ดอกเบี้ย</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link href="list_tab.css" rel="stylesheet" type="text/css" />

<script language=javascript>
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&type='+'<?php echo $type;?>'+'&Sdate='+'<?php echo $Sdate; ?>'+'&Edate='+'<?php echo $Edate;?>'+'&month='+'<?php echo $month;?>'+'&year='+'<?php echo $year;?>'+'&selectStyle='+'<?php echo $selectStyle; ?>'+'&whereContract='+'<?php echo $whereContract;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?type='+'<?php echo $type;?>'+'&Sdate='+'<?php echo $Sdate; ?>'+'&Edate='+'<?php echo $Edate;?>'+'&month='+'<?php echo $month;?>'+'&year='+'<?php echo $year;?>'+'&selectStyle='+'<?php echo $selectStyle; ?>'+'&whereContract='+'<?php echo $whereContract;?>',function(){
		list_tab_menu('0');
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&type='+'<?php echo $type;?>'+'&Sdate='+'<?php echo $Sdate; ?>'+'&Edate='+'<?php echo $Edate;?>'+'&month='+'<?php echo $month;?>'+'&year='+'<?php echo $year;?>'+'&selectStyle='+'<?php echo $selectStyle; ?>'+'&whereContract='+'<?php echo $whereContract;?>');
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<center>
<h2>แสดงรายงานตั้งหนี้ดอกเบี้ยตามปีลูกหนี้</h2>
</center>
<table width="950" align="center" border="0">
<tr>
	<td align="center"><b>
		<?php
		if($type == "year"){ echo "$txtcontractID ประจำปี  $year"; }
		if($type == "month"){ echo "$txtcontractID ประจำเดือน $nameMonthTH $year"; }
		if($type == "between"){echo "$txtcontractID ระหว่าง $Sdate ถึง $Edate";}
		?>
		<b>
	</td>
</tr>
</table>
<div id="tab_showgroup" style="width:950;margin:0 auto;"></div>

</body>
</html>