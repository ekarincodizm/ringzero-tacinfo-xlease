<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

pg_query("BEGIN WORK");
$status=0;

$user_id = $_SESSION["av_iduser"];
$add_date = nowDateTime();//วันเวลาปัจจุบันจาก server
$auto_id=pg_escape_string($_GET["auto_id"]);

//ดึงข้อมูล
$qry_main = pg_query("SELECT \"contractID\",\"voucherDate\",\"voucherTime\",\"fullname\",\"voucherRemark\",\"fromChannelDetails\",\"voucherPurpose\",
\"byChannel\",\"returnChqNo\",\"returnChqDate\",\"returnTranToCus\",\"returnTranToBank\",\"returnTranToAccNo\",\"returnChqCus\",\"ChannelAmt\" 
FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE  \"auto_id\" = '$auto_id'");
list($contractID,$voucherDate,$voucherTime,$fullname,$voucherRemark,$fromChannelDetails,$voucherPurpose,
$byChannel,$returnChqNo,$returnChqDate,$returnTranToCus,$returnTranToBank,$returnTranToAccNo,$returnChqCus,$ChannelAmt) = pg_fetch_array($qry_main);

//วันที่สัญญามีผล
$qry_conStartDate = pg_query("select \"conStartDate\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
$conStartDate = pg_fetch_result($qry_conStartDate,0);
if($conStartDate==''){$conStartDate='-';}
//จุดประสงค์
if($voucherPurpose !=""){			
	$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
	$purpose_name = pg_fetch_result($qry_purpose_name,0);
	$purpose_name =$voucherPurpose.' - '.$purpose_name;
}else{
	$purpose_name="";
}
//ช่องทางการจ่าย $byChannel
if($byChannel !=''){	
	$qry_bank_name = pg_query("select  \"BName\",\"BAccount\" from  \"BankInt\" where \"BID\" = '$byChannel' ");	
	list($bank_name,$bank_account) = pg_fetch_array($qry_bank_name);
}


if($returnTranToCus !='' and $returnTranToBank !='' and $returnTranToAccNo !=''){

	//โอน
	$channel='โอน';
	//หา ชื่อ bank
	$qry_bankName = pg_query("select  \"bankName\" from  \"BankProfile\" where \"bankID\" = '$returnTranToBank' ");	
	list($bankName) = pg_fetch_array($qry_bankName);
	$returnChqNo ='-';
	$returnChqDate='-';
	$returnChqCus ='-';
	$CusName_Chq='-';
	//หาชื่อ ลูกค้า
	$qry_CusName = pg_query("select \"full_name\" from \"VSearchCusCorp\"  where \"CusID\"='$returnTranToCus'");	
	list($CusName_Tran) = pg_fetch_array($qry_CusName);
	if($CusName_Tran==''){$CusName_Tran=$returnTranToCus;}

	// -------------------------------------------------------------
	// ค่าที่จะ Pass ไปยังหน้า Process สำหรับกรณีนี้
	// -------------------------------------------------------------
	// กำหนดประเภทช่องทางการจ่ายเงินออก 0-ไม่ใช่เงินโอนหรือเช็ค 1-เงินโอน 2-เช็ค
	$bywhat = 1;
	$payerchqno_or_payeebankno = $returnTranToAccNo;
	$payeebankname = $returnTranToBank;
}
else if($returnChqNo !='' and $returnChqDate !='' and $returnChqCus !=''){

	$channel='เช็ค';
	//เช็ค
	$returnTranToCus ='-';
	$returnTranToBank ='-';
	$returnTranToAccNo ='-';
	$CusName_Tran='-';
	$bankName='-';
	//หาชื่อ ลูกค้า
	$qry_CusName = pg_query("select \"full_name\" from \"VSearchCusCorp\"  where \"CusID\"='$returnChqCus'");	
	list($CusName_Chq) = pg_fetch_array($qry_CusName);
	if($CusName_Chq==''){$CusName_Chq=$returnChqCus;}
	
	// -------------------------------------------------------------
	// ค่าที่จะ Pass ไปยังหน้า Process สำหรับกรณีนี้
	// -------------------------------------------------------------
	// กำหนดประเภทช่องทางการจ่ายเงินออก 0-ไม่ใช่เงินโอนหรือเช็ค 1-เงินโอน 2-เช็ค
	$bywhat = 2;
	$payerchqno_or_payeebankno = $returnChqNo;
	$payeebankname = $returnChqCus;
	
}else if($returnTranToCus =='' and $returnTranToBank =='' and $returnTranToAccNo =='' and $returnChqNo =='' and $returnChqDate =='' and $returnChqCus ==''){

	$returnChqNo ='-';
	$returnChqDate='-';
	$returnChqCus ='-';
	$CusName_Chq='-';
	$returnTranToCus ='-';
	$returnTranToBank ='-';
	$returnTranToAccNo ='-';
	$CusName_Tran='-';
	$channel='-';
	$bankName='-';
	
	// -------------------------------------------------------------
	// ค่าที่จะ Pass ไปยังหน้า Process สำหรับกรณีนี้
	// -------------------------------------------------------------
	// กำหนดประเภทช่องทางการจ่ายเงินออก 0-ไม่ใช่เงินโอนหรือเช็ค 1-เงินโอน 2-เช็ค
	$bywhat = 0;
	$payerchqno_or_payeebankno = '';
	$payeebankname = '';
}
$ChannelAmt=number_format($ChannelAmt,2);
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function chk_note(){	
	if($('#str_note').val()==""){
		alert("กรุณาระบุหมายเหตุ!");
		return false;
	}
	else{
		return true;
	
	}
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>รายละเอียด</B></legend>

<div align="center">

<table border="0">
	<tr>
		<td align="right"><b>เลขที่สัญญา :</b></td><td><a onclick="javascript:popU('../thcap_installments/frm_Index.php?idno=<?php echo $contractID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')" style="cursor:pointer;"><font color="#0000FF"><u><?php echo $contractID;?></u></font></a></td>
	</tr>
	<tr>
		<td align="right"><b>วันที่สัญญามีผล :</b></td><td><?php echo $conStartDate;?></td>
	</tr>
	<tr>
		<td align="right"><b>ชื่อผู้กู้/ผู้ซื้อหลัก :</b></td><td><?php echo $fullname;?></td>
	</tr>
	<tr>
		<td align="right"><b>วันที่เวลาที่ใบสำคัญมีผล :</b></td><td><?php echo $voucherDate.' '.$voucherTime;?></td>
	</tr>
	
	<tr>
		<td align="right"><b>จุดประสงค์ :</b></td><td><?php echo $purpose_name;?></td>
	</tr>
	<tr>
		<td align="right"><b>คำอธิบาย :</b></td><td><?php echo $voucherRemark;?></td>
	</tr>	
	<tr>
		<td align="right"><b>ช่องทางการจ่าย :</b></td><td><?php echo $bank_name.'-'.$bank_account;?></td>
	</tr>
	<tr>
		<td align="right"><b>คืนโดย :</b></td><td><?php echo $channel;?></td>
	</tr>
	<!--เช็ค-->	
	<div id="chq">	
	<tr>
		<td align="right"><b>เลขที่เช็คที่คืน :</b></td><td><?php echo $returnChqNo;?></td>
	</tr>
	<tr>
		<td align="right"><b>วันที่บนเช็คที่คืน :</b></td><td><?php echo $returnChqDate;?></td>
	</tr>
	<tr>
		<td align="right"><b>ออกเช็คให้ :</b></td><td><?php echo $CusName_Chq;?></td>
	</tr>	
	</div>
	
	<!--โอน-->
	<div id="tran">
	<tr>
		<td align="right"><b>เจ้าของบัญชี  :</b></td><td><?php echo $CusName_Tran;?></td>
	</tr>
	<tr>
		<td align="right"><b>ธนาคาร :</b></td><td><?php echo $bankName;?></td>
	</tr>
	<tr>
		<td align="right"><b>เลขที่บัญชีปลายทาง :</b></td><td><?php echo $returnTranToAccNo;?></td>
	</tr>	
	</div>
	<tr>
		<td align="right"><b>จำนวนเงิน :</b></td><td><?php echo $ChannelAmt;?></td>
	</tr>
	<form  method="post" name="app" id="app" action="process_approved.php" >
	<tr>
        <td align="right" width="25%" valign="top"><b>หมายเหตุ :<b></td>
        <td><textarea id="str_note" name="str_note" rows="4" cols="60"></textarea></td>
	</tr>
</table>
<?php 
	echo "<input type=\"hidden\" value=\"$bywhat\" id=\"bywhat\" name=\"bywhat\">";
	echo "<input type=\"hidden\" value=\"$payerchqno_or_payeebankno\" id=\"payerchqno_or_payeebankno\" name=\"payerchqno_or_payeebankno\">";
	echo "<input type=\"hidden\" value=\"$payeebankname\" id=\"payeebankname\" name=\"payeebankname\">";
	echo "<input type=\"text\" value=\"$auto_id\" id=\"id\" name=\"id\" hidden>";
?>
<input type="submit" value="อนุมัติ" id="btn_app" name="btn_app" onclick="return chk_note()"/>
<input type="submit" value="ไม่อนุมัติ" id="btn_noapp" name="btn_noapp" onclick="return chk_note()"/>
</form>
</fieldset>
     </td>
    </tr>
</table>
<?php include("frm_list_approved.php");?>
</body>
</html>