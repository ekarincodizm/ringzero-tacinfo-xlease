<?php 
session_start();
include("config/config.php");
$iduser=$_SESSION['av_iduser'];
$cmd = pg_escape_string($_GET['cmd']);
$pass_status = pg_escape_string($_GET['pass_status']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="images/act.css"></link>
	<link type="text/css" href="jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script language="JavaScript" type="text/JavaScript">
function safe_level(no){	
	if(no==1){	
		chk_pass_safe_level('new_pass','safe_level');
	}
}
function chk_pass_safe_level(element_text,element_show){
	var v_safe_level= document.getElementById(element_text).value;	
	var nlength = v_safe_level.length;
	if(nlength < 11){
		if((nlength==6)||(nlength==7)){		
			$("#"+element_show).css('color','#ff0000');
			$("#"+element_show).html('(ระดับความปลอดภัย ปานกลาง)');
		}else if((nlength==8)||(nlength==9)){
			$("#"+element_show).css('color','#ff0000');
			$("#"+element_show).html('(ระดับความปลอดภัย สูง)');
		}else if(nlength==10){
			$("#safe_level").css('color','#ff0000');
			$("#safe_level").html('(ระดับความปลอดภัย สูงที่สุุด)');
		}else{		
			$("#"+element_show).css('color','#ff0000');
			$("#"+element_show).html('(ระดับความปลอดภัย ต่ำ)');
		}
	}

}
//ตรวจสอบการตั้ง password
function chk_pass_method(){	
	
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage;
	
	var v_new_pass= document.getElementById('new_pass').value;	//รหัสผ่านใหม่
	var v_cnew_pass= document.getElementById('cnew_pass').value;	//ยืนยัน รหัสผ่านใหม่
	var v_old_pass= document.getElementById('old_pass').value;	//ยืนยัน รหัสผ่านใหม่
	if(v_new_pass !=v_cnew_pass){
		 theMessage = theMessage + "\n -->  รหัสผ่านใหม่ กับ ยืนยัน รหัสผ่านใหม่ ไม่เหมือนกันกรุณาป้อนข้อมูลใหม่";
	}
	else{
		var pattern =/((?=.*[0-9])(?=.*[A-Za-z]))^.*/;
		var result_new = pattern.test(v_new_pass);
		var result_cnew = pattern.test(v_cnew_pass);
		if((result_new==false) ||(result_cnew==false)){
			theMessage = theMessage + "\n -->  กรุณาป้อนรหัส ใหม่เนื่องจาก ต้องใช้ตัวอักษรภาษาอังกฤษ (a-z, A-Z)ผสมกับ ตัวเลข(0-9) เท่านั้น";
		}
		
	}
	var chk_PASSWORD = v_new_pass.toUpperCase();			
	var result_password= chk_PASSWORD.match(/PASSWORD/);
	if(result_password =='PASSWORD'){
		theMessage = theMessage + "\n -->  กรุณาป้อนรหัส  ที่ไม่มีส่วนประกอบของคำว่า password";
	}
	if(v_new_pass ==''){
		 theMessage = theMessage + "\n -->  กรุณาป้อน รหัสผ่านใหม่"; 
	}
	if(v_cnew_pass ==''){
		 theMessage = theMessage + "\n -->  กรุณาป้อน ยืนยัน รหัสผ่านใหม่";
	}
	if(v_old_pass ==''){
		 theMessage = theMessage + "\n -->  กรุณาป้อน รหัสผ่านเดิม";
	}
	if((v_new_pass.length < 6) ||(v_cnew_pass.length < 6)){
		 theMessage = theMessage + "\n -->  กรุณาป้อนรหัส ใหม่เนื่องจาก จำนวนตัวอักขระต้องมี  6-10 ตัว";
	}
	
	if(document.getElementById('iduser').value=='1'){
		 theMessage = theMessage + "\n -->  กรุณาป้อนรหัส ใหม่เนื่องจาก รหัสผ่าน เหมือนกับรหัสผ่านเดิม";
	}
	
	if(theMessage == noErrors){return true;}
	else { alert(theMessage);return false;}
}
function chk_oldpasswordinsys(){
	$.post('change_pass/chk_password.php',{					
			new_pass:$('#new_pass').val()
	},function(data){		
		if(data == 0){
			document.getElementById("iduser").value= 0;
		}
		else{
			document.getElementById("iduser").value= 1;
		}
	});
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>

<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="wrapper">

<?php
if($cmd == "2"){}
elseif($cmd != "1"){
?>
<div align="right"><input type="button" value="กลับหน้าหลัก" onclick="location.href='list_menu.php'"></div>
<?php
}
?>

<fieldset><legend><b>เปลี่ยนรหัสผ่าน</b></legend>

<?php
if($cmd == "2"){echo "<div style=\"color:red; padding:10px; text-align: center;\">เนื่องจากมีการปรับปรุงระบบ กรุณาอ่านคำแนะนำแล้วเปลี่ยนรหัสผ่านใหม่<br><br>";}
elseif($cmd == "1"){
?>
<?php
if($pass_status == "2")
{
	echo "<div style=\"color:red; padding:10px; text-align: center;\">เนื่องจากถูก reset password ท่านต้องเปลี่ยนรหัสผ่านใหม่<br><br></div>";
}
else
{
	echo "<div style=\"color:red; padding:10px; text-align: center;\">เนื่องจากครบกำหนดระยะเวลา 45 วัน ท่านต้องเปลี่ยนรหัสผ่านใหม่<br><br>";
}
?>
ห้าม Share User หรือ Password เด็ดขาด หากมีปัญหาเกิดขึ้น<br>
User ที่มีชื่อเป็นผู้ทำรายการจะต้องเป็นผู้รับผิดชอบ<br><br>
หากมีข้อสงสัยหรือคิดว่าผู้อื่นทราบ password<br>
กรุณาเปลี่ยนใหม่ทันที หรือแจ้งที่ HelpDesk
</div>
<?php
}
?>
<input  id="iduser" name="iduser" value="1" hidden>
<FORM name="editpass"  method="post" action="change_pass_ok.php<?php if($cmd == 1){ echo "?cmd=1"; } ?>" onSubmit="return check()">
<table width="100%" cellpadding="3" cellspacing="0" >
    <tr>
        <td><font color="black">รหัสผ่านเดิม</font></td>
        <td align="left"><input type="password" id="old_pass" name="old_pass"></td>		
		<td width="40%"></td>
    </tr>
    <tr>
        <td><font color="black">รหัสผ่านใหม่</font></td>
        <td align="left"><input type="password" id="new_pass" name="new_pass" onkeyup="safe_level(1);chk_oldpasswordinsys();">
		<a onclick="javascript:popU('change_pass/frm_guidance.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><font color="#0000FF"><u>[คำแนะนำ]</u></font></a>
		
		</td>
		<td><span id="safe_level" name="safe_level"></span></td>
    </tr>
    <tr>
        <td><font color="black">ยืนยัน รหัสผ่านใหม่</font></td>
        <td align="left"><input type="password" id="cnew_pass" name="cnew_pass" ></td>
		<td></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
		<input type="submit" name="ok" value="  บันทึก  " onclick="return chk_pass_method()"></td>
		<td></td>
    </tr>
</table>
</FORM>
</fieldset>
</div>

        </td>
    </tr>
</table>

</body>
</html>