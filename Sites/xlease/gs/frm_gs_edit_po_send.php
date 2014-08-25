<?php
include("../config/config.php");

$id = pg_escape_string($_POST['id']);
$g_name = pg_escape_string($_POST['g_name']);
$g_type = pg_escape_string($_POST['g_type']);
$date_post = pg_escape_string($_POST['date_post']);
$date_install = pg_escape_string($_POST['date_install']);
$carnum = pg_escape_string($_POST['carnum']);
$marnum = pg_escape_string($_POST['marnum']);
$memo = pg_escape_string($_POST['memo']);

$qry_inf=pg_query("select costofgas from gas.\"Model\" WHERE modelid = '$g_type' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $costofgas = $res_inf["costofgas"];
    
    $qry_c=pg_query("select amt_before_vat('$costofgas')");
    $res_c=pg_fetch_result($qry_c,0);
    
    $vat = ($res_c*7)/100;
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

<fieldset><legend><B>แก้ไขข้อมูล</B></legend>
<div align="center">
<?php
$in_sql="UPDATE gas.\"PoGas\" SET \"idcompany\"='$g_name',\"idmodel\"='$g_type',\"podate\"='$date_post',\"date_install\"='$date_install',\"memo\"='$memo',\"carnum\"='$carnum',\"marnum\"='$marnum',\"costofgas\"='$res_c',\"vatofcost\"='$vat' WHERE \"poid\"='$id'";
if($result=pg_query($in_sql)){
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>

<br>
<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_maker.php'">
</div>
</fieldset>


        </td>
    </tr>
</table>

</body>
</html>