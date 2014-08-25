<?php
include("../config/config.php");

$mode = $_POST['mode'];
$edit_cheq = $_POST['edit_cheq'];
$edit_date = $_POST['edit_date'];
$newmemo = $_POST['newmemo'];
$oldmemo = $_POST['oldmemo'];

$sql_select=pg_query("select \"PostID\", \"NumOfReEnter\" from \"FCheque\" where \"ChequeNo\" = '$edit_cheq' AND \"IsPass\" = 'FALSE' AND \"Accept\" = 'TRUE' AND \"IsReturn\" = 'FALSE'");
if($res_cn=pg_fetch_array($sql_select)){
	$PostID = $res_cn["PostID"];
    $NumOfReEnter = $res_cn["NumOfReEnter"]+1;
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

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<fieldset><legend><B>ทำรายการเช็คคืน</B></legend>

<div align="center">
<?php

if(!empty($newmemo)){
    $iduser = $_SESSION["av_iduser"];
    $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
    $memo = "\nวันที่ $nowdate; โดย $iduser;\n".$newmemo."\n-----------------------------------\n".$oldmemo;
    $in_sql="UPDATE \"FCheque\" SET \"NumOfReEnter\"='$NumOfReEnter',\"DateEnterBank\"='$edit_date',\"IsReturn\"='FALSE',\"ReEnterDate\"='$edit_date',\"memo\"='$memo' WHERE \"ChequeNo\"='$edit_cheq' AND \"PostID\" = '$PostID'";
}else{
    $in_sql="UPDATE \"FCheque\" SET \"NumOfReEnter\"='$NumOfReEnter',\"DateEnterBank\"='$edit_date',\"IsReturn\"='FALSE',\"ReEnterDate\"='$edit_date' WHERE \"ChequeNo\"='$edit_cheq' AND \"PostID\" = '$PostID'";
}

if($result=pg_query($in_sql)){
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>
</div>

</div>
        </td>
    </tr>
</table>

<div align="center">
<input name="button" type="button" onclick="window.location='frm_edit_cheq.php'" value="  ย้อนกลับ  " />
</div>

</body>
</html>