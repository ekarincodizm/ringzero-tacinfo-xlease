<?php
session_start();
include("../config/config.php");
$get_id_user = $_SESSION["av_iduser"];
$cid = pg_escape_string($_GET['cid']);
$nowdate = date("Y/m/d");

pg_query("BEGIN WORK");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>รายการรับเล่มเข้า</B></legend>

<div align="center">

<?php
$in_sql="UPDATE carregis.\"CarTaxDue\" SET \"BookIn\" = 'true',\"BookInDate\" = '$nowdate' WHERE \"IDCarTax\"='$cid'";
if($result=pg_query($in_sql)){
    $status = 0;
}else{
    $status = 1;
}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>
<br>
<input type="button" value="  Close  " onclick="window.close()">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>