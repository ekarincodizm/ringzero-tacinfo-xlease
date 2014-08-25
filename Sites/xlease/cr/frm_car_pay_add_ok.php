<?php
session_start();
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);
$iduser = pg_escape_string($_POST['iduser']);

//$billnumber1 = pg_escape_string($_POST['billnumber1']);
$taxvalue1 = pg_escape_string($_POST['taxvalue1']);           if(empty($taxvalue1)) $taxvalue1 = 0;
$copaydate1 = pg_escape_string($_POST['copaydate1']);     if(empty($copaydate1)) $copaydate1 = date("Y-m-d");

//$billnumber2 = pg_escape_string($_POST['billnumber2']);
$taxvalue2 = pg_escape_string($_POST['taxvalue2']);           if(empty($taxvalue2)) $taxvalue2 = 0;
$copaydate2 = pg_escape_string($_POST['copaydate2']);     if(empty($copaydate2)) $copaydate2 = date("Y-m-d");

$remark = pg_escape_string($_POST['remark']);
$hiddenremark = pg_escape_string($_POST['hiddenremark']);
$typedep = pg_escape_string($_POST['typedep']);
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

pg_query("BEGIN WORK");
    $status = 0;
    if(!empty($remark)){
        $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
        $remark = "\nวันที่ $nowdate; โดย $iduser;\n".$remark."\n-----------------------------------\n".$hiddenremark;
        $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"remark\"='$remark' WHERE \"IDCarTax\"='$cid' ";
        if(!$result=pg_query($in_sql)){
            $status++;
        }
    }

if(isset($_POST['billnumber1'])){
    $in_sql2="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$copaydate1','$taxvalue1','".pg_escape_string($_POST[billnumber1])."','105')";
    if(!$result2=pg_query($in_sql2)){
        $status++;
    }
}

if(isset($_POST['billnumber2'])){
if($typedep == '101'){
    $in_sql3="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$copaydate2','$taxvalue2','".pg_escape_string($_POST[billnumber2])."','101')";
    if(!$result3=pg_query($in_sql3)){
        $status++;
    }
}
}

if(isset($_POST['billnumber3'])){
    $selecttype = pg_escape_string($_POST['selecttype']);
    $taxvalue3 = pg_escape_string($_POST['taxvalue3']);           if(empty($taxvalue3)) $taxvalue3 = 0;
    $copaydate3 = pg_escape_string($_POST['copaydate3']);     if(empty($copaydate3)) $copaydate3 = date("Y-m-d");
    $in_sql4="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$copaydate3','$taxvalue3','".pg_escape_string($_POST[billnumber3])."','$selecttype')";
    if(!$result4=pg_query($in_sql4)){
        $status++;
    }
}
 
    if($status == 0){
        pg_query("COMMIT");
        echo "บันทึกเรียบร้อยแล้ว";
    }else{
        pg_query("ROLLBACK");
        echo "ไม่สามารถบันทึกได้";
    }

?>

<br><br>
<input type="button" value=" ปิดหน้านี้ " onclick="window.close();">
</div>
</div>
        </td>
    </tr>
</table>

</body>
</html>