<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$IDNO=pg_escape_string($_GET["IDNO"]); 
$statusapp=pg_escape_string($_GET["stsapp"]);  
if($IDNO==""){
	$IDNO=pg_escape_string($_POST["IDNO"]); 
	$appvpg=pg_escape_string($_POST["appv"]);
	if($appvpg=="อนุมัติ"){ 
		$statusapp='1';//อนุมัติ
	}else{
		$statusapp='0';//ไม่อนุมัติ
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
	//ลบข้อมูลเก่าออก 
	$del="DELETE FROM \"ContactCus\" WHERE \"IDNO\"='$IDNO'";
	if($resdel=pg_query($del)){
	}else{
		$status++;
	}
	
	//เพิ่มข้อมูลใหม่ในตารางจริง
	$qrytemp=pg_query("select * from \"ContactCus_Temp\" where \"IDNO\"='$IDNO' and \"statusApp\"='2'");
	while($res=pg_fetch_array($qrytemp)){
		$CusID=$res["CusID"];
		$CusState=$res["CusState"];
		
		if($CusState == '0'){
			$fpup = "update \"Fp\" set \"CusID\"='$CusID' where \"IDNO\" = '$IDNO'";
			if($resultfp=pg_query($fpup)){
			}else{
				$status++;
			}	
		}
		
		$ins="INSERT INTO \"ContactCus\"(\"IDNO\", \"CusState\", \"CusID\") VALUES ('$IDNO', '$CusState', '$CusID')";
		if($resins=pg_query($ins)){
		}else{
			$status++;
		}	
	}
	
	//ดึงข้อมูลใน Fp_Fa1 เพื่อนำมาตรวจสอบ
	$qryfpfa1=pg_query("select \"CusID\",\"CusState\" from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"edittime\"='0' order by \"CusState\"");
	while($resfpfa1=pg_fetch_array($qryfpfa1)){
		list($CusID1,$CusState1)=$resfpfa1;
		$CusID1=trim($CusID1);
		$CusState1=trim($CusState1);
		
		//ตรวจสอบใน ContactCus_Temp ว่ามีข้อมูลนี้หรือไม่
		$qrycontact=pg_query("select \"CusState\" from \"ContactCus_Temp\" where \"IDNO\"='$IDNO' and \"CusID\"='$CusID1'");
		$numcontact=pg_num_rows($qrycontact);
		if($numcontact>0){ //กรณีพบข้อมูลให้ update ข้อมูลตาม ContactCus_Temp
			list($CusState2)=pg_fetch_array($qrycontact);
			$CusState2=trim($CusState2);
			if($CusState1!=$CusState2){
				$upfpfa1="update \"Fp_Fa1\" set \"CusState\"='$CusState2' where \"IDNO\"='$IDNO' and \"CusID\"='$CusID1'";
				if($resupfpfa1=pg_query($upfpfa1))
				{
					// ถ้ามีคนและลำดับเดียวกัน ซ้ำกันอยู่ ให้เอาออกไปคนนึง
					$qry_dupCus = pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusID\"='$CusID1' and \"CusState\"='$CusState2' ");
					$row_dupCus = pg_num_rows($qry_dupCus);
					if($row_dupCus > 1)
					{
						$qry_dupCusDel = pg_query("select \"auto_id\" from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusID\"='$CusID1' and \"CusState\"='$CusState2' limit 1 ");
						$dupCusDel = pg_fetch_result($qry_dupCusDel,0);
						$delDupCus = "delete from \"Fp_Fa1\" where \"auto_id\" = '$dupCusDel' and \"auto_id\" is not null ";
						if($resdelDupCus=pg_query($delDupCus)){
						}else{
							$status++;
						}
					}
				}
				else
				{
					$status++;
				}
			}
		}else{ //กรณีที่ไม่เจอใน ContactCus_Temp ให้ลบใน Fp_Fa1 ออก
			$del0="delete from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusID\"='$CusID1'";
			if($resdel0=pg_query($del0)){
			}else{
				$status++;
			}
		}
	}
	
	//ดึงข้อมูลใน ContactCus เพื่อ insert ข้อมูลที่เหลือ
	$qrycontact=pg_query("select \"CusID\",\"CusState\",\"userRequest\",\"userStamp\" from \"ContactCus_Temp\" where \"IDNO\"='$IDNO' and \"statusApp\"='2'");
	while($rescont=pg_fetch_array($qrycontact)){
		list($CusID3,$CusState3,$userRequest,$userStamp)=$rescont;
		$CusID3=trim($CusID3);
		$CusState3=trim($CusState3);
		$userRequest=trim($userRequest);
		$userStamp=trim($userStamp);
		
		//ตรวจสอบใน Fp_Fa1 ว่ามีข้อมูลนี้หรือไม่
		$qryfpfa=pg_query("select \"CusID\",\"CusState\" from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusState\"='$CusState3' and \"edittime\"='0'");
		$numfp=pg_num_rows($qryfpfa);
		if($numfp==0){ //กรณีไม่พบข้อมูลให้ insert
			$insfnew0="INSERT INTO \"Fp_Fa1\" (\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"CusState\", \"addUser\",\"addStamp\")
		  
			SELECT '$IDNO','$CusID3',\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",'$CusState3','$userRequest','$userStamp' FROM \"Fa1\"  
			WHERE \"CusID\"='$CusID3'";
			if($resins0=pg_query($insfnew0)){
			}else{
				$status++;
			}
		}
	}
	
	//update ข้อมูลให้เป็นสถานะอนุมัติ
	$uptemp="UPDATE \"ContactCus_Temp\"
			SET \"statusApp\"='1', \"appUser\"='$app_user', \"appStamp\"='$app_date'
			where \"IDNO\" = '$IDNO' and \"statusApp\"='2'";
	if($resuptemp=pg_query($uptemp)){
	}else{
		$status++;
	}	
}else{ //กรณีไม่อนุมัติ
	//update ข้อมูลให้เป็นสถานะอนุมัติ
	$uptemp="UPDATE \"ContactCus_Temp\"
			SET \"statusApp\"='0', \"appUser\"='$app_user', \"appStamp\"='$app_date'
			where \"IDNO\" = '$IDNO' and \"statusApp\"='2'";
	if($resuptemp=pg_query($uptemp)){
	}else{
		$status++;
	}	
}
if($status == 0){

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(TAL) อนุมัติแก้ไขการผูกคนกับสัญญา', '$app_date')");
	//ACTIONLOG---
	
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{
	pg_query("ROLLBACK");
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