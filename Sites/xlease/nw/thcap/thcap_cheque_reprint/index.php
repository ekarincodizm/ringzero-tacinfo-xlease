<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP)พิมพ์ใบรับเช็ค</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language="javascript">
$(document).ready(function(){	

	$("#datecon").datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	 $('#btnserach').click(function(){
			if($("#contype1").attr("checked") == true){
				var condition = $("#contype1").val();
			}else{
				var condition = $("#contype2").val();
			}			
			var datecon = $("#datecon").val();
			$("#showarea").load("กำลังดำเนินการ โปรดรอซักครู่...");
			$("#showarea").load("table_report.php?condition="+condition+"&datecon="+datecon);
	 });
});

function sort(sortdata,orderdata){
	if($("#contype1").attr("checked") == true){
		var condition = $("#contype1").val();
	}else{
		var condition = $("#contype2").val();
	}			
	var datecon = $("#datecon").val();
	$("#showarea").load("กำลังดำเนินการ โปรดรอซักครู่...");
	$("#showarea").load("table_report.php?condition="+condition+"&datecon="+datecon+"&sort="+sortdata+"&order="+orderdata);
};


</script> 
</head>
<body bgcolor="">
<form name="frm" method="post">
<table width="80%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
   <tr>
        <td align="center">
				<div style="padding-top:15px;"></div>
				<table align="center" width="100%">
					<tr>
						<td align="center">							
							<font color="red" size="5px;"><b> (THCAP)พิมพ์ใบรับเช็ค </b></font>
						</td>
					</tr>
				</table>
		</td>
	</tr>		
	<tr>
		<td align="center">
			<div style="padding-top:15px;"></div>
			<fieldset  style="width:60%;background-color:#EED5B7" >
				<table align="center" width="100%" >
					<tr>						
						<td align="center" width="10%">							
						แสดงตาม:
						<input type="radio" id="contype1" name="radio1" value="bankChqDate" checked>วันที่บนเช็ค
						<input type="radio" id="contype2" name="radio1" value="revChqDate">วันที่นำเช็คเข้าธนาคาร
						ของวันที่:<input type="text" name="datecon" id="datecon" value="<?php echo date("Y-m-d");?>" size="10">						
						<input type="button" value="ค้นหา" id="btnserach"></td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<div id="showarea"></div>
		</td>
	</tr>
</table>		
</form>
</body>