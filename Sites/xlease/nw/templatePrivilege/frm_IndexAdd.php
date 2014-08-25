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

$createDate = nowDateTime();//ดึงข้อมูลวันเวลาจาก server
$v_tempID=$_POST["tempID"];
$v_tempName=$_POST["tempName"];
$v_tempStatus=$_POST["tempStatus"];
$user_id = $_SESSION["av_iduser"];
$sent=$_GET["sent"];
$method=$_GET["method"];

pg_query("BEGIN WORK");
$status = 0;

if($v_tempID == "" and $v_tempName != ""){
	$in_sql="insert into \"nw_template\" (\"tempName\",\"tempStatus\",\"createDate\",\"id_user\") values ('$v_tempName','$v_tempStatus','$createDate','$id_user')";
	if($result=pg_query($in_sql)){
		$status1 ="Insert ข้อมูลแล้ว";
	}else{
		$status1 ="error Insert nw_template ".$in_sql;
		$status=$status+1;
	}

	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่ม Template สิทธิ', '$createDate')");
		//ACTIONLOG---
		pg_query("COMMIT");
	}else{
		pg_query("ROLLBACK");
	}
}else if($v_tempID != ""){
	$up_sql="update \"nw_template\" set \"tempName\"='$v_tempName',
									\"tempStatus\"='$v_tempStatus'
									where \"tempID\"='$v_tempID'";
	if($result=pg_query($up_sql)){
		$status1 ="Update ข้อมูลแล้ว";
	}else{
		$status1 ="error Update nw_template ".$up_sql;
		$status=$status+1;
	}

	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) แก้ไข Template สิทธิ', '$createDate')");
		//ACTIONLOG---
		pg_query("COMMIT");
	}else{
		pg_query("ROLLBACK");
	}
}

if($sent==1 || $method=="edit"){
	$v_tempID=$_GET["tempID"];
	$f_tempName=$_GET["f_tempName"];
}

if($v_tempID == "" and $v_tempName != ""){
	$query = pg_query("select * from \"nw_template\" order by \"tempID\" DESC limit (1)");
	if($resultq = pg_fetch_array($query)){
		$f_tempID = $resultq["tempID"];
		$f_tempName = $resultq["tempName"];
		$f_tempStatus = $resultq["tempStatus"];
	}
}else if($v_tempID != ""){
	$query = pg_query("select * from \"nw_template\" where \"tempID\"='$v_tempID'");
	if($resultq = pg_fetch_array($query)){
		$f_tempID = $resultq["tempID"];
		$f_tempName = $resultq["tempName"];
		$f_tempStatus = $resultq["tempStatus"];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>จัดการ Template</title>
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

if (document.form1.tempName.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกชื่อ Template";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
	document.form1.tempName.focus();
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
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">จัดการ Template<hr /></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<form method="post" name="form1" action="frm_IndexAdd.php">
			<table width="779" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
			<tr style="background-color:#D0DCA0;" align="left">
				<th width="400" height="25">ชื่อ Template</th>
				<th width="200">สถานะการใช้งาน</th>
			</tr>
			<tr style="background-color:#B7B7B7;">
				<td><input type="text" name="tempName" value="<?php echo $f_tempName;?>" size="75"></td>
				<td>
					<select name="tempStatus" id="tempStatus">
						<option value="TRUE" <?php if($f_tempStatus=="t"){ echo "selected";}?>>ใช้งาน</option>
						<option value="FALSE" <?php if($f_tempStatus=="f"){ echo "selected";}?>>ไม่ใช้งาน</option>
					</select>	
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="hidden" name="tempID" value="<?php echo $f_tempID;?>"><input type="submit" value="SAVE" onclick="return validate()"><br><?php echo $status1;?></td>
			</tr>
			</table>
			</form>

			<form method="post" action="update_menu_user.php" >
			<input type="hidden" name="s_id" value="<?php echo $piduser; ?>"  />
			<table width="778" border="0" style="background-color:#D5EAC8;">
			<tr style="background-color:#A8D38D;">
				<td colspan="2" height="25"><b>เมนูที่ใช้งาน</b></td>
				<td><b>สถานะ</b></td>
			</tr>
				<?php
				
				if($f_tempID !=""){
				
				$qry_menu=pg_query("select a.\"id_menu\",b.\"name_menu\",b.\"status_menu\" from \"nw_templateDetail\" a 
				LEFT OUTER JOIN f_menu b on a.id_menu=b.id_menu
				where \"tempID\"='$f_tempID' order by b.name_menu ");
				$numrow_menu=pg_num_rows($qry_menu);
				
				while($resmenu=pg_fetch_array($qry_menu)){
					$stas=$resmenu['status_menu'];
					if($stas=='1'){
						$txtstas="ใช้งาน";
					}else{
						$txtstas="ระงับใช้งาน";
					}
					?>
					<tr>    
						<td width="85" height="25"><?php echo $resmenu["id_menu"]; ?></td>
						<td width="545"><?php echo $resmenu["name_menu"]; ?></td>
						<td width="126"><?php echo $txtstas;?></td>
					</tr>
				<?php
				}
				}
				if($numrow_menu ==0 || $f_tempID==""){
					echo "<tr height=50><td align=center colspan=3><b>ไม่มีรายการ</b></td></tr>";
				}
				?>
			<tr style="background-color:#A8D38D;">
				<td width="85"><input type="button" value="เพิ่มหรือลบเมนู" onclick="parent.location='frm_addTemplate.php?tempID=<?php echo $f_tempID ;?>&tempName=<?php echo $f_tempName;?>'" <?php if($f_tempID==""){ echo "disabled";}?>></td>
				<td width="545">&nbsp;</td>
				<td width="126"><input type="button" value="BACK" onclick="window.location='frm_Index.php'" /></td>
			</tr>
			</table>
			</form>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>
