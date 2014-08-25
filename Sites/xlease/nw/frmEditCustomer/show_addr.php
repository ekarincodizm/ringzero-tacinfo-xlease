<?php
session_start();
include("../../config/config.php");
$IDNO=pg_escape_string($_GET["IDNO"]);
$CusID=pg_escape_string($_GET["CusID"]);
$fullname=pg_escape_string($_GET["fullname"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript">     
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	
	if (document.frm_edit.f_no.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่";
	}

	if (document.frm_edit.f_subno.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ หมู่ที่";
	}

	if (document.frm_edit.f_aum.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ แขวง/ตำบล";
	}

	if (document.frm_edit.f_tum.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ";
	}

	if (document.frm_edit.f_province.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุจังหวัด";
	}
	
	if (document.frm_edit.f_post.value=="") {
	theMessage = theMessage + "\n -->  กรุณากรอกรหัสไปรษณีย์";
	}
	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	} 
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}

function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function send(f_letter){
	eval("self.opener.document.frm_edit.f_letter.value = '"+f_letter+"'");
	window.close();
}
</script>
</script>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
}
.style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
}

-->
</style>

<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #FF0000;
	font-weight: bold;
}


#warppage
{
	width:600px;
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

-->
</style>
<!-- InstanceEndEditable -->

</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
	</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
	<div id="warppage"  style="width:600; text-align:left; margin-left:auto; margin-right:auto;">
		ที่อยู่เลขที่สัญญา <font color="red"><b><?php echo $IDNO?> : <?php echo $fullname;?></b></font><br /><hr />
		<div class="style5" style="width:auto;  padding-left:10px;">
		<?php	 
			$qry_fa1=pg_query("select * from \"Fp_Fa1\" where \"IDNO\" ='$IDNO' and \"CusID\"='$CusID' and \"edittime\"='0'");
			$numfa1=pg_num_rows($qry_fa1);
			if($numfa1==0){
				$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\"='$CusID'");
			}
			$res_fa1=pg_fetch_array($qry_fa1);
			$fa1_no=trim($res_fa1["A_NO"]);
			$fa1_subno=trim($res_fa1["A_SUBNO"]);
			$fa1_soi=trim($res_fa1["A_SOI"]);
			$fa1_rd=trim($res_fa1["A_RD"]);	
			$fa1_tum=trim($res_fa1["A_TUM"]);	
			$fa1_aum=trim($res_fa1["A_AUM"]);
			$fa1_pro=trim($res_fa1["A_PRO"]);	
			$fa1_post=trim($res_fa1["A_POST"]);
		?>
		</div>
		<form name="frm_edit" method="post" action="frm_Idnonew.php" onsubmit="return validate(this);">
		<table width="600" border="0" cellpadding="1" cellspacing="1" align="center">	
		<tr>
			<td colspan="2" height="25">&nbsp;</td>
		</tr>
		<tr>
			<td align="right">เลขที่</td>
			<td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">หมู่ที่</td>
			<td><input type="text" name="f_subno" value="<?php echo $fa1_subno; ?>"readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">ซอย</td>
			<td><input type="text" name="f_soi" value="<?php echo $fa1_soi; ?>"readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">ถนน</td>
			<td><input type="text" name="f_rd" value="<?php echo $fa1_rd; ?>"readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" value="<?php echo $fa1_tum; ?>"readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">เขต/อำเภอ</td>
			<td><input type="text" name="f_aum" value="<?php echo $fa1_aum; ?>"readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">จังหวัด</td>
			<td><input type="text" name="f_aum" value="<?php echo $fa1_pro;?>"readonly="true"/></td>
		</tr>
		<tr>
			<td align="right">รหัสไปรษณีย์</td>
			<td><input type="text" name="f_post" value="<?php echo $fa1_post; ?>" maxlength="5" readonly="true"/></td>
		</tr>
		<tr>
			<td colspan="2" height="25">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" value="   ปิด   " onclick="window.close()" />
			</td>
		</tr>
		<tr>
			<td colspan="2" height="25">&nbsp;</td>
		</tr>
		</table>
		</form>
	</div>
</div>
<?php
//process
$method=pg_escape_string($_POST["method"]);
$IDNO=pg_escape_string($_POST["IDNO"]);
$f_no=pg_escape_string($_POST["f_no"]);
$f_subno=pg_escape_string($_POST["f_subno"]); if($f_subno==""){ $f_subno2="null";}else{ $f_subno2="'".$f_subno."'";}
$f_soi=pg_escape_string($_POST["f_soi"]); if($f_soi==""){ $f_soi2="null";}else{ $f_soi2="'".$f_soi."'";}
$f_rd=pg_escape_string($_POST["f_rd"]); if($f_rd==""){ $f_rd2="null";}else{ $f_rd2="'".$f_rd."'";}
$f_tum=pg_escape_string($_POST["f_tum"]);
$f_aum=pg_escape_string($_POST["f_aum"]);
$f_province=pg_escape_string($_POST["f_province"]);
$f_post=pg_escape_string($_POST["f_post"]);

if($method=="add"){ //การ add เป็นการ add ชั่วคราวเท่านั้นถ้า user ยืนยันจะใช้ที่อยู่นี้จริง หลังจาก insert ในตารางหลักแล้วข้อมูลในนี้จะถูกลบ
	pg_query("BEGIN WORK");
	$status = 0;
	
	//delete ข้อมูลเก่าออกก่อนถ้ามี
	$del="DELETE FROM \"Fp_Fa1_addtemp\" WHERE \"IDNO\"='$IDNO'";
	if($resdel=pg_query($del)){
	}else{
		$status++;
	}
	
	//insert ข้อมูลล่าสุดลงไป
	$ins="INSERT INTO \"Fp_Fa1_addtemp\"(
            \"IDNO\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", 
            \"A_AUM\", \"A_PRO\", \"A_POST\")
    VALUES ('$IDNO', '$f_no', $f_subno2, $f_soi2, $f_rd2, '$f_tum', 
			'$f_aum', '$f_province', '$f_post')";
	if($resins=pg_query($ins)){
	}else{
		$status++;
	}
	
	if($status == 0){
		pg_query("COMMIT");
		if($f_subno!=""){
			$subno="หมู่ $f_subno";
		}
		if($f_soi!=""){
			$soi="ซอย$f_soi";
		}
		if($f_rd!=""){
			$road="ถนน$f_rd";
		}
		if($f_province=="กรุงเทพมหานคร"){
			$txttum="แขวง".$f_tum;
			$txtaum="เขต".$f_aum;
		}else{
			$txttum="ตำบล".$f_tum;
			$txtaum="อำเภอ".$f_aum;
		}
		$address="$f_no $subno $soi $road $txttum $txtaum $f_province $f_post";
		echo $address;
		echo "Java Script" ;// Echo Java Script ของเราไปเลยครับ เช่น
        echo"<script language = 'javascript'>";
        echo "send('$address')";
        echo"</script>";
	}else{
		pg_query("ROLLBACK");
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ผิดพลาด ไม่สามารถบันทึกข้อมูลได้</b></font><br>";
	}
}

?>
</body>
</html>

