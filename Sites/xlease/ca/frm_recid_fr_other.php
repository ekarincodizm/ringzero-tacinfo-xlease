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
<fieldset><legend><B>พิมพ์ใบเสร็จ - ค่าอื่นๆ</B></legend>

<div style="padding-top:10px;"><span style="background-color:#FFCCCC">&nbsp;&nbsp;&nbsp;&nbsp;</span> <b>คือ รายการที่ถูกยกเลิกใบเสร็จแล้ว</b></div>
<table width="700" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">เลขที่ใบเสร็จ</td>
      <td align="center">วันที่</td>
      <td align="center">จำนวนเงิน</td>
      <td align="center">Type</td>
      <td align="center">สถานะ</td>
      <td align="center">ช่องทาง</td>
      <td align="center">Print</td>
   </tr>

<?php
$j = 0;
$qry_in=pg_query("SELECT \"O_RECEIPT\",\"O_DATE\",\"O_Type\",\"O_MONEY\",\"O_BANK\",\"PayType\",\"Cancel\",\"IDNO\" 
FROM \"FOtherpay\" where \"IDNO\" = '$idno' ORDER BY \"O_RECEIPT\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $O_RECEIPT = $res_in["O_RECEIPT"];
    $O_DATE = $res_in["O_DATE"]; 
    $O_Type = $res_in["O_Type"];
    $O_MONEY = $res_in["O_MONEY"];
    $O_BANK = $res_in["O_BANK"];
    $PayType = $res_in["PayType"];
    $Cancel = $res_in["Cancel"];
	$idno = $res_in["IDNO"];
    
    $qry_in2=pg_query("SELECT \"TName\" FROM \"TypePay\" where \"TypeID\" = '$O_Type'");
    if($res_in2=pg_fetch_array($qry_in2)){
        $TName = $res_in2["TName"];
    }
    
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
      <td align="center"><?php echo $O_RECEIPT; ?></td>
      <td align="center"><?php echo $O_DATE; ?></td>
      <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
      <td align="left"><?php echo $TName; ?></td>
      <td align="left"><?php echo $O_BANK; ?></td>
      <td align="left"><?php echo $PayType; ?></td>
      <td align="center">
<?php
if($Cancel == 't'){
?>
    <a href="#" onclick="javascript:popU('reprint_reason.php?rec_id=<?php echo $O_RECEIPT; ?>&t=3','<?php echo "reason_".$O_RECEIPT; ?>','');">
    <img src="icoPrint.png" border="0" width="17" height="14" alt="Print"></a>
<?php
}else{
?>
    <a href="#" onclick="javascript:popU('reprint_reason.php?rec_id=<?php echo $O_RECEIPT; ?>&t=4&idno=<?php echo $idno; ?>','<?php echo "reason_".$O_RECEIPT; ?>','');">
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