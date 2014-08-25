<?php
include("../../config/config.php");
$fp_appID = $_POST["idapp"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="background-color:#DDDDDD;">


<table width="500" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="#CDC9C9">	
<tr><td>
<div style="margin-top:15px" align="center"><h3>ปฎิเสธการอนุมัติ</h3></div>
<form id="myform" name="myform" method="post" action="Process_approve.php">
<table width="500"  cellspacing="0" cellpadding="0"  align="center">		
<?php 
for($i=0;$i<sizeof($fp_appID);$i++){
	$sql = pg_query("SELECT recmenuid, id_menu, id_user, revision_num, recdetail, rec_date, 
       appuser, app_date, appstatus, recheader, reason_notapp
  FROM f_menu_manual where recmenuid = '$fp_appID[$i]'");
	$result = pg_fetch_array($sql);	
	
?>	

<tr>	
		<td  colspan="2">
			รหัส : <?php echo $result['recmenuid']; ?>
		</td>
		<td  colspan="2">
			เมนู : <?php echo $result['id_menu']; ?>
		</td>
		<td  colspan="2">
			ชื่อเรื่อง : <?php echo $result['recheader']; ?>
		</td>
</tr>	
<input type="hidden" value="<?php echo $fp_appID[$i] ?>" name="idapp[]">	
<?php } ?>
</table>
<table width="500" cellspacing="0" cellpadding="0"  align="center" bgcolor="#FFF68F">	
<tr>
	<td  colspan="2">เหตุผลที่ไม่อนุมัติ...</td>
</tr>
<tr>
	<td align="center" colspan="2">
		<textarea cols="70" rows="8" name="reasonnotapp"></textarea>
	</td>	
</tr>
<tr>
	<td><br></td>
</tr>
<tr>
	<td align="center"><input type="submit" value=" ตกลง " style="width:100px;height:50px"></td>
	<td align="center"><input type="button" value=" กลับ " onclick="parent.location='frm_approve.php'" style="width:100px;height:50px"></td>
	
	<input type="hidden" value="not" name="appstate">
	
	
</tr>
</table>
</td>
</table>
</body>
</form>
</html>