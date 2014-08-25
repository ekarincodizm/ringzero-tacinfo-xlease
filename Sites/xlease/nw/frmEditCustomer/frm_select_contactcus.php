<?php
session_start();
include("../../config/config.php");
$edt_idno=pg_escape_string($_GET["fIDNO"]);
$stsup = pg_escape_string($_GET["stsup"]);
$CusState = pg_escape_string($_GET["CusState"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->

<title>AV. leasing co.,ltd</title>
     
    <style type="text/css">

    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
    }
	</style>
    
<script type="text/javascript">               
$(document).ready(function(){
    $("#txtnames").autocomplete({
        source: "cus_listdata.php",
        minLength:1
    });
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
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
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;"></div>
	<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">เปลี่ยนผู้เช่าซื้อ/ผู้ค้ำ</div>
		<div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
		<div class="style5" style="width:auto; height:60px; padding-left:10px;"><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $edt_idno; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u>เลขที่สัญญา : <?php echo $edt_idno;?></u></font></span></div>
  
		<div class="style5" style="width:auto; height:100px; padding-left:10px;">
		<form name="frm_input" method="post" action="save_contactcus.php" onsubmit="return validate(this);">
			<input type="hidden" name="idno" id="idno" value="<?php echo $edt_idno; ?>" />
			<input type="hidden" name="CusState" id="CusState" value="<?php echo $CusState; ?>" />
			<input type="hidden" name="stsup" id="stsup" value="<?php echo $stsup; ?>" />

			<table width="100%" border="0" cellpadding="1" cellspacing="1">
			<tr>
				<td colspan="2" style="background-color:#FFFFCC;"><?php if($CusState=='0'){echo "ข้อมูลผู้เช่าซื้อ";}else{ echo "ข้อมูลผู้ค้ำประกัน";}?></td>
			</tr>
			<tr>
				<td>ค้นหาจากชื่อ,นามสกุล</td>
				<td>
					<input type="text" size="50" id="txtnames" name="txtnames" style="height:20;"/>
					<input type="submit" name="newcus" id="newcus" value="บันทึก"/>
					<input name="button" type="button" onclick="window.location='frm_edit_cus.php?idnog=<?php echo $edt_idno;?>'" value="BACK" tabindex="23" />
				</td>
			</tr>
			</table>
		</form>
		</div>
	</div>
</div>
</body>
</html>
