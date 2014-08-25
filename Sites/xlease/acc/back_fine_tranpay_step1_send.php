<?php
Ob_start();
include("../config/config.php");

header ('Content-type: text/html; charset=utf-8');

$id = pg_escape_string($_POST['id']);
$bank = pg_escape_string($_POST['bank']);
$date = pg_escape_string($_POST['date']);
$amt = pg_escape_string($_POST['amt']);
$trantype = pg_escape_string($_POST['trantype']);
$tb_idno = pg_escape_string($_POST['tb_idno']);

$arr_tb_idno = explode("#",$tb_idno);

$ud=pg_query("UPDATE \"TranPay\" SET \"ref1\"='$arr_tb_idno[2]',\"ref2\"='$arr_tb_idno[3]',\"ref_name\"='$arr_tb_idno[1]',\"post_to_idno\"='$arr_tb_idno[0]' WHERE \"id_tranpay\"='$id';");
if($ud){
    header("Refresh: 0; url=back_fine_tranpay_step2.php?id=$id&bank=$bank&idno=$arr_tb_idno[0]&date=$date&amt=$amt&trantype=$trantype");
    echo "<script language=Javascript>alert ('บันทึกเรียบร้อยแล้ว ไปขั้นตอนถัดไป');</script>";
}else{
    header("Refresh: 0; url=back_fine_tranpay_step1.php?id=$id&bank=$bank&idno=&date=$date&amt=$amt&trantype=$trantype");
    echo "<script language=Javascript>alert ('ไม่สามารถบันทึกได้');</script>";
}
?>