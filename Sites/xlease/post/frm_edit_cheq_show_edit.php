<?php
include("../config/config.php");

$edit_cheq = $_POST['edit_cheq'];

$sql_select=pg_query("select * from \"FCheque\" where \"ChequeNo\" = '$edit_cheq' AND \"IsPass\" = 'FALSE' AND \"Accept\" = 'TRUE' AND \"IsReturn\" = 'FALSE'");
if($res_cn=pg_fetch_array($sql_select)){
    $PostID = $res_cn["PostID"];
    $BankName = $res_cn["BankName"];
    $BankBranch = $res_cn["BankBranch"];
    $AmtOnCheque = $res_cn["AmtOnCheque"];
    $ReceiptDate = $res_cn["ReceiptDate"];
    $DateOnCheque = $res_cn["DateOnCheque"];
    $DateEnterBank = $res_cn["DateEnterBank"]; if(empty($DateEnterBank)) $DateEnterBank = nowDate();
    $IsReturn = $res_cn["IsReturn"];
    $IsPass = $res_cn["IsPass"];
    $memo = $res_cn["memo"];
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

<div align="left"><input name="button" type="button" onclick="window.location='frm_edit_cheq.php'" value="  ย้อนกลับ  " /></div>

<fieldset><legend><B>ทำรายการเช็คคืน</B></legend>

<form action="frm_edit_cheq_show_edit_ok.php" method="post" name="form1">
<input name="edit_postid" type="hidden" value="<?php echo $PostID; ?>" />
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr>
      <td width="15%"><b>หมายเลขเช็ค</b></td>
      <td width="35%"><?php echo $edit_cheq; ?><input name="edit_cheq" type="hidden" value="<?php echo $edit_cheq; ?>" /></td>
      <td width="15%"><b>PostID</b></td>
      <td width="35%"><?php echo $PostID; ?></td>
   </tr>
    <tr>
      <td><b>ธนาคาร</b></td>
      <td><?php echo $BankName; ?></td>
      <td><b>สาขา</b></td>
      <td><?php echo $BankBranch; ?></td>
   </tr>
    <tr>
      <td><b>วันที่รับเช็ค</b></td>
      <td><?php echo $ReceiptDate; ?></td>
      <td><b>วันที่บนเช็ค</b></td>
      <td><?php echo $DateOnCheque; ?></td>
   </tr>
    <tr>
      <td><b>จำนวนเงิน</b></td>
      <td><?php echo number_format($AmtOnCheque,2); ?> บาท.</td>
      <td><b>วันที่เข้าธนาคาร</b></td>
      <td><?php echo $DateEnterBank; ?></td>
   </tr>
    <tr>
      <td><b>สถานะ</b></td>
      <td>
<?php

if($IsReturn == "t"){
    echo "<span style=\"color:red;\">ส่งคืน</span>";
}else{
    echo "-";
}

?>
      </td>
   </tr>
</table>

<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
<tr>
    <td width="15%"><b>เพิ่มหมายเหตุ</b></td>
    <td>
<textarea name="newmemo" rows="3" cols="50"></textarea>
    </td>
</tr>
<tr>
    <td><b>หมายเหตุ</b></td>
    <td>
<textarea name="oldmemo" rows="3" cols="50" readonly><?php echo "$memo"; ?></textarea>
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input name="button" type="submit" value="บันทึก" /></td>
</tr>
</table>
</form>

</div>
        </td>
    </tr>
</table>

</body>
</html>