<?php
include("../config/config.php");
$mmm = $_GET['mmm'];
$yy = $_GET['yy'];


if(!empty($mmm) && !empty($yy)){
    
$result=pg_query("select account.\"CheckVatInMonth\"('$mmm','$yy')");
$return_data=pg_fetch_result($result,0);

$rt=explode(",",$return_data);

$rs = str_replace("(","",$rt[0]);
$rs = str_replace('"',"",$rs);

$rend = str_replace(")","",$rt[7]);
$rend = str_replace('"',"",$rend);
?>

<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td><b>ผลการค้นหา</b></td>
    <td><?php echo $mmm."/".$yy; ?>&nbsp;<a href="check_vat_in_month_print.php?mm=<?php echo $mmm; ?>&yy=<?php echo $yy; ?>" target="_blank"><span style="font-size:16px; color:#0000FF;">(พิมพ์รายงาน)</span></td>
</tr>
<tr align="left">
    <td width="35%"><b>Result</b></td>
    <td width="65%"><?php echo $rs; ?></a></td>
</tr>
<tr align="left">
    <td><b>จำนวนลูกค้าทั้งหมด</b></td>
    <td><?php echo $rt[1]; ?> ราย</td>
</tr>
<tr align="left">
    <td><b>จำนวนลูกค้าที่มีงวดแรกในเดือนหน้า</b></td>
    <td><?php echo $rt[2]; ?> ราย</td>
</tr>
<tr align="left">
    <td><b>จำนวนลูกค้าที่จ่ายล้วงหน้า</b></td>
    <td><?php echo $rt[4]; ?> ราย</td>
</tr>
<tr align="left">
    <td><b>จำนวนลูกค้าที่ซื้อสด</b></td>
    <td><?php echo $rt[5]; ?> ราย</td>
</tr>
<tr align="left">
    <td><b>จำนวนข้อมูลที่ผิดผลาด</b></td>
    <td><?php echo $rt[6]; ?> รายการ</td>
</tr>
</table>
<br />
<table width="100%" cellpadding="3" cellspacing="3" border="0" bgcolor="#FFFFD5">
<tr align="left">
    <td><b><u>รายละเอียดข้อมูลที่ผิดผลาด</u></b></td>
</tr>
<tr align="left">
    <td>
<textarea name="errortext" id="errortext" rows="10" cols="100"><?php echo $rend; ?></textarea>
    </td>
</tr>
</table>

<?php
}
?>