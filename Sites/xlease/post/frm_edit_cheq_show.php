<?php
session_start();
include("../config/config.php"); 
$chwq_id = $_POST["idno_names"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<div align="left"><input name="button" type="button" onclick="window.location='frm_edit_cheq.php'" value="  ย้อนกลับ  " /></div>

<fieldset><legend><B>ทำรายการเช็คคืน</B></legend>

<?php

$sql_select=pg_query("select * from \"FCheque\" where \"ChequeNo\" = '$chwq_id' AND \"IsPass\" = 'FALSE' AND \"Accept\" = 'TRUE' AND \"IsReturn\" = 'FALSE'");
if($res_cn=pg_fetch_array($sql_select)){
    $PostID = $res_cn["PostID"];
    $BankName = $res_cn["BankName"];
    $BankBranch = $res_cn["BankBranch"];
    $AmtOnCheque = $res_cn["AmtOnCheque"];
    $ReceiptDate = $res_cn["ReceiptDate"];
    $DateOnCheque = $res_cn["DateOnCheque"];
    $DateEnterBank = $res_cn["DateEnterBank"]; if(empty($DateEnterBank)) $DateEnterBank = "-";
    $IsReturn = $res_cn["IsReturn"];
    $IsPass = $res_cn["IsPass"];
}

?>

<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr>
      <td width="15%"><b>หมายเลขเช็ค</b></td>
      <td width="35%"><?php echo $chwq_id; ?></td>
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
<br>
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td width="25%" align="center"><b>CusID</b></td>
      <td width="25%" align="center"><b>IDNO</b></td>
      <td width="25%" align="center"><b>TypePay</b></td>
      <td width="25%" align="center"><b>จำนวนเงิน</b></td>
   </tr>
<?php
$sql_select=pg_query("select * from \"DetailCheque\" where \"ChequeNo\" = '$chwq_id' AND \"PostID\" = '$PostID' ORDER BY \"CusID\" ASC ");
while($res_cn=pg_fetch_array($sql_select)){
    $CusID = $res_cn["CusID"];
    $IDNO = $res_cn["IDNO"];
    $TypePay = $res_cn["TypePay"];
    $CusAmount = $res_cn["CusAmount"];
    
    $sql_typepay=pg_query("select \"TName\" from \"TypePay\" where \"TypeID\" = '$TypePay'");
    if($res_typepay=pg_fetch_array($sql_typepay)){
        $TName = $res_typepay["TName"];
    }
    
    $sql_typepay=pg_query("select \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" from \"Fa1\" where \"CusID\" = '$CusID'");
    if($res_typepay=pg_fetch_array($sql_typepay)){
        $A_FIRNAME = $res_typepay["A_FIRNAME"];
        $A_NAME = $res_typepay["A_NAME"];
        $A_SIRNAME = $res_typepay["A_SIRNAME"];
    }
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
      <td><?php echo "$A_FIRNAME $A_NAME $A_SIRNAME"; ?></td>
      <td align="center"><?php echo $IDNO; ?></td>
      <td><?php echo $TName; ?></td>
      <td align="right"><?php echo number_format($CusAmount,2); ?></td>
   </tr>
<?php
}
?>
</table>
<br>
<form action="frm_edit_cheq_show_edit.php" method="post" name="form1" onSubmit="JavaScript:return fncSubmit();">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr>
        <td align="center">
<input name="edit_cheq" type="hidden" value="<?php echo $chwq_id; ?>" />
<input name="button1" type="submit" value="  เช็คคืน  " <?php if($IsReturn == "t"){ echo "disabled"; } ?> />
<input name="button2" type="button" onclick="window.location='frm_edit_cheq_addnew.php?cheqid=<?php echo $chwq_id; ?>'" value="  เข้าใหม่  " />
        </td>
    </tr>
</table>
</form>

</fieldset> 

</div>
        </td>
    </tr>
</table>

</body>
</html>