<?php
include("../config/config.php");

$idno = $_POST['idno'];

$qry_name=@pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno'");
if($res_name=@pg_fetch_array($qry_name)){
    $data['success'] = true;
    $data['message'] = "<span style=\"color:green\">เลขที่สัญญาถูกต้อง</span>";
}else{
    $data['success'] = false;
    $data['message'] = "ไม่พบเลขที่สัญญาที่ระบุ กรุณาตรวจสอบอีกครั้ง !";
}

echo json_encode($data);
?>