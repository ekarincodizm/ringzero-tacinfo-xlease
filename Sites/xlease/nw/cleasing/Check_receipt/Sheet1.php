<?php
include('../../../config/config.php');
$count = 1;
$sumrows = 0;
echo "<body bgcolor=\"#EEEEEE\">";
echo "<center><h2>ตรวจสอบว่ามีการ gen เลขแต่ไม่มีใบเสร็จ</h2>";

//ค้นหาประเภทของสัญญาที่มีทั้งหมด เพื่อมาต่อกับ V เป้นประเภทของกำกับภาษี และ I เป็นประเภทใบแจ้งหนี้
$qry_contype = pg_query("SELECT \"conType\" FROM thcap_contract GROUP BY  \"conType\"");
while($re_contype = pg_fetch_array($qry_contype)){
	$contypev[] = "V".$re_contype["conType"]; //ใบกำกับภาษี
	$contypei[] = "I".$re_contype["conType"]; //ใบแจ้งหนี้
}
//ดึงข้อมูลการ run เลขใบเสร็จมาวน
$sql = pg_query("	SELECT 		\"receiptDate\", \"receiptType\",\"receiptRunning\"
					FROM		\"thcap_running_receipt\"
					ORDER BY  	\"receiptDate\"
				");
while($re = pg_fetch_array($sql)){
	$receiptDate = $re['receiptDate']; //วันที่รัน
	$receiptType = $re['receiptType']; //ประเภทที่รัย
	$receiptRunning = $re['receiptRunning']; //จำนวนที่รัน
	//หากไม่ใช่ประเภทของใบกำกับภาษีและใบแจ้งหนี้ให้ตรวจสอบ
	if(!in_array($receiptType,$contypev) && !in_array($receiptType,$contypei)){
		$sqlfindreceip = pg_query("	SELECT distinct(a.\"receiptID\")
									FROM thcap_temp_receipt_details a inner join thcap_temp_receipt_channel b 
									on a.\"receiptID\" = b.\"receiptID\"
									where 
										date(b.\"receiveDate\") = '$receiptDate' 
									AND
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
										,'-','') = '$receiptType'  order by a.\"receiptID\"");
		$rowsfindreceip = pg_num_rows($sqlfindreceip);
		
		if($rowsfindreceip != $receiptRunning){
			echo "<textarea cols=\"50\" rows=\"5\">"."วันที่ : ".$receiptDate." \nประเภท : ".$receiptType."  จำนวน running :".$receiptRunning." จำนวน receipID : ".$rowsfindreceip."\n(thcap_temp_receipt_details)\n\n";
				if($rowsfindreceip == 0){ 
					echo "ไม่มีข้อมูลในตาราง thcap_temp_receipt_details"; 
				}else{
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
		}else{
			$sqlfindreceip = pg_query("SELECT distinct(a.\"receiptID\")
									FROM thcap_temp_receipt_otherpay a inner join thcap_temp_receipt_channel b 
									on a.\"receiptID\" = b.\"receiptID\"
									where 
										date(b.\"receiveDate\") = '$receiptDate' 
									AND
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
									FROM thcap_temp_receipt_otherpay_cancel a inner join thcap_temp_receipt_channel b 
									on a.\"receiptID\" = b.\"receiptID\"
									where 
										date(b.\"receiveDate\") = '$receiptDate' 
									AND
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
			echo "<textarea cols=\"50\" rows=\"5\">"."วันที่11: ".$receiptDate." \nประเภท : ".$receiptType."  จำนวน running :".$receiptRunning." จำนวน receipID : ".$rowsfindreceip."\n(otherpay/otherpay_cancel)\n\n";
				if($rowsfindreceip == 0){ echo "ไม่มีข้อมูลในตาราง thcap_temp_receipt_otherpay / thcap_temp_receipt_otherpay_cancel"; }else{
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
}	


echo "<p>ข้อมูลต่างกัน ".$sumrows." รายการ";
?>
</center>
</body>