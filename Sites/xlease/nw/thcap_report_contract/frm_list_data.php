<?php
include("../../config/config.php");
$year=pg_escape_string($_GET["year"]);
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" >	
	<tr>
	<td align="left">
		<div><font color="red"> <span style="background-color:#CCCCCC;">&nbsp;&nbsp;&nbsp;</span> รายการสีเทา คือ สัญญาถูกปิดแล้ว</font></div>
	</td>
	<td align="right">
		<img src="images/print.gif" height="20px"> <a href="javascript:popU('frm_pdf.php?year=<?php echo $year;?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
	</td>
</tr>
</table>
<fieldset style="width:500" align="center"><legend><B>(THCAP) แสดงรายละเอียดข้อมูลสัญญา</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">

<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
	<td>เลขที่สัญญา</td>
    <td>ผู้กู้หลัก</td>
	<td>ประเภทสัญญา</td>
	<td>วันที่ทำสัญญา</td>
    <td>วันที่เริ่มกู้</td>
    <td>วงเงิน</td>
    <td>ยอดกู้</td>
	<td>ยอดเงินลงทุน/ยอดจัด<br>(ก่อนภาษีมูลค่าเพิ่ม)</td>
	<td>อัตราดอกเบี้ย</td>
	<td>จำนวนเดือน</td>
	<td>ยอดชำระขั้นต่ำ</td>
	<td>วันที่เริ่มจ่าย</td>
	<td>วันที่สิ้นสุดสัญญา</td>
	<td>% ค่าเสียหายปิดก่อนกำหนด</td>
</tr>
<?php	
	$qry_type=pg_query(" select \"conType\" from \"thcap_contract_type\" order by \"conType\" asc");
	while($res_type=pg_fetch_array($qry_type))
	{
		list($type)=$res_type;		
		
		echo "<tr><td align=\"left\" colspan=\"16\" bgcolor=\"#CDB79E\"><b>ประเภทสัญญา &nbsp$type</b></td></tr>";
		$qry_fr=pg_query("select * from \"thcap_contract\" where EXTRACT(YEAR FROM \"conDate\")='$year'  and \"conType\"='$type' ORDER BY \"contractID\"  ASC");
		$rows_con = pg_num_rows($qry_fr);
		if($rows_con > 0){ //หากประเภทนี้มีข้อมูล
			while($re_connew = pg_fetch_array($qry_fr)){
										
				//หาชื่อผู้กู้หลัก
				$contractID = $re_connew["contractID"];
				$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0'");
				list($thcap_fullname) = pg_fetch_array($qry_cusname);
										
				//หาวันที่ปิดบัญชี
				$dateclosesql = pg_query("SELECT thcap_checkcontractcloseddate('$contractID')");
				$dateclosere = pg_fetch_array($dateclosesql);
				$dateclose = $dateclosere['thcap_checkcontractcloseddate'];
										
				IF($dateclose != ""){ //หากสัญญาถูกปิดแล้วจะเป็นสีเทา
					echo "<tr bgcolor=#CCCCCC onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#CCCCCC';\" align=center>";	
					$bgcolortd = '#CCCCCC';
									
				}else{
									
					if($numrows%2==0){
						echo "<tr bgcolor=#FFFFFF onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#FFFFFF';\" align=center>";
					}else{
						echo "<tr bgcolor=#D5EFFD onmouseover=\"javascript:this.bgColor = '#87CEEB';\" onmouseout=\"javascript:this.bgColor = '#D5EFFD';\" align=center>";
					}
					$bgcolortd = '#BCD2EE';
					$numrows++;
				}	
				?>				
				<td align="left"><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
				<font color="red"><u><?php echo $re_connew["contractID"]; ?></u></font></span>
				</td>
				<td align="left"><?php echo $thcap_fullname; ?></td>
				<td><?php echo $re_connew["conType"]; ?></td>
				<td><?php echo $re_connew["conDate"]; ?></td>
				<td><?php echo $re_connew["conStartDate"]; ?></td>
				<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo number_format($re_connew["conCredit"],2); ?></td>
				<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo number_format($re_connew["conLoanAmt"],2); ?></td>
				<td align="right" bgcolor="<?php echo $bgcolortd; ?>"><?php echo number_format($re_connew["conFinAmtExtVat"],2); ?></td>				
				
				<td><?php echo $re_connew["conLoanIniRate"]; ?></td>
				<td><?php echo $re_connew["conTerm"]; ?></td>
				<td align="right"><?php echo number_format($re_connew["conMinPay"],2); ?></td>
				<td><?php echo $re_connew["conFirstDue"]; ?></td>
				<td><?php echo $re_connew["conEndDate"]; ?></td>
				<td align="right"><?php echo number_format($re_connew["conClosedFee"],2); ?></td>
			</tr>
			<?php	
				//รวมของแต่ละประเภทสัญญา
				unset($thcap_fullname); //ทำลายตัวแปรเก็บชื่อผู้กู้หลัก  เพื่อป้องกันการแสดงซ้ำซ้อนของข้อมูล
			}
			?>		 
			<tr bgcolor="#EECFA1">
				<td>ประเภท  <?php echo $type; ?>: <?php echo $rows_con; ?> สัญญา</td>
				<td colspan="14"></td>
			</tr> 
			<?php		 
			}else{
				echo "<tr><td align=\"center\" colspan=\"15\">-- ไม่มีข้อมูล --</td></tr>";
		}	
	}//end while type
	
?>
</table>
</fieldset>



