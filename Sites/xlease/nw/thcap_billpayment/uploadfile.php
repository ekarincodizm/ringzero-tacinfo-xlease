<?php
##เมนู Load Bill Payment (THCAP)
session_start();
include("../../config/config.php");
require_once ("../../core/core_functions.php");
include("../function/checknull.php");
$id_user=$_SESSION["av_iduser"];
$c_code=$_SESSION["session_company_code"];
$datelog = nowDateTime();
$bankint = $_POST["bankint"]; //ช่องทาง
$dateadd = $_POST["dateadd"]; //วันที่ upload

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
pg_query("BEGIN");
$status=0;
		
if ($_FILES["file"]["error"] > 0){
	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
}else{
	echo "Upload: " . $_FILES["file"]["name"] . "<br />"; //$_FILE['image']['name']    แทนชื่อไฟล์ที่อัพโหลด
	echo "Type: " . $_FILES["file"]["type"] . "<br />";  //$_FILE['image']['type']    แทนชนิดของไฟล์ที่อัพโหลด เช่น .jpg
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />"; //$_FILE['image']['size']    แทนขนาดของไฟล์
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />"; //$_FILE['image']['tmp_name']   แทนตำแหน่งไดเรกทอรีที่เก็บไฟล์ไว้ชั่วคราว
	
	if (file_exists("upload/".$c_code."/".$_FILES["file"]["name"])){ //file_exists() ตรวจสอบว่ามีไฟล์ชื่อนี้อยู่แล้วหรือไม่ 
		echo $_FILES["file"]["name"] ."<br>"."<b>"."ไฟล์นี้ได้ทำการโหลดไปแล้ว กรุุณาทำรายการใหม่"."<b>";
		echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
	}else{
		move_uploaded_file($_FILES["file"]["tmp_name"],
		"upload/".$c_code."/". $_FILES["file"]["name"]); //move_uploaded_file(ไฟล์ที่จะย้าย, ปลายทางที่เก็บไฟล์)

		echo "Stored in: " . "upload/".$c_code."/".$_FILES["file"]["name"];

		$strFileName = "upload/".$c_code."/".$_FILES["file"]["name"];
		$filename=$_FILES["file"]["name"];
		$objFopen = fopen($strFileName, 'r'); //เปิดไฟล์ขึ้นมาเพื่ออ่านอย่างเดียว
		if ($objFopen){
			//######begin head######//
			$file = fgets($objFopen, 4096); 
			$txtcheck=trim(substr($file,0,17)); //ตรวจสอบว่าวันนั้นมีข้อมูลหรือไม่
			
			//ถ้าข้อมูลวันนั้นไม่มีข้อมูลให้เปิดไฟล์นี้ไปไม่ต้องอ่านต่อ
			if($txtcheck=='**End Of Report**'){
				$datanow="no"; 
				fclose($objFopen); 
			}else{
				$datanow="yes"; 
				$head_text=substr($file,0,256);
				$t_bankcode=substr($head_text,7,3); 

				$st_namecompany=substr($head_text,20,40); //ตัดสตริงเอาแค่ชื่อบริษัท 40 ตัวอักษร
				$t_namecompany=iconv('windows-874','UTF-8',$st_namecompany);
				  
				$t_datesentdata=substr($head_text,59,67);
				  
				$sentdate=substr($t_datesentdata,0,3)."-".substr($t_datesentdata,3,2)."-".substr($t_datesentdata,5,4);
				  
				echo "code bank =".$t_bankcode."<br>".
					"company   =".$t_namecompany."<br>".
					"date send =".substr($t_datesentdata,0,3)."/".substr($t_datesentdata,3,2)."/".substr($t_datesentdata,5,4)."<br>";
				//-----end head-----//		

				fclose($objFopen); 
			}
		}
		
		//ถ้ามีข้อมูลให้ทำส่วนนี้
		if($datanow=="yes"){
			//######check total######//			
			$filetotal=fopen("upload/".$c_code."/".$_FILES["file"]["name"],"r"); //อ่านเพื่อหาค่า T
			while (!feof($filetotal)){
				$f_bffer = fgets($filetotal, 4096);
				
				$ftext= explode(" ",$f_bffer); //อ่านไฟล์เป็น array โดยตัดคำด้วยช่องว่าง
				
				$f_dst=substr($ftext[0],0,256); //นำ array ที่ได้ตัวที่แรกมาตัด string
				$resf=substr($f_dst,0,1); //substring ออกมา 1 ตัว
				if($resf=="T"){ //ตรวจสอบว่าใช่ตัว T หรือไม่
					echo  "This total =".$ftotal=number_format(substr($f_bffer,52,6));  //fix ว่าแสดงแค่ 6 ตัวสุดท้ายเพราะ T จะมีทั้งหมด 58 ตัว
				}   
			} 
			fclose($filetotal);
			//-----end check total-----//	
		

			//######list row data หาค่า D ว่ามีกี่ตัว######//	
			$cc_data=fopen("upload/".$c_code."/".$_FILES["file"]["name"],"r"); 
			while (!feof($cc_data)){
				
				$c_bffer = fgets($cc_data, 4096);
				$ctext= explode(" ",$c_bffer);

				$c_dst=substr($ctext[0],0,256);
				$resc=substr($c_dst,0,1);
				if($resc=="D"){ 
					$c++; 
				} 
			}		
			//เติมอักษรเข้าสตริงด้วยคำที่ต้องการ ด้วยฟังก์ชัน str_pad(สตริงที่ต้องการเติมคำ,ความยาวของสตริงที่ต้องการ,ตัวอักษรหรือคำที่ต้องการเติม,รูปแบบการเติม(STR_PAD_BOTH - เติมทั้งสองข้าง ถ้าไม่ลงตัวข้างขวาจะถูกเติมมากกว่า,STR_PAD_LEFT - เติมด้านซ้าย,STR_PAD_RIGHT - เติมด้านขวา (default)))
			echo "<br>"."count row =".$crow=number_format(str_pad($c,6,"0",STR_PAD_LEFT)); //เช่น $c=11 จะได้ 000011
			fclose($cc_data);	
			//-----end row data-----//	
		

			//######check ROW < > Total data then insert to table######//  
			if($ftotal!=$crow){ //ตรวจสอบว่าค่า $crow หลังจากเติมสตริงแล้วมีค่าเท่ากับค่าในไฟล์หรือไม่
				echo "<br><span style=\"background-color:#FFB5C5;\">รายการไม่สมบูรณ์ กรุณาโหลดใหม่หรือติดต่อธนาคาร</span>";
				
				$delFile=unlink("upload/".$c_code."/".$_FILES["file"]["name"]);
				if($delFile){
					echo "File Deleted";
				}else{
					echo "File can not delete";
				}			  
			}else{ //กรณีที่อ่านไฟล์ได้ครบ
				$datafile=fopen("upload/".$c_code."/".$_FILES["file"]["name"],"r");

				while (!feof($datafile)){
					$statusupload=1; //ความสมบูรณ์ของ ref1 และ ref2
					$buffer = fgets($datafile, 4096);
				
					$text= explode(" ",$buffer);
				
					$dst=substr($text[0],0,256);
					$resd=substr($dst,0,1);
					if($resd=="D"){
						$n++;
							
						$terminal_sq_no=substr($buffer,1,6); //เอามาแค่ 6 ตำแหน่งโดยนับจากตำแหน่งที่ 1 (ไม่นับตัวอักษร D ตัวแรก)
						$bank_no=substr($buffer,7,3); //รหัสธนาคาร นับตั้งแต่ตัวที่ 7 ไป 3 ตัว (ไม่นับตัวอักษร D ตัวแรก) 
						$bank_acc=substr($buffer,10,10); //เลขบัตรชีธนาคาร นับตั้งแต่ตัวที่ 10 ไป 10 ตัว (ไม่นับตัวอักษร D ตัวแรก) 
				
						$tr_date=substr($buffer,20,8); //วันที่ นับตั้งแต่ตัวที่ 20
						$tr_time=substr($buffer,28,6); //เวลา
						$fullname=substr($buffer,34,50); //ชื่อ โดยตรงนี้จะเว้น 50 ตัวอักษร
						$fullname=iconv('windows-874','UTF-8',$fullname); //แปลงชื่อให้อ่านออกนะจ้ะ
						if(trim($fullname)=='NO NAME' OR trim($fullname)=='NO DATA'){
							$fullname="null";
						}else{
							$fullname="'".$fullname."'";
						}
							
						$ref1=substr($buffer,84,20);
						$bf_tref1=trim($ref1);
						$sb_t1=substr($bf_tref1,0,19);
						$t1icon=iconv('windows-874','UTF-8',$sb_t1);
						//$tref_1=str_replace("-","",$t1icon);
						$tref_1 = $t1icon;
						$tref1=checknull($tref_1);
						
						//ค้นหาว่ามีข้อความ 'NO REF' หรือไม่
						$pos1 = strpos($tref_1,"NO REF");
						if($pos1!==FALSE){
							$tref1="null";
						}
						
						$ref2=substr($buffer,104,20);
						$bf_tref2=trim($ref2);
						$sb_t2=substr($bf_tref2,0,19);
						$t2icon=iconv('windows-874','UTF-8',$sb_t2);
						//$tref_2=str_replace("-","",$t2icon);
						$tref_2 = $t2icon;
						$tref2=checknull($tref_2);
						
						//ค้นหาว่ามีข้อความ 'NO REF' หรือไม่
						$pos2 = strpos($tref_2,"NO REF");
						if($pos2!==FALSE){
							$tref2="null";
						}
						
						//กำหนดค่าสำหรับบันทึกใน finance.thcap_receive_billpayment กรณี ref ไม่สมบูรณ์จะบันทึกตามปกติ
						$tref1_1=$tref1; 
						$tref2_1=$tref2; 
						
						$pay_bank_branch=substr($buffer,145,3);
						$terminal_id=substr($buffer,148,4);
						$tran_type=substr($buffer,153,3);
						$pay_cheque_no=checknull(substr(trim($buffer),156,7));
						   
						$samt=substr($buffer,163,13); //ต้องหาร /100
						$amt=$samt/100;

						$d_tr=substr($tr_date,4,4)."-".$date_tr=substr($tr_date,2,2)."-".substr($tr_date,0,2);
						$t_tr=substr($tr_time,0,2).":".substr($tr_time,2,2).":".substr($tr_time,4,2);
							
						//*******************เก็บข้อมูลส่วนนี้ไว้ในส่วนเงินโอนด้วย
						$contractID="null";
						$invoiceID="null";
						
						//กรณีมีค่า ref1
						if($tref1!="null"){	
							//หาว่าเป็นเลขที่สัญญาและใบแจ้งหนี้อะไรจากการ GEN
							$qryconinv=pg_query("SELECT thcap_decode_invoice_ref($tref1,$tref2)");
							list($coninv)=pg_fetch_array($qryconinv);
							
							//หากพบว่ามีเลขที่สัญญาหรือใบแจ้งหนี้จริงให้ทำการดึงข้อมูลออกมา
							if($coninv!=""){
								$qryinv=pg_query("SELECT ta_array1d_get(thcap_decode_invoice_ref($tref1,$tref2),0) as \"contractID\",
								ta_array1d_get(thcap_decode_invoice_ref($tref1,$tref2),1) as \"invoiceID\"");
								list($conchk,$invoicechk)=pg_fetch_array($qryinv);
								
								//*****หลังจากได้เลขที่สัญญาและใบแจ้งหนี้แล้ว ให้ตรวจสอบว่าค่าที่ได้ตรงกับความเป็นจริงหรือไม่*****//
								
								//กรณีใบแจ้งหนี้มีค่า หาเลขที่สัญญาจากรหัสใบแจ้งหนี้ที่ได้
								if($invoicechk!=""){
									$qrycontract=pg_query("SELECT \"thcap_invoiceIDTocontractID\"('$invoicechk')");
									list($contractID)=pg_fetch_array($qrycontract);
									
									//ตรวจสอบเลขที่สัญญาที่ได้ว่ามีใบแจ้งหนี้นี้จริงหรือไม่ ซึ่งถ้าพบแสดงว่าค่าที่ได้จาก DECODE ถูกต้อง
									if($contractID!=""){
										//นำเลขที่สัญญาที่ได้ไปตรวจสอบใบแจ้งหนี้จริงอีกรอบว่า มีใบแจ้งหนี้นี้หรือไม่
										$qryinvoice=pg_query("SELECT ta_array1d_count(\"thcap_contractIDToinvoiceID\"('$contractID'),'$invoicechk')");
										list($numchkin)=pg_fetch_array($qryinvoice);	
									}
									
									//กรณีที่เลขที่สัญญาที่ได้จากการ decode และที่ได้จากใบแจ้งหนี้ตรงกัน และพบว่าเลขที่ใบแจ้งหนี้มีจริง
									if($conchk==$contractID and $numchkin>0){
										$contractID=checknull($conchk);
										$invoiceID=checknull($invoicechk);
									}else{ //กรณีข้อมูลไม่ตรงกัน
										//อนุญาติให้บันทึก แต่ถือว่าเป็นข้อผิดพลาด 
										//กำหนดค่าสำหรับบันทึกใน finance.thcap_receive_transfer กรณี ref ไม่สมบูรณ์จะไม่บันทึกในนี้
										$contractID="null";
										$invoiceID="null";
										$statusupload=0;
									}
								}else{ //กรณีไม่พบใบแจ้งหนี้
									//ตรวจสอบเลขที่สัญญาที่ได้ว่ามีจริงในระบบหรือไม่
									$qrycon=pg_query("select * from \"thcap_contract\" where \"contractID\"='$conchk'");
									$numcon=pg_num_rows($qrycon);
									
									//กรณีที่พบค่าจริง ให้ทำการบันทึกตามปกติ
									if($numcon>0){
										$contractID=checknull($conchk);
										$invoiceID="null"; //จะได้เป็นค่าว่าง
									}else{
										//กรณีไม่เป็นจริงให้พนักงานตรวจสอบก่อน
										$contractID="null";
										$invoiceID="null";
										$statusupload=0;
									}
								}		
							}else{ //กรณีไม่พบข้อมูล								
								//กำหนดค่าสำหรับบันทึกใน finance.thcap_receive_transfer กรณี ref ไม่สมบูรณ์จะไม่บันทึกในนี้
								$contractID="null";
								$invoiceID="null";
								$statusupload=0;
							}
						}

						// เพิ่มข้อมูลลงในตาราง  finance.thcap_receive_billpayment
						$in_sql="insert into finance.thcap_receive_billpayment(terminal_sq_no, bank_no, tranfer_date, tranfer_time, cusname, bankrevref1, bankrevref2,
								 pay_bank_branch, terminal_id, tran_type, pay_cheque_no, amt,empupload,dateupload,filename,bank_acc,statusupload) values  
								('$terminal_sq_no','$bank_no','$d_tr','$t_tr',$fullname,$tref1_1,$tref2_1,
								'$pay_bank_branch','$terminal_id','$tran_type',$pay_cheque_no,'$amt','$id_user','$datelog','$filename','$bank_acc','$statusupload')";
						if($result=pg_query($in_sql)){
							$st_fn="OK".$in_sql;
						}else{
							$status++;
							$st_fn="error insert Re".$in_sql;
						}
						
						//หา running เงินโอนล่าสุดว่าเท่าไหร่แล้ว update เป็นตัวถัดไป จากนั้นนำค่าที่ได้ไปใช้ต่อไป
						$upnum="update \"thcap_running_number\" 
						set \"runningNum\"=\"runningNum\"+1 
						where \"compID\" = 'THCAP' AND \"fieldName\" = 'revTranID'
						returning \"runningNum\"";
						if($resnum=pg_query($upnum)){
							list($revTranID)=pg_fetch_array($resnum);
						}else{
							$status++;
						}
						
						$res_genpost=core_generate_frontzero($revTranID,10,'RT');
						
						//หารหัสสาขาจากตาราง bankInt
						$qrybankcode=pg_query("select \"BID\",\"BName\" from \"BankInt\" where \"BAccount\"='$bank_acc'");
						list($BID,$BName)=pg_fetch_array($qrybankcode);
						
						//insert ข้อมูลในตาราง  finance."thcap_receive_transfer" (ตารางเก็บเงินโอน)
						$in_transfer="insert into \"finance\".\"thcap_receive_transfer\" (\"revTranID\",\"cnID\",\"bankRevAccID\",\"bankRevBranch\",\"bankRevStamp\",\"bankRevAmt\",\"revTranStatus\",
						\"bankRevRef1\",\"bankRevRef2\",\"contractID\",\"invoiceID\",\"cusnamebill\") 
						values  ('$res_genpost','BILL','$BID','$pay_bank_branch','$d_tr $t_tr','$amt' ,'9',
						$tref1,$tref2,$contractID,$invoiceID,$fullname)";
						
						if($result=pg_query($in_transfer)){   
						}else{
							$result1=$result;
							$status+=1;
						}
						
						$in_transfer_action="insert into \"finance\".\"thcap_receive_transfer_action\" (\"tranActionType\",\"revTranID\",\"doerID\",\"doerStamp\") 
									values  ('I','$res_genpost','$id_user','$datelog')";
						if($resultac=pg_query($in_transfer_action)){   
						}else{
							$result2=$resultac;
							$status+=1;
						}
						
						//LOG
						if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
						\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
						VALUES ('เพิ่มรายการเงินโอน(billpayment)','$res_genpost','$id_user', '$datelog','$bank_acc - $BName',
						'$pay_bank_branch','$amt','$d_tr $t_tr')")); else $status++;
						//LOG---
						
						// $upnum="update \"thcap_running_number\" set \"runningNum\"='$revTranID' where \"compID\" = 'THCAP' AND \"fieldName\" = 'revTranID'";
					
						// if($resnum=pg_query($upnum)){
						// }else{
							// $status++;
						// }
						
					//*******************จบขั้นตอนการเก็บข้อมูลไว้ในส่วนเงินโอน
					}else{
						//ไม่ต้องทำอะไร
					} 
					unset($conchk);
					unset($invoicechk);
					unset($contractID);
					unset($numchkin);
					unset($tref_1);
					unset($tref_2);
				}  //end while วน record ที่อยู่ใน text file  
				
				$in_sql="	INSERT INTO finance.thcap_load_file_billpayment(
							lfb_filename, lfb_channel, \"doerID\", \"doerStamp\",\"lfb_date\")
							VALUES ('".$_FILES["file"]["name"]."','$bankint','$id_user',LOCALTIMESTAMP(0),'$dateadd')
						";
				if($result=pg_query($in_sql)){
				}else{
					$status++;
					$st_fn="error insert Re".$in_sql;
				}	
				
				//ACTIONLOG
				if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำการโหลด Bill Payment (THCAP)', '$datelog')")){}else{ $status++; };
				//ACTIONLOG---
				
				if($status==0)
				{					
					pg_query("COMMIT");
					echo "<br>"."<b>"."บันทึกข้อมูลเรียบร้อย  "."</b>";
					fclose($datafile);
					echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
				}else{
					pg_query("ROLLBACK");
							
					echo "มีข้อผิดพลาดในการบันทึก กรุณาทำรายการใหม่".$residno;
					fclose($datafile);
					
					unlink("upload/".$c_code."/".$_FILES["file"]["name"]);//ลบไฟล์ที่เพิ่ง upload
					echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
				} 
			}
		}else{ //กรณีวันนั้นไม่มีข้อมูล
			//ให้เก็บไฟล์ตามปกติ
			$in_sql="	INSERT INTO finance.thcap_load_file_billpayment(
							lfb_filename, lfb_channel, \"doerID\", \"doerStamp\",\"lfb_date\")
							VALUES ('".$_FILES["file"]["name"]."','$bankint','$id_user',LOCALTIMESTAMP(0),'$dateadd')
						";
			if($result=pg_query($in_sql)){
			}else{
				$status++;
				$st_fn="error insert Re".$in_sql;
			}	
			
			//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำการโหลด Bill Payment (THCAP)', '$datelog')");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "<br>"."<b>"."วันที่ $dateadd ไม่ปรากฏข้อมูล"."</b>";
			echo "<br>"."<b>"."บันทึกข้อมูลเรียบร้อย  "."</b>";
			echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";

		}
	}
}

?> 
</body>
</html>