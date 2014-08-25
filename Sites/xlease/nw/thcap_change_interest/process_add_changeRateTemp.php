<?php
include("../../config/config.php");
include('class.upload.php');

$nowdatetofile = date("YmdHis");

$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN");
$status = 0;

$contractID = $_POST["contractID"]; // เลขที่สัญญา
$oldRate = $_POST["oldRate"]; // อัตราเก่า
$newRate = $_POST["newRate"]; // อัตราใหม่
$actionDate = $_POST["actionDate"]; // วันที่มีผล
$H_time = $_POST["H_time"]; // ชั่วโมงที่มีผล
$M_time = $_POST["M_time"]; // นาทีที่มีผล
$S_time = $_POST["S_time"]; // วินาทีที่มีผล
$remark = $_POST["remark"]; // หมายเหตุ
$rowsFile = $_POST["rowsFile"]; // จำนวนครั้งที่กดปุ่มเพิ่มไฟล์
$timebegin = $actionDate." ".$H_time.":".$M_time.":".$S_time;


$a1 = nowDate();
$a2 = date('H:i:s');
list($b1,$b2,$b3) = explode("-",$a1);
list($c1,$c2,$c3) = explode(":",$a2);
$namenotsame = $b1.$b2.$b3.$c1.$c2.$c3;

if(!empty($_FILES["fileChangeRate"]["name"])){	
		for($i=0;$i<sizeof($_FILES["fileChangeRate"]["name"]);$i++)
		{		
			if($_FILES["fileChangeRate"]["name"][$i] != "")
			{
				$file_name = $namenotsame.$_FILES["fileChangeRate"]["name"][$i];
				$info = substr( $file_name , strpos( $file_name , '.' )+1 );
				$Share_newfile = md5("$file_name");
				if($i == sizeof($_FILES["fileChangeRate"]["name"]) - 1){							
					$file = $file.$namenotsame."_".$Share_newfile.".".$info;											   						   							
				}else{
					$file = $namenotsame."_".$Share_newfile.".".$info.",".$file;	
				}
			}	
		}
}


IF($file != ''){
	$file = "'{".$file."}'";
}else{
	$file = "null";
}

$qry_add = pg_query("SELECT thcap_process_changeintrate('$contractID','$newRate','$timebegin','$remark',$file,'$id_user',null,'REQUEST',null)");
if($qry_add){
	list($maxTempID) = pg_fetch_array($qry_add);
	if(!empty($_FILES["fileChangeRate"]["name"])){
		@mkdir("upload_reqchgintrate/".$maxTempID."/",0777);
		$path = "upload_reqchgintrate/$maxTempID/";
		for($i=0;$i<sizeof($_FILES["fileChangeRate"]["name"]);$i++)
		{		
			if($_FILES["fileChangeRate"]["name"][$i] != "")
			{
				$file_name = $namenotsame.$_FILES["fileChangeRate"]["name"][$i];
				$info = substr( $file_name , strpos( $file_name , '.' )+1 );
				$Share_newfile = md5("$file_name");
				move_uploaded_file($_FILES["fileChangeRate"]["tmp_name"][$i],$path.$namenotsame."_".$Share_newfile.".".$info);	
			}	
		}
	}
}else{$status++;}

if($status == 0)
{
	
	pg_query("COMMIT");
	//pg_query("ROLLBACK"); //test
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<meta http-equiv = 'refresh' content='2; URL=frm_Index.php'>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<meta http-equiv = 'refresh' content='5; URL=frm_Index.php?idno=$contractID'>";
}
?>