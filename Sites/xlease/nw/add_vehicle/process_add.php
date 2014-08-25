<?php
include("../../config/config.php");
include("../function/checknull.php");
session_start();
$id_user = $_SESSION["av_iduser"];
$timenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$C_CARNAME =checknull($_POST['C_CARNAME']);
$C_YEAR =checknull($_POST['C_YEAR']);
$C_REGIS =checknull($_POST['C_REGIS']);
$C_REGIS_BY =checknull($_POST['C_REGIS_BY']);
$C_COLOR =checknull($_POST['f_carcolor']);
$C_CARNUM =checknull($_POST['C_CARNUM']);
$C_MARNUM =checknull($_POST['C_MARNUM']);
$C_Milage =checknull($_POST['C_Milage']);
$C_TAX_ExpDate =checknull($_POST['C_TAX_ExpDate']);
$C_TAX_MON =checknull($_POST['C_TAX_MON']);
$C_StartDate =checknull($_POST['C_StartDate']);
$RadioID =checknull($_POST['RadioID']);
$CarType =checknull($_POST['CarType']);
$C_CAR_CC =checknull($_POST['C_CAR_CC']);


$fp_fc_type = checknull($_POST["f_type_vehicle"]); // ประเภท รถยนต์/จักรยายนต์
$fp_fc_brand = $_POST["f_brand"]; //ยี่ห้อ
$fp_fc_model = checknull($_POST["f_model"]); //รุ่น
$qrysel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '".$_POST["f_model"]."' ");
list($model_name)=pg_fetch_array($qrysel_model);
$fp_fc_category = checknull($_POST["f_useful_vehicle"]); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = checknull($_POST["f_status_vehicle"]); //รถใหม่หรือรถใช้แล้ว
$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '$fp_fc_brand' ");
list($fp_band) = pg_fetch_array($qry_sel_brand);
$fp_band=$fp_band." ".$model_name; //เก็บทั้งชื่อยี่ห้อและรุ่น
$fp_fc_brand = checknull($_POST["f_brand"]); //ยี่ห้อ
$fp_fc_gas = checknull($_POST["gas_system"]); //ระบบแก๊สรถยนต์




if(!empty($_FILES["filedoc"]["name"])){
@mkdir("upload_regis_doc",0777);
	$path="upload_regis_doc/";
	$YY = date('Y');
	$mm = date('m');
	$dd = date('d');
	$timenow1 = date('H:i:s');
	list($hh,$ii,$ss) = explode(":",$timenow1);
	
					$file_name = $_FILES["filedoc"]["name"];
					
					$info = substr( $file_name , strpos( $file_name , '.' )+1 ) ;
					if(move_uploaded_file($_FILES["filedoc"]["tmp_name"],$path.$file_name))
						{		
								$md5file = md5_file($path.$file_name);
								$newfile_name = $YY.$mm.$dd.$hh.$ii.$ss."_".$md5file;
							   $file = $path.$newfile_name.".".$info;
							   $flgRename = rename($path.$file_name, $path.$newfile_name.".".$info);	
						}						
		$file = "'".$file."'";
}else{
		 $file = "null";
}

$status = 0;
pg_query("BEGIN");

	$qry_add = pg_query("INSERT INTO \"Fc_temp\"(
            \"C_CARNAME\", \"C_YEAR\", \"C_REGIS\", \"C_REGIS_BY\", 
            \"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
            \"C_TAX_MON\", \"C_StartDate\", \"RadioID\", \"CarType\", \"C_CAR_CC\", 
            id_user, date_submit, appstatus,file,\"fc_type\",\"fc_brand\",\"fc_model\",\"fc_category\",\"fc_newcar\",\"fc_gas\")
    VALUES ('$fp_band', $C_YEAR, $C_REGIS, $C_REGIS_BY, 
            $C_COLOR, $C_CARNUM, $C_MARNUM, $C_Milage, $C_TAX_ExpDate, 
            $C_TAX_MON, $C_StartDate, $RadioID, $CarType, $C_CAR_CC, 
            '$id_user','$timenow','0',$file,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar,$fp_fc_gas)");
			
	if($qry_add){ }else{ $status++ ;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php
	if($status == 0){
		pg_query("commit");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_add.php\">";
		echo "<script type='text/javascript'>alert('บันทึกสำเร็จ ')</script>";
	}else{
		pg_query("rollback");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_add.php\">";
		echo "<script type='text/javascript'>alert('ไม่สามารถบันทึกข้อมูลได้ \n กรุณา copy error code ส่งให้ฝ่าย IT ตรวจสอบ')</script>";
		echo "Error: $qry_add";
	}	


?>
</html>