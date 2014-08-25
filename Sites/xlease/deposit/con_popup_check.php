<?php
session_start();
include("../config/config.php");

$user=pg_escape_string($_POST['user']);
$seed = $_SESSION["session_company_seed"];
$pass = md5(md5(pg_escape_string($_POST['pass'])).$seed);
$id=pg_escape_string($_POST['sid']);
$_SESSION['check_admin_confirm'] = "";
$data['success'] = false;

$qry_chk=pg_query("select COUNT(\"id_user\") AS cid from \"fuser\" WHERE \"username\"='$user' AND \"password\"='$pass' AND \"user_group\"='AD' ");
if($res_chk=pg_fetch_array($qry_chk)){
    $cid=$res_chk["cid"];
    if($cid > 0){
        $_SESSION['check_admin_confirm'] = $id;
        $data['success'] = true;
        $data['message'] = $_SESSION['check_admin_confirm'];
    }else{
        $data['success'] = false;
        $data['message'] = "Username หรือ Password ไม่ถูกต้อง!";
    }
}

echo json_encode($data);
?>