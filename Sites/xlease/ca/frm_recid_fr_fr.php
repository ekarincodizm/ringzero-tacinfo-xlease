<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);
$c_code=$_SESSION["session_company_code"];
//$c_code="THA";

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
function pdf(r,idno2){
	
popU('frm_recprint_<?php echo $c_code; ?>.php?id=<?php echo $R_Receipt; ?>&idno='+idno2,'<?php echo "fs2323dfs4342sadsa_".$R_Receipt; ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600')

}
</script>

</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">
<fieldset><legend><B>พิมพ์ใบเสร็จ - ค่างวด</B></legend>
<div style="padding-top:10px;"><span style="background-color:#FFCCCC">&nbsp;&nbsp;&nbsp;&nbsp;</span> <b>คือ รายการที่ถูกยกเลิกใบเสร็จแล้ว</b></div>
<table width="700" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
	<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
      <td>เลขที่ใบเสร็จ</td>
      <td>วันที่</td>
	  <td>งวดที่</td>
      <td>จำนวนเงิน</td>
      <td>สถานะ</td>
      <td>ช่องทาง</td>
      <td>Print</td>
   </tr>

<?php
$j = 0;
$qry_in=pg_query("SELECT \"IDNO\",\"R_Receipt\",\"R_Money\",\"PayType\",\"Cancel\",\"R_Date\",\"R_DueNo\" FROM \"Fr\" where \"IDNO\" = '$idno' ORDER BY \"R_Receipt\",\"R_DueNo\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"]; 
    $R_Money = $res_in["R_Money"];
    $R_Bank = $res_in["R_Bank"];
    $PayType = $res_in["PayType"];
    $Cancel = $res_in["Cancel"];
	$R_DueNo = $res_in["R_DueNo"];
    
    if($Cancel == 't'){
        echo "<tr class=\"red\" align=\"center\">";
    }else{
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\" align=\"center\">";
        }else{
            echo "<tr class=\"even\" align=\"center\">";
        }
    }
?>
      <td><?php echo $R_Receipt; ?></td>
      <td><?php echo $R_Date; ?></td>
	  <td><?php echo $R_DueNo; ?></td>
      <td align="right"><?php echo number_format($R_Money,2); ?></td>
      <td align="left"><?php echo $R_Bank; ?></td>
      <td align="left"><?php echo $PayType; ?></td>
      <td>
<?php
if($Cancel == 't'){
?>
    <a href="#" onclick="javascript:popU('reprint_reason.php?rec_id=<?php echo $R_Receipt; ?>&t=1','<?php echo "dsadwqedfsd_".$R_Receipt; ?>','');">
    <img src="icoPrint.png" border="0" width="17" height="14" alt="Print"></a>
<?php
}else{
?>
    <a href="#" onclick="javascript:popU('reprint_reason.php?rec_id=<?php echo $R_Receipt; ?>&t=2&idno=<?php echo $idno; ?>','<?php echo "reason_".$R_Receipt; ?>','');">
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