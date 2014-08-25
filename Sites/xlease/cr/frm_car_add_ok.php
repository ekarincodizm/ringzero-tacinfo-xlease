<?php
session_start();
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);
$iduser = pg_escape_string($_POST['iduser']);
$apointmentdate = pg_escape_string($_POST['apointmentdate']);
$remark = pg_escape_string($_POST['remark']);
    if(!empty($remark)){
        $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
        $remark = "\nวันที่ $nowdate; โดย $iduser;\n".$remark."\n-----------------------------------";
    }
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
<div class="wrapper" align="center">

<?php
    $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"ApointmentDate\"='$apointmentdate',\"userid\"='$iduser',\"remark\"='$remark' WHERE \"IDCarTax\"='$cid' ";
    if($result=pg_query($in_sql)){
          echo "บันทึกเรียบร้อยแล้ว"; 
    }else{
          echo "ไม่สามารถบันทึกได้";
    }

?>

</div>

        </td>
    </tr>
</table>

</body>
</html>