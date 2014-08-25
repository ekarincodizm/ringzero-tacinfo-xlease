<?php

include("../../config/config.php");
include("../../core/core_functions.php");

/*
	หัวไฟล์ HTML
*/
echo"
	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
	<html xmlns=\"http://www.w3.org/1999/xhtml\">
	<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
	<title>ตารางชำระหนี้เงินกู้จำนองที่ดิน</title>
	
	<STYLE TYPE=\"text/css\">
	<!--
	TH{font-family: Arial; font-size: 9pt;}
	--->
	</STYLE>
	
	</head>
	
		<table width=\"1300\" border=\"1\">
		<tr>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">รหัสสัญญา</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">เลขที่ใบเสร็จ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">วันที่รับชำระ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">อัตราดอกเบี้ย</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">จำนวนวันนับตั้งแต่จ่ายครั้งก่อน</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">ยอดรับชำระ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFD700\" scope=\"col\">ดอกเบี้ยคงเหลือก่อนใบเสร็จ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#98FB98\" scope=\"col\">เงินต้นคงเหลือก่อนใบเสร็จ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFD700\" scope=\"col\">จ่ายดอกเบี้ย</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#98FB98\" scope=\"col\">จ่ายเงินต้น</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#008B8B\" scope=\"col\">ดอกเบี้ยคงเหลือหลังใบเสร็จ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#008B8B\" scope=\"col\">เงินต้นคงเหลือหลังใบเสร็จ</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#FFFF99\" scope=\"col\">ช่องทางรับชำระ</th>
		</tr>";

$contractID = "MG-BK01-5400001";

if($contractID != ""){

	/*
		ประกาศตัวแปร
	*/
	$lastReceiveDate = NULL;

	if($lastReceiveDate == NULL){
		$conDtlQuery = "SELECT
							\"conStartDate\"
						FROM
							thcap_mg_contract
						WHERE
							\"contractID\"='".$contractID."'
		";

		$sql_conDtlQuery = pg_query($conDtlQuery);
		while($sql_row = pg_fetch_array($sql_conDtlQuery))
		{
			$conStartDate = $sql_row['conStartDate'];
		}
		$lastReceiveDate = $conStartDate;
	}

	/*
		Query ข้อมูล Statement ของสัญญาที่สนใจ
	*/
	$conQuery =	"SELECT
					thcap_mg_receipt.\"contractID\",
					thcap_mg_receipt.\"receiptID\",
					thcap_mg_receipt.\"receiveDate\",
					thcap_mg_receipt.\"receiveRate\",
					thcap_mg_receipt.\"receiveAmt\",
					thcap_mg_statement.\"sBeforePrinciple\",
					thcap_mg_statement.\"sBeforeInterest\",
					thcap_mg_statement.\"sAfterPrinciple\",
					thcap_mg_statement.\"sAfterInterest\",
					thcap_mg_receipt.\"receiveBy\"
				FROM 
					account.thcap_mg_receipt,
					account.thcap_mg_statement
				WHERE 
					thcap_mg_receipt.\"receiptID\" = thcap_mg_statement.\"receiptID\" AND
					thcap_mg_receipt.\"contractID\" ='".$contractID."'
				";

	$sql_conQuery = pg_query($conQuery);
	while($sql_row = pg_fetch_array($sql_conQuery))
	{
		$receiptID = $sql_row['receiptID'];
		$receiveDate = $sql_row['receiveDate'];
		$receiveRate = $sql_row['receiveRate'];
		$receiveAmt = $sql_row['receiveAmt'];
		$sBeforePrinciple = $sql_row['sBeforePrinciple'];
		$sBeforeInterest = $sql_row['sBeforeInterest'];
		$sAfterPrinciple = $sql_row['sAfterPrinciple'];
		$sAfterInterest = $sql_row['sAfterInterest'];
		$receiveBy = $sql_row['receiveBy'];

		/*
			Query ข้อมูล ใบเสร็จของใบเสร็จที่สนใจ
		*/
		$recDtlQuery =	"SELECT
							\"receiptID\",
							\"rType\",
							\"rAmt\"
						FROM 
							account.thcap_mg_receipt_details
						WHERE 
							thcap_mg_receipt_details.\"receiptID\" = '".$receiptID."'
						";

		$sql_recDtlQuery = pg_query($recDtlQuery);
		while($sql_row = pg_fetch_array($sql_recDtlQuery))
		{
			$rType = $sql_row['rType'];
			$rAmt = $sql_row['rAmt'];

			/*
				ประเภทใบเสร็จ
					1000 - จ่ายเงินต้น
					1001 - จ่ายดอกเบี้ย
			*/
			if($rType == "1000"){
				$paidPrinciple = $rAmt;
			} else if($rType == "1001"){
				$paidInterest = $rAmt;
			}
		}

		/*
			เตรียมข้อมูลก่อนการแสดงผล
		*/
		$numDateDiff = core_time_datediff(substr($lastReceiveDate, 0, 10), substr($receiveDate, 0, 10));


		/*
			แสดงรายละเอียดและ Statement ที่เกิดขึ้นจากใบเสร็จแต่ละใบ
		*/
		echo"
		<tr>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">$contractID</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">$receiptID</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">$receiveDate</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">$receiveRate</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">$numDateDiff</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">".number_format($receiveAmt,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#EEEE00\" scope=\"col\">".number_format($sBeforeInterest,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#90EE90\" scope=\"col\">".number_format($sBeforePrinciple,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#EEEE00\" scope=\"col\">".number_format($paidInterest,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#90EE90\" scope=\"col\">".number_format($paidPrinciple,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#00CDCD\" scope=\"col\">".number_format($sAfterInterest,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#00CDCD\" scope=\"col\">".number_format($sAfterPrinciple,2)."</th>
			<th width=\"100\" height=\"45\" bgcolor=\"#CCFFCC\" scope=\"col\">$receiveBy</th>
		</tr>";
		
		/*
			ล้างค่าก่อนในไปวน Loop รอบใหม่
		*/
		$paidPrinciple = 0;
		$paidInterest = 0;

		/*
			ตั้งค่าสำหรับตัวแปรที่จะต้องนำไปใช้ใน loop รอบใหม่
		*/
		$lastReceiveDate = $receiveDate;
	}

	echo "</table>";
}
?>