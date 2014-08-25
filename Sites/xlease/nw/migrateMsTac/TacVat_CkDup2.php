<?php
set_time_limit (0); 
ini_set("memory_limit","1024M"); 
//error_reporting(0); 
include("config/config.php");
echo "InvNO ที่มี VatNO > 1 <br> ";
//echo "<table border=1><tr><td>ลำดับ</td><td>InvNO</td><td>VatNO</td></tr>";
//pg_query("BEGIN WORK");
$status = 0;
$i= 1;

for($k=0;$k<12;$k++){
	$ck_02[$k] = $k;  //0-9
	$ck_01[$k] = 0;  //0-9
}

//หาที่ซ้ำ เจอ  320
$sql_fc=mssql_query("select distinct(a.invno) from TacInvoice a
left join TacInvoice b on b.invno = a.invno
where b.cusid <> a.cusid  order by a.invno ",$conn); 

$num_row_loop = mssql_num_rows($sql_fc);

for($w=0;$w<$num_row_loop;$w++){

$sql_fc=mssql_query("select distinct top 1 a.invno from TacInvoice a
left join TacInvoice b on b.invno = a.invno
where b.cusid <> a.cusid  order by a.invno ",$conn); 


while($res_fc = mssql_fetch_array($sql_fc)){
	//$VatNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatNO"]));
	//$VatFixDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatFixDate"]));
	//$InvType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvType"]));
	$InvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["invno"]));
	echo "<b>".$i." ".$InvNO."</b><br>";


					//หาเฉพาะช่องว่าง
					
					$Last_po_Inv = substr($InvNO,-5,4);
					
					$inv_miss1 = find_miss_inv($InvNO,$Last_po_Inv,$ck_02,$conn,$ck_01);
					$num_miss_inv1 =  	count($inv_miss1[0]); // จำนวนที่หาย
					$num_dup_inv1 =  	count($inv_miss1[1]);  // จำนวนที่ซ้ำ
					echo "จำนวนตัวเลขที่ซ้ำกัน = ".$num_dup_inv1." <br>";
					echo "จำนวนตัวเลขที่หาย  = ".$num_miss_inv1." <br>";
					
					
					$h = 0;
							if($num_dup_inv1>0){ //ถ้ามีซ้ำกัน ให้ไป หา เลขที่ว่าง
							
							for($s=0;$s<$num_miss_inv1;$s++){ //มีตัวเลขที่ว่าง >1 
				
			
					if($inv_miss1[1][$h] < $inv_miss1[0][$s] ){ // เลขซ้ำกัน  น้อยกว่า เลขที่หาย
					
					
				
					
						$InvNO_new = substr($InvNO,0,strlen($InvNO)-5);	//ลบ 4ตำแหน่ง
						$InvNO_new = $InvNO_new.$Last_po_Inv.$inv_miss1[0][$s]; 
						
						$InvNO_old = substr($InvNO,0,strlen($InvNO)-5);	//ลบ 4ตำแหน่ง
						$InvNO_old = $InvNO_old.$Last_po_Inv.$inv_miss1[1][$h]; 
															
						
							$ins="update \"TacInvoice\" set invno = '$InvNO_new' where invno = '$InvNO_old' and invtype = 'OTV'  ;";
							
							$gen_sql .=$ins."<br>"; 
							echo $ins."<br> ";
									/*
		if($res_inss=mssql_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}*/
							$ins="update \"TacInvat\" set invno = '$InvNO_new' where invno = '$InvNO_old' and invtype = 'OTV'  ;";
							echo $ins."<br> ";
							$gen_sql .=$ins."<br>"; 
							/*
		if($res_inss=mssql_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}*/
						$num_dup_inv1--; //ลดจำนวนที่ ซ้ำกัน
						$num_miss_inv1--;
						$h++;
						

						}
						
						}
					
							
							}
							/*
							if($num_dup_inv1==0){
								
							continue; //ออก Loop เลย ถ้า เจอหมด
							}*/
						
						 // ถ้ายังไม่เจอในช่วงเดียวกัน ให้ไป หาอีกช่วงนึง
						
						 $p=2;
						 											while($num_dup_inv1>0){ 
																	
															$Last_po_Inv2 = $Last_po_Inv+1;
																				echo " ค้นหาช่วงถัดไป ครั้งที่ $p  <br> ";
																		$inv_miss2 = find_miss_inv($InvNO,$Last_po_Inv2,$ck_02,$conn,$ck_01);

																		$num_miss_inv2 =  	count($inv_miss2[0]); // จำนวนที่หาย
																		$num_dup_inv2 =  	count($inv_miss2[1]);  // จำนวนที่ซ้ำ	
																				//echo $num_miss_inv2."<br>";
																				//echo $num_dup_inv2."<br>";
															
															for($s2=0;$s2<$num_miss_inv2;$s2++){ //มีตัวเลขที่ว่าง >1  //มีตัวเลขที่ว่าง >1 
																
														//if($inv_miss1[1][$h]<$inv_miss2[0][$s2] &&  $inv_miss2[0][$s2]!=null &&  $inv_miss1[1][$h]!=null){ // เลขซ้ำกัน  น้อยกว่า เลขที่หาย
														
															$InvNO_new = substr($InvNO,0,strlen($InvNO)-5);	//ลบ 4 ตำแหน่ง
															$InvNO_new = $InvNO_new.$Last_po_Inv2.$inv_miss2[0][$s2]; 
															
															$InvNO_old = substr($InvNO,0,strlen($InvNO)-5);	//ลบ 4ตำแหน่ง
															$InvNO_old = $InvNO_old.$Last_po_Inv.$inv_miss1[1][$h]; 
															// + 1 ตำแหน่งใหม่ ที่จะค้นหา
															
															$ins="update \"TacInvoice\" set invno = '$InvNO_new' where invno = '$InvNO_old' and invtype = 'OTV'  ;";
															$gen_sql .=$ins."<br>"; 
																echo $ins."<br> ";
																		/*
		if($res_inss=mssql_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}*/
															$ins="update \"TacInvat\" set invno = '$InvNO_new' where invno = '$InvNO_old' and invtype = 'OTV'  ;";
															$gen_sql .=$ins."<br>"; 
																echo $ins."<br> ";	
																		/*
		if($res_inss=mssql_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}*/
																

															$num_dup_inv1--; //ลดจำนวนที่ ซ้ำกัน
															$num_miss_inv2--;
															$h++;
															
																	//}
															}
															if($p==20){
															break;	
																
															}
																				
																								$p++;
																							}																				

						// ถ้า ยังหา ตัวเลขที่ว่างไม่เจอ ให้ไป ค้นหา ช่วงถัดไป (อีก 10 record)
$i++;
}
}		
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>แก้ไขข้อมูลเรียบร้อยแล้ว";
}else{
  //  pg_query("ROLLBACK");
    echo "ไม่สามารถแก้ไขข้อมูลได้";
}

