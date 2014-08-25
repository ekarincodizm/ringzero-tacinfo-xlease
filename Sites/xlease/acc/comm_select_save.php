<?php
include("../config/config.php");

$comm=pg_escape_string($_POST['comm']);
$idno=pg_escape_string($_POST['idno']);

pg_query("BEGIN WORK");

$status = 0;

$qr=pg_query("select \"CreateAccPayment\"('$idno')");
$rs=pg_fetch_result($qr,0);
if(!$rs){
    $status++;
}

$resuilt = pg_query("UPDATE \"Fp\" SET \"Comm\"='$comm' WHERE \"IDNO\"='$idno' ");
if(!$resuilt){
    $status++;
}

if($status == 0){
    pg_query("COMMIT");
    $data['success'] = true;
    $data['message'] = "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง!!";
}
echo json_encode($data);
?>