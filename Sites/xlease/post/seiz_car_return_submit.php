<?php
include("../config/config.php");
$idno=$_POST['idno'];

$qry_fp=@pg_query("UPDATE \"Fp\" SET \"repo\"='FALSE', \"repo_date\"=DEFAULT WHERE \"IDNO\"='$idno' ");
if($qry_fp){
    $data['success'] = true;
    $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกข้อมูลได้ !";
}

echo json_encode($data);
?>