//ฟังค์ชั่นค้นหา เลขที่หายไป
function find_miss_inv($InvNO,$Last_po_Inv,$ck_02,$conn,$ck_01){
						//echo $InvNO;
					$InvNO_for_Search2 = substr($InvNO,0,strlen($InvNO)-5);	//ลบ 4 ตำแหน่ง
						$InvNO_for_Search2 = $InvNO_for_Search2.$Last_po_Inv; // + 1 ตำแหน่งใหม่ ที่จะค้นหา
					//echo $InvNO_for_Search2."<br>";
												// Loop 3 
										$sql_fc3=mssql_query("SELECT invno FROM TacInvoice WHERE invno LIKE '$InvNO_for_Search2%' ",$conn); 
									//	echo "SELECT invno,invtype FROM TacInvoice WHERE invno LIKE '$InvNO_for_Search2%' ";
										$num_row = mssql_num_rows($sql_fc3);
									//$num_row=0;
										$c = 0;
									
									while($res_fc3 = mssql_fetch_array($sql_fc3)){
										//echo 123;
										$InvNO_for_ck2[$c]=trim(iconv('WINDOWS-874','UTF-8',$res_fc3["invno"]));
										//echo trim(iconv('WINDOWS-874','UTF-8',$res_fc3["invno"]));
										$Last_po_Inv2[$c] = substr($InvNO_for_ck2[$c],-1,1); // ตำแหน่งสุดท้าย 
										//echo $Last_po_Inv2[$c];
										//$InvType2[$c]=trim(iconv('WINDOWS-874','UTF-8',$res_fc3["invtype"]));

															$ck_01[$Last_po_Inv2[$c]] = $ck_01[$Last_po_Inv2[$c]]+1;

													
													$c++;
													
										
									}

															//$ck_01[$Last_po_Inv2[$c]] ++;
															//echo $Last_po_Inv2[$c]." ".$ck_01[$c]."<br>";
								$xx = 	$num_row;		
							if($num_row==9)$xx=10;	
									
													
				$v2=0;
				$b2=0;
				
					for($k=0;$k<$xx;$k++){
						//echo $k." $num_row ".$ck_01[$k]."<br>";
						//echo $k;
						if($ck_01[$k]==0){ // หายไป
						//	echo $k."<br>";
							$inv_missing[0][$b2]=	$k;
						$b2++;
						//$num_miss_inv = $b2++;
						}
						
							if($ck_01[$k]>1){ // ซ้ำกัน
							//echo $k."<br>";
					//	echo $ck_01[$k]."<br>";
						$inv_missing[1][$v2]=	$k;
						//echo $inv_missing[1][0]."<br>";
						$v2++;
						//$num_dup2 = $v2++;
					
							
							}
						
						
					}
				//	echo $inv_missing[1][0]."<br>";
					//echo $inv_missing[0][1]."<br>";
					//echo $inv_missing[1][0]."<br>";
					return $inv_missing;
					//$inv_missing1=null;
					//$inv_missing = null; //Clear array value
					
					}
					
					echo "<br> SQL UPDATE <br>";
					
					echo $gen_sql;
?>

