<?php
session_start();
include("../../config/config.php");
$creditID=$_GET["creditID"];

$query=pg_query("select * from \"nw_credit\" where \"creditID\" = '$creditID'");
$result=pg_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขประเภทสินเชื่อ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
<script>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.creditID.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกรหัสประเภทสินเชื่อ";
}
if (document.form1.creditType.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกประเภทสินเชื่ีอ";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}

</script>
</head>
<body>
<form name="form1" method="post" action="process_credit.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="header"><h2>แก้ไขประเภทสินเชื่อ</h2></div>
		<div class="wrapper">
			<fieldset><legend><B>แก้ไขรายละเอียด</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right"><b>รหัสประเภทสินเชื่อ</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="creditID" value="<?php echo $result["creditID"];?>" size="30"></td>
				</tr>
				<tr align="left">
					<td align="right"><b>ประเภทสินเชื่อ</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="creditType" value="<?php echo $result["creditType"];?>" size="60"></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>คำอธิบายรายละเอียด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td class="text_gray"><textarea name="creditDetail" cols="50" rows="4"><?php echo $result["creditDetail"];?></textarea></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b></b></td>
					<td width="10" align="center"valign="top"></td>
					<td><input type="checkbox" name="creditReserved" value="1" <?php if($result["creditReserved"]=="1") echo "checked";?>> มีค่าแนะนำ</td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b></b></td>
					<td width="10" align="center"valign="top"></td>
					<td><input type="checkbox" name="oldIDNO" value="1" <?php if($result["oldidno"]=="1") echo "checked";?>> มีเลขที่สัญญาเก่า</td>
				</tr>
				<tr align="left">
					<td align="right"><b>สถานะการเปิดใช้</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<select name="statusUse">
							<option value="TRUE" <?php if($result["statusUse"]=="t"){ echo "selected";}?>>เปิดใช้งาน</option>
							<option value="FALSE" <?php if($result["statusUse"]=="f"){ echo "selected";}?>>ไม่เปิดใช้งาน</option>
						</select>
					</td>
				</tr>
				<tr align="center">
				  <td colspan=3 height="50"><input type="hidden" name="method" value="edit"><input type="hidden" name="creditID2" value="<?php echo $creditID;?>"><input name="submit" type="submit" value="บันทึก" onclick="return validate()"><input name="button" type="button" onclick="window.location='frm_Index2.php'" value=" ย้อนกลับ " /></td>
				</tr>
				</table>
			</fieldset> 
		</div>
    </td>
</tr>
</table>         
</form>
</body>
</html>