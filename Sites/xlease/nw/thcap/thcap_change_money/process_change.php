<?php
session_start();
$id_user = $_SESSION["av_iduser"];
include("../../../config/config.php");

$typechange = $_POST['typechange']; // รูปแบบการโอน
$conidchange = $_POST['conidchange']; // โอนให้เลขที่สัญญา (ผู้กู้/ผู้ค้ำ ต่างกัน)
$sameconid = $_POST['sameconid']; // โอนให้เลขที่สัญญา (ผู้กู้/ผู้ค้ำ เดียวกัน)
$selectmoney = $_POST['selectmoney']; // ประเภทเงินที่ใช้โอนย้าย
$moneychange = $_POST['moneychange']; // จำนวนเงินที่โอนแต่ละรายการ
$typechangeto = $_POST['typechangeto']; // ย้ายไปให้ประเภทเงินอะไร
$conidori = $_POST['conidori']; // เงินจากเลขที่สัญญา
$nowdate = nowDateTime(); // วันเวลาปัจจุบัน
$datechange = $_POST['datechange']; // วันที่โอน
$selectmoney = $_POST['selectmoney']; // ประเภทเงินที่ใช้ในการโอนไป
$sumallmoney = $_POST['hiddensumall']; // จำนวนเงินทั้งหมดที่ต้นทางจะโอน
$reason = $_POST['reason']; // เหตุผลที่ขอย้ายเงิน

if($datechange == nowDate())
{
	$datechange = $nowdate;
}
else
{
	$datechange = $datechange." 23:59:59";
}
?>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php

$status= 0;
pg_query("BEGIN");

$newReceiptID = "";
$waitAppv = ""; // เลขที่สัญญา ที่ต้องรอการอนุมัติก่อน

// รหัสรายการที่ทำพร้อมกัน
$qryMaxMasterID = pg_query("select max(\"masterID\") from \"thcap_transfermoney_c2c_temp\" ");
$MaxMasterID = pg_fetch_result($qryMaxMasterID,0);
if($MaxMasterID == ""){$MaxMasterID = 1;}
else{$MaxMasterID = $MaxMasterID + 1;}

for($i=0;$i<sizeof($typechange);$i++)
{  	
	if(($typechange[$i] == "def") or ($typechange[$i] == "same")){
	  if($selectmoney == "998")
		{
			$limitmoney = $_POST["changechos998"];
		}
		elseif($selectmoney == "997")
		{
			$limitmoney = $_POST["changechos997"];
		}
	    if($typechange[$i] == "def"){
		     $data=$conidchange[$i];
		}
		else{
		    $data=$sameconid[$i];		
		}
		$sql = "INSERT INTO \"thcap_transfermoney_c2c_temp\"(\"begin_conid\", \"begin_trans_type\", \"end_conid\", \"end_trans_type\", \"end_trans_money\", \"all_trans_money\", \"masterID\", \"doerID\", \"doerStamp\", \"appstatus\", \"reason\", \"changeMoneyDate\")
		VALUES ('$conidori', '$selectmoney', '$data', '$typechangeto[$i]', '$moneychange[$i]', '$limitmoney', '$MaxMasterID', '$id_user', '$nowdate', '2', '$reason', '$datechange')";	
		if($sqlqry = pg_query($sql)){}else{ $stauts++; echo $sql;}
		if($waitAppv == ""){$waitAppv = $data;}
		else{$waitAppv = $waitAppv."#".$data;}
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><br><font color=\"#0000FF\">บันทึกสำเร็จ</font></center>";
	
	if($newReceiptID != "")
	{
		$printReceipt = explode("#",$newReceiptID);
		for($i=0;$i<sizeof($printReceipt);$i++)
		{
			echo "<script type=\"text/javascript\">";
			echo "javascript:popU('../../Payments_Other/print_receipt_pdf.php?receiptID=$printReceipt[$i]&typepdf=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
			echo "</script>";
		}
	}
	
	if($waitAppv != "")
	{
		echo "<br><center><br>";
		echo "เลขที่สัญญาดังต่อไปนี้ต้องรอการอนุมัติก่อน<br>";
		$showWaitAppv = explode("#",$waitAppv);
		for($i=0;$i<sizeof($showWaitAppv);$i++)
		{
			echo "<br>$showWaitAppv[$i]<br>";
		}
		echo "</center>";
	}
	
	if($waitAppv == "")
	{
	?>
		<meta http-equiv='refresh' content='2; URL=frm_data.php'>
	<?php
	}
	else
	{
	?>
		<center><br><input type="button" value="ตกลง" onClick="parent.location.href='frm_data.php'"></center>
	<?php
	}
}
else
{
	pg_query("ROLLBACK");
	echo "<center><font color=\"#FF0000\">ERROR!! Can not save data.</font></center>";
	?>
	<meta http-equiv='refresh' content='4; URL=frm_data.php'>
	<?php
}
?>
