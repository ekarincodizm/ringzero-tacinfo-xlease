<?php
session_start();
include("../config/config.php");

$seed = $_SESSION["session_company_seed"];
$passwd = md5(md5($_POST['passwd']).$seed);

$sql_select_check=pg_query("SELECT \"password\" FROM \"fuser\" WHERE \"id_user\"='$_SESSION[av_iduser]';");
if($res_cn_check=pg_fetch_array($sql_select_check)){
    $password = $res_cn_check["password"];
    if($password != $passwd){
        $data['success'] = false;
        $data['message'] = "รหัสผ่านไม่ถูกต้อง ไม่สามารถพิมพ์ได้!";
    }else{
        $data['success'] = true;
    }
}

echo json_encode($data);
?>