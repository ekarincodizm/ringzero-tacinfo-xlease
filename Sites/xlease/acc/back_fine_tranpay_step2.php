<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$bank = pg_escape_string($_GET['bank']);
$idno = pg_escape_string($_GET['idno']);
$date = pg_escape_string($_GET['date']);
$amt = pg_escape_string($_GET['amt']);
$trantype = pg_escape_string($_GET['trantype']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
</head>
<body>

<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<fieldset>

<div>
<?php echo "<b>IDNO: $idno | ยอดเงิน : ".number_format($amt,2)." บาท.</b>"; ?>
</div>

<form name="frm1" id="frm1" action="back_fine_tranpay_step2_send.php" method="post">
<input type="hidden" name="id" id="id" value="<?php echo "$id"; ?>">
<input type="hidden" name="bank" id="bank" value="<?php echo "$bank"; ?>">
<input type="hidden" name="idno" id="idno" value="<?php echo "$idno"; ?>">
<input type="hidden" name="date" id="date" value="<?php echo "$date"; ?>">
<input type="hidden" name="amt" id="amt" value="<?php echo "$amt"; ?>">
<input type="hidden" name="trantype" id="trantype" value="<?php echo "$trantype"; ?>">
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่</td>
    <td>เลขที่ใบเสร็จ</td>
    <td>ยอดเงิน</td>
    <td>PayType</td>
    <td>select</td>
</tr>
<?php
$query=pg_query("select * from \"Fr\" WHERE \"R_Date\"='$date' AND \"IDNO\"='$idno' ORDER BY \"R_Receipt\" ASC");
while($resvc=pg_fetch_array($query)){
    $nub++;
    $IDNO = $resvc['IDNO'];
    $R_DueNo = $resvc['R_DueNo'];
    $R_Date = $resvc['R_Date'];
    $R_Prndate = $resvc['R_Prndate'];
    $R_Receipt = $resvc['R_Receipt'];
    $R_Money = $resvc['R_Money'];
    $PayType = $resvc['PayType'];
    
    $query_fvat=pg_query("select * from \"FVat\" WHERE \"IDNO\"='$IDNO' AND \"V_DueNo\"='$R_DueNo'");
    if($resvc_fvat=pg_fetch_array($query_fvat)){
        $VatValue = $resvc_fvat['VatValue'];
    }
    
    $R_Money = $R_Money+$VatValue;
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $R_Date; ?></td>
    <td align="center"><?php echo $R_Receipt; ?></td>
    <td align="right"><?php echo number_format($R_Money,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><input type="checkbox" name="chk[]" id="chk" value="<?php echo "FR#$IDNO#$R_Receipt#$R_Prndate#$R_Money#$R_DueNo"; ?>"></td>
</tr>
<?php
}

$query=pg_query("select * from \"FOtherpay\" WHERE \"O_DATE\"='$date' AND \"IDNO\"='$idno' ORDER BY \"O_RECEIPT\" ASC");
while($resvc=pg_fetch_array($query)){
    $nub++;
    $IDNO = $resvc['IDNO'];
    $O_DATE = $resvc['O_DATE'];
    $O_PRNDATE = $resvc['O_PRNDATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $O_MONEY = $resvc['O_MONEY'];
    $PayType = $resvc['PayType'];
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><input type="checkbox" name="chk[]" id="chk" value="<?php echo "OT#$IDNO#$O_RECEIPT#$O_PRNDATE#$O_MONEY"; ?>"></td>
</tr>
<?php
}

if($nub==0){
    echo "<tr><td colspan=\"10\" align=\"center\">- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>
<?php
if($nub>0){
    echo '<div align="center" style="padding: 10px 0 10px 0"><input type="submit" name="btn1" id="btn1" value="บันทึก" class="ui-button"></div>';
}
?>

</form>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>