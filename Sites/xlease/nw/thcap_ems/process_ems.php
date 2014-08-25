<?php
session_start();
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$doerid_ems=$_SESSION["av_iduser"];
$doerstamp_ems=nowDateTime();
pg_query("BEGIN WORK");
$status = 0;

$contractID = $_POST['contractID']; //เลขที่สัญญา
$type = $_POST['type']; 
$id = $_POST['id']; //autoid
$id0 = $_POST['id0']; //autoid
$ems_back = $_POST['ems_back'];//เลขที่ EMS
if($type==0){//ตารางหลัก 
	$sql_update="UPDATE \"thcap_letter_send\" SET \"emsnumber\"='$ems_back',\"doreid_ems\"='$doerid_ems',\"dorestamp_ems\"='$doerstamp_ems' WHERE \"auto_id\"='$id'";	
}
else if($type==1){ //ตารางรายละเอียด
	$sql_update="UPDATE \"thcap_letter_detail\" SET \"emsnumber\"='$ems_back',\"doreid_ems\"='$doerid_ems',\"dorestamp_ems\"='$doerstamp_ems' WHERE \"auto_id\"='$id0'";
}
if($result_update=pg_query($sql_update)){
    }else{
        $status+=1;
    }
if($status == 0){
    pg_query("COMMIT");
    echo "<center><b>บันทึกข้อมูลเรียบร้อยแล้ว</b><br /><br />";
	echo "<input type=\"button\" value=\"   ปิด  \" onclick=\"window.opener.location.reload();window.close();\"></center>";
}else{
    pg_query("ROLLBACK");
    echo "<center><b>ไม่สามารถ บันทึกสำเร็จ</b><br /><br />";
	echo "<input type=\"button\" value=\"   ปิด  \" onclick=\"window.opener.location.reload();window.close();\"></center>";
}
?>