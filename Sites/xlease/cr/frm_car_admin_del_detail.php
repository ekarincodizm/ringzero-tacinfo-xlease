<?php 
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$crid = pg_escape_string($_GET['crid']);

$qry_dt = "DELETE FROM carregis.\"DetailCarTax\" WHERE \"IDDetail\"='$id' ";
if( pg_query($qry_dt) ){
    header("Refresh: 0; url=frm_car_admin_edit.php?cid=$crid");
    echo "<script language=Javascript>alert ('ลบข้อมูลเรียบร้อยแล้ว');</script>";
}else{
    header("Refresh: 0; url=frm_car_admin_edit.php?cid=$crid");
    echo "<script language=Javascript>alert ('ไม่สามารถลบข้อมูล');</script>";
}
?>