<?php
session_start();
include("../../config/config.php");
$iduser = $_SESSION['uid'];
$c_code=$_SESSION["session_company_code"];
//$c_code="THA";

//หา emplevel ของพนักงาน
$qrylevel=pg_query("select ta_get_user_emplevel('$iduser')");
list($emplevel)=pg_fetch_array($qrylevel);

if($emplevel<=1){
  
	$s_did=$_GET["d_id"];

	$delFile=unlink("upload/".$c_code."/".$s_did);
	if($delFile){
		echo "File Deleted";
	}else{
		echo "File can not delete";
	}  
}else{
	echo "<center>"."คุณไม่มีสิทธิในการลบไฟล์ กรุณาติดต่อผู้ดูแลระบบ "."</center>";
}
	   
?>
<br><br><center><button onClick="window.close();" >CLOSE</button></center></br>