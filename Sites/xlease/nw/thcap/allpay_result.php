<?php
session_start();
include("../../config/config.php");
$receiptID = pg_escape_string($_GET['receiptID']);
$method = pg_escape_string($_GET['method']);

if($method=="add"){
	$remark="";
	$readonly="";
}else{
	$typesql = pg_query("select \"receiptRemark\"  from thcap_v_receipt_details
	where \"receiptID\" = '$receiptID' ");
	list($remark)=pg_fetch_array($typesql);
	$readonly="readonly";
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<title>รายละเอียดหมายเหตุ</title>
<script language="javascript">
$(document).ready(function(){
	$('#btn1').click(function(){
		if($('#remark').val()==""){
			alert("กรุณาระบุหมายเหตุ");
			$('#remark').focus();
		}else{
			$.post('process_resultreceiptID.php',{
				receiptID: '<?php echo $receiptID; ?>',
				remark: $('#remark').val(),
			},
			function(data){
				if(data==1){
					alert("มีการบันทึกหมายเหตุไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ");
					opener.location.reload(true);
					self.close();
				}else if(data==2){
					alert("บันทึกเรียบร้อยแล้ว");
					opener.location.reload(true);
					self.close();
				}else{
					alert(data);
					alert("ไม่สามารถบันทึกได้ กรุณาตรวจสอบ");
				}
			});
		}
	});
});
</script>
</head>
<body>
<div style="text-align:center"><h2>หมายเหตุใบเสร็จ <font color="red"><?php echo $receiptID; ?></font></h2></div>
<table width="100%" cellSpacing="1" cellPadding="3"frame="box" bgcolor="#E8E8E8" align="center">
<tr>
    <td height="25" colspan="4" align="center">
		<textarea cols="40" rows="5" <?php echo $readonly; ?> id="remark"><?php echo $remark?></textarea>
		<font color="red"><b>*</b></font>
	</td>
</tr>

</table><br>
<div style="text-align:center;">
	<?php
	if($method=="add"){
		echo "<input type=\"button\" id=\"btn1\" value=\"บันทึก\">";
	}
	echo "<input type=\"button\" onclick=\"window.close();\" value=\"ปิดหน้านี้\">";
	?>
</div>
</body>
</html>