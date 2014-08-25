<?php
include('../../../config/config.php');
$count = 1;
$sumrows = 0;
echo "<body bgcolor=\"#EEEEEE\">";
echo "<center><h2>ตรวจสอบว่ามีการ gen เลขแต่ไม่มีใบกำกับภาษี</h2>";

//ค้นหาประเภทของสัญญาที่มีทั้งหมด เพื่อมาต่อกับ V เป้นประเภทของกำกับภาษี
$qry_contype = pg_query("SELECT \"conType\" FROM thcap_contract GROUP BY  \"conType\"");
while($re_contype = pg_fetch_array($qry_contype)){
	$contypev[] = "V".$re_contype["conType"]; //ใบกำกับภาษี
}

//วนหาข้อมูลการ รันเลข
$sql = pg_query("	
					SELECT 		\"receiptDate\", \"receiptType\",\"receiptRunning\"
					FROM 		\"thcap_running_receipt\" 
					ORDER BY  	\"receiptDate\"
				");
while($re = pg_fetch_array($sql)){
	$receiptDate = $re['receiptDate'];
	$receiptType = $re['receiptType'];
	$receiptRunning = $re['receiptRunning'];
	if(in_array($receiptType,$contypev)){
			$sqlfindreceip = pg_query("SELECT \"taxinvoiceID\"
										FROM thcap_temp_taxinvoice_details 
										where 
											date(\"taxpointDate\") = '$receiptDate' 
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
																						\"taxinvoiceID\"
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
											,'-','') = '$receiptType' order by \"taxinvoiceID\" ");
			$rowsfindreceip = pg_num_rows($sqlfindreceip);
		
			if($rowsfindreceip != $receiptRunning){
					echo "<textarea cols=\"50\" rows=\"5\">"."วันที่ :  ".$receiptDate." \nประเภท :".$receiptType."  จำนวน running : ".$receiptRunning." จำนวน tax : ".$rowsfindreceip."\n(thcap_temp_taxinvoice_details)\n\n";
						if($rowsfindreceip == 0){ echo "ไม่มีข้อมูลในตาราง thcap_temp_taxinvoice_details"; }else{
							while($refindreceip = pg_fetch_array($sqlfindreceip)){			
								echo $refindreceip['taxinvoiceID']."   ";		
							}
						}
					echo "</textarea>";
					
					if($count%4 == 0){
						echo "<br>";
					}
				
					$count++;
					$sumrows++;
		
			}else{
					$sqlfindreceip = pg_query("SELECT distinct(a.\"taxinvoiceID\")
											FROM thcap_temp_taxinvoice_otherpay a inner join thcap_temp_taxinvoice_details b on a.\"taxinvoiceID\" = b.\"taxinvoiceID\"
											where 
												date(b.\"taxpointDate\") = '$receiptDate' 
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
																							a.\"taxinvoiceID\"
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
											
											SELECT distinct(a.\"taxinvoiceID\")
											FROM thcap_temp_taxinvoice_otherpay_cancel a inner join thcap_temp_taxinvoice_details b on a.\"taxinvoiceID\" = b.\"taxinvoiceID\"
											where 
												date(b.\"taxpointDate\") = '$receiptDate' 
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
																							a.\"taxinvoiceID\"
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
												");
				$rowsfindreceip = pg_num_rows($sqlfindreceip);
		
				if($rowsfindreceip != $receiptRunning){

				
				echo "<textarea cols=\"50\" rows=\"5\">"."วันที่ :  ".$receiptDate." \nประเภท :".$receiptType."  จำนวน running : ".$receiptRunning." จำนวน tax : ".$rowsfindreceip."\n(taxinvoice_otherpay/taxinvoice_otherpay_cancel)\n\n";
					if($rowsfindreceip == 0){ echo "ไม่มีข้อมูลในตาราง taxinvoice_otherpay/taxinvoice_otherpay_cancel"; }else{
						while($refindreceip = pg_fetch_array($sqlfindreceip)){			
							echo $refindreceip['taxinvoiceID']."   ";		
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