<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$auto_id=$_REQUEST["auto_id"]; 
$statusapp=$_REQUEST["stsapp"];
if($statusapp==""){ //ถ้ามาจาก หน้า  showdetail.php
	if(isset($_POST["appv_detil"])){ //กด อนุมัติ
		$statusapp=1;
	}
	else if(isset($_POST["unappv_detil"])){ //กดไม่อนุมัติ
		$statusapp=0;
	}
}
if($statusapp==""){ //ถ้ามาจาก หน้า  showdetailcondo.php
	if(isset($_POST["appv_condo"])){ //กด อนุมัติ
		$statusapp=1;
	}
	else if(isset($_POST["unappv_condo"])){ //กดไม่อนุมัติ
		$statusapp=0;
	}
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
pg_query("BEGIN WORK");
$status = 0;

if($statusapp==1){ //กรณีอนุมัติ ต้องดูก่อนว่าอนุมัติเพิ่มข้อมูลหรือแก้ไข
	$qry_cus=pg_query("select * from \"temp_securities\" where \"auto_id\"='$auto_id'");
	$res_cus=pg_fetch_array($qry_cus);
	$securID=$res_cus["securID"];
	$guaranID=trim($res_cus["guaranID"]); 
	$numDeed=trim($res_cus["numDeed"]);
	
	
	if($guaranID=="1" || $guaranID=="3"){ //กรณีเป็นที่ีดิน
		$numBook = trim($res_cus["numBook"]); if($numBook==""){ $numBook="null";}else{ $numBook="'".$numBook."'"; }//เล่มที่
		$numPage = trim($res_cus["numPage"]); if($numPage==""){ $numPage="null";}else{ $numPage="'".$numPage."'"; }//หน้าที่
		$numLand = trim($res_cus["numLand"]); if($numLand==""){ $numLand="null";}else{ $numLand="'".$numLand."'"; }//เลขที่ดิน
		$pageSurvey = trim($res_cus["pageSurvey"]); if($pageSurvey==""){ $pageSurvey="null";}else{ $pageSurvey="'".$pageSurvey."'"; }//หน้าสำรวจ
		$area_acre = trim($res_cus["area_acre"]); if($area_acre==""){ $area_acre="null";}else{ $area_acre="'".$area_acre."'"; }//เนื้อที่ไร่
		$area_ngan = trim($res_cus["area_ngan"]); if($area_ngan==""){ $area_ngan="null";}else{ $area_ngan="'".$area_ngan."'"; }//เนื้อที่งาน
		$area_sqyard = trim($res_cus["area_sqyard"]); if($area_sqyard==""){ $area_sqyard="null";}else{ $area_sqyard="'".$area_sqyard."'"; }//เนื้อที่ตารางวา
	}else{ //กรณีเป็นห้องชุด
		$condoroomnum = trim($res_cus["condoroomnum"]); if($condoroomnum==""){ $condoroomnum="null";}else{ $condoroomnum="'".$condoroomnum."'"; }//ห้องชุดเลขที่
		$condofloor = trim($res_cus["condofloor"]); if($condofloor==""){ $condofloor="null";}else{ $condofloor="'".$condofloor."'"; }//ชั้นที่
		$condobuildingnum = trim($res_cus["condobuildingnum"]); if($condobuildingnum==""){ $condobuildingnum="null";}else{ $condobuildingnum="'".$condobuildingnum."'"; }//อาคารเลขที่
		$condoregisnum = trim($res_cus["condoregisnum"]); if($condoregisnum==""){ $condoregisnum="null";}else{ $condoregisnum="'".$condoregisnum."'"; }//ทะเบียนอาคารชุด
		$condobuildingname = trim($res_cus["condobuildingname"]); if($condobuildingname==""){ $condobuildingname="null";}else{ $condobuildingname="'".$condobuildingname."'"; }//ชื่ออาคารชุด
		$area_smeter = trim($res_cus["area_smeter"]); if($area_smeter==""){ $area_smeter="null";}else{ $area_smeter="'".$area_smeter."'"; }//เนื้อที่ตารางเมตร
	}
	
	
	$district=trim($res_cus["district"]); if($district==""){ $district="null";}else{ $district="'".$district."'"; }//ตำบล
	$proID=trim($res_cus["proID"]);	
	$edittime=trim($res_cus["edittime"]);
	$note=trim($res_cus["note"]);  if($note==""){ $note="null";}else{ $note="'".$note."'"; }//หมายเหตุ
	$contractID = trim($res_cus["contractID"]); // เลขที่สัญญา ของ ไทยเอซ แคปปิตอล
	
	$contractID = checknull($contractID); // เลขที่สัญญา ของ ไทยเอซ แคปปิตอล
	
	if($edittime==0){ //กรณีเพิ่มข้อมูล
		//insert ใน nw_securities
		if($guaranID=="1" || $guaranID=="3"){ //กรณีเป็นที่ีดิน
			$insnw="INSERT INTO nw_securities(
					\"securID\", \"guaranID\", \"numDeed\", \"numBook\", \"numPage\", \"numLand\", 
					\"pageSurvey\", district, \"proID\", area_acre, area_ngan, area_sqyard, 
					note, \"contractID\")
				VALUES ('$securID', $guaranID, '$numDeed', $numBook, $numPage, $numLand, 
					$pageSurvey, $district, '$proID', $area_acre, $area_ngan, $area_sqyard, 
					$note, $contractID)";
		}else{
			$insnw="INSERT INTO nw_securities(
					\"securID\", \"guaranID\", \"numDeed\", condoroomnum, condofloor, condobuildingnum, 
					condobuildingname, condoregisnum, area_smeter, district, \"proID\",note, \"contractID\")
				VALUES ('$securID', $guaranID, '$numDeed', $condoroomnum, $condofloor, $condobuildingnum, 
					$condobuildingname,$condoregisnum,$area_smeter, $district, '$proID', $note, $contractID)";
		}
		if($res_nw=pg_query($insnw)){
		}else{
			$error1=$insnw;
			$status++;
		}
		
		
		
		//เพิ่มข้อมูลที่อยู่ลงตาราง  <--- บอสทำ พังบอกบอส
		
		$qry_addr=pg_query("select * from \"temp_securities_address\" where \"temp_secid\"='$auto_id'");
		$res_addr=pg_fetch_array($qry_addr);
		
		
		$RealsecID  = $res_addr['securID']; //รหัส
		$S_BUILDING  = $res_addr['S_BUILDING']; //รหัส
		$S_ROOM  = $res_addr['S_ROOM']; //รหัส
		$S_FLOOR  = $res_addr['S_FLOOR']; //รหัส
		$soi = $res_addr['S_SOI']; //ซอย
		$rd = $res_addr['S_RD']; //ถนน
		$amphur = $res_addr['S_AUM']; //อำเภอ
		$district = $res_addr['S_TUM']; //ตำบล
		$post = $res_addr['S_POST']; //ไปรษณีย์
		$S_NO=$res_addr["S_NO"];	
		$S_SUBNO=$res_addr["S_SUBNO"];	
		$S_VILLAGE=$res_addr["S_VILLAGE"];
		
		$S_NO = checknull($S_NO);
		$S_SUBNO = checknull($S_SUBNO);
		$S_VILLAGE = checknull($S_VILLAGE);
		$S_BUILDING = checknull($S_BUILDING);
		$S_ROOM = checknull($S_ROOM);
		$S_FLOOR = checknull($S_FLOOR);
		$amphur = checknull($amphur);
		$soi = checknull($soi);
		$rd = checknull($rd);		
		$district = checknull($district);
		$post = checknull($post);
		
		
		
		if($guaranID=="1" || $guaranID=="3"){ //ที่ดิน
			$ins_addr="INSERT INTO nw_securities_address(
            \"securID\", \"S_SOI\",\"S_NO\",\"S_SUBNO\",\"S_VILLAGE\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\")
			VALUES ('$RealsecID',$soi,$S_NO,$S_SUBNO,$S_VILLAGE, $rd, $district, $amphur, '$proID', $post)";
		}else{ //ห้องชุด
			$ins_addr="INSERT INTO nw_securities_address(
            \"securID\", \"S_BUILDING\", \"S_ROOM\", \"S_FLOOR\", 
             \"S_SOI\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\")
			VALUES ('$RealsecID', $S_BUILDING, $S_ROOM, $S_FLOOR, $soi,$rd, $district, $amphur, '$proID', $post)";
		}
		
		if($resin_addr=pg_query($ins_addr)){
		}else{
			$status++;
		}
		
		// จบการเพิ่มที่อยู่    <--- บอสทำ พังบอกบอส
		
		
		
		
		
		
		
		
		
		
		
			
		//หาข้อมูลลูกค้าใน temp มาเก็บใน nw
		$qry_tempcus=pg_query("select * from \"temp_securities_customer\" where auto_id='$auto_id'");
		while($res_tempcus=pg_fetch_array($qry_tempcus)){
			$CusID=$res_tempcus["CusID"];
				
			//insert ลงในตาราง nw
			$inscus="INSERT INTO nw_securities_customer(\"securID\", \"CusID\") VALUES ('$securID', '$CusID')";
			if($res_cus=pg_query($inscus)){
			}else{
				$error2=$qry_tempcus;
				$status++;
			}
		}
			
		//หาข้อมูล upload ใน temp มาเก็บใน nw
		$qry_tempup=pg_query("select * from \"temp_securities_upload\" where auto_id='$auto_id'");
		while($res_tempup=pg_fetch_array($qry_tempup)){
			$upload=$res_tempup["upload"];
				
			//insert ลงในตาราง nw
			$insup="INSERT INTO nw_securities_upload(\"securID\", \"upload\") VALUES ('$securID', '$upload')";
			if($res_up=pg_query($insup)){
			}else{
				$error3=$qry_tempup;
				$status++;
			}
		}
			
		//update ใน temp_securities ว่าได้มีการ update แล้ว
		$up_temp="update \"temp_securities\" set \"user_app\"='$app_user', 
				\"stampDateApp\"='$app_date', 
				\"statusApp\"='1'
				where \"auto_id\" = '$auto_id'";
		if($res_temp=pg_query($up_temp)){
		}else{
			$error4=$up_temp;
			$status++;
		}	
	}else{ //กรณีแก้ไข
		//update ใน  nw_securities ว่าได้มีการ update แล้ว
		if($guaranID=="1" || $guaranID=="3"){ //กรณีเป็นที่ีดิน
			$up_nw="UPDATE nw_securities
					SET \"guaranID\"=$guaranID, \"numDeed\"='$numDeed', \"numBook\"=$numBook, \"numPage\"=$numPage, 
						\"numLand\"=$numLand, \"pageSurvey\"=$pageSurvey, district=$district, \"proID\"='$proID', area_acre=$area_acre, 
						area_ngan=$area_ngan, area_sqyard=$area_sqyard, note=$note, \"contractID\" = $contractID
					where \"securID\" = '$securID'";
		}else{
			$up_nw="UPDATE nw_securities
					SET \"guaranID\"=$guaranID, \"numDeed\"='$numDeed',condoroomnum=$condoroomnum, condofloor=$condofloor, 
					condobuildingnum=$condobuildingnum, condobuildingname=$condobuildingname, condoregisnum=$condoregisnum, 
					area_smeter=$area_smeter, district=$district, \"proID\"='$proID', note=$note, \"contractID\" = $contractID
					where \"securID\" = '$securID'";
		}
		
		if($res_nw=pg_query($up_nw)){
		}else{
			$error4=$res_nw;
			$status++;
		}	
			
			
			//เพิ่มข้อมูลที่อยู่ลงตาราง  <--- บอสทำ พังบอกบอส
		
		$qry_addr=pg_query("select * from \"temp_securities_address\" where \"temp_secid\"='$auto_id'");
		$res_addr=pg_fetch_array($qry_addr);
		
		
		$RealsecID  = $res_addr['securID']; //รหัส
		$S_BUILDING  = $res_addr['S_BUILDING']; //รหัส
		$S_ROOM  = $res_addr['S_ROOM']; //รหัส
		$S_FLOOR  = $res_addr['S_FLOOR']; //รหัส
		$soi = $res_addr['S_SOI']; //ซอย
		$rd = $res_addr['S_RD']; //ถนน
		$amphur = $res_addr['S_AUM']; //อำเภอ
		$district = $res_addr['S_TUM']; //ตำบล
		$post = $res_addr['S_POST']; //ไปรษณีย์
		$S_NO=$res_addr["S_NO"];	
		$S_SUBNO=$res_addr["S_SUBNO"];	
		$S_VILLAGE=$res_addr["S_VILLAGE"];
		
		$S_NO = checknull($S_NO);
		$S_SUBNO = checknull($S_SUBNO);
		$S_VILLAGE = checknull($S_VILLAGE);
		$S_BUILDING = checknull($S_BUILDING);
		$S_ROOM = checknull($S_ROOM);
		$S_FLOOR = checknull($S_FLOOR);
		$amphur = checknull($amphur);
		$soi = checknull($soi);
		$rd = checknull($rd);		
		$district = checknull($district);
		$post = checknull($post);
		
		 

		
		if($guaranID=="1" || $guaranID=="3"){ //ที่ดิน
			$ins_addr="UPDATE nw_securities_address
   SET  \"S_SOI\"=$soi, \"S_NO\"=$S_NO,\"S_SUBNO\"=$S_SUBNO,\"S_VILLAGE\"=$S_VILLAGE,\"S_RD\"=$rd, \"S_TUM\"=$district, \"S_AUM\"=$amphur, \"S_PRO\"='$proID', \"S_POST\"=$post WHERE \"securID\" = '$securID'";
		}else{ //ห้องชุด
			$ins_addr="UPDATE nw_securities_address
   SET  \"S_BUILDING\" = $S_BUILDING,\"S_ROOM\" = $S_ROOM,\"S_FLOOR\" = $S_FLOOR,\"S_SOI\"=$soi, \"S_RD\"=$rd, \"S_TUM\"=$district, \"S_AUM\"=$amphur, \"S_PRO\"='$proID', \"S_POST\"=$post WHERE \"securID\" = '$securID'";
		}
		
		if($resin_addr=pg_query($ins_addr)){
		}else{
			$status++;
		}
		
		// จบการเพิ่มที่อยู่    <--- บอสทำ พังบอกบอส
			
			
			
			
			
			
		//ลบข้อมูลเก่าออกก่อนแล้ว add เข้าใหม่
		$del="DELETE FROM nw_securities_customer WHERE \"securID\" = '$securID'";
		if($resdel=pg_query($del)){
		}else{
			$status++;
		}
			
		//ดึงข้อมูลลูกค้าใน temp มาเก็บใน nw
		$qry_tempcus=pg_query("select * from \"temp_securities_customer\" where auto_id='$auto_id'");
		while($res_tempcus=pg_fetch_array($qry_tempcus)){
			$CusID=$res_tempcus["CusID"];
									
			//insert ข้อมูลใหม่ลงในตาราง nw
			$inscus="INSERT INTO nw_securities_customer(\"securID\", \"CusID\") VALUES ('$securID', '$CusID')";
			if($res_cus=pg_query($inscus)){
			}else{
				$error2=$qry_tempcus;
				$status++;
			}
		}
			
		//ลบข้อมูลเก่าออกก่อนแล้ว add เข้าใหม่
		$delup="DELETE FROM nw_securities_upload WHERE \"securID\" = '$securID'";
		if($resdel2=pg_query($delup)){
		}else{
			$status++;
		}
			
		//ดึงข้อมูลลูกค้าใน temp มาเก็บใน nw
		$qry_tempup=pg_query("select * from \"temp_securities_upload\" where auto_id='$auto_id'");
		while($res_tempup=pg_fetch_array($qry_tempup)){
			$upp=$res_tempup["upload"];
									
			//insert ข้อมูลใหม่ลงในตาราง nw
			$insup="INSERT INTO nw_securities_upload(\"securID\", \"upload\") VALUES ('$securID', '$upp')";
			if($res_up=pg_query($insup)){
			}else{
				$error3=$res_up;
				$status++;
			}
		}
			
		//update ใน temp_securities ว่าได้มีการ update แล้ว
		$up_temp="update \"temp_securities\" set \"user_app\"='$app_user', 
				\"stampDateApp\"='$app_date', 
				\"statusApp\"='1'
				where \"auto_id\" = '$auto_id'";
		if($res_temp=pg_query($up_temp)){
		}else{
			$error4=$up_temp;
			$status++;
		}	
	}
}else{ //กรณีไม่อนุมัติ
		$up_temp="update \"temp_securities\" set \"user_app\"='$app_user', 
					\"stampDateApp\"='$app_date', 
					\"statusApp\"='0'
					where \"auto_id\" = '$auto_id'";
		if($res_temp=pg_query($up_temp)){
		}else{
			$error4=$up_temp;
			$status++;
		}	
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(ALL) อนุมัติข้อมูลหลักทรัพย์', '$app_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{
	pg_query("ROLLBACK");
	echo $error1."<br>$error2 <br>$error3 <br>$error4";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
	echo $error_check."</div>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}
?>
</td>
</tr>
</table>
</body>
</html>