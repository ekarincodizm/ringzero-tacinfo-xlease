<?php
pg_query("BEGIN");
$status=0;
if($_FILES["file"]["error"] > 0)
{
	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
}
else
{
	echo "Upload: " . $_FILES["file"]["name"] . "<br />"; //$_FILE['image']['name']    แทนชื่อไฟล์ที่อัพโหลด
	echo "Type: " . $_FILES["file"]["type"] . "<br />";  //$_FILE['image']['type']    แทนชนิดของไฟล์ที่อัพโหลด เช่น .jpg
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />"; //$_FILE['image']['size']    แทนขนาดของไฟล์
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />"; //$_FILE['image']['tmp_name']   แทนตำแหน่งไดเรกทอรีที่เก็บไฟล์ไว้ชั่วคราว
	
	if(file_exists("upload/".$_FILES["file"]["name"]))
	{ //file_exists() ตรวจสอบว่ามีไฟล์ชื่อนี้อยู่แล้วหรือไม่ 
		echo $_FILES["file"]["name"] ."<br>"."<b>"."ไฟล์นี้ได้ทำการโหลดไปแล้ว กรุุณาทำรายการใหม่"."<b>";
		echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
		exit;
	}
	else
	{
		move_uploaded_file($_FILES["file"]["tmp_name"],
		"upload/". $_FILES["file"]["name"]); //move_uploaded_file(ไฟล์ที่จะย้าย, ปลายทางที่เก็บไฟล์)

		echo "Stored in: " . "upload/".$_FILES["file"]["name"];

		$strFileName = "upload/".$_FILES["file"]["name"];
		$filename=$_FILES["file"]["name"];
		
		$objFopen=fopen("upload/".$_FILES["file"]["name"],"r");
		if($objFopen)
		{
			$i=0;
			$p=0;
			$Debit_Amount_Row = 0;
			$Debit_Amount_Sum = 0.00;
			$Credit_Amount_Row = 0;
			$Credit_Amount_Sum = 0.00;
			while (!feof($objFopen))
			{
				$i++;
				
				$buffer = fgets($objFopen, 4096);
				$buffer=iconv('TIS-620', 'UTF-8', $buffer);
				
				if($i >= 2) // ข้อมูลเริ่มตั้งแต่บรรทัดที่ 2
				{
					// แปลงข้อความให้อยู่ในรูปแบบที่ใช้ได้
					list($text1, $money1, $text2, $money2, $text3) = explode("\"",$buffer);
					$money1 = str_replace(",","",$money1);
					$money2 = str_replace(",","",$money2);
					$buffer = $text1.$money1.$text2.$money2.$text3;
					
					// แยกข้อความ
					list($Account_Number[$i], $Date[$i], $Time[$i], $Transaction_Code[$i], $Channel[$i], $Cheque_Number[$i], $Debit_Amount[$i], $Credit_Amount[$i], $Balance_Sign[$i], $Balance_Amount[$i], $Description[$i]) = explode(",",$buffer);
					
					// ตัดช่องว่าง หน้า/หลัง ออก
					$Account_Number[$i] = trim($Account_Number[$i]); //เลขที่บัญชี
					$Date[$i] = trim($Date[$i]);
					$Time[$i] = trim($Time[$i]);
					$Transaction_Code[$i] = trim($Transaction_Code[$i]); // รายละเอียดการทำรายการ
					$Channel[$i] = trim($Channel[$i]); // หมายเลขช่องบริการ
					$Cheque_Number[$i] = trim($Cheque_Number[$i]); //เลขที่เช็ค
					$Debit_Amount[$i] = trim($Debit_Amount[$i]); //จำนวนเงินหักบัญชี
					$Credit_Amount[$i] = trim($Credit_Amount[$i]); //จำนวนเงินเข้าบัญชี
					$Balance_Sign[$i] = trim($Balance_Sign[$i]);
					$Balance_Amount[$i] = trim($Balance_Amount[$i]); //ยอดเงินคงเหลือ
					$Description[$i] = trim($Description[$i]); // สาขาที่ให้บริการ
					
					if(strlen($Time[$i]) < 8 || strlen($Time[$i]) == 5)
					{
						$Time[$i] = $Time[$i].":00";
					}
					
					if($Account_Number[$i] == "")
					{
						continue;
					}
					
					// แปลงรูปแบบวันที่
					list($dd, $mm, $yyyy) = explode("/",$Date[$i]);
					if($dd < 10){$dd = "0".$dd;}
					$Date[$i] = "$yyyy"."-"."$mm"."-"."$dd";
					
					//หารหัสบัญชีจากเลขบัญชีที่ได้
					$qrybid = pg_query("select \"BID\", \"BBranch\" from \"BankInt\" where \"BAccount\"='$Account_Number[$i]'");
					list($accno_id, $sbj_bank_data_branch) = pg_fetch_array($qrybid);
					
					//กรณีธนาคารที่เลือกกับบัญชีที่ upload ไม่ตรงกันให้ไม่สามารถทำรายการได้
					if($accno_id!=$bankint){
						echo "<br><b>"."บัญชีไม่ตรงกัน กรุณาทำรายการใหม่ "."<b>";
						fclose($objFopen);
						unlink("upload/".$_FILES["file"]["name"]);	
						echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
						exit;
					}
					
					if($i >= 3)
					{
						if($Account_Number[$i] != $Account_Number[$i-1])
						{
							echo "<br><b>"."เลขที่บัญชีในไฟล์ไม่ตรงกันทั้งหมด กรุณาทำรายการใหม่"."<b>";
							fclose($objFopen);
							unlink("upload/".$_FILES["file"]["name"]);	
							echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_Index.php'\">";
							exit;
						}
					}
					
					$sbj_bank_data_accname = "บจก.ไทยเอช แคปปิตอล"; //ชื่อบัญชี
					
					// เงินหักบัญชี
					if($Debit_Amount[$i] != "" && $Debit_Amount[$i] != 0.00)
					{
						$Debit_Amount_Row++;
						$Debit_Amount_Sum += $Debit_Amount[$i];
					}
					
					// เงินเข้าบัญชี
					if($Credit_Amount[$i] != "" && $Credit_Amount[$i] != 0.00)
					{
						$Credit_Amount_Row++;
						$Credit_Amount_Sum += $Credit_Amount[$i];
					}
					
					if($Cheque_Number[$i] == "N/A"){$Cheque_Number[$i] = "";}
					if($Debit_Amount[$i] == ""){$Debit_Amount[$i] = "0.00";}
					if($Credit_Amount[$i] == ""){$Credit_Amount[$i] = "0.00";}
					
					// วันที่สิ้นสุด
					if($Date[$i] != ""){$sbj_bank_data_end = $Date[$i];}
				}				
			} //end while
			
			fclose($objFopen);
			
			//นำข้อมูลส่วน HEAD
			$insjob="INSERT INTO finance.thcap_statement_bank_job(
			sbj_filename, sbj_channel, \"doerID\", \"doerStamp\", 
			sbj_date,sbj_bank_data_accno, sbj_bank_data_accname, sbj_bank_data_branch, sbj_bank_data_start, 
			sbj_bank_data_end, sbj_bank_withdraw_record, sbj_bank_deposit_record, 
			sbj_bank_withdraw_amt, sbj_bank_deposit_amt)
			VALUES ('$filename', '$bankint', '$id_user', '$datelog', 
					'$dateadd','$Account_Number[2]', '$sbj_bank_data_accname', '$sbj_bank_data_branch','$Date[2]', 
					'$sbj_bank_data_end', '$Debit_Amount_Row', '$Debit_Amount_Row', 
					'$Debit_Amount_Sum', '$Credit_Amount_Sum') RETURNING sbj_serial";
			if($resinjob=pg_query($insjob)){
				list($sbj_serial)=pg_fetch_array($resinjob);
			}else{
				$status++;
			}
			
			// วนลูป บันทึกข้อมูล DETAIL
			for($subloop = 2; $subloop <= $i; $subloop++)
			{
				if($Account_Number[$subloop] == "")
				{
					continue;
				}
				else
				{
					//วันที่รายการมีผลให้เป็นรูปแบบ ค.ศ.
					$sbr_receivedate = $Date[$subloop];
		
					//วันที่สร้างรายการให้เป็นรูปแบบ ค.ศ.
					$sbr_bankcreate = $Date[$subloop]." ".$Time[$subloop];
					
					//เลขที่เช็ค
					$sbr_chqno = checknull($Cheque_Number[$subloop]);
					
					//จำนวนเงินหักบัญชี
					$sbr_amtwithdraw = $Debit_Amount[$subloop];
					
					//จำนวนเงินเข้าบัญชี
					$sbr_amtdeposit = $Credit_Amount[$subloop];
					
					$sbr_amtoutstanding = $Balance_Amount[$subloop]; //ยอดเงินคงเหลือ
					$sbr_counterservice = $Channel[$subloop]; //หมายเลขช่องบริการ
					$sbr_bankbranch = $Description[$subloop]; //สาขาที่ให้บริการ
					
					if($sbr_chqno=="null"){
						$chqno="and sbr_chqno is null";
					}else{
						$chqno="and sbr_chqno=$sbr_chqno";
					}
					
					// รายละเอียดการทำรายการ
					$detail = $Transaction_Code[$subloop];
					
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
					
					// หา LoadStatementDate
					$qry_LoadStatementDate = pg_query("select \"LoadStatementDate\" from \"BankInt\" where \"BAccount\" = '$Account_Number[$subloop]' ");
					$LoadStatementDate = pg_result($qry_LoadStatementDate,0);
					
					$res_genpost=""; //รหัสเงินโอน
					if($sbr_receivedate >= $LoadStatementDate)
					{ //ถ้าวันที่ตั้งแต่ LoadStatementDate เป็นต้นไป ให้บันทึกใน tranfer ด้วย
					
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
							values  ('$res_genpost','TSF','$bankint','$sbr_bankbranch','$sbr_receivedate $Time[$subloop]',
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
							'$sbr_bankbranch','$bankRevAmt','$sbr_receivedate $Time[$subloop]')")); else $status++;
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
					VALUES ('$bankint', '$sbj_serial', '$sbr_receivedate', '$Time[$subloop]', 
							'$detail', $sbr_chqno, '$sbr_amtwithdraw', '$sbr_amtdeposit', '$sbr_amtoutstanding', 
							'$sbr_counterservice','$sbr_bankbranch', '$sbr_bankcreate',$res_genpost)";
					if($resinjrow=pg_query($insrow)){
					}else{
						$status++;
					}
				}
			}
		}
		else
		{
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