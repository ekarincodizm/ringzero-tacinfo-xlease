<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$Up_autoID = pg_escape_string($_POST["up_autoid"]);;
$status=0;
		//หา ข้อมูลเดิม เก่า
		$qlr_add_or_edt = pg_query("select \"contractID\",\"conType\",\"docTypename\",\"add_or_edit\" from thcap_upload_document where \"up_autoID\"='$Up_autoID'");
		if($res_old = pg_fetch_array($qlr_add_or_edt)){
			$contractID = $res_old['contractID'];
			$conType = $res_old['conType'];
			$docName = $res_old['docTypename'];
			$add_or_edit = $res_old['add_or_edit']; 
		}
		$add_or_edit+=1;
		$note = pg_escape_string($_POST["note"]);
		$file["error"] = $_FILES["file"]["error"];
		$file["name"] = $_FILES["file"]["name"];
			$newName = $datenow.$file["name"];
			$reName = str_replace(" ","",$newName);
			$reName2 = str_replace("-","",$reName);
			$lastName = str_replace(":","",$reName2);
		$file["type"] = $_FILES["file"]["type"];
		$file["size"] = $_FILES["file"]["size"];
		$file["tmp_name"] = $_FILES["file"]["tmp_name"];
			
			$newNote=checknull($note);
			
			if($file["type"]==""){
			} else {
				if($file["type"]=="application/pdf"){
					if($file["error"]>0){
						echo $file["error"];
					} else {
						if(file_exists("upload/" . $file["name"])){
							echo $file["name"]."มีอยู่ในระบบแล้ว";
						} else {
							if(move_uploaded_file($file["tmp_name"],"../upload/document_contract/" . $lastName)){
								//unlink("../upload/document_contract/" .$old_file); กรณีถ้าจะให้ลบไฟล์เดิมหลังแก้ไข
								$pathfile = "$lastName";
							} else {
								$status++;
							}
						}
					}
			
			//insert to database
			$qry_update = "insert into thcap_upload_document (\"contractID\",\"conType\",\"docTypename\",\"pathFile\",\"noteFile\",\"up_doerID\",\"up_doerStamp\",\"Approved\",add_or_edit)
							values ('$contractID','$conType','$docName','$pathfile',$newNote,'$user_id','$datenow','2','$add_or_edit') ";
							
						if(pg_query($qry_update)){
						}else {
							$status++;
						}
				} else {
					$alertU="Upload ได้เฉพาะ file .pdf เท่านั้น";
					$status++;
				}
			}
		
	pg_query("Begin");
			
	if($status == 0){
	pg_query("COMMIT");
	$alert="บันทึกข้อมูลสำเร็จแล้ว";
	}else{
	pg_query("ROLLBACK");
	$alert="บันทึกข้อมูลล้มเหลว";
	}
?>
<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
<html>
	<form>
		<center>
			<H1><?php echo $alert ?></H1><br>
			<h2><?php echo $alertU ?></h2><br>
			<input type="submit" name="OK" value="OK" onclick="refres();">
		</center>
	</form>
</html>