<?php
session_start();
include("../../config/config.php");
$user_key=$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
$nowdate=nowDate();
$method=$_GET["method"];
if($method=="edit"){
	$id_user=$_GET["id_user"];
	$query=pg_query("select *,a.id_user as id_user2 from \"fuser\" a 
					left join \"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"
					where a.\"id_user\"='$id_user'");
	if($result=pg_fetch_array($query)){
		$id_user=$result["id_user2"];
		$title=$result["title"];
		$fname=$result["fname"];
		$lname=$result["lname"];
		$title_eng=$result["title_eng"];
		$fname_eng=$result["fname_eng"];
		$lname_eng=$result["lname_eng"];
		$nickname=$result["nickname"];
		$username=$result["username"];
		$u_birthday=$result["u_birthday"];
		if($u_birthday=="1900-01-01"){
			$u_birthday="";
		}
		$u_status=$result["u_status"];
		$u_sex=$result["u_sex"];
		$u_idnum=$result["u_idnum"];
		$u_pic=$result["u_pic"];
		$u_pos=$result["u_pos"];
		$u_salary=$result["u_salary"];
		$u_tel=$result["u_tel"];
		$u_extens=$result["u_extens"];
		$u_direct=$result["u_direct"];
		$u_email=$result["u_email"];
		$startwork=$result["startwork"];
		if($startwork=="1900-01-01"){
			$startwork="";
		}
		$dep_id=$result["user_group"];
		$fdep_id=$result["user_dep"];
		$email=$result["email"];
		$section_ID_old=$result["section_ID"];
		
		$work_status=$result["work_status"]; //ครั้งที่เข้ามาทำงานล่าสุด
		$resign_date=$result["resign_date"]; //วันที่ลาออกถ้าเป็น null คือยังทำงานอยู่
		
		//กรณีรับเข้าทำงานใหม่ จะแสดงครั้งที่เข้ามาทำงานปัจจุบันด้วย
		$work_status_now=$work_status+1;
	}
}
if($u_pic == "noimage.jpg" || $u_pic==""){
	$pathpic="images/noimage.jpg";
}else{
	$pathpic="upload_images/$u_pic";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php if($method=="edit"){ echo "แก้ไข";}else{ echo "เพิ่ม";}?>พนักงาน</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>  
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<style type="text/css">
#warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
.weightfont{
	font-weight:bold
}
</style>
<script language=javascript>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.fname.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกชื่อภาษาไทย";
}
if(document.form1.lname.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอกนามสกุลภาษาไทย";
}
if(document.form1.username.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก Username";
}
if(document.form1.u_birthday.value==""){
	theMessage = theMessage + "\n -->  กรุณาระบุวันเกิด";
}
if(document.form1.u_idnum.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอกเลขที่บัตรประชาชน";
}
if(document.form1.dep_id.value==""){
	theMessage = theMessage + "\n -->  กรุณาเลือก กลุ่มผู้ใช้";
}
if(document.form1.section_ID.value==""){
	theMessage = theMessage + "\n -->  กรุณาเลือก แผนก";
}

if (theMessage == noErrors) {
    //ตรวจสอบว่า email ถูกต้องหรือไม่
	if(document.getElementById("u_email").value!=""){
		var emailFilter=/^.+@.+\..{2,3}$/;
		var str=document.getElementById("u_email").value;
		if (!(emailFilter.test(str))) { 
			   alert ("ท่านใส่อีเมล์ไม่ถูกต้อง");
			   document.getElementById("u_email").select();
			   return false;
		}
	}
	if (document.getElementById("statuswork1").checked==true){
		if(document.getElementById("resign_date").value==""){
			alert ("กรุณาระบุวันที่ลาออก");
			document.getElementById("resign_date").focus();
			return false;
		}
	}
	
}else{
    alert(theMessage);
    return false;
}

}
$(document).ready(function(){
	$("#showdate").hide();
	$("#showtime").hide();
	
	$("#u_birthday").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#startwork").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#resign_date").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#statuswork1").click(function(){
		if (document.getElementById("statuswork1").checked==true){
			$("#showdate").show();
			$("#resign_date").focus();
		}else{
			$("#showdate").hide();
		}
    });
	
	$("#statuswork2").click(function(){
		if (document.getElementById("statuswork2").checked==true){
			$("#showtime").show();
		}else{
			$("#showtime").hide();
		}
    });
});
function check_salary(evt) {
	//ให้ใส่จุดได้  ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.u_salary.focus();
		return false;
	}
	return true;
}
function check_num(evt) {
	//ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 45 || charCode > 45) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.u_idnum.focus();
		return false;
	}
	return true;
}
function check_num_tel(evt) {
	//ให้เป็นตัวเลขเท่านั้น  
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 45 || charCode > 45) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.u_idnum.focus();
		return false;
	}
	return true;
}

