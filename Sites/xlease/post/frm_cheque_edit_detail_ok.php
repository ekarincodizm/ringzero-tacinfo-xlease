<?php
include("../config/config.php");

$in_PostID = $_POST['in_PostID'];
$in_ChequeNo = $_POST['in_ChequeNo'];
$in_ChequeNo_old = $_POST['in_ChequeNo_old'];
$in_BankName = $_POST['in_BankName'];
$in_BankBranch = $_POST['in_BankBranch'];
$DateOnCheque = $_POST['DateOnCheque'];

//echo "$in_PostID $in_ChequeNo $in_BankName $in_BankBranch $DateOnCheque";
//exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td> 

<div class="wrapper">

<fieldset><legend><B>แก้ไขรายการเช็ค</B></legend>

<div align="center">
<?php
$in_sql="UPDATE \"FCheque\" SET \"ChequeNo\"='$in_ChequeNo',\"BankName\"='$in_BankName',\"BankBranch\"='$in_BankBranch',\"DateOnCheque\"='$DateOnCheque' WHERE \"PostID\"='$in_PostID' AND \"ChequeNo\"='$in_ChequeNo_old'";
if($result=pg_query($in_sql)){
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>
</div>

<div align="center"><br /><input name="button" type="button" onclick="window.location='frm_cheque_edit_select.php'" value="ย้อนกลับ" /></div>

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>