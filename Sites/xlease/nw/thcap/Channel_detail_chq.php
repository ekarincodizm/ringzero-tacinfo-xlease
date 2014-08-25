<?php
session_start();
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ
$revChqID=pg_escape_string($_GET['revChqID']); //รหัสเช็ค
$tranid=pg_escape_string($_GET['tranid']); //รหัสรายการเงินโอน
$show=pg_escape_string($_GET['show']);//ถ้าเท่ากับ 1 แสดงว่าม่จาก เมนู (THCAP) รายงานเช็ค 
$chqKeeperID=pg_escape_string($_GET['chqKeeperID']); // แสดงว่าม่จาก เมนู (THCAP) รายงานเช็ค 

//ตรวจสอบว่ามีเลขที่เช็คใบนี้จริงหรือไม่
$qrychkrec=pg_query("select * from finance.thcap_receive_cheque where \"revChqID\"='$revChqID'");
if(pg_num_rows($qrychkrec)==0){ 
	echo "<div align=center><h2>---ไม่พบเลขที่เช็ค---</h2></div>";
	exit();
}
//################################เตรียมข้อมูลสำหรับตรวจสอบว่าสามารถขอยกเลิกใบเสร็จภายในหน้านี้ได้หรือไม่


$qrydata=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqID\"='$revChqID'");
$numrows = pg_num_rows($qrydata);
//ตรวจสอบกรณีที่ เลขที่เช็คมีรายการมากกว่า 1 รายการ
if($numrows>1){
	if(($chqKeeperID=='')and ($show=='')){
		$qrydata_chqKeeperID=pg_query("select \"chqKeeperID\" from finance.\"thcap_receive_transfer\" where \"revTranID\"='$tranid'");
		list($chqKeeperID) = pg_fetch_array($qrydata_chqKeeperID,0);
	}else if(($chqKeeperID !='')and ($show=='1')){//แสดงว่าม่จาก เมนู (THCAP) รายงานเช็ค 
		$chqKeeperID=$chqKeeperID;
	}
	else{$chqKeeperID='';}	
	$qrydata=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqID\"='$revChqID' AND \"chqKeeperID\" ='$chqKeeperID'");	
}
if($resdata=pg_fetch_array($qrydata)){
	$contractID=$resdata["revChqToCCID"]; //เลขที่สัญญา
	$revChqDate=$resdata["revChqDate"]; //วันที่รับเช็ค
	$bankChqNo=$resdata["bankChqNo"]; //เลขที่เช็ค
	$bankChqDate=$resdata["bankChqDate"]; //วันที่สั่งจ่าย/วันที่บนเช็ค
	$bankOutID=$resdata["bankOutID"]; //รหัสธนาคารที่ออกเช็ค
	$BankName=$resdata["BankName"]; //ชื่อธนาคารที่ออกเช็ค
	$bankOutBranch=$resdata["bankOutBranch"]; //สาขาที่ออกเช็ค
	$bankChqAmt=$resdata["bankChqAmt"]; //จำนวนเงิน
	$result=$resdata["result"]; //หมายเหตุ นำเช็คเข้าธนาคาร
	$namebank=$resdata["namebank"]; //เข้าบัญชี
	$givetakername=$resdata["givetakername"]; //พนักงานที่นำเช็คไปเข้า
	$giveTakerDate=$resdata["giveTakerDate"]; //วันที่มอบเช็คให้พนักงาน
	$namestatus=$resdata["namestatus"]; //สถานะเช็ค
	$isPostChq=$resdata["isPostChq"]; //เช็คชำระล่วงหน้า 0 คือไม่ใช่ 1 คือใช่
	$isInsurChq=$resdata["isInsurChq"]; //0 = ไม่ใช่เช็คค้ำประกันหนี้ 1 = เป็นเช็คค้ำประกันหนี้
	$receiverFullName=$resdata["receiverFullName"]; //ผู้รับเช็ค
	$receiverStamp=$resdata["receiverStamp"]; //วันเวลาที่รับเช็ค
	
	
	if($isPostChq==1){
		$txtchq="(เป็นเช็คชำระล่วงหน้า)";
	}else if($isInsurChq==1){
		$txtchq="(เป็นเช็คค้ำประกันหนี้)";
	}

}
//ตรวจสอบว่าเงินโอนถูกใช้หรือยัง  
$query=pg_query("select \"revTranStatus\" from \"finance\".\"thcap_receive_transfer\" WHERE \"revChqID\" = '$revChqID'");
list($revTranStatus) = pg_fetch_array($query);
//ตรวจสอบ emplevel <=1
$qrylevel=pg_query("select \"ta_get_user_emplevel\"('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);
//ตรวจสอบว่าเป็นรายการ ล่าสุดหรือไม่
$query_maxrevTranID=pg_query("SELECT max(\"revTranID\") from finance.\"thcap_receive_transfer\" where \"revChqID\"='$revChqID'");
list($maxrevTranID)=pg_fetch_array($query_maxrevTranID);
//ตรวจสอบว่าเช็คนั้นต้องยังไม่ผ่าน  revChqStatus=1 ผลเช็คผ่านแล้ว
$query_revChqStatus=pg_query("SELECT \"revChqStatus\" from finance.\"thcap_receive_cheque\" where \"revChqID\"='$revChqID'");
list($ChqStatus)=pg_fetch_array($query_revChqStatus);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<title>รายละเอียดเช็ค</title>
</head>
<body>

<div style="text-align:center"><h2>รายละเอียดเช็ค</h2></div>
<table width="80%" cellSpacing="1" cellPadding="1" bgcolor="#C1CDC1" align="center">
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" width="30%" bgcolor="#EEEEE0"><b>เลขที่เช็ค :</b></td>
		<td>&nbsp;<font color="red"> <?php echo $bankChqNo; ?></font> <?php echo $txtchq;?></b></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>เลขที่สัญญา :</b></td>
		<td>&nbsp;<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="blue"><u><?php echo $contractID; ?></u></b></font></span></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันที่สั่งจ่าย :</b></td>
		<td>&nbsp;<?php echo $bankChqDate; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันที่รับเช็ค :</b></td>
		<td>&nbsp;<?php echo $revChqDate; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>ธนาคารที่ออกเช็ค :</b></td>
		<td>&nbsp;<?php echo $BankName; ?> สาขา<?php echo $bankOutBranch; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>จำนวนเงิน :</b></td>
		<td> <?php echo number_format($bankChqAmt,2); ?> บาท</td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>พนักงานที่นำเช็คไปเข้า :</b></td>
		<td>&nbsp;<?php echo $givetakername; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันที่มอบเช็คให้พนักงาน :</b></td>
		<td>&nbsp;<?php echo $giveTakerDate; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>เข้าบัญชี :</b></td>
		<td>&nbsp;<?php echo $namebank; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>สถานะเช็ค :</b></td>
		<td>&nbsp;<?php echo $namestatus; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>ผู้รับเช็ค :</b></td>
		<td>&nbsp;<?php echo $receiverFullName; ?>&nbsp;<b>วันที่</b> &nbsp;<?php echo $receiverStamp; ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<fieldset><legend><b>หมายเหตุนำเข้าธนาคาร</b></legend>
				<textarea cols="60" rows="4" readonly><?php echo $result;?></textarea>
			</fieldset>
		</td>
	</tr>
</table>

<div style="text-align:center;padding:20px">
<?php if(($revTranStatus !='3') and ($emplevel<=1) and ($maxrevTranID==$tranid) and ($ChqStatus !='1') and ($show !='1')){ ?>
<form name="my" method="post" action="process_reset_data.php">
	<input type="text" name="tranid" id="tranid" value='<?php echo $tranid; ?>' hidden >
	<input type="submit"  value="ทำรายการกลับไปยัง(THCAP) ยืนยันรายการเงินโอน(การเงิน)">
</form>
<?php } else if(($ChqStatus =='1') and ($revTranStatus !='3')and ($show !='1')){ ?>
<form name="my" method="post" action="process_reset_data.php">
	<input type="text" name="tranid" id="tranid" value='<?php echo $tranid; ?>' hidden >
	<input type="submit"  value="ทำรายการกลับไปยัง(THCAP) ยืนยันรายการเงินโอน(การเงิน)">
</form>
<?php } ?>
<input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>