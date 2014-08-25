<?php
include("../../config/config.php");
include("../function/checknull.php");
session_start();
$id_user = $_SESSION["av_iduser"];
$timenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$appstatus = $_POST['appstate'];
$CarIDtemp = $_POST['chkapp'];

$status = 0;
pg_query("BEGIN");

if($appstatus == 'allow'){
		for($i = 0 ;$i<sizeof($CarIDtemp);$i++){
		$CarIDtemp[$i];
			$qry_sel = pg_query("SELECT \"CarIDtemp\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS\", \"C_REGIS_BY\", 
			   \"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
			   \"C_TAX_MON\",\"C_StartDate\", \"RadioID\", \"CarType\", \"C_CAR_CC\",file,\"fc_type\",\"fc_brand\",\"fc_model\",\"fc_category\",\"fc_newcar\",\"fc_gas\"
		  FROM \"Fc_temp\" where \"CarIDtemp\" = '$CarIDtemp[$i]' ");
			$re_Sel = pg_fetch_array($qry_sel);
			
			$C_CARNAME =checknull($re_Sel['C_CARNAME']);
			$C_YEAR =checknull($re_Sel['C_YEAR']);
			$C_REGIS =checknull($re_Sel['C_REGIS']);
			$C_REGIS_BY =checknull($re_Sel['C_REGIS_BY']);
			$C_COLOR =checknull($re_Sel['C_COLOR']);
			$C_CARNUM =checknull($re_Sel['C_CARNUM']);
			$C_MARNUM =checknull($re_Sel['C_MARNUM']);
			$C_Milage =checknull($re_Sel['C_Milage']);
			$C_TAX_ExpDate =checknull($re_Sel['C_TAX_ExpDate']);
			$C_TAX_MON =checknull($re_Sel['C_TAX_MON']);
			$C_StartDate =checknull($re_Sel['C_StartDate']);
			$RadioID =checknull($re_Sel['RadioID']);
			$CarType =checknull($re_Sel['CarType']);
			$C_CAR_CC =checknull($re_Sel['C_CAR_CC']);
			$file =checknull($re_Sel['file']);
			$fp_fc_type = checknull($re_Sel["fc_type"]); // ประเภท รถยนต์/จักรยายนต์
			$fp_fc_brand = checknull($re_Sel["fc_brand"]); //ยี่ห้อ
			$fp_fc_model = checknull($re_Sel["fc_model"]); //รุ่น
			$fp_fc_category = checknull($re_Sel["fc_category"]); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
			$fp_fc_newcar = checknull($re_Sel["fc_newcar"]); //รถใหม่หรือรถใช้แล้ว
			$fp_fc_gas = checknull($re_Sel["fc_gas"]); //รถใหม่หรือรถใช้แล้ว
			
			$qry_selpk = pg_query("SELECT MAX(\"CarID\") as carid FROM \"Fc\"");
			list($maxcarid) = pg_fetch_array($qry_selpk);
			$maxcarid = str_replace("TAX","",$maxcarid);
			$maxcarid++;
			$maxcarid = "TAX".$maxcarid;



			$qry_in = "INSERT INTO \"Fc\"(
					\"CarID\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS\", \"C_REGIS_BY\", \"C_COLOR\", 
					\"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", \"C_TAX_MON\", 
					\"C_StartDate\", \"RadioID\", \"CarType\", \"C_CAR_CC\",\"fc_type\",\"fc_brand\",\"fc_model\",\"fc_category\",\"fc_newcar\",\"fc_gas\")
			VALUES ('$maxcarid',$C_CARNAME,$C_YEAR,$C_REGIS,$C_REGIS_BY,$C_COLOR, 
					$C_CARNUM,$C_MARNUM,$C_Milage,$C_TAX_ExpDate,$C_TAX_MON, 
					$C_StartDate,$RadioID,$CarType,$C_CAR_CC,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar,$fp_fc_gas)";
			$re_in = pg_query($qry_in);					
			if($re_in){ }else{ $status++ ; echo "Error: $re_in";}
			
			$qry_in = "INSERT INTO \"Fc_document_upload\"(\"CarID\", \"Pathfile\") VALUES ('$maxcarid',$file)";
			$re_in = pg_query($qry_in);					
			if($re_in){ }else{ $status++ ; echo "Error: $re_in";}
			
			$qry_up = "UPDATE \"Fc_temp\" SET  app_user='$id_user', date_app='$timenow', appstatus='1',\"CarID\"='$maxcarid' WHERE \"CarIDtemp\" = '$CarIDtemp[$i]' ";
			$re_up = pg_query($qry_up);					
			if($re_up){ }else{ $status++ ; echo "Error: $qry_up";}
		}	
	
}else if($appstatus == 'not'){
$textnotapp = $_POST['reasonnotapp'];	
		for($i = 0 ;$i<sizeof($CarIDtemp);$i++){
			$qry_up = "UPDATE \"Fc_temp\" SET  app_user='$id_user', date_app='$timenow', appstatus='2',reason_not_app='$textnotapp' WHERE \"CarIDtemp\" = '$CarIDtemp[$i]' ";
			$re_up = pg_query($qry_up);					
			if($re_up){ }else{ $status++ ; echo "Error: $qry_up";}
		}
}else{
	$status++;
}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php	

	if($status == 0){
		pg_query("commit");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
		echo "<script type='text/javascript'>alert('บันทึกสำเร็จ ')</script>";
	}else{
		pg_query("rollback");
		echo "<meta http-equiv=\"refresh\" content=\"7; URL=frm_approve.php\">";
		echo "<script type='text/javascript'>alert('ไม่สามารถบันทึกข้อมูลได้ \n กรุณา copy error code ส่งให้ฝ่าย IT ตรวจสอบ')</script>";
	}	


?>
</html>