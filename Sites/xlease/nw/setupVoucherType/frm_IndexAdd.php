<?php
session_start();
include("../../config/config.php");
$id_user=$_SESSION["av_iduser"];
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
$method=$_GET["method"];
$showtext=$_GET["showtext"];
if($showtext=="1"){
	$showtext="รหัสรายการซ้ำ กรุณากรอกข้อมูลใหม่อีกครั้ง!!";
}

if($method=="edit"){
	$vtid=$_GET["vtid"];
	$query=pg_query("select * from Account.\"nw_voucher_type\" where \"vtid\"='$vtid'");
	if($res=pg_fetch_array($query)){
		$vtid=$res["vtid"];
		$voucher_type_name=$res["voucher_type_name"];
		$voucher_type_desc=$res["voucher_type_desc"];
		$voucher_type_status=$res["voucher_type_status"];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php if($method=="edit"){ echo "แก้ไข";}else{ echo "เพิ่ม";}?>ประเภทค่าใช้จ่าย</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>  
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
</style>
<script language=javascript>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.vtid.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกรหัสรายการ";
}
if(document.form1.voucher_type_name.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอกประเภทค่าใช้จ่าย";
}
if(document.form1.voucher_type_desc.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอกคำอธิบาย";
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
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;">
		<span class="style2" style="padding-left:10px; height:60px; width:800px; ">
		<div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div>
		<div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div>
	</div>
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><?php if($method=="edit"){ echo "แก้ไข";}else{ echo "เพิ่ม";}?>ประเภทค่าใช้จ่าย<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<form method="post" name="form1" action="process_voucher.php">
			<table width="600" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">
			<tr><th colspan="2"><?php echo $showtext;?></th></tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th width="50%" height="25" align="right"><br><br>รหัสรายการ :</th>
				<td><br><br><input type="text" name="vtid" value="<?php echo $vtid?>"></td>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right">ประเภทค่าใช้จ่าย :</th>
				<td><input type="text" name="voucher_type_name" size="45" value="<?php echo $voucher_type_name?>"></td>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right" valign="top">คำอธิบาย :</th>
				<td><textarea cols="35" rows="3" name="voucher_type_desc"><?php echo $voucher_type_desc?></textarea></td>
			</tr>
			<tr style="background-color:#D0DCA0;">
				<th align="right" valign="top">สถานะการใช้งาน :</th>
				<td>
					<select name="voucher_type_status">
						<option value="TRUE" <?php if($voucher_type_status=="t"){ echo "selected";}?>>ใช้งาน</option>
						<option value="FALSE" <?php if($voucher_type_status=="f"){ echo "selected";}?>>ระงับการใช้งาน</option>
					</select>
					<br><br>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center" height="50"><?php if($method=="edit"){?><input type="hidden" name="method" value="edit"><input type="hidden" name="vtidold" value="<?php echo $vtid?>"><?php }else{?><input type="hidden" name="method" value="add"><?php }?><input type="submit" value="SAVE" onclick="return validate()"><input type="button" value="BACK" onclick="window.location='frm_Index.php'" /></td>
			</tr>
			</table>
			</form>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>
