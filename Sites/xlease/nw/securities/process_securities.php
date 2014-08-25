<?php
session_start();
include("../../config/config.php");
include('class.upload.php');
include('../function/checknull.php');


$cmd = $_REQUEST['cmd'];
$method = $_REQUEST['method'];
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

if($cmd == "save"){
    pg_query("BEGIN WORK");
    $status = 0;
	
	$guaranID = $_POST["guaranID"];//ประเภทหลักประกัน 
	$numDeed = $_POST["numDeed"]; if($numDeed==""){ $numDeed="null";}else{ $numDeed="'".$numDeed."'"; }//โฉนดที่ดินเลขที่
	$cid = $_POST["cid"]; // เลขที่สัญญา แคปปิตอล
	if($guaranID=="1" || $guaranID=="3"){ //กรณีเป็นที่ดิน
		$numBook = $_POST["numBook"]; if($numBook==""){ $numBook="null";}else{ $numBook="'".$numBook."'"; }//เล่มที่
		$numPage = $_POST["numPage"]; if($numPage==""){ $numPage="null";}else{ $numPage="'".$numPage."'"; }//หน้าที่
		$numLand = $_POST["numLand"]; if($numLand==""){ $numLand="null";}else{ $numLand="'".$numLand."'"; }//เลขที่ดิน
		$pageSurvey = $_POST["pageSurvey"]; if($pageSurvey==""){ $pageSurvey="null";}else{ $pageSurvey="'".$pageSurvey."'"; }//หน้าสำรวจ
		$area_acre = $_POST["area_acre"]; if($area_acre==""){ $area_acre="null";}else{ $area_acre="'".$area_acre."'"; }//เนื้อที่ไร่
		$area_ngan = $_POST["area_ngan"]; if($area_ngan==""){ $area_ngan="null";}else{ $area_ngan="'".$area_ngan."'"; }//เนื้อที่งาน
		$area_sqyard = $_POST["area_sqyard"]; if($area_sqyard==""){ $area_sqyard="null";}else{ $area_sqyard="'".$area_sqyard."'"; }//เนื้อที่ตารางวา
	}else{ //กรณีเป็นห้องชุด
		$condoroomnum = $_POST["condoroomnum"]; if($condoroomnum==""){ $condoroomnum="null";}else{ $condoroomnum="'".$condoroomnum."'"; }//ห้องชุดเลขที่
		$condofloor = $_POST["condofloor"]; if($condofloor==""){ $condofloor="null";}else{ $condofloor="'".$condofloor."'"; }//ชั้นที่
		$condobuildingnum = $_POST["condobuildingnum"]; if($condobuildingnum==""){ $condobuildingnum="null";}else{ $condobuildingnum="'".$condobuildingnum."'"; }//อาคารเลขที่
		$condoregisnum = $_POST["condoregisnum"]; if($condoregisnum==""){ $condoregisnum="null";}else{ $condoregisnum="'".$condoregisnum."'"; }//ทะเบียนอาคารชุด
		$condobuildingname = $_POST["condobuildingname"]; if($condobuildingname==""){ $condobuildingname="null";}else{ $condobuildingname="'".$condobuildingname."'"; }//ชื่ออาคารชุด
		$area_smeter = $_POST["area_smeter"]; if($area_smeter==""){ $area_smeter="null";}else{ $area_smeter="'".$area_smeter."'"; }//เนื้อที่ตารางเมตร
	}
	$district = $_POST["district"]; if($district==""){ $district="null";}else{ $district="'".$district."'"; }//ตำบล/อำเภอ
	$proID = $_POST["proID"]; //จังหวัด
	$note = $_POST["note"]; if($note==""){ $note="null";}else{ $note="'".$note."'"; }//หมา่ยเหตุ
	$cus=$_POST["CusID"];
	
	//หาค่า auto_id
	$qry_autoId=pg_query("select MAX(\"auto_id\") as autoid from \"temp_securities\" ");
	if($res_autoid=pg_fetch_array($qry_autoId)){
		$auto_id=$res_autoid["autoid"];
				
		if($auto_id=="" || $auto_id=="0"){
			$auto_id=1;
		}else{
			$auto_id++;
		}
	}	
	
	//กรณีเพิ่มข้อมูล
	if($method=="add"){	
		//หาค่า securID running เพิ่มขึ้นเรื่อยๆ กรณีที่เป็นเพิ่มข้อมูลเท่านั้น
		$qry_securId=pg_query("select MAX(\"securID\") as numsecur from \"temp_securities\" where \"statusAdd\"='0'");	
		if($res_secur=pg_fetch_array($qry_securId)){
			$numsecur=$res_secur["numsecur"];
				
			if($numsecur=="" || $numsecur=="0"){
				$numsecur=1;
			}else{
				$numsecur++;
			}
		}	
		
		//หาครั้งที่แก้ไขถ้าไม่พบให้ครั้งที่แก้ไขคือ 0 คือเพิ่มข้อมูล
		$qry_time=pg_query("select edittime from \"temp_securities\" where \"securID\"='$numsecur'");
		$num_time=pg_num_rows($qry_time);
		if($num_time==0){
			$edittime=0;
		}else{
			$res_time=pg_fetch_array($qry_time);
			$edittime=$res_time["edittime"]+1;
		}
		
		$cid = checknull($cid); // เลขที่สัญญา ของ ไทยเอซ แคปปิตอล
		
		//บันทึกข้อมูลในตารางหลัก temp_securities 
		if($guaranID=="1" || $guaranID=="3"){ //ที่ดิน
			$ins_secur="INSERT INTO temp_securities(
				auto_id,\"securID\", \"guaranID\", \"numDeed\", \"numBook\", \"numPage\", 
				\"numLand\", \"pageSurvey\", district, \"proID\", area_acre, area_ngan, 
				area_sqyard, edittime, note, user_add, \"stampDateAdd\", \"statusAdd\",\"statusApp\",\"contractID\")
				VALUES ('$auto_id','$numsecur', $guaranID, $numDeed, $numBook, $numPage, 
				$numLand, $pageSurvey, $district, '$proID', $area_acre, $area_ngan, 
				$area_sqyard, '$edittime', $note, '$id_user', '$currentdate', '0','2',$cid)";
		}else{ //ห้องชุด
			$ins_secur="INSERT INTO temp_securities(
				auto_id,\"securID\", \"guaranID\", \"numDeed\", condoroomnum, condofloor, condobuildingnum, 
				condobuildingname, condoregisnum, area_smeter, district, \"proID\", 
				edittime, note, user_add, \"stampDateAdd\", \"statusAdd\",\"statusApp\",\"contractID\")
				VALUES ('$auto_id','$numsecur', $guaranID,$numDeed, $condoroomnum, $condofloor, $condobuildingnum, 
				$condobuildingname, $condoregisnum,$area_smeter, $district, '$proID', 
				'$edittime', $note, '$id_user', '$currentdate', '0','2',$cid)";
		}
		if($resin_secur=pg_query($ins_secur)){
		}else{
			$status++;
		}
		
		//เพิ่มข้อมูลที่อยู่ลงตาราง  <--- บอสทำ พังบอกบอส
		
		 $s_no = $_POST['s_no']; //บ้านเลขที่	
		 $s_subno = $_POST['s_subno']; //หมู่
		 $s_village = $_POST['s_village']; //หมู่บ้าน
		 $soi = $_POST['soi']; //ซอย
		 $rd = $_POST['rd']; //ถนน
		 $amphur = $_POST['amphur']; //อำเภอ
		 $district = $_POST['district']; //ตำบล
		 $post = $_POST['post']; //ตำบล
		
		$s_no = checknull($s_no);
		$s_subno = checknull($s_subno);
		$s_village = checknull($s_village);
		$soi = checknull($soi);
		$rd = checknull($rd);		
		$district = checknull($district);
		$post = checknull($post);
		
		if($amphur != ""){
			//หาชื่ออำเภอ
			$se_amp = "SELECT \"AMPHUR_NAME\" FROM amphur where \"AMPHUR_ID\" = '$amphur'";
			$que_amp = pg_query($se_amp);
			$re_amp = pg_fetch_array($que_amp);
			
			$amphur = $re_amp['AMPHUR_NAME'];
			$amphur = checknull($amphur);	
			
		}else{
			$amphur = checknull($amphur);
		}
		if($guaranID=="1" || $guaranID=="3"){ //ที่ดิน
			$ins_addr="INSERT INTO temp_securities_address(
            temp_secid,\"securID\", \"S_NO\",\"S_SUBNO\",\"S_VILLAGE\",\"S_SOI\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\")
			VALUES ('$auto_id','$numsecur',$s_no,$s_subno,$s_village, $soi, $rd, $district, $amphur, '$proID', $post)";
		}else{ //ห้องชุด
			$ins_addr="INSERT INTO temp_securities_address(
            temp_secid,\"securID\", \"S_BUILDING\", \"S_ROOM\", \"S_FLOOR\", 
             \"S_SOI\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\")
			VALUES ('$auto_id','$numsecur', $condobuildingname, $condoroomnum, $condofloor, $soi,$rd, $district, $amphur, '$proID', $post)";
		}
		
		if($resin_addr=pg_query($ins_addr)){
		}else{
			$status++;
		}
		
		// จบการเพิ่มที่อยู่    <--- บอสทำ พังบอกบอส
		
		
		
		
		
		$pp=0;
		$tt=0;
		for($i=0;$i<sizeof($cus);$i++){
			$a1 = $cus[$i];
				
			$a1=substr($a1,0,6);
				
			//ตรวจสอบว่ามีข้อมูลจริงหรือไม่ กันไว้เผื่อข้อมูลเกิดจากการคีย์เองไม่ได้เลือกจากระบบ
			$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\"='$a1'");
			$numfa1=pg_num_rows($qry_fa1);
			if($numfa1>0){ //กรณีพบข้อมูลให้ตรวจสอบต่อว่าข้อมูลซ้ำหรือไม่ถ้าซ้ำให้กรอกใหม่
				$inscus="INSERT INTO temp_securities_customer(auto_id, \"CusID\")VALUES ('$auto_id', '$a1')";
				if($rescus=pg_query($inscus)){
				}else{
					$status++;
				}
			}else{ //กรณีข้อมูลไม่มีจริง อาจจะกรอกมั่วมา
				$tt++;
				break;
			}
		}
		
		
		//add file upload 
		$cli = (isset($argc) && $argc > 1);
		if ($cli) {
			if (isset($argv[1])) $_GET['file'] = $argv[1];
			if (isset($argv[2])) $_GET['dir'] = $argv[2];
			if (isset($argv[3])) $_GET['pics'] = $argv[3];
		}

		// set variables
		$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'upload');
		$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
		
		$files = array();
		foreach ($_FILES['my_field'] as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
					$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
		foreach ($files as $file) {
			$handle = new Upload($file);
	   
			if($handle->uploaded) {
				// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
				$prepend = date("YmdHis")."_";
				$handle->file_name_body_pre = $prepend;
				$handle->Process($dir_dest);    
				if ($handle->processed) {
					
					$pathfile=$handle->file_dst_name;
					
					$insupload="INSERT INTO temp_securities_upload(auto_id, \"upload\") VALUES ('$auto_id', '$pathfile')";
					if($resup=pg_query($insupload)){
					}else{
						$status++;
					}
				} else {
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $handle->error . '';
					echo '</fieldset>';
					$status++;
				}
			}
		}

	}else if($method=="edit"){
		$securID = $_POST["securID"]; 
		$delcus=$_POST["delcus"];
		$delpic=$_POST["delpic"];
		
		//หาครั้งที่แก้ไข
		$qry_time=pg_query("select MAX(edittime) as timeedit from \"temp_securities\" where \"securID\"='$securID'");
		$res_time=pg_fetch_array($qry_time);
		$edittime=$res_time["timeedit"]+1;

		
		if($guaranID=="1" || $guaranID=="3"){ //ที่ดิน
			$ins_secur="INSERT INTO temp_securities(
				auto_id,\"securID\", \"guaranID\", \"numDeed\", \"numBook\", \"numPage\", 
				\"numLand\", \"pageSurvey\", district, \"proID\", area_acre, area_ngan, 
				area_sqyard, edittime, note, user_add, \"stampDateAdd\", \"statusAdd\",\"statusApp\")
				VALUES ('$auto_id','$securID', $guaranID, $numDeed, $numBook, $numPage, 
				$numLand, $pageSurvey, $district, '$proID', $area_acre, $area_ngan, 
				$area_sqyard, '$edittime', $note, '$id_user', '$currentdate', '1','2')";
		}else{ //ห้องชุด
			$ins_secur="INSERT INTO temp_securities(
				auto_id,\"securID\", \"guaranID\", \"numDeed\", condoroomnum, condofloor, condobuildingnum, 
				condobuildingname, condoregisnum, area_smeter, district, \"proID\", 
				edittime, note, user_add, \"stampDateAdd\", \"statusAdd\",\"statusApp\")
				VALUES ('$auto_id','$securID', $guaranID,$numDeed, $condoroomnum, $condofloor, $condobuildingnum, 
				$condobuildingname, $condoregisnum,$area_smeter, $district, '$proID', 
				'$edittime', $note, '$id_user', '$currentdate', '1','2')";
		}
		
		if($resin_secur=pg_query($ins_secur)){
		}else{
			$status++;
		}
		
		
		//เพิ่มข้อมูลที่อยู่ลงตาราง  <--- บอสทำ พังบอกบอส
		
		$s_no = $_POST['s_no']; //บ้านเลขที่	
		 $s_subno = $_POST['s_subno']; //หมู่
		 $s_village = $_POST['s_village']; //หมู่บ้าน
		 $soi = $_POST['soi']; //ซอย
		 $rd = $_POST['rd']; //ถนน
		 $amphur = $_POST['amphur']; //อำเภอ
		 $district = $_POST['district']; //ตำบล
		 $post = $_POST['post']; //ตำบล
		
		if($district == ""){
		
		$district = $_POST['tumhid'];
		}
		
		$s_no = checknull($s_no);
		$s_subno = checknull($s_subno);
		$s_village = checknull($s_village);
		$soi = checknull($soi);
		$rd = checknull($rd);		
		$district = checknull($district);
		$post = checknull($post);
		
		if($amphur != ""){
			//หาชื่ออำเภอ
			$se_amp = "SELECT \"AMPHUR_NAME\" FROM amphur where \"AMPHUR_ID\" = '$amphur'";
			$que_amp = pg_query($se_amp);
			$re_amp = pg_fetch_array($que_amp);
			
			$amphur = $re_amp['AMPHUR_NAME'];
			$amphur = checknull($amphur);	
			
		}else{
			$amphur = checknull($amphur);
		}
		if($guaranID=="1" || $guaranID=="3"){ //ที่ดิน
			$ins_addr="INSERT INTO temp_securities_address(
            temp_secid,\"securID\",\"S_NO\",\"S_SUBNO\",\"S_VILLAGE\", \"S_SOI\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\")
			VALUES ('$auto_id','$securID',$s_no,$s_subno,$s_village, $soi, $rd, $district, $amphur, '$proID', $post)";
		}else{ //ห้องชุด
			$ins_addr="INSERT INTO temp_securities_address(
            temp_secid,\"securID\", \"S_BUILDING\", \"S_ROOM\", \"S_FLOOR\", 
             \"S_SOI\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\")
			VALUES ('$auto_id','$securID', $condobuildingname, $condoroomnum, $condofloor, $soi,$rd, $district, $amphur, '$proID', $post)";
		}
		
		if($resin_addr=pg_query($ins_addr)){
		}else{
			$status++;
		}
		
		// จบการเพิ่มที่อยู่    <--- บอสทำ พังบอกบอส
		
		
		
		
		//ลบ CusID ที่ไม่ต้องการออก ให้ add เฉพาะข้อมูลที่เราต้องการ
		$qry_cus=pg_query("select * from \"nw_securities_customer\" where \"securID\" ='$securID' ");					
		while($res_cus=pg_fetch_array($qry_cus)){
			$CusID2=trim($res_cus["CusID"]);		
			$x=1;
			//ตรวจสอบว่าใช่ record ที่ต้องการลบหรือไม่
			for($p=0;$p<sizeof($delcus);$p++){
				$aa = $delcus[$p];
				if($CusID2==trim($aa)){
					$x=0;
					break;
				}else{
					$x++;
				}
			}
			
			if($x>0){				
				$inscus="INSERT INTO temp_securities_customer(auto_id, \"CusID\")VALUES ('$auto_id', '$CusID2')";
				if($rescus=pg_query($inscus)){
				}else{
					$status++;
				}
				
			}
		}	
		
		//insert ข้อมูล  cusid ที่เพิ่มเติม
		$pp=0;
		$tt=0;
		for($i=0;$i<sizeof($cus);$i++){
			$a1 = $cus[$i];
				
			$a1=substr($a1,0,6);
			
			//ตรวจสอบว่ามีข้อมูลจริงหรือไม่ กันไว้เผื่อข้อมูลเกิดจากการคีย์เองไม่ได้เลือกจากระบบ
			$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\"='$a1'");
			$numfa1=pg_num_rows($qry_fa1);
			if($numfa1>0){ //กรณีพบข้อมูลให้ตรวจสอบต่อว่าข้อมูลซ้ำหรือไม่ถ้าซ้ำให้กรอกใหม่
				$qry_cus=pg_query("select * from \"nw_securities_customer\" a
							left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
							where a.\"securID\" ='$securID' and a.\"CusID\"='$a1'");
				$numcus=pg_num_rows($qry_cus);
				if($numcus>0){ //กรณีพบว่าซ้ำกับข้อมูลเก่า
					$pp++;
					break;
				}else{ //กรณีไม่พบว่าซ้ำกับข้อมูลเก่าให้ insert ตามปกติ
					$inscus="INSERT INTO temp_securities_customer(auto_id, \"CusID\")VALUES ('$auto_id', '$a1')";
					if($rescus=pg_query($inscus)){
					}else{
						$status++;
					}
				}
			}else{ //กรณีข้อมูลไม่มีจริง อาจจะกรอกมั่วมา
				$tt++;
				break;
			}
		}//end for
		
		//ลบ pic ที่ไม่ต้องการออก ให้ add เฉพาะข้อมูลที่เราต้องการ
		$qry_pic=pg_query("select * from \"nw_securities_upload\" where \"securID\" ='$securID' ");					
		while($res_pic=pg_fetch_array($qry_pic)){
			$picload=trim($res_pic["upload"]);		
			$y=1;
			//ตรวจสอบว่าใช่ record ที่ต้องการลบหรือไม่
			for($t=0;$t<sizeof($delpic);$t++){
				$picdel = $delpic[$t];
				if($picload==trim($picdel)){
					$y=0;
					break;
				}else{
					$y++;
				}
			}
			
			if($y>0){		
				$inscus="INSERT INTO temp_securities_upload(auto_id, \"upload\")VALUES ('$auto_id', '$picload')";
				if($rescus=pg_query($inscus)){
				}else{
					$status++;
				}
				
			}
		}	
		
		//add file upload 
		$cli = (isset($argc) && $argc > 1);
		if ($cli) {
			if (isset($argv[1])) $_GET['file'] = $argv[1];
			if (isset($argv[2])) $_GET['dir'] = $argv[2];
			if (isset($argv[3])) $_GET['pics'] = $argv[3];
		}

		// set variables
		$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'upload');
		$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
		
		$files = array();
		foreach ($_FILES['my_field'] as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
					$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
		foreach ($files as $file) {
			$handle = new Upload($file);
	   
			if($handle->uploaded) {
				// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
				$prepend = date("YmdHis")."_";
				$handle->file_name_body_pre = $prepend;
				$handle->Process($dir_dest);    
				if ($handle->processed) {
					
					$pathfile=$handle->file_dst_name;
					
					$insupload="INSERT INTO temp_securities_upload(auto_id, \"upload\") VALUES ('$auto_id', '$pathfile')";
					if($resup=pg_query($insupload)){
					}else{
						$status++;
					}
				} else {
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $handle->error . '';
					echo '</fieldset>';
					$status++;
				}
			}
		}
	} 
	if($pp>0){  //
		pg_query("ROLLBACK");
		echo "<div style=\"padding:20px;text-align:center\"><b>ข้อมูลเจ้าของกรรมสิทธิ์บางรายซ้ำกับข้อมูลเก่า กรุณาทำรายการใหม่อีกครั้ง</b></div>";
		if($method=="edit"){
			echo "<meta http-equiv='refresh' content='5; URL=frm_Edit.php?securID=$securID'>";
		}else{
			echo "<meta http-equiv='refresh' content='5; URL=frm_IndexAdd.php'>";
		}	
	}else if($tt>0){
		pg_query("ROLLBACK");
		echo "<div style=\"padding:20px;text-align:center\"><b>เจ้าของกรรมสิทธิ์บางรายผิดพลาด !! เพื่อป้องกันการผิดพลาดกรุณาเลือกเจ้าของกรรมสิทธิ์ที่ระบบกำหนดให้ค่ะ</b></div>";
		if($method=="edit"){
			echo "<meta http-equiv='refresh' content='5; URL=frm_Edit.php?securID=$securID'>";
		}else{
			echo "<meta http-equiv='refresh' content='5; URL=frm_IndexAdd.php'>";
		}	
	}else{
		if($status == 0){
			
			pg_query("COMMIT");
			echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
			if($method=="edit"){
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ขอแก้ไขหลักทรัพย์', '$currentdate')");
				//ACTIONLOG---	
				echo "<meta http-equiv='refresh' content='2; URL=frm_IndexEdit.php'>";
			}else{
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ขอเพิ่มหลักทรัพย์', '$currentdate')");
				//ACTIONLOG---	
				echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
			}	
		}else{
			pg_query("ROLLBACK");
			echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
			if($method=="edit"){
				echo "<meta http-equiv='refresh' content='3; URL=frm_IndexEdit.php'>";
			}else{
				echo "<meta http-equiv='refresh' content='3; URL=frm_IndexAdd.php'>";
			}
		}
	}	
}else if($cmd=="return"){
	pg_query("BEGIN WORK");
    $status = 0;
	
	$returnDate=$_POST["returnDate"];
	$securID=$_POST["securID"];
	$numid=$_POST["numid"];
	$CusID2=$_POST["CusID"];
	$CusID=substr($CusID2,0,6);
	
	//ขอคืนหลักทรัพย์
	$ins="INSERT INTO temp_securities_reqreturns(
            \"securID\", \"returnDate\", \"CusIDReceiveReturn\", \"userRequest\", 
            \"dateRequest\", \"statusApp\",\"numid\")
    VALUES ('$securID', '$returnDate', '$CusID', '$id_user', 
            '$currentdate', '2','$numid')";
	if($res_ins=pg_query($ins)){
	}else{
		$status++;
	}
	
	if($status == 0){
	
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ขอคืนหลักทรัพย์ค้ำประกัน', '$currentdate')");
		//ACTIONLOG---
		pg_query("COMMIT");
		//pg_query("ROLLBACK");
		
		?>
		<div align="center" style="padding:50px;">
		<FORM METHOD=GET ACTION="#">
		<input type="submit" value="  ปิดหน้าต่าง  " onclick="javascript:RefreshMe();" />
		</FORM>
		</div>
	<?php
	}else{
		pg_query("ROLLBACK");
	}
	?>
	<script language="JavaScript" type="text/javascript">
	function RefreshMe(){
		opener.location.reload(true);
		self.close();
	}
	</script> 
	<?php
}
?>
