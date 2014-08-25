<?php
require_once("../../config/config.php");

$tab_id = $_GET['tabid']; 
$contractID = $_GET['contractID']; 

if($tab_id==1){ //แสดงหนี้ทั้งหมดที่ถึงกำหนดต้องชำระตามเงื่อนไข
	$qry_fr=pg_query("select \"typePayID\",\"tpDesc\",\"typePayRefValue\",\"debtNet\",\"debtVat\",\"debtAmt\",\"debtDueDate\",\"debtID\" from \"thcap_v_debt_free_to_make_invoice\" where \"contractID\"='$contractID'
	group by \"typePayID\",\"tpDesc\",\"typePayRefValue\",\"debtNet\",\"debtVat\",\"debtAmt\",\"debtDueDate\",\"debtID\" order by \"debtID\",\"typePayRefValue\"");
}else if($tab_id==2){ //แสดงหนี้ทั้งหมดที่ถึงกำหนดหรือยังไม่ถึงกำหนด
	$qry_fr=pg_query("select \"typePayID\",\"tpDesc\",\"typePayRefValue\",\"debtNet\",\"debtVat\",\"debtAmt\",\"debtDueDate\",\"debtID\" from \"thcap_v_debt_free_to_make_invoice_all\" where \"contractID\"='$contractID'
	group by \"typePayID\",\"tpDesc\",\"typePayRefValue\",\"debtNet\",\"debtVat\",\"debtAmt\",\"debtDueDate\",\"debtID\" order by \"debtID\",\"typePayRefValue\"");
}
$nub=pg_num_rows($qry_fr);
echo "
<table width=\"100%\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" align=\"center\" bgcolor=\"#F0F0F0\">
<tr style=\"font-weight:bold;\" valign=\"middle\" bgcolor=\"#79BCFF\" align=\"center\">
	<td>รหัสประเภทค่าใช้จ่าย</td>
	<td>รายละเอียดค่าใช้จ่าย</td>
	<td>ค่าอ้างอิงของค่าใช้จ่าย</td>
	<td>จำนวนเงินไม่รวม VAT</td>
	<td>VAT</td>
	<td>จำนวนเงินรวม VAT</td>
	<td>วันที่ครบกำหนดชำระ</td>
	<td>เลือกรายการ<br>(<a onclick=\"javascript:selectAll('debt');\" style=\"cursor:pointer;\"><u>ทั้งหมด</u></a>)</td>
</tr>";
while($res=pg_fetch_array($qry_fr)){
	//ตรวจสอบข้อมูลก่อนว่ามีรายการใดที่ได้รับการยกเว้นหนี้หรือไม่
	$qrychk=pg_query("select * from thcap_temp_except_debt where \"debtID\"='$res[debtID]' and (\"Approve\"='TRUE' or \"Approve\" is null)");
	$numchk=pg_num_rows($qrychk); //กรณีมีข้อมูลแสดงว่ามีการรออนุมัติยกเว้นหรือถูกยกเว้นหนี้ไปแล้ว
	if($numchk>0){
		$textchk="<font color=red>(รออนุมัติยกเว้นหนี้)</font>";
	}else{
		$textchk="";
	}
	$i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\" align=center>";
	}else{
		echo "<tr class=\"even\" align=center>";
	}
?>
	<td><?php echo $res['typePayID']; ?></td>
	<td><?php echo "$res[tpDesc] $textchk"; ?></td>
	<td><?php echo $res['typePayRefValue']; ?></td>
	<td align="right"><?php echo number_format($res['debtNet'],2); ?></td>
	<td align="right"><?php echo number_format($res['debtVat'],2); ?></td>
	<td align="right"><?php echo number_format($res['debtAmt'],2); ?></td>
	<td><?php echo $res['debtDueDate']; ?></td>
	<td><input type="checkbox" name="debt[]" value="<?php echo $res['debtID']; ?>" <?php if($numchk>0) echo "disabled";?>></td>
</tr>
<?php
} //end while
if($nub == 0){
	echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
}
echo "</table>";


?>