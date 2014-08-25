<?php
include("../../config/config.php");

$cancelID=$_GET['cancelID'];
$show=$_GET['show'];//มาจาก (THCAP) ตรวจสอบรายการรับชำระเงิน
	//หาเหตุผลโดยการนำเลขที่ใบเสร็จไปค้นในตาราง  thcap_temp_receipt_cancel
	$qryresult=pg_query("SELECT \"receiptID\",\"approveUser\",\"approveDate\",\"approveStatus\",result FROM thcap_temp_receipt_cancel where \"cancelID\"='$cancelID' ");
	$resresult=pg_fetch_array($qryresult);
	list($receiptID,$appvuser,$appvdate,$appvestatus,$result)=$resresult;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title>รายละเอียดการยกเลิก</title>
</head>
<script type="text/javascript"> 
if(document.my1.resultcancel.value == '')
{ alert('กรุณาป้อนหมายเหตุ');return false;}
else{ return true;}
</script>
<body>
<form name="my1" method="post" action="process_notecancel.php">
<input type="hidden" name="cancelID" id="cancelID" value="<?php echo $cancelID; ?>">
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#EAF9FF" align="center">
<tr>
    <td align="center" colspan="3"><h2>- เหตุผลยกเลิกใบเสร็จ -</h2></td>
</tr>
<tr><td align="right"><span onclick="window.close();" style="cursor:pointer;"><u>X ปิดหน้านี้</u></span></td></tr>
<tr>
    <td height="25"><b>ใบเสร็จที่ขอยกเลิก: <font color="red"><?php echo $receiptID; ?></font></b></td>
</tr>
<?php if(($show=='1')||($show=='2')){
	//ผู้อนุมัติ
	$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$appvuser' ");
	$nameuser = pg_fetch_array($query_fullname);
	$fullnameappv=$nameuser["fullname"];
	//ผลการอนุมัติ
	if($appvestatus=='1'){
		$appvestatus="อนุมัติ";
	}
	else if($appvestatus=='0'){
		$appvestatus="ไม่อนุมัติ";
	}
	else{
		$appvestatus="ผิดพลาด";
	}
?>
<table width="100%" bgcolor="#EAF9FF">
<tr>
    <td height="25" width="102" align="Right"><b>ผู้อนุมัติ:</td><td><?php echo $fullnameappv; ?></b></td>
</tr>
<tr>
    <td height="25" width="102" align="Right"><b>วันที่อนุมัติ:</td><td><?php echo $appvdate; ?></b></td>
</tr>
<tr>
    <td height="25" width="102" align="Right"><b>ผลการอนุมัติ:</td><td><font color="red"><?php echo $appvestatus; ?></font></b></td>
</tr>
</table>
<?php }?>
<tr bgcolor="#F5F5F5">
	<?php if($show=="1"){?>
		<td colspan="5"><b>::เหตุผลที่ยกเลิก::</b><br>
		<textarea name="resultcancel" id="resultcancel" cols="50" rows="5"></textarea></td>
	<?php } else { ?>
		<td colspan="5"><b>::เหตุผลที่ยกเลิก::</b><br><textarea name="resultcancel" id="resultcancel" cols="50" rows="5" readonly="true"><?php echo $result;?></textarea></td>
	<?php }?>
</tr>
<?php if($show=="1"){?>
<table>
<br>
<tr align="center">
	<input type="submit" onclick="return chk()" value="บันทึก">
</tr>
</table>
<?php }?>
</table>
</form>
</body>
</html>