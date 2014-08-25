<?php
include("../config/config.php");

$add_remark = pg_escape_string($_POST['add_remark']);
$payid = pg_escape_string($_POST['payid']);

$in_sql="UPDATE gas.\"PayToGas\" SET \"Remark\"='$add_remark' WHERE \"payid\"='$payid'";
if($result=pg_query($in_sql)){
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>