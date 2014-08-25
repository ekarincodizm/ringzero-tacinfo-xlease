<?php
if($status=="1"){
	$contractID=$idno; //กรณีส่งค่ามาจากหน้า "(THCAP) ตารางแสดงการผ่อนชำระ"
}else{
	include("../../config/config.php");
	$contractID = $_GET['dt'];
}

if(empty($contractID)){
    exit;
}
?>

<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#CDCDB4">
<tr style="font-weight:bold;color:#FFFFFF;" valign="middle" bgcolor="#8B8B7A" align="center">
    <td>วันที่ใบกำกับภาษี</td>
    <td>เลขที่ใบกำกับภาษี</td>
    <td>จำนวนเงิน</td>
</tr>

<?php
//แสดงเฉพาะใบกำกับภาษีที่ยังไม่ได้ส่ง และมีวันที่ออกใบกำกับตั้งแต่ 2013-03-01 เป็นต้นไป
$qrydata=pg_query("SELECT a.\"taxinvoiceID\", \"debtAmt\",\"taxpointDate\"
FROM thcap_temp_taxinvoice_otherpay a
LEFT JOIN thcap_temp_taxinvoice_details b on a.\"taxinvoiceID\" = b.\"taxinvoiceID\"
LEFT JOIN thcap_temp_otherpay_debt c on a.\"debtID\" = c.\"debtID\"
WHERE c.\"contractID\"='$contractID' AND \"taxpointDate\">='2013-03-01' AND a.\"taxinvoiceID\" NOT IN (SELECT \"detailRef\" FROM vthcap_letter WHERE \"detailRef\" IS NOT NULL)
ORDER BY \"taxpointDate\"");
$num_row = pg_num_rows($qrydata);
$sumAmt=0;
while($res_name2=pg_fetch_array($qrydata)){
	$taxpointDate = $res_name2["taxpointDate"]; //วันที่ใบกำกับภาษี
	$taxinvoiceID = $res_name2["taxinvoiceID"]; //เลขที่ใบกำกับภาษี
	$debtAmt = $res_name2["debtAmt"]; //จำนวนเงิน
    $sumAmt+=$debtAmt;
    $i+=1;
    if($i%2==0){
        echo "<tr bgcolor=\"#EEEED1\" align=center>";
    }else{
        echo "<tr bgcolor=\"#FFFFE0\" align=center>";
    }
	?>
    <td><?php echo "$taxpointDate"; ?></td>
    <td><?php echo "$taxinvoiceID"; ?></td>
    <td align="right"><?php echo number_format($debtAmt,2); ?></td>
</tr>
<?php
} //end while

if($num_row == 0){
    echo "<tr><td colspan=3 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr style=\"font-weight:bold\"><td align=left>พบข้อมูลทั้งหมด $num_row รายการ</td><td align=right>รวม</td><td align=right>".number_format($sumAmt,2)."</td></tr>";
}
?>
</table>