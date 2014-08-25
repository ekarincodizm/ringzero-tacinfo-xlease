<?php
session_start();
include("../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];
$idno = pg_escape_string($_POST['gidno']);
$typepay = pg_escape_string($_POST['typepay']);
$apointmentdate = pg_escape_string($_POST['apointmentdate']);
$money = pg_escape_string($_POST['money']);
$remark = pg_escape_string($_POST['remark']);
$ndate = nowDate();//ดึง วันที่จาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<?php include("menu.php"); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>

<fieldset><legend><B>สร้างรายการชำระเงินค่าอื่นๆ</B></legend>        

<div align="center">

<?php

    if(empty($idno)){ $idno = "00-00-00000"; }
    
    pg_query("BEGIN WORK");
    
    $oins=pg_query("select carregis.gen_id('$ndate')");
    $genid=pg_fetch_result($oins,0);
    
    $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
    $remark = "\nวันที่ $nowdate; โดย $get_id_user;\n".$remark;
    
    $in_sql="INSERT INTO carregis.\"CarTaxDue\" (\"IDCarTax\",\"IDNO\",\"TaxDueDate\",\"ApointmentDate\",\"userid\",\"remark\",\"CusAmt\",\"TypeDep\") 
    VALUES ('$genid','$idno','$ndate','$apointmentdate','$get_id_user','$remark','$money','$typepay') ";
    if($result=pg_query($in_sql)){		
        pg_query("COMMIT");
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(TAL) ทำรายการระบบทะเบียนรถ - บันทึกรายการชำระเงินค่าอื่นๆ', '$datelog')");
		//ACTIONLOG---
        echo "บันทึกเรียบร้อยแล้ว";
    }else{
        pg_query("ROLLBACK");
        echo "ไม่สามารถบันทึกได้";
    }
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_other_add.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>