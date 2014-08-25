<?php
session_start();
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);
$iduser = pg_escape_string($_POST['iduser']);
$taxvalue = pg_escape_string($_POST['taxvalue']);
$copaydate = pg_escape_string($_POST['copaydate']);
$chargevalue = pg_escape_string($_POST['chargevalue']);
$remark = pg_escape_string($_POST['remark']);
$hiddenremark = pg_escape_string($_POST['hiddenremark']);
$billnumber = pg_escape_string($_POST['billnumber']);
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

    for($i=0;$i<count($billnumber);$i++){
        if($billnumber[$i] != ""){
            if(count($billnumber)-1 == $i) $add_bill .= $billnumber[$i];
            else $add_bill .= $billnumber[$i].",";
        }
    }
    
    if(!empty($remark)){
        $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
        $remark = "\nวันที่ $nowdate; โดย $iduser;\n".$remark."\n-----------------------------------\n".$hiddenremark;
        $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"TaxValue\"='$taxvalue',\"CoPayDate\"='$copaydate',\"ChargeValue\"='$chargevalue',\"userid\"='$iduser',\"remark\"='$remark',\"BillNumber\"='$add_bill' WHERE \"IDCarTax\"='$cid' ";
    }else{
        $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"TaxValue\"='$taxvalue',\"CoPayDate\"='$copaydate',\"ChargeValue\"='$chargevalue',\"userid\"='$iduser',\"BillNumber\"='$add_bill' WHERE \"IDCarTax\"='$cid' ";    
    }
    
    if($result=pg_query($in_sql)){
          echo "บันทึกเรียบร้อยแล้ว"; 
    }else{
          echo "ไม่สามารถบันทึกได้";
    }
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_car_pay.php'">
</div>
</div>
        </td>
    </tr>
</table>

</body>
</html>