<?php
##เมนู Load Bill Payment
include("../config/config.php");
session_start();
$id_user=$_SESSION["av_iduser"];
$c_code=$_SESSION["session_company_code"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
//$c_code="AVL";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	if ($_FILES["file"]["error"] > 0){
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	}else{
		echo "Upload: " . $_FILES["file"]["name"] . "<br />"; //$_FILE['image']['name']    แทนชื่อไฟล์ที่อัพโหลด
		echo "Type: " . $_FILES["file"]["type"] . "<br />";  //$_FILE['image']['type']    แทนชนิดของไฟล์ที่อัพโหลด เช่น .jpg
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />"; //$_FILE['image']['size']    แทนขนาดของไฟล์
		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />"; //$_FILE['image']['tmp_name']   แทนตำแหน่งไดเรกทอรีที่เก็บไฟล์ไว้ชั่วคราว
	
		if (file_exists("upload/".$c_code."/".$_FILES["file"]["name"])){ //file_exists() ตรวจสอบว่ามีไฟล์ชื่อนี้อยู่แล้วหรือไม่ 
			echo $_FILES["file"]["name"] ."<br>"."<b>"."ไฟล์นี้ได้ทำการโหลดไปแล้ว กรุุณาทำรายการใหม่"."<b>";
			//echo "<meta http-equiv=\"refresh\" content=\"3;URL=frm_loaddata.php\">";   
		}else{
			move_uploaded_file($_FILES["file"]["tmp_name"],
			"upload/".$c_code."/". $_FILES["file"]["name"]); //move_uploaded_file(ไฟล์ที่จะย้าย, ปลายทางที่เก็บไฟล์)

			echo "Stored in: " . "upload/".$c_code."/".$_FILES["file"]["name"];

			
          
			pg_query("BEGIN");
		  
			$strFileName = "upload/".$c_code."/".$_FILES["file"]["name"];
			$objFopen = fopen($strFileName, 'r'); //เปิดไฟล์ขึ้นมาเพื่ออ่านอย่างเดียว
			if ($objFopen) {
				//begin head //
				$file = fgets($objFopen, 4096); 
				$head_text=substr($file,0,256);
				$t_bankcode=substr($head_text,7,3); 

				$st_namecompany=substr($head_text,20,40); //ตัดสตริงเอาแค่ชื่อบริษัท 40 ตัวอักษร
				$t_namecompany=iconv('windows-874','UTF-8',$st_namecompany);
			  
				$t_datesentdata=substr($head_text,59,67);
			  
				$sentdate=substr($t_datesentdata,0,3)."-".substr($t_datesentdata,3,2)."-".substr($t_datesentdata,5,4);
			  
				echo "code bank =".$t_bankcode."<br>".
					"company   =".$t_namecompany."<br>".
					"date send =".substr($t_datesentdata,0,3)."/".substr($t_datesentdata,3,2)."/".substr($t_datesentdata,5,4)."<br>";
				//end head //		

				fclose($objFopen); 
			}
		 
			//check total     //
			
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
			//end check total //	

			// list row data หาค่า D ว่ามีกี่ตัว//	
			$cc_data=fopen("upload/".$c_code."/".$_FILES["file"]["name"],"r"); 
			while (!feof($cc_data)){
			
				$c_bffer = fgets($cc_data, 4096);
				$ctext= explode(" ",$c_bffer);

				$c_dst=substr($ctext[0],0,256);
				$resc=substr($c_dst,0,1);
				if($resc=="D"){ 
					$c++; 
					//echo  "This row  =".substr($f_bffer,52,6);  
				} 
			}
			//เติมอักษรเข้าสตริงด้วยคำที่ต้องการ ด้วยฟังก์ชัน str_pad(สตริงที่ต้องการเติมคำ,ความยาวของสตริงที่ต้องการ,ตัวอักษรหรือคำที่ต้องการเติม,รูปแบบการเติม(STR_PAD_BOTH - เติมทั้งสองข้าง ถ้าไม่ลงตัวข้างขวาจะถูกเติมมากกว่า,STR_PAD_LEFT - เติมด้านซ้าย,STR_PAD_RIGHT - เติมด้านขวา (default)))
			echo "<br>"."count row =".$crow=number_format(str_pad($c,6,"0",STR_PAD_LEFT)); //เช่า $c=11 จะได้ 000011
			fclose($cc_data);	
			// end row data  //	

			// check ROW < > Total data then insert to table //
		   
			if($ftotal!=$crow){ //ตรวจสอบว่าค่า $crow หลังจากเติมสตริงแล้วมีค่าเท่ากับค่าในไฟล์หรือไม่
				echo "ข้อมูลมาไม่ครบ  กรุณาโหลดข้อมูลใหม่";
			
				$delFile=unlink("upload/".$c_code."/".$_FILES["file"]["name"]);
				if($delFile){
					echo "File Deleted";
				}else{
					echo "File can not delete";
				}
			  
				//echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_loaddata.php'\">";
				//echo "<meta http-equiv=\"refresh\" content=\"5;URL=frm_loaddata.php\">";   
			}else{ //กรณีที่อ่านไฟล์ได้ครบ
				$datafile=fopen("upload/".$c_code."/".$_FILES["file"]["name"],"r");

				while (!feof($datafile)){
					$buffer = fgets($datafile, 4096);
			
					$text= explode(" ",$buffer);
			
					$dst=substr($text[0],0,256);
					$resd=substr($dst,0,1);
					if($resd=="D"){
						$n++;
						//$dtext=explode("H",$buffer);
						//echo $resdtext=substr($dtext[0],0,256)."<br>";
						//$dtext=explode(" ",$buffer);
				 
						//echo  $buffer."###"; 
						
						$terminal_sq_no=substr($buffer,1,6); //เอามาแค่ 6 ตำแหน่งโดยนับจากตำแหน่งที่ 1 (ไม่นับตัวอักษร D ตัวแรก)
						$bank_no=substr($buffer,7,3); //รหัสธนาคาร นับตั้งแต่ตัวที่ 7 ไป 3 ตัว (ไม่นับตัวอักษร D ตัวแรก) 
			
						$tr_date=substr($buffer,20,8); //วันที่ 
						$tr_time=substr($buffer,28,6); //เวลา
						$ref_name=substr($buffer,34,50); //ชื่อ โดยตรงนี้จะเว้น 50 ตัวอักษร
					    $ref_name=iconv('windows-874','UTF-8',$ref_name); //แปลงชื่อให้อ่านออกนะจ้ะ
						
						$ref1=substr($buffer,84,20);
						$bf_tref1=trim($ref1);
						$sb_t1=substr($bf_tref1,0,19);
						$t1icon=iconv('windows-874','UTF-8',$sb_t1);
						$tref1=str_replace("-","",$t1icon);
						
						$ref2=substr($buffer,104,20);
						$bf_tref2=trim($ref2);
						$sb_t2=substr($bf_tref2,0,19);
						$t2icon=iconv('windows-874','UTF-8',$sb_t2);
						$tref2=str_replace("-","",$t2icon);

						$pay_bank_branch=substr($buffer,145,3);
						$terminal_id=substr($buffer,148,4);
						$tran_type=substr($buffer,153,3);
						$pay_cheque_no=substr($buffer,156,7);
					   
						$samt=substr($buffer,163,13); //ต้องหาร /100
						$amt=$samt/100;

						$d_tr=substr($tr_date,4,4)."-".$date_tr=substr($tr_date,2,2)."-".substr($tr_date,0,2);
						$t_tr=substr($tr_time,0,2).":".substr($tr_time,2,2).":".substr($tr_time,4,2);

						//insert PostLog
			
						$datenow=date("Y-m-d");
						$qry_post=pg_query("select gen_pos_no('$datenow')");
						$res_genpost=pg_fetch_result($qry_post,0); //postID
						//end gen postcode
					
						//insert PostLog 
					
						$sql_ipostlog="insert into \"PostLog\"
									(\"PostID\",\"UserIDPost\",\"PostDate\",paytype ) 
									values 
									('$res_genpost','$id_user','$datenow','TR')";
						if($result_ps=pg_query($sql_ipostlog)){
							$status ="OK".$sql_ipostlog;
						}else{
							$status ="error insert sql".$sql_ipostlog;
						}
											
						// echo $status;
					
						// ใส่ข้อมูลการรับชำระลง  Table => TranPay
						//echo $tref1.",$tref2<br>";
						$qry_ref=pg_query("select \"IDNO\", \"full_name\" from \"VContact\" where (\"TranIDRef1\"='$tref1') AND (\"TranIDRef2\"='$tref2') ");
						$res_ref=pg_fetch_array($qry_ref);  //ขั้นตอนนี้ถ้า ref1 = 77 จะไม่แสดงข้อมูลเนื่องจากข้อมูลใน  VContact ไม่มีข้อมูลลูกค้าของจรัญ
						
						// ถ้าข้อมูล Ref1 และ Ref2 ไม่ตรงอาจจะเป็นเพราะรหัสการชำระเป็น Ref ที่ Gen จากระบบเก่าก็ได้ ซึ่งต่างกับระบบใหม่ ให้ ตรวจสอบดูกับฐานข้อมูลเก่าอีกครั้ง
						$num_rows = pg_num_rows($qry_ref);
						if($num_rows == 0){
							// ไม่มีข้อมูลในระบบปัจจุบัน ... ลองเช็คกับระบบเก่าดู ถ้ามีให้เก็บค่า IDNO ที่ Ref1, Ref2 ตรงกันในระบบเก่าไว้
							$qry_oldref = pg_query("select * from pmain.new_fp_trans where (\"TranIDRef1\"='$tref1') AND (\"TranIDRef2\"='$tref2') ");
							$num_oldref = pg_num_rows($qry_oldref);
							
							if($num_oldref == 0){
								$checktref=substr($tref1,0,2);  //ตัดคำเอาเลข 2 ตัวแรกมา ถ้าเป็น 77 แสดงว่าเป็นจรัญ ถ้าไม่ใช่ แสดงว่า อาจกรอกข้อมูลผิด
								
								if($checktref == "77"){
									$branch_id = "2";
								}else{
									$branch_id = "1";	
								}
								$res_idno="";
								$tref1=$tref1;
								$tref2=$tref2;
								$fullname = $ref_name;
								//echo "<br>$branch_id,$terminal_sq_no,";
							}else{
								$res_oldref=pg_fetch_array($qry_oldref);
								$res_oldidno = $res_oldref["IDNO"];
								
								// นำค่า IDNO ในระบบเก่าที่ตรงกันทั้ง Ref1, Ref2 มา หา Ref1, Ref2 ใหม่เพื่อให้ระบบใหม่รู้จักอย่างถูกต้อง
								$qry_ref=pg_query("select \"IDNO\", \"full_name\", \"TranIDRef1\", \"TranIDRef2\" from \"VContact\" where \"IDNO\"='$res_oldidno'");
								$res_ref=pg_fetch_array($qry_ref);
								$res_idno=$res_oldidno;
								$tref1=$res_ref["TranIDRef1"];
								$tref2=$res_ref["TranIDRef2"];
								$fullname=$res_ref["full_name"];
							}
						}else{
							// มีข้อมูลในระบบปัจจุบัน ให้ ดึง IDNO และ full_name ไปใช้ได้เลย
							$res_idno=$res_ref["IDNO"];
							$fullname=$res_ref["full_name"];
						}
					
						if($branch_id == "1" || $branch_id == "2"){
							$in_sql="insert into \"TranPay\"(branch_id,terminal_sq_no, bank_no, tr_date, tr_time, ref_name, ref1, ref2,
							   pay_bank_branch, terminal_id, tran_type, pay_cheque_no, amt,\"PostID\",post_to_idno) 
							   values  
							  ('$branch_id','$terminal_sq_no','$bank_no','$d_tr','$t_tr','$fullname','$tref1','$tref2',
							   '$pay_bank_branch','$terminal_id','$tran_type','$pay_cheque_no','$amt','$res_genpost','$res_idno')";
						}else{
						// INSERT ข้อมูล TranPay ลงใน Table
							$in_sql="insert into \"TranPay\"(terminal_sq_no, bank_no, tr_date, tr_time, ref_name, ref1, ref2,
							   pay_bank_branch, terminal_id, tran_type, pay_cheque_no, amt,\"PostID\",post_to_idno) 
							   values  
							  ('$terminal_sq_no','$bank_no','$d_tr','$t_tr','$fullname','$tref1','$tref2',
							   '$pay_bank_branch','$terminal_id','$tran_type','$pay_cheque_no','$amt','$res_genpost','$res_idno')";
						}
						if($result=pg_query($in_sql)){
							$st_fn="OK".$in_sql;
						}else{
							// แสดง error เพราะไม่มีข้อมูลไป insert
							$st_fn="error insert Re".$in_sql;
						}	
						//echo $st_fn."<br>";	
					}else{
					} 
				$branch_id = 0;
				}  //end while วน record ที่อยู่ใน text file  
			}
			// fclose($datafile);	

			if(($result) and ($result_ps) and ($res_genpost))
			{
				
					//ACTIONLOG
						$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำการโหลด Bill Payment', '$datelog')");
					//ACTIONLOG---
				pg_query("COMMIT");
				echo "<br>"."<b>"."บันทึกข้อมูลเรียบร้อย รอสักครู่ "."</b>";
				fclose($datafile);
				echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='list_load.php?pdate=$d_tr'\">";
				//echo "<meta http-equiv=\"refresh\" content=\"4;URL=list_load.php?pdate=$d_tr\">";
			}
			else
			{
				pg_query("ROLLBACK");
				echo "มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่".$residno;
				fclose($datafile);
				echo "<input type=\"button\" value=\"ตกลง\" onclick=\"window.location='frm_loaddata.php'\">";
				//echo "<meta http-equiv=\"refresh\" content=\"5;URL=frm_loaddata.php\" >";
			} 
		  
		}
	}
?> 
</body>
</html>