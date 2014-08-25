<?php
session_start();
include("../config/config.php");

$did = pg_escape_string($_POST['did']);
$billnumber = pg_escape_string($_POST['billnumber']);
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">
<div align="center">
<?php

$in_sql="UPDATE carregis.\"DetailCarTax\" SET \"CoPayDate\"='$copaydate',\"BillNumber\"='$billnumber' WHERE \"IDDetail\"='$did' ";    
if($result=pg_query($in_sql)){
      echo "บันทึกเรียบร้อยแล้ว"; 
}else{
      echo "ไม่สามารถบันทึกได้";
}
?>

<br><br>
<input type="button" value="  Close  " onclick="window.close()">
</div>
</div>
        </td>
    </tr>
</table>

</body>
</html>