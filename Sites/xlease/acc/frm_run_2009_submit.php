<?php
include("../config/config.php");

$datepicker=pg_escape_string($_POST['datepicker']);

$rs=pg_query("select account.\"Create2009EndYear\"('$datepicker')");
$rt1=pg_fetch_result($rs,0);
if(!$rt1){
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกข้อมูลได้";
}else{
    $data['success'] = true;
    $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
}

echo json_encode($data);
?>