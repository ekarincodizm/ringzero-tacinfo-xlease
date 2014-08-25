<?php
session_start();
include("../config/config.php");

$get_id_user = $_SESSION["av_iduser"];

$id = pg_escape_string($_POST['id']);
$invoice = pg_escape_string($_POST['invoice']);
$date_invoice = pg_escape_string($_POST['date_invoice']);
$costofgas = pg_escape_string($_POST['costofgas']);
$vatofcost = pg_escape_string($_POST['vatofcost']);
$company = pg_escape_string($_POST['company']);

if(empty($invoice) || empty($date_invoice)){
    header("Refresh: 0; url=frm_pay_invoice_add.php?id=$id"); 
    echo "<script language=Javascript>alert ('กรุณากรอกข้อมูล ให้ครบ !');</script>"; 
    exit(); 
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>ใส่ใบกำกับ</B></legend>
<div align="center">
<?php

pg_query("BEGIN WORK");

$in_sql="UPDATE gas.\"PoGas\" SET \"invoice\"='$invoice',\"vat_date\"='$date_invoice' WHERE \"poid\"='$id'";
if($result=pg_query($in_sql)){
    $status=0;
}else{
    $status=1;
}

$select_sql1 = pg_query("SELECT account.save_acc_gas_maker('$date_invoice', '$id', '$costofgas', '$vatofcost', '$invoice','$company');");
$res_sql1=pg_fetch_result($select_sql1,0);

if($status==1){
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}else{
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}
?>

<br>
<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_pay_addinvoice.php'">
</div>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>