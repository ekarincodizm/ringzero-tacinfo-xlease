<?php
include("../config/config.php");
$datepicker = pg_escape_string($_GET['datepicker']);

if(!empty($datepicker)){
?>
<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>
<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>

<div align="right"><a href="receipt_day_pdf.php?date=<?php echo "$datepicker"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>

<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td><b>ผลการค้นหา : VFrEachDay</b></td>
    <td align="right"><!--<a href="#"><span style="font-size:15px; color:#0000FF;">(<u>พิมพ์รายงาน</u>)</span></a>--></td>
</tr>
</table>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>R_Date</td>
    <td><u>R_Receipt</u></td>
    <td>IDNO</td>
    <td>ชื่อสกุล</td>
    <td>assetname</td>
    <td>ทะเบียน</td>
    <td>value</td>
    <td>vat</td>
    <td>money</td>
    <td>Type</td>
    <td>PayType</td>
    <td>R_Bank</td>
    <td>R_memo</td>
</tr>

<?php
$query=pg_query("select * from \"VFrEachDay\" WHERE \"R_Prndate\"='$datepicker' ORDER BY \"R_Receipt\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $R_Date = $resvc['R_Date'];
    $R_Receipt = $resvc['R_Receipt'];
    $IDNO = $resvc['IDNO'];
    $full_name = $resvc['full_name'];
    $assetname = $resvc['assetname'];
    $regis = $resvc['regis'];
    $value = $resvc['value'];
    $vat = $resvc['vat'];
    $money = $resvc['money'];
    $typepay_name = $resvc['typepay_name'];
    $PayType = $resvc['PayType'];
    $R_Bank = $resvc['R_Bank'];
    $R_memo = $resvc['R_memo'];

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td><?php echo $R_Date; ?></td>
        <td><?php echo $R_Receipt; ?></td>
        <td><a onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer" ><u><?php echo $IDNO; ?></u></a></td>
        <td><?php echo $full_name; ?></td>
        <td><?php echo $assetname; ?></td>
        <td><?php echo $regis; ?></td>
        <td align="right"><?php echo number_format($value,2); ?></td>
        <td align="right"><?php echo number_format($vat,2); ?></td>
        <td align="right"><?php echo number_format($money,2); ?></td>
        <td><?php echo $typepay_name; ?></td>
        <td><?php echo $PayType; ?></td>
        <td><?php echo $R_Bank; ?></td>
        <td><?php echo $R_memo; ?></td>
    </tr>
    
<?php
}

if($num_row==0){
?>
<tr>
    <td colspan="13" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>

</table>

<br />

<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td><b>ผลการค้นหา : VFOtherpayEachDay</b></td>
    <td align="right"><!--<a href="#"><span style="font-size:15px; color:#0000FF;">(<u>พิมพ์รายงาน</u>)</span></a>--></td>
</tr>
</table>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>O_DATE</td>
    <td><u>O_RECEIPT</u></td>
    <td>IDNO</td>
    <td>ชื่อสกุล</td>
    <td>assetname</td>
    <td>ทะเบียน</td>
    <td>TName</td>
    <td>O_MONEY</td>
    <td>PayType</td>
    <td>O_BANK</td>
    <td>O_memo</td>
</tr>

<?php
$num_row=0;
$i=0;
$query=pg_query("select * from \"VFOtherpayEachDay\" WHERE \"O_PRNDATE\"='$datepicker' ORDER BY \"O_RECEIPT\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $IDNO = $resvc['IDNO'];
    $full_name = $resvc['full_name'];
    $assetname = $resvc['assetname'];
    $regis = $resvc['regis'];
    $TName = $resvc['TName'];
    $O_MONEY = $resvc['O_MONEY'];
    $PayType = $resvc['PayType'];
    $O_BANK = $resvc['O_BANK'];
    $O_memo = $resvc['O_memo'];

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td><?php echo $O_DATE; ?></td>
        <td><?php echo $O_RECEIPT; ?></td>
        <td><a onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer" ><u><?php echo $IDNO; ?></u></a></td>
        <td><?php echo $full_name; ?></td>
        <td><?php echo $assetname; ?></td>
        <td><?php echo $regis; ?></td>
        <td><?php echo $TName; ?></td>
        <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
        <td><?php echo $PayType; ?></td>
        <td><?php echo $O_BANK; ?></td>
        <td><?php echo $O_memo; ?></td>
    </tr>
    
<?php
}

if($num_row==0){
?>
<tr>
    <td colspan="13" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>

</table>

<?php
}
?>