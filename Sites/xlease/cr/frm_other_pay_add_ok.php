<?php
session_start();
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);
$iduser = pg_escape_string($_POST['iduser']);
$billnumber = pg_escape_string($_POST['billnumber']);
$taxvalue = pg_escape_string($_POST['taxvalue']);
$copaydate = pg_escape_string($_POST['copaydate']);
//$chargevalue = pg_escape_string($_POST['chargevalue']);
$remark = pg_escape_string($_POST['remark']);
$hiddenremark = pg_escape_string($_POST['hiddenremark']);
$typepay = pg_escape_string($_POST['typepay']);
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
    pg_query("BEGIN WORK");
    
    $in_sql="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$copaydate','$taxvalue','$billnumber','$typepay')";
    if($result=pg_query($in_sql)){
        $status = 0;
    }else{
        $status = 1;
    }

    if(!empty($remark)){
        $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
        $remark = "\nวันที่ $nowdate; โดย $iduser;\n".$remark."\n-----------------------------------\n".$hiddenremark;
        $in_sql2="UPDATE carregis.\"CarTaxDue\" SET \"remark\"='$remark' WHERE \"IDCarTax\"='$cid' ";
        if($result2=pg_query($in_sql2)){
            $status = 0;
        }else{
            $status = 1;
        }
    }
    
    if($status == 0){
        pg_query("COMMIT");
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    }else{
        pg_query("ROLLBACK");
        echo "ไม่สามารถบันทึกข้อมูลได้";
    }
?>

<br><br>
<input type="button" value=" ปิดหน้านี้ " onclick="window.close();">

</div>

        </td>
    </tr>
</table>

</body>
</html>