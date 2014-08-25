<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);
$c_code=$_SESSION["session_company_code"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">
<fieldset><legend><B>พิมพ์ใบเสร็จ - ภาษี</B></legend>
<div style="padding-top:10px;"><span style="background-color:#FFCCCC">&nbsp;&nbsp;&nbsp;&nbsp;</span> <b>คือ รายการที่ถูกยกเลิกใบเสร็จแล้ว</b></div>
<table width="700" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">เลขที่ใบเสร็จ</td>
      <td align="center">วันที่</td>
      <td align="center">จำนวนเงิน</td>
      <td align="center">วันที่พิมพ์</td>
      <td align="center">สถานะ</td>
      <td align="center">Print</td>
   </tr>

<?php
$j = 0;
$qry_in=pg_query("SELECT \"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\",\"Cancel\" FROM \"FVat\" where \"IDNO\" = '$idno' ORDER BY \"V_Receipt\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $V_Receipt = $res_in["V_Receipt"];
    $V_Date = $res_in["V_Date"]; 
    $VatValue = $res_in["VatValue"];
    $V_PrnDate = $res_in["V_PrnDate"];
    $Paid_Status = $res_in["Paid_Status"];
        if($Paid_Status == 't') $show_stat = "ชำระแล้ว";
        else $show_stat = "ยังไมชำระ";
    $Cancel = $res_in["Cancel"];

    if($Cancel == 't'){
        echo "<tr class=\"red\">";
    }else{
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
    }
?>
      <td align="center"><?php echo $V_Receipt; ?></td>
      <td align="center"><?php echo $V_Date; ?></td>
      <td align="right"><?php echo number_format($VatValue,2); ?></td>
      <td align="center"><?php echo $V_PrnDate; ?></td>
      <td align="left"><?php echo $show_stat; ?></td>
      <td align="center">
<?php
if($Cancel == 't'){
?>
    <a href="#" onclick="javascript:popU('reprint_reason.php?rec_id=<?php echo $V_Receipt; ?>&t=5','<?php echo "reason_".$V_Receipt; ?>','');">
    <img src="icoPrint.png" border="0" width="17" height="14" alt="Print"></a>
<?php
}else{
?>
    <a href="#" onclick="javascript:popU('reprint_reason.php?rec_id=<?php echo $V_Receipt; ?>&t=6','<?php echo "reason_".$V_Receipt; ?>','');">
    <img src="icoPrint.png" border="0" width="17" height="14" alt="Print"></a>
<?php
}
?>
      </td>
   </tr>
<?php
} // WHILE
?>
</table>

</fieldset> 

</div>
        </td>
    </tr>
</table>

</body>
</html>