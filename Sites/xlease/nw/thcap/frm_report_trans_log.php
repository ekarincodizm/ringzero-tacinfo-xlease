<?php
include("../../config/config.php");
if($datepicker==""){
	$datepicker=nowDate();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตรวจสอบประวัติทำรายการเงินโอน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    //กำหนดให้ default รายการที่ 1 ค้างไว้รายการอื่นให้ hide
	$("#revTranID").hide(); //ซ่อน text รหัสรายการเงินโอน รายการที่ 2
	
	$("#txt3").hide(); //ซ่อนข้อความรายการที่ 3
	$("#iduser").hide(); //ซ่อน text รหัสและชื่อพนักงานของรายการที่ 3
	
	//กรณีเลือก แสดงประวัติการแก้ไขทั้งหมด 
	$('#chk1').click(function(){ 
		$("#txt1").show();
		$("#txt2").show();
		
		$("#revTranID").hide();
		$("#revTranID").val('');
		
		$("#txt3").hide(); 
		$("#iduser").hide();
		$("#iduser").val('');
	});
	
	//กรณีเลือก รหัสรายการเงินโอน  
	$('#chk2').click(function(){
		$("#txt1").hide();
		$("#txt2").hide();
		$("#datepicker").val('');
		
		$("#revTranID").show();
		$("#revTranID").focus();
		
		$("#txt3").hide(); 
		$("#iduser").hide(); 
		$("#iduser").val('');
	});
	//ค้นหารหัสรายการเงินโอน
	$("#revTranID").autocomplete({
		source: "s_revTranID.php",
		minLength:1
	});
	
	//กรณีเลือก พนักงานที่ทำรายการ  
	$('#chk3').click(function(){
		$("#txt1").hide();
		$("#txt2").hide();
		$("#datepicker").val('');
		
		$("#revTranID").hide();
		$("#revTranID").val('');
		
		$("#txt3").show(); 
		$("#iduser").show();
		$("#iduser").focus();
	});
	//ค้นหารหัสหรือชื่อลูกค้า
	$("#iduser").autocomplete({
		source: "s_user.php",
		minLength:1
	});
	
	$("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	
	
	$('#submitbutton').click(function(){	
		var condition;
		var detail;
		//ถ้า แสดงประวัติการแก้ไขทั้งหมด  ถูกเลือก
		if($("#chk1").attr("checked") == true){
			condition=$("#chk1").val();
			detail=$("#datepicker").val();
		}
		
		//ถ้า รหัสรายการเงินโอน  ถูกเลือก
		if($("#chk2").attr("checked") == true){
			if($("#revTranID").val()==""){
				alert("กรุณาระบุรหัสรายการเงินโอน");
				$("#revTranID").focus();
				return false;
			}else{
				condition=$("#chk2").val();
				detail=$("#revTranID").val();
			}
		}
		//ถ้า รหัสพนักงาน  ถูกเลือก
		if($("#chk3").attr("checked") == true){
			if($("#iduser").val()==""){
				alert("กรุณาเลือกพนักงานจากที่ระบบกำหนดให้");
				$("#iduser").focus();
				return false;
			}else{
				condition=$("#chk3").val();
				userstr=$("#iduser").val()
				detail=userstr.replace(" ","5SPACE5");
			}
		}
		//กรณีที่ทำรายการผ่านจะเข้าส่วนนี้
		$("#panel").load("กำลังค้นหาข้อมูล กรุณารอซักครู่...");
		$("#panel").load("frm_report_trans_log_show.php?condition="+ condition +"&detail=" + detail);
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
</style>
    
</head>
<body id="mm">
<div style="text-align:center"><h1>(THCAP) ตรวจสอบประวัติทำรายการเงินโอน</h1></div>
<div style="width:850px;margin:0 auto;">
	<div style="text-align:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
	<fieldset><legend><B>เลือกเงื่อนไขการแสดงรายการ</B></legend>
		<div style="width:650px;padding-left:150px;">
			<div style="padding:5px";><input type="radio" name="condition" id="chk1" value="1" checked>แสดงประวัติการแก้ไขทั้งหมด 							
				<label id="txt1"><b>วันที่จัดการกับข้อมูล: </b></label>
				<label id="txt2"><input type="text" id="datepicker" name="datepicker"  size="15">(ถ้าไม่ระบุวันจะแสดงทุกรายการ)</label>
			</div>
			<div style="padding:5px"><input type="radio" name="condition" id="chk2" value="2">รหัสรายการเงินโอน
				<input type="text" id="revTranID" name="revTranID">
			</div>
			<div style="padding:5px"><input type="radio" name="condition" id="chk3" value="3">พนักงานที่ทำรายการ
				<label id="txt3"><b>รหัส หรือชื่อพนักงาน : </b></label>
				<input type="text" id="iduser" name="iduser" size="40">
			</div>
		</div>
		<div style="text-align:center;padding:10px"><input type="button" id="submitbutton" value="ค้นหา" style="width:100px;height:30px;"></div>
	</fieldset>
</div>
<div id="panel"></div>
</body>
</html>