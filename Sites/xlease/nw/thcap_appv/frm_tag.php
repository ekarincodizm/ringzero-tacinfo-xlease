<?php
include("../../config/config.php");	
	$voucherID = pg_escape_string($_GET['voucherID']);
	$rootpath = redirect($_SERVER['PHP_SELF'],'');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เพิ่ม TAG ของใบสำคัญ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
     <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<div style="width:80%;margin-left:auto;margin-right:auto;">

<div style="margin-top:10px;" align="center"><font color="red"><b>
กรุณาทำการเชื่อม (tag) สัญญาเพื่อให้ทราบว่าใบสำคัญใบนี้ เกี่ยวข้องกับสัญญาใดบ้าง เพื่อให้สามารถตรวจสอบได้ในอนาคตจากเมนู "(THCAP) ตารางแสดงการผ่อนชำระ"</b></font></div>
<?php if($voucherID!=""){ ?>
<form name="frm" method="post" action="<?php echo $rootpath."nw/thcap_appv/process_addTag.php"?>">
	<input type="hidden" name="voucherID" id="voucherID" value="<?php echo $voucherID;?>" size="50"><br>
<table width="70%" align="center">	
	<tr>
		<td align="right"><b>เลขที่ voucher:</b></td>
		<td><input type="text" name="s_voucher" id="s_voucher" size="50" value="<?php echo $voucherID;?>" style="border: none" readonly></td>	
	</tr>
	<tr>
		<td align="right"><b>เลขที่สัญญา:</b></td>
		<td><input type="text" name="s_conid" id="s_conid" size="50"></td>	
	</tr>
	<tr>
		<td align="right"></td>
		<td>
		<input type="submit" value="บันทึก" onclick="return chk_data()"/>
		<input type="button" value="  Close  " onclick="javascript:window.close();">		
		</td>	
	</tr>
</table>
</form>
<?php } else { ?>
<center><b>ไม่พบข้อมูล</b><center>
<?php } ?>
</div>
</body>
<script>
$("#s_conid").autocomplete({
        source: "s_idno.php",
        minLength:1
});
function chk_data(){
	if(document.getElementById('s_conid').value == ""){
		alert("กรุณาป้อนเลขที่สัญญา");
		return false;
	}
	else{
		return true;
	}
}
</script>
</html>