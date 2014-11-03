<?php
session_start();
include("../config/config.php");
$user = pg_escape_string($_POST['user']);
$seed = $_SESSION["session_company_seed"];
$pass = md5(md5($_POST['pass']).$seed);

$cmd = pg_escape_string($_POST['cmd']);
$id = pg_escape_string($_POST['id']);
$w = pg_escape_string($_POST['w']);

$qry_chk=pg_query("select COUNT(\"id_user\") AS cid from \"fuser\" WHERE \"username\"='$user' AND \"password\"='$pass' AND \"isadmin\"='1' ");
if($res_chk=pg_fetch_array($qry_chk)){
    $cid=$res_chk["cid"];
    if($cid > 0){
        if($cmd == "bill"){
            $sql="UPDATE carregis.\"DetailCarTax\" SET \"BillNumber\"='$w' WHERE \"IDDetail\" = '$id'";
            if( $result=@pg_query($sql) ){
                 $data['success'] = true;
            }else{
                $data['success'] = false;
            }
        }elseif($cmd == "nobill" || $cmd == "notnobill"){
            $sql="UPDATE carregis.\"DetailCarTax\" SET \"TaxValue\"='$w' WHERE \"IDDetail\" = '$id'";
            if( $result=@pg_query($sql) ){
                 $data['success'] = true;
            }else{
                $data['success'] = false;
            }
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