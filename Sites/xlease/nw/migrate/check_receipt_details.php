<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<center>
<a href="frm_migrate_receipt_detail.php">กลับ</a><br>
<?php
include("../../config/config.php");

set_time_limit(350);

$i = 0;

$qry = pg_query("select * FROM \"thcap_running_receipt\" order by \"receiptDate\" ");
while($res = pg_fetch_array($qry))
{
	$receiptDate = $res["receiptDate"];
	$receiptRunning = $res["receiptRunning"];
	
	$qryChk = pg_query("select * from \"thcap_temp_receipt_details\" where date(\"doerStamp\") = '$receiptDate' ");
	$numRow_qryChk = pg_num_rows($qryChk);
	
	if($numRow_qryChk != $receiptRunning)
	{
		while($resChk = pg_fetch_array($qryChk))
		{
			$receiptID = $resChk["receiptID"];
			$doerStamp = $resChk["doerStamp"];
			
			echo "เลขที่สัญญา $receiptID วันเวลาที่ทำรายการ  $doerStamp<br>";
			
			$i++;
		}
	}
}

if($i == 0)
{
	echo "ไม่พบรายการที่ผิดพลาด";
}
?>
</center>