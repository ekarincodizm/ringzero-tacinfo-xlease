<?php
include("../../config/config.php");

$accBookID = pg_escape_string($_POST["accBookID"]); // เลขที่สมุดบัญชี
$accBookName = pg_escape_string($_POST["accBookName"]); // ชื่อสมุดบัญชี
$accBookType = pg_escape_string($_POST["accBookType"]); // ประเภทสมุดบัญชี
$accBookNameFS = pg_escape_string($_POST["accBookNameFS"]);
$accBookStatus = pg_escape_string($_POST["accBookStatus"]); // สถานะบัญชี
$accBookableFS = pg_escape_string($_POST["accBookableFS"]); 
$accBookTypeFS = pg_escape_string($_POST["accBookTypeFS"]);
$accBookserial = pg_escape_string($_POST["accBookserial"]);
$actionpage="process_add.php";
if($accBookserial==""){
	$accBookserial=pg_escape_string($_GET["accBookserial"]);//กรณีที่เป็นการแก้ไข
}
if($accBookserial !=""){
	$actionpage="process_edit.php";
	$query = pg_query("select * from account.\"all_accBook\" where \"accBookserial\"='$accBookserial'");
	$numrows = pg_num_rows($query);
	$result = pg_fetch_array($query);	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<?php if($accBookserial !='') { ?>
	<title>แก้ไขสมุดบัญชี</title>
	<?php } else { ?>	
    <title>เพิ่มสมุดบัญชี</title>	
	<?php } ?>	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลข
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 46 && key != 47)
		{
			// ถ้าเป็นตัวเลข
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 46 && key != 47)
		{
			// ถ้าเป็นตัวเลข
		}
		else
		{
			key = e.preventDefault();
		}
	}
};
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm1.accBookID.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่สมุดบัญชี";
	}
	
	if (document.frm1.accBookName.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ชื่อสมุดบัญชี";
	}
	
	if (document.frm1.accBookType.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกประเภทสมุดบัญชี";
	}	
	if (document.frm1.accBookGroup.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ประเภทกลุ่ม ";
	}	
	if (document.frm1.accBookCustom.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ประเภทชนิด";
	}
	if (document.frm1.accBookUnit.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ประเภทย่อย";
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
</script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<form name="frm1" method="post" action="<?php echo $actionpage;?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">
<input type="hidden" name="accserial" value="<?php echo $accBookserial; ?>">

	<?php if($accBookserial !='') { ?>
	<center><h2>แก้ไขสมุดบัญชี</h2></center>
	<?php } else { ?>	
    <center><h2>เพิ่มสมุดบัญชี</h2></center>
	<?php } ?>
	
<center>
<table>
	<tr>
		<td align="right">บริษัทเจ้าของบัญชี : </td><td><input type="text" name="accBookComp" size="30" value="THCAP" readonly></td>
	</tr>
	<tr>
		<td align="right">เลขที่สมุดบัญชี : </td><td><input type="text" name="accBookID" size="30" value="<?php  if ($numrows==1){ echo $result["accBookID"];}else{ echo $accBookID;} ?>"><font color="#FF0000"> *</font></td>
	</tr>
	<tr>
		<td align="right">ชื่อสมุดบัญชี : </td><td><input type="text" name="accBookName" size="30" value="<?php if ($numrows==1){ echo $result["accBookName"];}else{ echo $accBookName;} ?>"><font color="#FF0000"> *</font></td>
	</tr>
	<tr>
		<td align="right">ประเภทสมุดบัญชี  : </td>
		<td>
			<select name="accBookType">
				<option value="">--เลือกประเภทสมุดบัญชี--</option>
				<option <?php if (($numrows==1) and ($result["accBookType"]=="1")){ echo "selected";} else {if($accBookType == "1"){echo "selected";} }?> value="1">ทรัพย์สิน</option>
				<option <?php if (($numrows==1) and ($result["accBookType"]=="2")){ echo "selected";} else {if($accBookType == "2"){echo "selected";} }?> value="2">หนี้สิน</option>
				<option <?php if (($numrows==1) and ($result["accBookType"]=="3")){ echo "selected";} else {if($accBookType == "3"){echo "selected";} }?> value="3">ทุน</option>
				<option <?php if (($numrows==1) and ($result["accBookType"]=="4")){ echo "selected";} else {if($accBookType == "4"){echo "selected";} }?> value="4">รายได้</option>
				<option <?php if (($numrows==1) and ($result["accBookType"]=="5")){ echo "selected";} else {if($accBookType == "5"){echo "selected";} }?> value="5">รายจ่าย</option>
			</select><font color="#FF0000"> *</font>
		</td>
	</tr>
	<tr>
		<td align="right">ประเภทกลุ่ม : </td><td><input type="text" name="accBookGroup" size="30" value="<?php if ($numrows==1){ echo $result["accBookGroup"];}else{ echo $accBookGroup; }?>" onkeypress="check_num(event);"><font color="#FF0000"> *(ระบุเป็นตัวเลข)</font></td>
	</tr>
	<tr>
		<td align="right">ประเภทชนิด : </td><td><input type="text" name="accBookCustom" size="30" value="<?php if ($numrows==1){ echo $result["accBookCustom"];}else{ echo $accBookCustom; }?>" onkeypress="check_num(event);"><font color="#FF0000"> *(ระบุเป็นตัวเลข)</font></td>
	</tr>
	<tr>
		<td align="right">ประเภทย่อย : </td><td><input type="text" name="accBookUnit" size="30" value="<?php if ($numrows==1){ echo $result["accBookUnit"];}else{ echo $accBookUnit;} ?>" onkeypress="check_num(event);"><font color="#FF0000"> *(ระบุเป็นตัวเลข)</font></td>
	</tr>
	<tr>
		<td align="right">รูปแบบการรับรู้รายได้  : </td>
		<td>
			<select name="accBookRealiseType">
				<option value="">ไม่ระบุ</option>
				<option <?php if (($numrows==1) and ($result["accBookRealiseType"]=="1")){ echo "selected";} else {if($accBookRealiseType == "1"){echo "selected";} }?> value="1">CASH BASIS</option>
				<option <?php if (($numrows==1) and ($result["accBookRealiseType"]=="2")){ echo "selected";} else {if($accBookRealiseType == "2"){echo "selected";} }?> value="2">CASH ACCRUAL</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td align="right">accBookNameFS : </td><td><input type="text" name="accBookNameFS" size="30" value="<?php if ($numrows==1){ echo $result["accBookNameFS"];}else{ echo $accBookNameFS; }?>"></td>
	</tr>
	<tr>
		<td align="right">สถานะบัญชี  : </td>
		<td>
			<select name="accBookStatus">
				<option <?php if (($numrows==1) and ($result["accBookStatus"]=="1")){ echo "selected";} else { if($accBookStatus == "1"){echo "selected";} }?> value="1">ใช้งาน</option>
				<option <?php if (($numrows==1) and ($result["accBookStatus"]=="0")){ echo "selected";} else { if($accBookStatus == "0"){echo "selected";} }?> value="0">ไม่ใช้งาน</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">accBookableFS : </td>
		<td>
			<select name="accBookableFS">			
				<option <?php if (($numrows==1) and ($result["accBookableFS"]=="1")){ echo "selected";} else { if($accBookableFS == "1"){echo "selected";}} ?> value="1">ใช่</option>
				<option <?php if (($numrows==1) and ($result["accBookableFS"]=="0")){ echo "selected";} else { if($accBookableFS == "0"){echo "selected";}}?> value="0">ไม่</option>
			</select>
		</td>
	</tr>
		<tr>
		<td align="right">แยกประเภท : </td>
		<td>
			<select name="accBookTypeFS">
				<option value="">ไม่ระบุ</option>			
				<option <?php if (($numrows==1) and ($result["accBookTypeFS"]=="1")){ echo "selected";} else { if($accBookTypeFS == "1"){echo "selected";}} ?> value="1">งบดุล</option>
				<option <?php if (($numrows==1) and ($result["accBookTypeFS"]=="2")){ echo "selected";} else { if($accBookTypeFS == "2"){echo "selected";}}?> value="2">งบกำไรขาดทุนเบ็ดเสร็จ</option>
			</select>
		</td>
	</tr>
</table>
<br><br>
<input type="submit" name="add" value="<?php  if ($numrows==1){ echo "บันทึกการแก้ไข";}else{ echo " ตกลง ";} ?>" onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="ยกเลิก/ปิด" onclick="javascript:window.close();">
</center>
</form>
</body>
</html>