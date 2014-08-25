<?php
session_start();
include("../config/config.php");

$id = pg_escape_string($_POST['id']);
$typepay = pg_escape_string($_POST["typepay"]);
$billnumber = pg_escape_string($_POST["billnumber"]);
$taxvalue = pg_escape_string($_POST['taxvalue']);
$copaydate = pg_escape_string($_POST['copaydate']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div align="center">

<?php
    $in_sql="UPDATE carregis.\"DetailCarTax\" SET \"CoPayDate\"='$copaydate',\"TaxValue\"='$taxvalue',\"BillNumber\"='$billnumber',\"TypePay\"='$typepay' WHERE \"IDDetail\"='$id' ";
    if($result=pg_query($in_sql)){
          echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    }else{
          echo "ไม่สามารถบันทึกข้อมูลได้";
    }
?>

<br><br>

<input type="button" name="cls" value=" Close " onclick="javascript:window.close()">

</div>

</body>
</html>