<?php
include('../../../config/config.php');
$count = 1;
$sumrows = 0;
echo "<body bgcolor=\"#EEEEEE\">";
echo "<center><h2>ตรวจสอบว่ามีใบเสร็จ แต่เป็นการ gen ใบกำกับให้กับใบเสร็จที่ไม่มี VAT</h2>";
echo "<center><h2>ตาราง taxinvoice_otherpay และ taxinvoice_otherpaycancel</h2>";
//ค้นหาประเภทของสัญญาที่มีทั้งหมด เพื่อมาต่อกับ V เป้นประเภทของกำกับภาษี และ I เป็นประเภทใบแจ้งหนี้
$qry_contype = pg_query("SELECT \"conType\" FROM thcap_contract GROUP BY  \"conType\"");
while($re_contype = pg_fetch_array($qry_contype)){
	$contypev[] = "V".$re_contype["conType"]; //ใบกำกับภาษี
	$contypei[] = "I".$re_contype["conType"]; //ใบแจ้งหนี้
}

$sql = pg_query("SELECT 
	\"receiptDate\", 
	\"receiptType\",
	\"receiptRunning\"
	FROM thcap_running_receipt order by  \"receiptDate\"");
while($re = pg_fetch_array($sql)){
	$receiptDate = $re['receiptDate'];
	$receiptType = $re['receiptType'];
	$receiptRunning = $re['receiptRunning'];
	if(!in_array($receiptType,$contypev) && !in_array($receiptType,$contypei)){
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
		
		if($rowsfindreceip == $receiptRunning){
			while($refindreceip = pg_fetch_array($sqlfindreceip)){	
				
				$receipid = $refindreceip['receiptID'];						
				$sqlchk = pg_query("	SELECT 		a.\"receiptID\",b.\"ableVAT\" 
										FROM 		thcap_temp_receipt_otherpay a 
										left join 	account.\"thcap_typePay\" b on a.\"typePayID\" = b.\"tpID\" 
										where 		a.\"receiptID\" not in (
																				SELECT c.\"receiptID\" 
																				FROM thcap_temp_receipt_otherpay c 
																				left join account.\"thcap_typePay\" d on c.\"typePayID\" = d.\"tpID\" 
																				where d.\"ableVAT\" = '1'
																			) and 
													a.\"receiptID\" = '$receipid' and
													b.\"ableVAT\" = '0'
									");
				$sqlchkre = pg_fetch_array($sqlchk);
					$receipid1 = $sqlchkre['receiptID'];	
				
					$sqlchk2 = pg_query("		SELECT 	\"taxinvoiceID\"
												FROM 	thcap_temp_taxinvoice_otherpay 
												where \"taxinvoiceID\" = '$receipid1'									  
											union all
												SELECT 	\"taxinvoiceID\"
												FROM 	thcap_temp_taxinvoice_otherpay_cancel 
												where \"taxinvoiceID\" = '$receipid1'
										");
					$rowchk2 = pg_num_rows($sqlchk2);
					$sqlchkre2 = pg_fetch_array($sqlchk2);
						$taxid = $sqlchkre2['taxinvoiceID'];
					if($rowchk2 > 0){
						echo "<textarea cols=\"50\" rows=\"5\">"."วันที่ :  ".$receiptDate." \nประเภท :".$receiptType." มีใบเสร็จที่ไม่มี VAT แต่ออกใบกำกับภาษีคือ\n\n";
								echo "ใบเสร็จ : $receipid1 ใบกำกับ : $taxid";
						echo "</textarea>";
						$sumrows++;
					}
					if($count%4 == 0){
						echo "<br>";
						$count++;			
					}
			
			}
		}
	 }	
}

echo "<p>ข้อมูลต่างกัน ".$sumrows." รายการ";

?>

</center>
</body>