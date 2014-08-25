<?php
include("../config/config.php");
$idno = $_POST['idno'];
$signdate = $_POST['signdate'];
$startdate = $_POST['startdate'];
$typecontact = $_POST['typecontact'];
$carnum = $_POST['carnum'];
$cusid = $_POST['cusid'];
$carid = $_POST['carid'];

pg_query("BEGIN WORK");

$status = 0;
$error_msg = "";

$qry_gen=pg_query("select \"generate_id\"('$signdate',2,4)");
$newidno=pg_fetch_result($qry_gen,0);
if( empty($newidno) ){
    $error_msg .= "generate_id : empty\n";
    $status++;
}

$qry_ref1=pg_query("select \"gen_encode_ref1\"('$newidno')");
$ref1=pg_fetch_result($qry_ref1,0);
if( empty($ref1) ){
    $error_msg .= "gen_encode_ref1 : empty\n";
    $status++;
}

$len_carnum = strlen($carnum);
$ref2 = substr($carnum, ($len_carnum-9), $len_carnum);

$in="insert into \"FpOutCus\" (\"IDNO\",\"CusID\",\"CarID\",\"OCRef1\",\"OCRef2\",\"ACStartDate\",\"SignDate\",\"TypeContact\") values 
('$newidno','$cusid','$carid','$ref1','$ref2','$startdate','$signdate','$typecontact')";
if(!$result=pg_query($in)){
    $error_msg .= "insert FpOutCus : false\n";
    $status++;
}

$qry_invnewcus=pg_query("select corporate.\"gen_invoice_new_cus\"('$newidno')");
$invnewcus=pg_fetch_result($qry_invnewcus,0);
if(!$invnewcus){
    $error_msg .= "gen_invoice_new_cus : false\n";
    $status++;
}

if($status == 0){
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    $data['success'] = true;
    $data['message'] = "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้\nError Message:\n$error_msg";
}

echo json_encode($data);
?>