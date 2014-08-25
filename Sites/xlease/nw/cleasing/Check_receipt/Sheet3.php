<?php
include('../../../config/config.php');
$count = 1;
$sumrows = 0;
echo "<body bgcolor=\"#EEEEEE\">";
echo "<center><h2>ตรวจสอบว่ามีใบเสร็จ แต่เป็นการ gen ใบกำกับให้กับใบเสร็จที่ไม่มี VAT</h2>";
$sql = pg_query("SELECT 
	\"receiptDate\", 
	\"receiptType\",
	\"receiptRunning\"
	FROM thcap_running_receipt order by  \"receiptDate\"");
while($re = pg_fetch_array($sql)){
	$receiptDate = $re['receiptDate'];
	$receiptType = $re['receiptType'];
	$receiptRunning = $re['receiptRunning'];
	if($receiptType != 'VFA' && $receiptType != 'VPN' && $receiptType != 'VCG' && $receiptType != 'VMG' && $receiptType != 'VSM'){
		$sqlfindreceip = pg_query("	SELECT distinct(a.\"receiptID\")
							FROM thcap_temp_receipt_otherpay a inner join thcap_temp_receipt_channel b 
							    on a.\"receiptID\" = b.\"receiptID\" 
									where date(b.\"receiveDate\") = '$receiptDate' and 
										replace(
											replace(
												replace(
													replace(
														replace(
															replace(
																replace(
																	replace(
																		replace(
																			replace(
																				replace(
																					a.\"receiptID\"
																				,'0','')
																			,'1','')
																		,'2','') 
																	,'3','')
																,'4','')
															,'5','') 
														,'6','')
													,'7','')
												,'8','') 
											,'9','')
										,'-','') = '$receiptType'
		
		union all
			
										SELECT distinct(a.\"receiptID\")       
															FROM thcap_temp_receipt_otherpay_cancel a
																inner join thcap_temp_receipt_channel b on a.\"receiptID\" = b.\"receiptID\" 
																	where date(b.\"receiveDate\") = '$receiptDate' and 
																		replace(
																			replace(
																				replace(
																					replace(
																						replace(
																							replace(
																								replace(
																									replace(
																										replace(
																											replace(
																												replace(
																													a.\"receiptID\"
																												,'0','')
																											,'1','')
																										,'2','') 
																									,'3','')
																								,'4','')
																							,'5','') 
																						,'6','')
																					,'7','')
																				,'8','') 
																			,'9','')
																		,'-','') = '$receiptType' order by \"receiptID\"");
		$rowsfindreceip = pg_num_rows($sqlfindreceip);
		
		if($rowsfindreceip != $receiptRunning){

		
		echo "<textarea cols=\"50\" rows=\"5\">"."วันที่ :  ".$receiptDate." \nประเภท :".$receiptType."  จำนวนrunning : ".$receiptRunning." จำนวนใบเสร็จ : ".$rowsfindreceip."\n\n";
			if($rowsfindreceip == 0){ echo "ไม่มีข้อมูลในตาราง thcap_temp_receipt_otherpay"; }else{
				while($refindreceip = pg_fetch_array($sqlfindreceip)){			
					echo $refindreceip['receiptID']."   ";		
				}
			}
		echo "</textarea>";
		
		if($count%4 == 0){
			echo "<br>";
		}
		
		$count++;
		$sumrows++;
		}
	 }	
}

echo "<p>ข้อมูลต่างกัน ".$sumrows." รายการ";

?>

</center>
</body>