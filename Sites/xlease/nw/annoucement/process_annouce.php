<?php
session_start();
include("../../config/config.php");
include('class.upload.php');
?>
 <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$annAuthor=$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$method=$_POST["method"];
$typeAnnId=$_POST["typeAnnId2"];
// $annTitle=str_replacein($_POST["annTitle"]);
// $annContent=str_replacein($_POST["annContent"]);
$annTitle=$_POST["annTitle"];
$annContent=$_POST["annContent"];
$statusImportance=$_POST["statusImportance"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$typeann = pg_escape_string($_POST['typeann']);
if($statusImportance=="TRUE"){
	$statusim="TRUE";
}else{
	$statusim="FALSE";
}

$keyDate=date("Y-m-d");

pg_query("BEGIN WORK");
$status = 0;

if($method == "add"){
	$qryid=pg_query("select max(\"annId\") from \"nw_annoucement\"");
	list($numrowid)=pg_fetch_array($qryid);
	$annId=$numrowid+1;   
	
	
	$in_sql="insert into \"nw_annoucement\"(\"annId\",\"typeAnnId\",\"annTitle\",\"annContent\",\"annAuthor\",\"keyDate\",\"statusApprove\",\"statusImportance\")values('$annId','$typeAnnId','$annTitle','$annContent','$annAuthor','$keyDate','FALSE','$statusim')";
	if($resultins=pg_query($in_sql)){
	}else{
		$status++;
	}
	
	//add file upload 
	$cli = (isset($argc) && $argc > 1);
	if ($cli) {
		if (isset($argv[1])) $_GET['file'] = $argv[1];
		if (isset($argv[2])) $_GET['dir'] = $argv[2];
		if (isset($argv[3])) $_GET['pics'] = $argv[3];
	}

	// set variables
	$dir_dest = (isset($_GET['dir']) ? pg_escape_string($_GET['dir']) : 'upload');
	$dir_pics = (isset($_GET['pics']) ? pg_escape_string($_GET['pics']) : $dir_dest);
	
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
				$qrylastid=pg_query("select \"annfileId\" from \"nw_annoucefile\"");
				$numrow=pg_num_rows($qrylastid);
				$annfileId=$numrow+1;   
				$pathfile=$handle->file_dst_name;
				$in_file="insert into \"nw_annoucefile\"(\"annfileId\",\"annId\",\"pathfile\")values('$annfileId','$annId','$pathfile')";
				if($resultins=pg_query($in_file)){
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
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$annAuthor', '(ALL) ขอเพิ่ม Annoucement', '$add_date')");
//ACTIONLOG---
}else if($method=="edit"){	
	$annId=$_POST["annId"];
	$upfuser="update \"nw_annoucement\" set 
			\"typeAnnId\"='$typeAnnId',
			\"annTitle\"='$annTitle',
			\"annContent\"='$annContent',
			\"annAuthor\"='$annAuthor',
			\"keyDate\"='$keyDate',
			\"statusImportance\"='$statusim'
			 where \"annId\"='$annId'";
	if($res_up=pg_query($upfuser)){
	}else{
		$status++;
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
				$qrylastid=pg_query("select \"annfileId\" from \"nw_annoucefile\"");
				$numrow=pg_num_rows($qrylastid);
				$annfileId=$numrow+1;   
				$pathfile=$handle->file_dst_name;
				$in_file="insert into \"nw_annoucefile\"(\"annfileId\",\"annId\",\"pathfile\")values('$annfileId','$annId','$pathfile')";
				if($resultins=pg_query($in_file)){
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
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$annAuthor', '(ALL) ขอแก้ไข Annoucement', '$add_date')");
//ACTIONLOG---
}

if($status == 0){
	pg_query("COMMIT");
	echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
	if($method=="edit"){
		if($typeann == "edit"){
			echo "<meta http-equiv='refresh' content='2; URL=frm_annEdit_newbie.php'>";
		}else{
			echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php?typeAnnId=$typeAnnId&method=edit'>";
		}	
	}else{
		echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	}	
}else{
	pg_query("ROLLBACK");
	echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	if($method=="edit"){
		echo $upfuser;
		echo "<meta http-equiv='refresh' content='3; URL=frm_annEdit.php?annId=$annId'>";
	}else{
		echo $in_sql;
		echo "<meta http-equiv='refresh' content='3; URL=frm_Index.php'>";
	}
}


