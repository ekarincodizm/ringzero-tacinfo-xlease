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
	
	if (file_exists("upload/".$_FILES["file"]["name"])){ //file_exists() ตรวจสอบว่ามีไฟล์ชื่อนี้อยู่แล้วหรือไม่ 
		echo $_FILES["file"]["name"] ."<br>"."<b>"."ไฟล์นี้ได้ทำการโหลดไปแล้ว กรุุณาทำรายการใหม่"."<b>";
		echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
		exit;
	}else{
		move_uploaded_file($_FILES["file"]["tmp_name"],
		"upload/". $_FILES["file"]["name"]); //move_uploaded_file(ไฟล์ที่จะย้าย, ปลายทางที่เก็บไฟล์)

		echo "Stored in: " . "upload/".$_FILES["file"]["name"];

		$strFileName = "upload/".$_FILES["file"]["name"];
		$filename=$_FILES["file"]["name"];
		
		$objFopen=fopen("upload/".$_FILES["file"]["name"],"r");
		if($objFopen){
			$i=0;
			$p=0;
			while (!feof($objFopen)){
				$buffer = fgets($objFopen, 4096);
				$buffer=iconv('TIS-620', 'UTF-8', $buffer);
				
				$i++;
				if($i==3){ //เลขที่บัญชี
					$accno=explode(",",$buffer);
					$accno=trim(str_replace("'","",$accno[1]));
					
					//หารหัสบัญชีจากเลขบัญชีที่ได้
					$qrybid=pg_query("select \"BID\" from \"BankInt\" where \"BAccount\"='$accno'");
					list($accno_id)=pg_fetch_array($qrybid);
					
					//กรณีธนาคารที่เลือกกับบัญชีที่ upload ไม่ตรงกันให้ไม่สามารถทำรายการได้
					if($accno_id!=$bankint){
						echo "<br><b>"."บัญชีไม่ตรงกัน กรุณาทำรายการใหม่"."<b>";
						fclose($objFopen);
						unlink("upload/".$_FILES["file"]["name"]);	
						echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
						exit;
					}
				}

				if($i==4){ //วันที่ในรายการเดินบัญชี เริ่มต้น,วันที่ในรายการเดินบัญชี สิ้นสุด
					$startend=explode(",",$buffer);
					//แปลงวันเริ่มต้นให้เป็นรูปแบบ ค.ศ.
					list($d,$m,$y)=explode("-",$startend[1]);
					$m=numMonthTH($m);
					$y=$y-543;
					$sbj_bank_data_start=$y."-".$m."-".$d;
					
					//แปลงวันสิ้นสุดให้เป็นรูปแบบ ค.ศ.
					list($d,$m,$y)=explode("-",$startend[3]);
					if($d<10){
						$d='0'.$d;
					}
					$m=numMonthTH($m);
					$y=$y-543;	
					$sbj_bank_data_end=$y."-".$m."-".$d;				
				}
				if($i==5){ //ชื่อบัญชี
					$accname=explode(",",$buffer);
					$sbj_bank_data_accname=trim(str_replace('"',"",$accname[1]));				
				}
				if($i==6){ //ชื่อสาขา
					$branch=explode(",",$buffer);
					$sbj_bank_data_branch=trim(str_replace('"',"",$branch[1]));
				}
			
				if($i==7){ //เข้าบัญชี
					$deposit=explode(",",$buffer);
					$sbj_bank_deposit_record=trim($deposit[1]);
					$sbj_bank_deposit_amt=trim($deposit[3]);
				}
			
				if($i==8){ //หักบัญชี
					$withdraw=explode(",",$buffer);
					$sbj_bank_withdraw_record=trim($withdraw[1]);
					$sbj_bank_withdraw_amt=trim($withdraw[3]);
				}
				
				if($i==9){ //นำข้อมูลส่วน HEAD				
					$insjob="INSERT INTO finance.thcap_statement_bank_job(
					sbj_filename, sbj_channel, \"doerID\", \"doerStamp\", 
					sbj_date,sbj_bank_data_accno, sbj_bank_data_accname, sbj_bank_data_branch, sbj_bank_data_start, 
					sbj_bank_data_end, sbj_bank_withdraw_record, sbj_bank_deposit_record, 
					sbj_bank_withdraw_amt, sbj_bank_deposit_amt)
					VALUES ('$filename', '$bankint', '$id_user', '$datelog', 
							'$dateadd','$accno', '$sbj_bank_data_accname', '$sbj_bank_data_branch','$sbj_bank_data_start', 
							'$sbj_bank_data_end', '$sbj_bank_withdraw_record', '$sbj_bank_deposit_record', 
							'$sbj_bank_withdraw_amt', '$sbj_bank_deposit_amt') RETURNING sbj_serial";
					if($resinjob=pg_query($insjob)){
						list($sbj_serial)=pg_fetch_array($resinjob);
					}else{
						$status++;
					}
				}

				//เริ่มบันทึกข้อมูล DETAIL
				if($i>10){
					$objdata=explode(",",$buffer); //สำหรับตรวจสอบข้อมูลของแถว
					$objdate=strpos($buffer,"*"); //ตรวจสอบว่ามี * ในแถวหรือไม่
					if($objdate!==FALSE || trim($objdata[0])==""){ //กรณีพบ * ในแถว หรือ ค่าแรกเป็นค่าว่างให้เริ่มข้อมูลใหม่
						continue;
					}else{
						//แปลงวันที่รายการมีผลให้เป็นรูปแบบ ค.ศ.
						list($d,$m,$y)=explode("-",$objdata[0]);
						$m=numMonthTH($m);
						$y=$y-543;
						$sbr_receivedate=$y."-".$m."-".$d;
			
						//แปลงวันที่สร้างรายการให้เป็นรูปแบบ ค.ศ.
						list($d,$m,$y)=explode("-",$objdata[8]);
						$m=numMonthTH($m);
						$y=$y-543;
						$sbr_bankcreate=$y."-".$m."-".$d.' 00:00:00';
						
						//เลขที่เช็ค
						$sbr_chqno=checknull(trim($objdata[2]));
						
						//จำนวนเงินหักบัญชี
						if(trim($objdata[3])==""){
							$sbr_amtwithdraw=0;
						}else{
							$sbr_amtwithdraw=trim($objdata[3]);
						}
						
						//จำนวนเงินเข้าบัญชี
						if(trim($objdata[4])==""){
							$sbr_amtdeposit=0;
						}else{
							$sbr_amtdeposit=trim($objdata[4]);
						}
						
						$sbr_amtoutstanding=trim($objdata[5]); //ยอดเงินคงเหลือ
						$sbr_counterservice=trim($objdata[6]); //หมายเลขช่องบริการ
						$sbr_bankbranch=trim($objdata[7]); //สาขาที่ให้บริการ
						
						if($sbr_chqno=="null"){
							$chqno="and sbr_chqno is null";
						}else{
							$chqno="and sbr_chqno=$sbr_chqno";
						}
						//แปลงค่าของตัวแปร  TIS-620 เป็น UTF-8
						$detail=trim($objdata[1]);
						
						//ตรวจสอบรายการเบื้องต้นว่ามีรายการนี้ถูกเพิ่มหรือยัง ในการ uplode ครั้งก่อนหรือไม่
						$qrychk_job=pg_query("select * from finance.thcap_statement_bank_raw
						where sbr_channel='$bankint' AND sbr_receivedate='$sbr_receivedate' AND sbr_details='$detail' AND
						sbr_amtwithdraw='$sbr_amtwithdraw' AND 
						sbr_amtdeposit='$sbr_amtdeposit' AND sbr_amtoutstanding='$sbr_amtoutstanding' AND sbr_counterservice='$sbr_counterservice' AND 
						sbr_bankbranch='$sbr_bankbranch' AND sbr_bankcreate='$sbr_bankcreate' AND sbr_refjob <> '$sbj_serial' $chqno");
						if(pg_num_rows($qrychk_job)>0){
							continue;
						}else{
							$p++;
						}
						$res_genpost=""; //รหัสเงินโอน
						if($sbr_receivedate >= '2013-06-01')
						{ //ถ้าวันที่ตั้งแต่ 2013-06-01 เป็นต้นไป ให้บันทึกใน tranfer ด้วย
						
							// ถ้าเป็นเงินขาเข้า เท่านั้น จึงจะบันทึก
							if($sbr_amtdeposit != "" && $sbr_amtdeposit > 0)
							{
								
								//จำนวนเงินที่นำไปบันทึกในตาราง thcap_receive_transfer
								$bankRevAmt=$sbr_amtdeposit;
								
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
								
								//insert ข้อมูลในตาราง  finance."thcap_receive_transfer" (ตารางเก็บเงินโอน)
								$in_transfer="insert into \"finance\".\"thcap_receive_transfer\" 
								(\"revTranID\",\"cnID\",\"bankRevAccID\",\"bankRevBranch\",\"bankRevStamp\",
								\"bankRevAmt\",\"revTranStatus\") 
								values  ('$res_genpost','TSF','$bankint','$sbr_bankbranch','$sbr_receivedate 00:00:00',
								'$bankRevAmt' ,'9')";
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
								
								//หารหัสสาขาจากตาราง bankInt
								$qrybankcode=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$bankint'");
								list($BAccount,$BName)=pg_fetch_array($qrybankcode);
									
								//LOG
								if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
								\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
								VALUES ('เพิ่มรายการเงินโอน(Statement bank)','$res_genpost','$id_user', '$datelog','$BAccount - $BName',
								'$sbr_bankbranch','$bankRevAmt','$sbr_receivedate 00:00:00')")); else $status++;
								//LOG---
								
							}
						}
						if($sbr_amtdeposit>0){
							if($res_genpost==""){
								echo "<br><b>"."พบข้อผิดพลาดในการบันทึกรหัสเงินโอน"."<b>";
								fclose($objFopen);
								unlink("upload/".$_FILES["file"]["name"]);	
								echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
								exit;	
							}
						}
						$res_genpost=checknull($res_genpost);
						$insrow="INSERT INTO finance.thcap_statement_bank_raw(
						sbr_channel, sbr_refjob, sbr_receivedate, sbr_receivetime, 
						sbr_details, sbr_chqno, sbr_amtwithdraw, sbr_amtdeposit, sbr_amtoutstanding, 
						sbr_counterservice, sbr_bankbranch, sbr_bankcreate,\"revtranferID\")
						VALUES ('$bankint', '$sbj_serial', '$sbr_receivedate', '00:00:00', 
								'$detail', $sbr_chqno, '$sbr_amtwithdraw', '$sbr_amtdeposit', '$sbr_amtoutstanding', 
								'$sbr_counterservice','$sbr_bankbranch', '$sbr_bankcreate',$res_genpost)";
						if($resinjrow=pg_query($insrow)){
						}else{
							$status++;
						}
					}
				}				
			} //end while
			
			fclose($objFopen);
		}else{
			echo "<br><b>"."ไม่สามารถเปิดไฟล์ได้ กรุณาตรวจสอบ"."<b>";
			unlink("upload/".$_FILES["file"]["name"]);	
			echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";

		}
	}
}

if($p==0){
	pg_query("ROLLBACK");
	unlink("upload/".$_FILES["file"]["name"]);	
	echo "<br>"."<b>"."ไม่มีการบันทึกรายการ เนื่องจากทุกรายการซ้ำกับข้อมูลในระบบ  "."</b>";
	echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";	
}else{
	if($status==0){	
		pg_query("COMMIT");
		echo "<br>"."<b>"."บันทึกข้อมูลเรียบร้อย  "."</b>";
		echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
	}else{
		pg_query("ROLLBACK");
		unlink("upload/".$_FILES["file"]["name"]);	
		echo "มีข้อผิดพลาดในการบันทึก กรุณาทำรายการใหม่".$residno;	
		echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
	} 
}

?>