<?php
session_start();
include("../../config/config.php");

$cmd = $_REQUEST['cmd'];
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;

$payment = json_decode(stripcslashes($_POST["payment"]));
$payment2 = json_decode(stripcslashes($_POST["payment2"]));
$note = pg_escape_string($_POST["note"]); if($note==""){ $note="null";}else{ $note="'".$note."'"; }

//หาค่า auto_id (ที่ใช้ SEQUENCE ไม่ได้เนื่องจากต้องนำค่านี้ไปบันทึกต่อในตารางอื่นๆด้วย)
$qry_autoId=pg_query("select MAX(\"auto_id\") as autoid from \"temp_linksecur\" ");
if($res_autoid=pg_fetch_array($qry_autoId)){
	$auto_id=$res_autoid["autoid"];
						
	if($auto_id=="" || $auto_id=="0"){
		$auto_id=1;
	}else{
		$auto_id++;
	}
}	
if($cmd == "add"){ 
	$number_running = pg_escape_string($_POST["number_running"]);  //ลำดับที่ 
	
	//ตรวจสอบว่าเลข number_running นี้มีการรออนุมัติ หรือได้ถูกเพิ่มไปก่อนหน้านี้แ้ล้วหรือไม่
	$qry_link=pg_query("SELECT \"statusApp\" FROM temp_linksecur where \"number_running\"='$number_running' and edittime='0' order by \"edittime\" DESC limit(1)");
	$res_link=pg_fetch_array($qry_link);
	$statusApp=$res_link["statusApp"];
		
	if($statusApp=="" || $statusApp=="0"){ //กรณีที่เลขนี้ยังไม่มีการบันทึกในตารางหลักให้ insert รายการได้	
		/*
		//หาค่า number_running เพิ่มขึ้นเรื่อยๆ กรณีเพิ่มข้อมูลเท่านั้น ตอนนี้ยังไม่ได้ใช้ให้ key มือไปก่อน
		$qry_numID=pg_query("select MAX(\"number_running\") as numid from \"temp_linksecur\" where \"edittime\"='0'");	
		if($res_numID=pg_fetch_array($qry_numID)){
			$numID=$res_numID["numid"];
						
			if($numID=="" || $numID=="0"){
				$numID=1;
			}else{
				$numID++;
			}
		}	
		*/	
			
		//บันทึกข้อมูลในตาราง temp_linksecur 
		$ins_templink="INSERT INTO temp_linksecur(
			auto_id,\"number_running\", note, edittime,user_add, \"stampDateAdd\",\"statusApp\")
			VALUES ('$auto_id', '$number_running', $note, '0', '$id_user', '$currentdate', '2')";
		if($resin_templink=pg_query($ins_templink)){
		}else{
			$status++;
		}

		//บันทึกข้อมูลในตาราง temp_linknumsecur เพื่อเก็บว่าการเชื่อมโยงครั้งนี้มีหลักทรัพย์อะไรบ้าง
		$p=0;
		$x=0;
		$a=0;
		foreach($payment as $key => $value){
			$securID2 = $value->securID;
					
			if( empty($securID2)){
					continue;   
			}
			
			//ตรวจสอบค่าที่ได้ว่าเกิดการจากการคีย์เองหรือไม่ โดยเงื่อนไขแรกที่เช็คคือมีการคีย์ # หรือไม่ (เพราะถ้ามี # มี 2 เงื่อนไขคือเลือก และ คีย์เอง)
			$checksecur=strpos($securID2,'#'); //หาว่ามี # ในประโยคหรือไม่
			if($checksecur == true){   //กรณีพบค่าให้ตรวจสอบว่ามีข้อมูลนี้ในระบบหรือไม่
				$securID=explode("#",$securID2);
				$numDeed= str_replace("เลขที่โฉนด","",$securID[1]);
				$numDeed=trim($numDeed);
				
				//นำ securID ไปตรวจสอบก่อนว่ามีในตารางจริงหรือไม่ ถ้ามีให้บันทึก ถ้าไม่มีให้แจ้งกลับ
				$qry_checksecur=pg_query("select \"securID\" from \"nw_securities\" where \"securID\"='$securID[0]' and \"numDeed\"='$numDeed' and \"cancel\"='FALSE'");
				$num_checksecur=pg_num_rows($qry_checksecur);
				if($num_checksecur==0){ //กรณีไม่พบข้อมูลนี้ในระบบให้ออกจาก loop และแจ้งเตือน
					$status2=1;
					break;
				}else{ //กรณีพบข้อมูลในระบบจริง
					//เช็คด้วยว่าเลขที่คีย์ซ้ำกันหรือไม่ โดยนำ record ที่ได้มาวนซ้ำอีกรอบว่าซ้ำกับตัวใดหรือไม่
					$t=0;
					foreach($payment as $key => $value){
						$securOld2 = $value->securID;
						if( empty($securOld2)){
							continue;   
						}
						$securOld=explode("#",$securOld2);
						$numDeedOld= str_replace("เลขที่โฉนด","",$securOld[1]);
						$numDeedOld=trim($numDeedOld);
						
						$qry_checksecur2=pg_query("select \"securID\" from \"nw_securities\" where \"securID\"='$securOld[0]' and \"numDeed\"='$numDeedOld' and \"cancel\"='FALSE'");
						$num_checksecur2=pg_num_rows($qry_checksecur2);
						if($num_checksecur2==0){ //กรณีไม่พบข้อมูลนี้ในระบบให้ออกจาก loop และแจ้งเตือน
							$status2=1;
							break;
						}else{
							if($p==$t){ //ถือว่าเป็นตำแหน่งเดียวกันให้ตรวจสอบกันตัวถัดไป
								$t++;
								continue;
							}else{		//ถ้าคนละตำแหน่งต้องตรวจสอบว่ามีค่าเท่ากับหรือไม่
								if(trim($securOld[0])==trim($securID[0])){ //กรณีมีค่าเท่ากับถือว่าเป็นค่าซ้ำกันให้ออกจาก loop
									$a=1;
									break;
								}else{
									$x++;
								}
								$t++;
							}
						}
						
					} //end foreach 2
					if($a==1){  //กรณีมีค่าเท่ากับแสดงว่าเป็นค่าซ้ำให้ออกจาก loop
						$status2=2;
						break;
					}else{
						//กรณีไม่พบปัญหาใดๆ ให้ insert ตามปกติ
						$inssecur="INSERT INTO temp_linknumsecur(auto_id, \"securID\")VALUES ('$auto_id', '$securID[0]')";
						if($ressec=pg_query($inssecur)){
						}else{
							$status++;
						}
					}
				}
			}else{  
				//กรณีคีย์ด้วยมือโดยไม่ได้คีย์ # 
				$status2=1;
				break;
			}
			$p++;
		} //end foreach 1
		
		
		//บันทึกข้อมูลในตาราง temp_linkIDNO เพื่อเก็บว่าการเชื่อมโยงครั้งนี้มีเลขที่สัญญาอะไรบ้าง
		$p=0;
		$x=0;
		$a=0;
		foreach($payment2 as $key => $value){
			$IDNO = $value->IDNO;
			$guaranteeDate = $value->guaranteeDate; 
			if($guaranteeDate==""){ 
				$guaranteeDate="null";
			}else{ 
				$guaranteeDate="'".$guaranteeDate."'"; 
			}
			if( empty($IDNO)){
				continue;   
			}
			
			//ตรวจสอบ IDNO ที่ได้ก่อนว่ามีในฐานข้อมูลหรือไม่
			$qry_checkfp=pg_query("select \"IDNO\" from \"Fp\" where \"IDNO\"='$IDNO'");
			$num_checkfp=pg_num_rows($qry_checkfp);
			if($num_checkfp==0){ //หากพบว่าไม่มีข้อมูล
				$status2=3;
				break;
			}else{
				//กรณีถูกต้องตามเงื่อนไข ให้ตรวจสอบด้วยว่าเลือกเลขซ้ำหรือไม่
				$t=0;
				foreach($payment2 as $key => $value){
					$idnoOld = $value->IDNO;
					if( empty($idnoOld)){
						continue;   
					}
					if($p==$t){ //ถือว่าเป็นตำแหน่งเดียวกันให้ตรวจสอบกันตัวถัดไป
						$t++;
						continue;
					}else{		//ถ้าคนละตำแหน่งต้องตรวจสอบว่ามีค่าเท่ากับหรือไม่
						if(trim($idnoOld)==trim($IDNO)){ //กรณีมีค่าเท่ากับถือว่าเป็นค่าซ้ำกันให้ออกจาก loop
							$a=1;
							break;
						}else{
							$x++;
						}
						$t++;
					}
				}
				if($a==1){  //กรณีมีค่าเท่ากับแสดงว่าเป็นค่าซ้ำให้ออกจาก loop
					$status2=4;
					break;
				}else{		
					$insIDNO="INSERT INTO \"temp_linkIDNO\"(auto_id, \"IDNO\",\"guaranteeDate\")VALUES ('$auto_id', '$IDNO', $guaranteeDate)";
					if($resIDNO=pg_query($insIDNO)){
					}else{
						$status++;
					}
				}	
			}
			$p++;
		}
			
		if($status2==1){
			pg_query("ROLLBACK");
			echo "4";
		}else if($status2==2){
			pg_query("ROLLBACK");
			echo "5";
		}else if($status2==3){
		pg_query("ROLLBACK");
			echo "6";
		}else if($status2==4){
			pg_query("ROLLBACK");
			echo "7";
		}else{
			if($status == 0){
				pg_query("COMMIT");
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ขอเชื่อมโยงหลักทรัพย์ค้ำประกัน', '$currentdate')");
				//ACTIONLOG---	
				echo "1";
			}else{
				pg_query("ROLLBACK");
				echo "2";
			}
		}
	}else{
		echo "3";
	}
}else if($cmd=="edit"){
	$payment3 = json_decode(stripcslashes($_POST["payment3"]));
	$payment4 = json_decode(stripcslashes($_POST["payment4"]));
	$numid = pg_escape_string($_POST["numid"]); 
	
	//หาครั้งที่แก้ไข
	$qry_time=pg_query("select MAX(edittime) as timeedit from \"temp_linksecur\" where \"number_running\"='$numid'");
	$res_time=pg_fetch_array($qry_time);
	$edittime=$res_time["timeedit"]+1;
	
	//บันทึกข้อมูลในตารางหลัก temp_linksecur 
	$ins_linksecur="INSERT INTO temp_linksecur(
		auto_id,\"number_running\", \"note\",edittime, user_add, \"stampDateAdd\",\"statusApp\")
		VALUES ('$auto_id','$numid',$note,'$edittime','$id_user', '$currentdate','2')";
	if($resin_linksecur=pg_query($ins_linksecur)){
	}else{
		$status++;
	}
	
	// ให้ add securID เฉพาะข้อมูลที่เราต้องการกรณีที่เลือกลบข้อมูลเก่า
	$qry_linknumsecur=pg_query("select \"securID\" from \"nw_linknumsecur\" 
	where \"numid\" ='$numid' ");					
	while($res_numsecur=pg_fetch_array($qry_linknumsecur)){
		$securID2=trim($res_numsecur["securID"]);		
		$x=1;
		//ตรวจสอบว่าใช่ record ที่ต้องการลบหรือไม่
		foreach($payment3 as $key => $value){
			$delsecur = $value->delsecur;
			if($securID2==trim($delsecur)){
				$x=0;
				break;
			}else{
				$x++;
			}
		}
			
		if($x>0){	
			//ให้ insert เฉพาะข้อมูลที่เราต้องการ
			$inslinksecur="INSERT INTO temp_linknumsecur(auto_id, \"securID\")VALUES ('$auto_id', '$securID2')";
			if($reslinksecur=pg_query($inslinksecur)){
			}else{
				$status++;
			}		
		}
	}
	
	//กรณีเพิ่มเติมข้อมูลให้ insert เพิ่มเติม
	$p=0;
	$x=0;
	$a=0;
	foreach($payment as $key => $value){
		$securID2 = $value->securID;
				
		if( empty($securID2)){
				continue;   
		}
		//ตรวจสอบค่าที่ได้ว่าเกิดการจากการคีย์เองหรือไม่ โดยเงื่อนไขแรกที่เช็คคือมีการคีย์ # หรือไม่ (เพราะถ้ามี # มี 2 เงื่อนไขคือเลือก และ คีย์เอง)
		$checksecur=strpos($securID2,'#'); //หาว่ามี # ในประโยคหรือไม่
		if($checksecur == true){   //กรณีพบค่าให้ตรวจสอบว่ามีข้อมูลนี้ในระบบหรือไม่
			$securID=explode("#",$securID2);
			$numDeed= str_replace("เลขที่โฉนด","",$securID[1]);
			$numDeed=trim($numDeed);
				
			//นำ securID ไปตรวจสอบก่อนว่ามีในตารางจริงหรือไม่ ถ้ามีให้บันทึก ถ้าไม่มีให้แจ้งกลับ
			$qry_checksecur=pg_query("select \"securID\" from \"nw_securities\" where CAST(\"securID\" AS character varying)='$securID[0]' and \"numDeed\"='$numDeed' and \"cancel\"='FALSE'");
			$num_checksecur=pg_num_rows($qry_checksecur);
			if($num_checksecur==0){ //กรณีไม่พบข้อมูลนี้ในระบบให้ออกจาก loop และแจ้งเตือน
				$status2=1;
				break;
			}else{ //กรณีพบข้อมูลในระบบจริง
				//เช็คด้วยว่าเลขที่คีย์ซ้ำหรือไม่ โดยนำ record ที่ได้มาวนซ้ำอีกรอบว่าซ้ำกับตัวใดหรือไม่
				$t=0;
				foreach($payment as $key => $value){
					$securOld2 = $value->securID;
					if( empty($securOld2)){
						continue;   
					}
					$securOld=explode("#",$securOld2);
					$numDeedOld= str_replace("เลขที่โฉนด","",$securOld[1]);
					$numDeedOld=trim($numDeedOld);
						
					$qry_checksecur2=pg_query("select \"securID\" from \"nw_securities\" where \"securID\"='$securOld[0]' and \"numDeed\"='$numDeedOld' and \"cancel\"='FALSE'");
					$num_checksecur2=pg_num_rows($qry_checksecur2);
					if($num_checksecur2==0){ //กรณีไม่พบข้อมูลนี้ในระบบให้ออกจาก loop และแจ้งเตือน
						$status2=1;
						break;
					}else{
						if($p==$t){ //ถือว่าเป็นตำแหน่งเดียวกันให้ตรวจสอบกันตัวถัดไป
							$t++;
							continue;
						}else{		//ถ้าคนละตำแหน่งต้องตรวจสอบว่ามีค่าเท่ากับหรือไม่
							if(trim($securOld[0])==trim($securID[0])){ //กรณีมีค่าเท่ากับถือว่าเป็นค่าซ้ำกันให้ออกจาก loop
								$a=1;
								break;
							}else{
								$x++;
							}
							$t++;
						}
					}
						
				} //end foreach 2
				if($a==1){  //กรณีมีค่าเท่ากับแสดงว่าเป็นค่าซ้ำให้ออกจาก loop
					$status2=2;
					break;
				}else{
					$inssecur="INSERT INTO temp_linknumsecur(auto_id, \"securID\")VALUES ('$auto_id', '$securID[0]')";
					if($ressec=pg_query($inssecur)){
					}else{
						$status++;
					}
				}
			}
		}else{  
			//กรณีคีย์ด้วยมือโดยไม่ได้คีย์ # 
			$status2=1;
			break;
		}
		$p++;
	} //end foreach 1


	//ให้ add IDNO เฉพาะข้อมูลที่เราต้องการกรณีที่เลือกลบข้อมูลเก่า
	$qry_linkIDNO=pg_query("select \"IDNO\", \"guaranteeDate\" from \"nw_linkIDNO\" where \"numid\" ='$numid' ");					
	while($res_idno=pg_fetch_array($qry_linkIDNO)){
		$IDNO2=trim($res_idno["IDNO"]);	
		$guaranteeDate=trim($res_idno["guaranteeDate"]);
		if($guaranteeDate==""){ 
			$guaranteeDate="null";
		}else{ 
			$guaranteeDate="'".$guaranteeDate."'"; 
		}		
		$x=1;
		//ตรวจสอบว่าใช่ record ที่ต้องการลบหรือไม่
		foreach($payment4 as $key => $value){
			$delidno = $value->delidno;
			
			if($IDNO2==trim($delidno)){
				$x=0;
				break;
			}else{
				$x++;
			}
		}
			
		if($x>0){	
			//ให้ insert เฉพาะข้อมูลที่เราต้องการ
			$inslinkidno="INSERT INTO \"temp_linkIDNO\"(auto_id, \"IDNO\",\"guaranteeDate\")VALUES ('$auto_id', '$IDNO2',$guaranteeDate)";
			if($reslinkidno=pg_query($inslinkidno)){
			}else{
				$status++;
			}		
		}
	}
	
	//บันทึกข้อมูลในตาราง temp_linkIDNO เพื่อเก็บว่าเพิ่ม IDNO อะไรบ้าง
	$p=0;
	$x=0;
	$a=0;
	foreach($payment2 as $key => $value){
		$IDNO = $value->IDNO;
		$guaranteeDate = $value->guaranteeDate; 
		if($guaranteeDate==""){ 
			$guaranteeDate="null";
		}else{ 
			$guaranteeDate="'".$guaranteeDate."'"; 
		}
		if( empty($IDNO)){
			continue;   
		}
		
		//ตรวจสอบ IDNO ที่ได้ก่อนว่ามีในฐานข้อมูลหรือไม่
		$qry_checkfp=pg_query("select \"IDNO\" from \"Fp\" where \"IDNO\"='$IDNO'");
		$num_checkfp=pg_num_rows($qry_checkfp);
		if($num_checkfp==0){ //หากพบว่าไม่มีข้อมูล
			$status2=3;
			break;
		}else{
			//กรณีถูกต้องตามเงื่อนไข ให้ตรวจสอบด้วยว่าเลือกเลขซ้ำหรือไม่
			$t=0;
			foreach($payment2 as $key => $value){
				$idnoOld = $value->IDNO;
				if( empty($idnoOld)){
					continue;   
				}
				if($p==$t){ //ถือว่าเป็นตำแหน่งเดียวกันให้ตรวจสอบกันตัวถัดไป
					$t++;
					continue;
				}else{		//ถ้าคนละตำแหน่งต้องตรวจสอบว่ามีค่าเท่ากับหรือไม่
					if(trim($idnoOld)==trim($IDNO)){ //กรณีมีค่าเท่ากับถือว่าเป็นค่าซ้ำกันให้ออกจาก loop
						$a=1;
						break;
					}else{
						$x++;
					}
					$t++;
				}
			}
			if($a==1){  //กรณีมีค่าเท่ากับแสดงว่าเป็นค่าซ้ำให้ออกจาก loop
				$status2=4;
				break;
			}else{		
				//กรณีไม่พบปัญหาใดๆ ให้ ตรวจสอบว่าค่าซ้ำกับค่าเดิมหรือไม่
				$qryoldidno=pg_query("select \"IDNO\" from \"nw_linkIDNO\" where \"numid\" ='$numid' and \"IDNO\"='$IDNO'");
				$num_oldidno=pg_num_rows($qryoldidno);
				if($num_oldidno>0){ //แสดงว่ามีค่าซ้ำกับค่าเดิม
					$status2=5;
					break;
				}else{
					$insIDNO="INSERT INTO \"temp_linkIDNO\"(auto_id, \"IDNO\",\"guaranteeDate\")VALUES ('$auto_id', '$IDNO', $guaranteeDate)";
					if($resIDNO=pg_query($insIDNO)){
					}else{
						$status++;
					}
				}
			}	
		}
		$p++;
	}

	if($status2==1){
		pg_query("ROLLBACK");
		echo "4";
	}else if($status2==2){
		pg_query("ROLLBACK");
		echo "5";
	}else if($status2==3){
		pg_query("ROLLBACK");
		echo "6";
	}else if($status2==4){
		pg_query("ROLLBACK");
		echo "7";
	}else if($status2==5){
		pg_query("ROLLBACK");
		echo "8";
	}else{
		if($status == 0){
			pg_query("COMMIT");
			echo "1";
		}else{
			pg_query("ROLLBACK");
			echo "2";
		}
	}
}else if($cmd=="checknum"){
	$number_running=pg_escape_string($_POST["number_running"]);
	
	//ตรวจสอบว่ากำลังรออนุมัติอยู่หรือไม่
	$qry_link=pg_query("SELECT * FROM temp_linksecur where \"number_running\"='$number_running' and edittime='0' order by \"edittime\" DESC limit(1)");
	$res_link=pg_fetch_array($qry_link);
	$statusApp=$res_link["statusApp"];
	if($statusApp=="" || $statusApp=="0"){
		echo "1";
	}else if($statusApp=="2"){
		echo "2";
	}else{
		echo "3";
	}

}	
	 


?>