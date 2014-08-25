<?php
session_start();
include("../config/config.php");
mysql_query("SET NAMES UTF8");
$cusname = $_POST['cusname'];
$IDNO = $_POST['idno'];
$iduser = $_SESSION["av_iduser"];
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$detail = $_POST['detail'];
$type = $_POST['type'];
$status = 0;

$a1 = nowDate();
$a2 = date('H:i:s');
list($b1,$b2,$b3) = explode("-",$a1);
list($c1,$c2,$c3) = explode(":",$a2);
$namenotsame = $b1.$b2.$b3.$c1.$c2.$c3;

if($cusname == ""){
	$cusname = "null";
}else{
	$cusname = "'".$cusname."'";
}

if($detail == ""){
	$detail = "null";
}else{
	$detail = "'".$detail."'";
}

if(!empty($_FILES["file"]["name"])){

	@mkdir("fileupload",0777);
	@mkdir("fileupload/".$IDNO."/",0777);
	$path="fileupload/".$IDNO."/";
		
		for($i=0;$i<sizeof($_FILES["file"]["name"]);$i++)
		{		
			if($_FILES["file"]["name"][$i] != "")
				{
					$file_name = $namenotsame.$_FILES["file"]["name"][$i];
					$info = substr( $file_name , strpos( $file_name , '.' )+1 ) ;
					if(move_uploaded_file($_FILES["file"]["tmp_name"][$i],$path.$i.$namenotsame."_".$type[$i].".".$info))
						{							
							   $file = $file."!#".$i.$namenotsame."_".$type[$i].".".$info;							   
						}
				}
		}
		
		$file = "'".$file."'";
}else{
		 $file = "null";
}

pg_query("BEGIN");


	$sql = "INSERT INTO \"Fp_document_pic\"(\"IDNO\", picname, cusname, date, id_user,detail) VALUES ('$IDNO' , $file, $cusname, '$date' , '$iduser',$detail)";
	$sqlquery = pg_query($sql);

	if($sqlquery){
	
	}else{
		$status++;
	}
	
		if($status==0){
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(TAL) เพิ่มประวัติรับเอกสารป้ายภาษีมิเตอร์', '$date')");
			//ACTIONLOG---
			pg_query("COMMIT");
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=tagvatmeter.php?idno=$IDNO\">";
				echo "<script type='text/javascript'>alert('การบันทึกเสร็จสิ้น')</script>";
					
		}else{
			pg_query("ROLLBACK");
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=tagvatmeter.php?idno=$IDNO\">";
				echo "<script type='text/javascript'>alert('การบันทึกไฟล์ล้มเหลว กรุณาลองใหม่ในภายหลัง')</script>";
				echo "$sql";
		}



?>