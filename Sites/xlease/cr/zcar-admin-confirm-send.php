<?php
session_start();
include("../config/config.php");
$user = pg_escape_string($_POST['user']);
$seed = $_SESSION["session_company_seed"];
$pass = md5(md5($_POST['pass']).$seed);
$sid = pg_escape_string($_POST['sid']);
$type = pg_escape_string($_POST['type']);

$qry_chk=pg_query("select COUNT(\"id_user\") AS cid from \"fuser\" WHERE \"username\"='$user' AND \"password\"='$pass' AND \"isadmin\"='1' ");
if($res_chk=pg_fetch_array($qry_chk)){
    $cid=$res_chk["cid"];
    if($cid > 0){
        if($type == 101){
            $typechange = 105;
        }elseif($type == 105){
            $typechange = 101;
        }
        $sql="UPDATE carregis.\"CarTaxDue\" SET \"TypeDep\"='$typechange' WHERE \"IDCarTax\"='$sid' ";
        if($result=pg_query($sql)){
            $data['success'] = true;
        }else{
            $data['success'] = false;
            $data['message'] = "ไม่สามารถแก้ไขได้! กรุณาลองใหม่อีกครั้ง";
        }
    }else{
        $data['success'] = false;
        $data['message'] = "ข้อมูลอนุมัติไม่ถูกต้อง!";
    }
}else{
    $data['success'] = false;
    $data['message'] = "ข้อมูลอนุมัติไม่ถูกต้อง!";
}

echo json_encode($data);
?>
