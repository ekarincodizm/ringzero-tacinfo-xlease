<?php
include("../../config/config.php");
$autoid=pg_escape_string($_GET["autoid"]);
$last_autoid=pg_escape_string($_GET["last_autoid"]);
$costtype =pg_escape_string($_GET["costtype"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) อนุมัติประเภทต้นทุนเริ่มแรก</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
$(document).ready(function(){  
	$("#old").load("frm_appvtypeloan.php?autoid="+<?php echo $last_autoid;?>+"&show="+<?php echo 0;?> );
	$("#new").load("frm_appvtypeloan.php?autoid="+<?php echo $autoid;?>+"&show="+<?php echo 1;?> );
	});
</script>
<?php 

if($autoid!='0'){//0 คือการ บันทึก  ถ้า เป็นเลขอื่น คือ  autoid ที่จะแก้ไข
	$sql = pg_query("select *  from \"thcap_cost_type_temp\" where \"autoid\" ='$autoid'");
	$result = pg_fetch_array($sql);
	$costname_edit=$result["costname"];
	$costtype_edit=$result["costtype"];
	$typeloansuse_edit=$result["typeloansuse"];
	$note_edit=$result["note"];
}
$rest = substr($typeloansuse_edit,1,strlen($typeloansuse_edit)-2); 
$typeloan = explode(",",$rest );
$i=0;	
?>
<center><h2>(THCAP) อนุมัติประเภทต้นทุนสัญญา</h2></center>

<table  align="center" width="90%">
<tr>
	<td align="left">
	<fieldset><legend><font color="black"><b>ประเภทต้นทุนสัญญา ที่เคยทำการอนุมัติล่าสุด</font></b></font></legend>
		<div id="old" name="old">	
		<div>
	</fieldset>	
	</td>
	<td align="right" valign="top" >
	<fieldset><legend><font color="black"><b>ประเภทต้นทุนสัญญา ที่รออนุมัติ</font></b></font></legend>
		<div id="new" name="new">
		<div>
	</fieldset>	
	</td>
</tr>
<tr></tr>
<tr>
	<td align="center" colspan="2">
	<!--ส่งแบบ FORM ใน HTML-->
	<form name="my" method="post" action="process_appvtypeloan.php">
		<input type="submit" name="appv" value="อนุมัติ" >
		<input type="submit" name="unappv" value="ไม่อนุมัติ" ></td>
		<input type="hidden" name="costtype" id="costtype" value="<?php echo $costtype_edit;?>">
		<input type="hidden" name="autoid" id="autoid" value="<?php echo $autoid;?>">
	</form>
	</tr>
<tr>
	<td align="center" colspan="2">
		<input type="button" value="ปิด" onclick="window.close();">
	</td>
</tr>
</table>
</html>