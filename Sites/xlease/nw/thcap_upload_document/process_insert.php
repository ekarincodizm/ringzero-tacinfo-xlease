<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$countfill = pg_escape_string($_POST['countfill']);
$countrow = pg_escape_string($_POST['countrow']);
$contractID = pg_escape_string($_POST["contractID"]);
$notRequire = pg_escape_string($_POST["notRequire"]);
$status=0;

		
		//รับค่าไฟล์ เป็น arry
		for($i=1;$i<=$countrow;$i++){
		$docName[$i] = $_POST["docName$i"];
		$conType[$i] = $_POST["conType$i"];
		$note[$i] = $_POST["note$i"];
		$file[$i]["error"] = $_FILES["file$i"]["error"];
		$file[$i]["name"] = $_FILES["file$i"]["name"];
			$newName = $datenow.$file[$i]["name"];
			$reName = str_replace(" ","",$newName);
			$reName2 = str_replace("-","",$reName);
			$lastName = str_replace(":","",$reName2);
		$file[$i]["type"] = $_FILES["file$i"]["type"];
		$file[$i]["size"] = $_FILES["file$i"]["size"];
		$file[$i]["tmp_name"] = $_FILES["file$i"]["tmp_name"];
			
			$newNote[i]=checknull($note[$i]);
			
			if($file[$i]["type"]==""){
			} else {
				if($file[$i]["type"]=="application/pdf"){
					if($file[$i]["error"]>0){
						echo $file[$i]["error"];
					} else {
						if(file_exists("upload/" . $file[$i]["name"])){
							echo $file[$i]["name"]."มีอยู่ในระบบแล้ว";
						} else {
							if(move_uploaded_file($file[$i]["tmp_name"],"../upload/document_contract/" . $lastName)){
								$pathfile[$i] = "$lastName";
							} else {
								$status++;
							}
						}
					}
			
			//insert to database
			$qry_insert = "insert into thcap_upload_document (\"contractID\",\"conType\",\"docTypename\",\"pathFile\",\"noteFile\",\"up_doerID\",\"up_doerStamp\",\"Approved\",add_or_edit)
							values ('$contractID','$conType[$i]','$docName[$i]','$pathfile[$i]',$newNote[i],'$user_id','$datenow','2','0') ";
							
						if(pg_query($qry_insert)){
						}else {
							$status++;
						}
				} else {
					$alertU="Upload ได้เฉพาะ file .pdf เท่านั้น";
					$status++;
				}
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
			<?php if($notRequire!=""){
				echo "<input type=\"button\" name=\"OK\" value=\"OK\" onclick=\"location.href='detail_contract.php?conid=$contractID&refresh=Y'\">";
			} else {
				echo "<input type=\"submit\" name=\"OK\" value=\"OK\" onclick=\"refres();\">";
			}
			?>
		</center>
	</form>
</html>