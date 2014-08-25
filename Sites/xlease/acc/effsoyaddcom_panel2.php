<?php
include("../config/config.php");
$yy = pg_escape_string($_GET['yy']);
$datepicker = pg_escape_string($_GET['datepicker']);
?>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
</style>

<div style="float:left">&nbsp;</div>
<div style="float:right"><a href="effsoyaddcom_pdf.php?datepicker=<?php echo $datepicker; ?>&yy=<?php echo $yy; ?>" target="_blank"><u>(พิมพ์รายงาน)</u></a></div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>เลขที่สัญญา</td>
    <td>ชื่อลูกค้า</td>
    <td>สะสมปีก่อน</td>
    <td>ตัดจ่ายปีนี้</td>
    <td>รอตัดยกไป</td>
    <td>ทั้งหมด</td>
</tr>

<?php
$nub = 0;
$query=pg_query("SELECT \"idno\",\"comlastyear\",\"comaccthisyear\",\"comnextyear\" FROM \"account\".\"effsoyaddcom\" WHERE \"acclosedate\"='$datepicker' AND \"custyear\"='$yy' ORDER BY \"idno\" ASC ");
while($resvc=pg_fetch_array($query)){
    $nub++;
    $sum = 0;
    $idno = $resvc['idno'];
    $comlastyear = $resvc['comlastyear'];
    $comaccthisyear = $resvc['comaccthisyear'];
    $comnextyear = $resvc['comnextyear'];
    $sum = $comlastyear+$comaccthisyear+$comnextyear;
    
    $sum_comlastyear += $comlastyear;
    $sum_comaccthisyear += $comaccthisyear;
    $sum_comnextyear += $comnextyear;
    $sum_sum += $sum;
    
    $full_name = "";
    $query1=pg_query("SELECT \"full_name\" FROM \"VContact\" WHERE \"IDNO\"='$idno'");
    if($resvc1=pg_fetch_array($query1)){
        $full_name = $resvc1['full_name'];
    }

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo "$idno"; ?></td>
        <td><?php echo $full_name; ?></td>
        <td align="right"><?php echo number_format($comlastyear,2); ?></td>
        <td align="right"><?php echo number_format($comaccthisyear,2); ?></td>
        <td align="right"><?php echo number_format($comnextyear,2); ?></td>
        <td align="right"><?php echo number_format($sum,2); ?></td>
    </tr>
<?php
}

if($nub > 0){
?>
<tr>
    <td><b>จำนวน <?php echo "$nub"; ?> รายการ</b></td>
    <td align="right"><b>รวมทั้งสิ้น</b></td>
    <td align="right"><b><?php echo number_format($sum_comlastyear,2); ?></b></td>
    <td align="right"><b><?php echo number_format($sum_comaccthisyear,2); ?></b></td>
    <td align="right"><b><?php echo number_format($sum_comnextyear,2); ?></b></td>
    <td align="right"><b><?php echo number_format($sum_sum,2); ?></b></td>
</tr>
<?php
}else{
    echo "<tr><td colspan=6 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>