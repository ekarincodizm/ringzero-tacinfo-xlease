<?php
session_start();
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);

$date_start = pg_escape_string($_POST['date_start']); 
$apointmentdate = pg_escape_string($_POST['apointmentdate']);  
$money = pg_escape_string($_POST['money']); 

$remark = pg_escape_string($_POST['remark']); 
$hiddenremark = pg_escape_string($_POST['hiddenremark']); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">

<?php
    if(!empty($remark)){
        $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
        $remark = "\nวันที่ $nowdate; โดย $iduser;\n".$remark."\n-----------------------------------\n".$hiddenremark;
        $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"TaxDueDate\"='$date_start',\"ApointmentDate\"='$apointmentdate',\"CusAmt\"='$money',\"remark\"='$remark' WHERE \"IDCarTax\"='$cid' ";
    }else{
        $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"TaxDueDate\"='$date_start',\"ApointmentDate\"='$apointmentdate',\"CusAmt\"='$money' WHERE \"IDCarTax\"='$cid' ";
    }
    
    if($result=pg_query($in_sql)){
          echo "บันทึกเรียบร้อยแล้ว"; 
    }else{
          echo "ไม่สามารถบันทึกได้";
    }

?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_car_admin_ot.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>