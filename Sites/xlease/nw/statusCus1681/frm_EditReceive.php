<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขรับชำระแทนชั่วคราว 1681</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	//เริ่มแรกกำหนดซ่อนรายละเอียดทุกเงื่อนไข
	$("#showxlsDate").hide(); //วันที่รับชำระ
	$("#showmarkerStamp").hide(); //วันที่ทำรายการ
	$("#showmxlsDate").hide(); //เดือนที่รับชำระ
	$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
	$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
	$("#showtacID").hide(); //เลขที่สัญญา
	
	$("#condition").change(function(){
		var src = $('#condition option:selected').attr('value');
		
		if(src=="1"){
			$("#showxlsDate").show();
			$("#showmarkerStamp").hide(); //วันที่ทำรายการ
			$("#showmxlsDate").hide(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
			$("#showtacID").hide(); //เลขที่สัญญา
		}else if(src=="2"){
			$("#showxlsDate").hide();
			$("#showmarkerStamp").show(); //วันที่ทำรายการ
			$("#showmxlsDate").hide(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
			$("#showtacID").hide(); //เลขที่สัญญา
		}else if(src=="3"){
			$("#showxlsDate").hide();
			$("#showmarkerStamp").hide(); //วันที่ทำรายการ
			$("#showmxlsDate").show(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
			$("#showtacID").hide(); //เลขที่สัญญา
		}else if(src=="4"){
			$("#showxlsDate").hide();
			$("#showmarkerStamp").hide(); //วันที่ทำรายการ
			$("#showmxlsDate").hide(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").show(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
			$("#showtacID").hide(); //เลขที่สัญญา
		}else if(src=="5"){
			$("#showxlsDate").hide();
			$("#showmarkerStamp").hide(); //วันที่ทำรายการ
			$("#showmxlsDate").hide(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").show(); //เลขที่ใบเสร็จ
			$("#showtacID").hide(); //เลขที่สัญญา
		}else if(src=="6"){
			$("#showxlsDate").hide();
			$("#showmarkerStamp").hide(); //วันที่ทำรายการ
			$("#showmxlsDate").hide(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
			$("#showtacID").show(); //เลขที่สัญญา
		}else{
			$("#showxlsDate").hide(); //วันที่รับชำระ
			$("#showmarkerStamp").hide(); //วันที่ทำรายการ
			$("#showmxlsDate").hide(); //เดือนที่รับชำระ
			$("#showmmarkerStamp").hide(); //เดือนที่ทำรายการ
			$("#showtacXlsRecID").hide(); //เลขที่ใบเสร็จ
			$("#showtacID").hide(); //เลขที่สัญญา			
		}
	});
	
    $("#tacID").autocomplete({
        source: "s_cusidintemp.php",
        minLength:1
    });
	
	$("#tacXlsRecID").autocomplete({
        source: "s_recidintemp.php",
        minLength:1
    });
	
    $("#xlsDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#markerStamp").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	$('#btn1').click(function(){
		var conselect; //ตัวแปรสำหรับเลือกเงื่อนไข
		
		var src = $('#condition option:selected').attr('value');
		if(src==""){
			conselect="";
		}else if(src=="1"){
			conselect=$('#xlsDate').val();
		}else if(src=="2"){
			conselect=$('#markerStamp').val();
		}else if(src=="3"){
			conselect=$('#mxlsDate option:selected').attr('value')+","+$('#yxlsDate').val();
		}else if(src=="4"){
			conselect=$('#mmarkerStamp option:selected').attr('value')+","+$('#ymarkerStamp').val();
		}else if(src=="5"){
			conselect=$('#tacXlsRecID').val();
		}else if(src=="6"){
			conselect=$('#tacID').val();
		}

		$("#panel").text('กำลังค้นหาข้อมูลลูกค้า  โปรดรอซักครู่....');
        $("#panel").load("frm_AllReceiveDlt.php?condition="+ src + "&conselect="+conselect);
    });
});
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>
<div style="text-align:center;"><h2>แก้ไขรับชำระแทนชั่วคราว 1681</h2></div>
<div style="margin-top:10px;">
	<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<fieldset><legend><B>ค้นหาข้อมูล</B></legend>
				<div class="ui-widget" align="center">

				<div style="margin:0"><b>ค้นหาจาก :</b>
				<select name="condition" id="condition">
					<option value="">ทั้งหมด</option>
					<option value="1">วันที่ชำระ</option>
					<option value="2">วันที่ทำรายการ</option>
					<option value="3">เดือนที่รับชำระ</option>
					<option value="4">เดือนที่ทำรายการ</option>
					<option value="5">เลขที่ใบเสร็จ</option>
					<option value="6">เลขที่สัญญาหรือทะเบียนรถ</option>
				</select>
				<input type="button" id="btn1" value="ค้นหา"/>
				</div>
				<div id="showxlsDate">วันที่ : <input type="text" id="xlsDate" name="xlsDate" value="<?php echo nowDate();?>" size="15" style="text-align: center;" readonly></div>
				<div id="showmarkerStamp">วันที่ : <input type="text" id="markerStamp" name="markerStamp" value="<?php echo nowDate();?>" size="15" style="text-align: center;" readonly></div>
				<div id="showmxlsDate">เดือน : 
					<select name="mxlsDate" id="mxlsDate">
						<option value="01">มกราคม</option>
						<option value="02">กุมภาพันธ์</option>
						<option value="03">มีนาคม</option>
						<option value="04">เมษายน</option>
						<option value="05">พฤษภาคม</option>
						<option value="06">มิถุนายน</option>
						<option value="07">กรกฎาคม</option>
						<option value="08">สิงหาคม</option>
						<option value="09">กันยายน</option>
						<option value="10">ตุลาคม</option>
						<option value="11">พฤศจิกายน</option>
						<option value="12">ธันวาคม</option>
					</select> ค.ศ.
					<input type="text" name="yxlsDate" id="yxlsDate" value="<?php echo date('Y'); ?>" maxlength="4" size="10" style="text-align:center;">
				</div>
				<div id="showmmarkerStamp">เดือน : 
					<select name="mmarkerStamp" id="mmarkerStamp">
						<option value="01">มกราคม</option>
						<option value="02">กุมภาพันธ์</option>
						<option value="03">มีนาคม</option>
						<option value="04">เมษายน</option>
						<option value="05">พฤษภาคม</option>
						<option value="06">มิถุนายน</option>
						<option value="07">กรกฎาคม</option>
						<option value="08">สิงหาคม</option>
						<option value="09">กันยายน</option>
						<option value="10">ตุลาคม</option>
						<option value="11">พฤศจิกายน</option>
						<option value="12">ธันวาคม</option>
					</select> ค.ศ.
					<input type="text" name="ymarkerStamp" id="ymarkerStamp" value="<?php echo date('Y'); ?>" maxlength="4" size="10" style="text-align:center;">
				</div>
				<div id="showtacXlsRecID">เลขที่ใบเสร็จ : <input type="text" name="tacXlsRecID" id="tacXlsRecID"></div>
				<div id="showtacID">เลขที่สัญญาหรือทะเบียนรถ : <input type="text" name="tacID" id="tacID"></div>
				</div>
				<div style="padding-top:10px;color:red;">* หากค้นหารายการใดแล้วไม่พบ อาจเป็นไปได้ว่ารายการนั้นกำลังรออนุมัติอยู่ กรุณาตรวจสอบจากรายการที่รออนุมัติ</div>
				 </fieldset>
			</td>
		</tr>
	</table>
</div>
<div>
	<?php 
		$readonly = 'readonly';
		include("frm_wait_appv.php"); 
	?>
</div>
<div id="panel" style="padding-top: 20px;text-align:center;" ></div>
<div style="padding-top:20px;">
	<?php include("frm_history_limit.php"); ?>
</div>	


</body>
</html>