function check_num_tel_direct(evt) {
	//ให้เป็นตัวเลขเท่านั้น  
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 45 || charCode > 45) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.u_direct.focus();
		return false;
	}
	return true;
}
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;">
		<span class="style2" style="padding-left:10px; height:60px; width:800px; ">
		<div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div>
		<div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div>
	</div>
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><?php if($method=="edit"){ echo "แก้ไข";}else{ echo "เพิ่ม";}?>พนักงาน<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<form method="post" name="form1" action="process_manage.php" enctype="multipart/form-data">
			<table width="780" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">
			<tr id="showtime"><td colspan="9" align="right">(<b>เข้ามาทำงานครั้งที่ <?php echo $work_status_now;?></b>)</td></tr>
			<tr style="background-color:#D0DCA0;" align="right">
				<td height="25" align="right"><b>ชื่อภาษาไทย</b></td>
				<td>คำนำหน้า :</td>
				<td><input type="text" name="title" value="<?php echo $title?>" size="10"></td>
				<td>ชื่อ :</td>
				<td><input type="text" name="fname" value="<?php echo $fname?>"></td>
				<td>นามสกุล :</td>
				<td align="left"><input type="text" name="lname" value="<?php echo $lname?>"></td>
				<td>ชื่อเล่น :</td>
				<td align="left"><input type="text" name="nickname" value="<?php echo $nickname?>"></td>
			</tr>
			<tr style="background-color:#D0DCA0;" align="right">
				<td height="25" align="right"><b>Name Eng.</b></td>
				<td>Title :</td>
				<td><input type="text" name="title_eng" id="title_eng" value="<?php echo $title_eng?>" size="10"></td>
				<td>Name :</td>
				<td><input type="text" name="fname_eng" value="<?php echo $fname_eng?>"></td>
				<td>Surname :</td>
				<td colspan="3" align="left"><input type="text" name="lname_eng" value="<?php echo $lname_eng?>"></td>
			</tr>
			<tr align="left">
				<td height="25" colspan="9" bgcolor="#B8DEB1">&nbsp;<b>ข้อมูลทั่วไป</b></td>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<td colspan="9">
					<table width="780" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">	
						<tr>
							<td rowspan="11" align="center"><img src="<?php echo $pathpic;?>" width="150" height="164"><br>
								<input name="image_name" type="file" id="image_name" onChange="browse()"><input type="hidden" name="MM_insert" value="form1" />
							</td>
						</tr>
						<?php
							if($method!="edit"){
								/*$qrylastid=pg_query("select id_user from fuser");
								$numrow=pg_num_rows($qrylastid);
						 
								$idplus=$numrow+1;
						 
								function insertZero($inputValue , $digit ){
									$str = "" . $inputValue;
									while (strlen($str) < $digit){
										$str = "0" . $str;
									}
									return $str;
								}
								$id_user=insertZero($idplus , 3);
								$seed = $_SESSION["session_company_seed"];
								$v_pass = md5(md5($_POST['v_pass']).$seed);*/
								
								$id_user = "ระบบจะสร้างให้อัตโนมัติ";
							}
							
						?>
						<tr>
							<td align="right" class="weightfont">รหัสพนักงาน</td>
							<td width="10">:</td>
							<td><input type="text" name="id_user" value="<?php echo $id_user; ?>" readonly></td>
							<td align="right" class="weightfont">Username</td>
							<td width="10">:</td>
							<td><input type="text" name="username" value="<?php echo $username?>"></td>
						</tr>
						<tr>
							<td align="right" class="weightfont">วันเกิด</td>
							<td width="10">:</td>
							<td><input type="text" name="u_birthday" id="u_birthday" value="<?php echo $u_birthday?>" size="15"></td>
							<td align="right" class="weightfont">เลขบัตรประชาชน</td>
							<td width="10">:</td>
							<td><input type="text" name="u_idnum" value="<?php echo $u_idnum?>" onkeypress="return check_num(event);"></td>
						</tr>
						<tr>
							<td align="right" class="weightfont"></td>
							<td width="10"></td>
							<td colspan="4">(ระบุเป็น ค.ศ.-เดือน-วัน เช่น <?php echo $nowdate;?>)</td>
						</tr>
						<tr>
							<td align="right" class="weightfont">เพศ</td>
							<td width="10">:</td>
							<td><input type="radio" name="u_sex" value="ชาย" <?php if($u_sex=="" || $u_sex=="ชาย"){ echo "checked"; }?>>ชาย <input type="radio" name="u_sex" value="หญิง" <?php if($u_sex=="หญิง"){ echo "checked"; }?>>หญิง</td>
							<td align="right" class="weightfont">สถานภาพ</td>
							<td width="10">:</td>
							<td><input type="radio" name="u_status" value="โสด" <?php if($u_status=="" || $u_status=="โสด"){ echo "checked"; }?>>โสด <input type="radio" name="u_status" value="สมรส" <?php if($u_status=="สมรส"){ echo "checked"; }?>>สมรส <input type="radio" name="u_status" value="หย่าร้าง"<?php if($u_status=="หย่าร้าง"){ echo "checked"; }?>>หย่าร้าง <input type="radio" name="u_status" value="หม้าย" <?php if($u_status=="หม้าย"){ echo "checked"; }?>>หม้าย</td>
						</tr>
						
						<tr>
							<td align="right" class="weightfont">กลุ่มผู้ใช้</td>
							<td width="10">:</td>
							<td colspan="4">
								<select name="dep_id" id="dep_id">
									<option value="">---เลือก---</option>
									<?php
									$qry_gpuser=pg_query("select * from department");
									while($resg=pg_fetch_array($qry_gpuser))
									{
									?>
										<option value="<?php echo $resg["dep_id"]; ?>" <?php if($dep_id==$resg["dep_id"]){ echo "selected"; }?>><?php echo $resg["dep_name"]; ?></option>
									<?php
									}
									?>  
								</select>
							</td>
						</tr>
						
						<tr>
							<td align="right" class="weightfont">แผนก</td>
							<td width="10">:</td>
							<td colspan="4">
								<select name="section_ID" id="section_ID">
									<option value="">---เลือก---</option>
									<?php
									$qry_f_section = pg_query("select * from \"f_section\" order by \"organizeID\", \"fdep_id\", \"dep_id\" ");
									while($res_f_section = pg_fetch_array($qry_f_section))
									{
										$section_ID = $res_f_section["section_ID"]; // รหัสกลุ่ม
										$organizeID = $res_f_section["organizeID"]; // รหัสบริษัท
										$dep_id = $res_f_section["dep_id"]; // รหัสแผนก
										$fdep_id = $res_f_section["fdep_id"]; // รหัสฝ่าย
										
										// หาชื่อบริษัท
										$qry_organize_name = pg_query("select \"organize_name\" from \"nw_organize\" where \"organizeID\" = '$organizeID' ");
										$organize_name = pg_fetch_result($qry_organize_name,0);
										
										// หาชื่อแผนก
										$qry_dep_name = pg_query("select \"dep_name\" from \"department\" where \"dep_id\" = '$dep_id' ");
										$dep_name = pg_fetch_result($qry_dep_name,0);
										
										// หาชื่อฝ่าย
										$qry_fdep_name = pg_query("select \"fdep_name\" from \"f_department\" where \"fdep_id\" = '$fdep_id' ");
										$fdep_name = pg_fetch_result($qry_fdep_name,0);
									?>
										<option value="<?php echo $section_ID; ?>" <?php if($section_ID==$section_ID_old){ echo "selected"; }?>><?php echo "$organize_name / ฝ่าย$fdep_name / แผนก$dep_name"; ?></option>
									<?php
									}
									?>  
								</select>
							</td>
						</tr>
						
						<tr>
							<td align="right" class="weightfont">ตำแหน่ง</td>
							<td width="10">:</td>
							<td><input type="text" name="u_pos" value="<?php echo $u_pos?>"></td>
							<td align="right" class="weightfont">เงินเดือน (บาท)</td>
							<td width="10">:</td>
							<td><input type="text" name="u_salary" style="text-align:right;" value="<?php echo number_format($u_salary,2)?>" onkeypress="return check_salary(event);"></td>
						</tr>
						<tr>
							<td align="right" class="weightfont">เบอร์มือถือ</td>
							<td width="10">:</td>
							<td><input type="text" name="u_tel" value="<?php echo $u_tel?>" onkeypress="return check_num(event);"></td>
							<td align="right" class="weightfont">เบอร์ต่อภายใน</td>
							<td width="10">:</td>
							<td><input type="text" name="u_extens" value="<?php echo $u_extens?>" onkeypress="return check_num(event);">&nbsp;</td>
							
						</tr>
						<tr>

							<td align="right" class="weightfont">เบอร์ตรง</td>
							<td width="10">:</td>
							<td><input type="text" name="u_direct" value="<?php echo $u_direct ?>" onkeypress="return check_num_tel_direct(event);"></td>
							<td align="right" class="weightfont">E-mail</td>
							<td width="10">:</td>
							<td><input type="text" name="u_email" id="u_email" value="<?php echo $u_email;?>" size="30"></td>
						</tr>
						<tr>
							<td align="right" class="weightfont" valign="top">วันที่เริ่มทำงาน</td>
							<td width="10">:</td>
							<td colspan="4"><input type="text" name="startwork" id="startwork" value="<?php echo $startwork?>" size="15"> (ระบุเป็น ค.ศ.-เดือน-วัน เช่น <?php echo $nowdate;?>)</td>
						</tr>
						<tr>
							<td align="right" class="weightfont" valign="top">
							</td>
							<td width="10"></td>
							<td colspan="4">
								<?php
								if($resign_date==""){
									echo "<input type=\"checkbox\" name=\"statuswork\" id=\"statuswork1\" value=\"0\">";
									echo "<b>แจ้งพนักงานลาออก</b>";
									echo "<div id=\"showdate\">วันที่ลาออก <input type=\"text\" name=\"resign_date\" id=\"resign_date\" size=\"15\"> (ระบุเป็น ค.ศ.-เดือน-วัน เช่น $nowdate)</div>";
								}else{
									echo "<input type=\"checkbox\" name=\"statuswork\" id=\"statuswork2\" value=\"1\">";
									echo "รับพนักงานกลับเข้าทำงานใหม่";
								}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="9" align="center" height="50"><?php if($method=="edit"){?><input type="hidden" name="work_status" value="<?php echo $work_status;?>"><input type="hidden" name="method" value="edit"><input type="hidden" name="u_pic2" value="<?php echo $u_pic;?>"><input type="hidden" name="id_user" value="<?php echo $id_user?>"><?php }else{?><input type="hidden" name="method" value="add"><?php }?><input type="submit" value="บันทึก" onclick="return validate()"><input type="button" value="BACK" onclick="window.location='<?php if($method=="edit"){ echo "frm_Update.php";}else{ echo "frm_Index.php";}?>'" /></td>
			</tr>
			</table>
			</form>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>
