<?php
	include("../../config/config.php");

	$chk_year=date("Y");
	$selectMonth = pg_escape_string($_POST['slbxSelectMonth']);
	$selectYear = pg_escape_string($_POST['slbxSelectYear']);
	$searchPoint=$selectYear."-".$selectMonth;
	$type = pg_escape_string($_GET['type']);
	if($selectMonth=="")
	{
		$selectMonth = date("m");
	}
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) รายงานภาษีซื้อ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
 <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function loadpage(){
	alert(55);
	$("#space1").load("frm_taxinvoice_cancel.php");
}
</script>
</head>

<body >
<center>
	<b><h1>(THCAP) รายงานภาษีซื้อ</h1></b>
	<hr width="60%">
			<form action="frm_Taxinvoice.php?type=search" name="frm_select_month" id="frm_select_month" method="post">
				<span class="span_label">เลือกเดือน: </span>
				<span>
					<select id="slbxSelectMonth" name="slbxSelectMonth">
						<option value="01"<?php if($selectMonth=='01'){echo "selected";} ?>>มกราคม</option>
						<option value="02"<?php if($selectMonth=='02'){echo "selected";} ?>>กุมภาพันธ์</option>
						<option value="03"<?php if($selectMonth=='03'){echo "selected";} ?>>มีนาคม</option>
						<option value="04"<?php if($selectMonth=='04'){echo "selected";} ?>>เมษายน</option>
						<option value="05"<?php if($selectMonth=='05'){echo "selected";} ?>>พฤษภาคม</option>
						<option value="06"<?php if($selectMonth=='06'){echo "selected";} ?>>มิถุนายน</option>
						<option value="07"<?php if($selectMonth=='07'){echo "selected";} ?>>กรกฎาคม</option>
						<option value="08"<?php if($selectMonth=='08'){echo "selected";} ?>>สิงหาคม</option>
						<option value="09"<?php if($selectMonth=='09'){echo "selected";} ?>>กันยายน</option>
						<option value="10"<?php if($selectMonth=='10'){echo "selected";} ?>>ตุลาคม</option>
						<option value="11"<?php if($selectMonth=='11'){echo "selected";} ?>>พฤศจิกายน</option>
						<option value="12"<?php if($selectMonth=='12'){echo "selected";} ?>>ธันวาคม</option>
					</select>
				</span>
				<span class="span_label">ปี: </span>
				<span>
					<select id="slbxSelectYear" name="slbxSelectYear">
					<?php
						
						$sql1="select min(\"taxpointDate\") as \"taxpointDate\" from \"thcap_v_taxinvoice_details\"";
						$dbquery1=pg_query($sql1);
						$rs1=pg_fetch_assoc($dbquery1);
						$minYear=mb_substr($rs1['taxpointDate'],0,4);
						while($chk_year>=$minYear)
						{
							IF($selectYear == ""){ ?>
								<option value="<?php echo $minYear ?>" <?php if($minYear==$chk_year){ echo "selected"; } ?> ><?php echo $minYear ?></option>
					<?php	}else{ ?>
								<option value="<?php echo $minYear ?>" <?php if($minYear==$selectYear){ echo "selected"; } ?> ><?php echo $minYear ?></option>
					<?php	}
							$minYear++;
						}
					?>
					</select>
				</span>
				<span><input type="submit" value="เรียกดู" ></span>
			</form>
			
		<?php
		// ตัวแปรสำหรับรวมทุกกลุ่ม
		$sumAll1 = 0; // รวม จำนวนเงิน จากทุกกลุ่ม
		$sumAll2 = 0; // รวม ภาษีมูลค่าเพิ่ม จากทุกกลุ่ม
		$sumAll3 = 0; // รวม จำนวนเงินรวม จากทุกกลุ่ม
		?>
		
		<table border="0" cellpadding="1" cellspacing="0" width="95%">
			<tr>
				<td align="left">
					<font color="#6C7B8B"><b>- ภาษีซื้อที่เกิดจากการคืนเงิน</b></font>
				</td>
				<td align="right">
					<!--<img src="../thcap/thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_excel.php?date=<?php echo $searchPoint; ?>')"><b><u>พิมพ์รายงาน (Excel)</u></b></a>	
					<img src="image/print.gif" height="20px"><a href="javascript:popU('frm_pdf.php?date=<?php echo $searchPoint; ?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>-->
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table frame="box" cellpadding="1" cellspacing="1" width="100%" >
						<tr bgcolor="#9FB6CD">
							<td  align="center">วันที่ภาษี</td>
							<td  align="center">เลขที่ใบกำกับภาษี (หรือใบสำคัญ)</td>
							<td  align="center">เลขที่สัญญา (ถ้ามี)</td>
							<td  align="center">ชื่อผู้ออกใบกำกับภาษี</td>
							<td  align="center">รายละเอียดรายการ</td>
							<td  align="center">จำนวนเงิน</td>
							<td  align="center">ภาษีมูลค่าเพิ่ม</td>
							<td  align="center">จำนวนเงินรวม</td>
						</tr>
						<?php
						$sum1=0;
						$sum2=0;
						$sum3=0;
						if($type=="search")
						{
							$sql2="	SELECT \"dcNoteDate\", \"dcNoteID\", \"contractID\", \"dcNoteDescription\", \"dcNoteAmtNET\", \"dcNoteAmtVAT\", \"dcNoteAmtALL\"
									FROM account.\"v_thcap_dncn_active\"
									WHERE cast(\"dcNoteDate\" as varchar) like '$searchPoint%' AND \"dcNoteAmtVAT\" > '0.00' AND \"subjectStatus\" = '3'
									ORDER BY \"dcNoteDate\"
									";
							$dbquery2=pg_query($sql2);
							$rowquery2 = pg_num_rows($dbquery2);
							IF($rowquery2){
									$i=0;
									while($rs2=pg_fetch_assoc($dbquery2))
									{
										$i++;
										$sum1=$sum1+$rs2['dcNoteAmtNET'];
										$sum2=$sum2+$rs2['dcNoteAmtVAT'];
										$sum3=$sum3+$rs2['dcNoteAmtALL'];
										$conidd = $rs2['contractID'];
										
										// รวมทุกกลุ่ม
										$sumAll1 += $rs2['dcNoteAmtNET'];
										$sumAll2 += $rs2['dcNoteAmtVAT'];
										$sumAll3 += $rs2['dcNoteAmtALL'];
										
										$dcNoteIDpopup = "<a onclick=\"javascript:popU('../thcap_dncn/popup_dncn.php?idapp=".$rs2['dcNoteID']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=700')\" style=\"cursor:pointer;\" ><u>".$rs2['dcNoteID']."</u></a>";
										
										if($i%2==0){
											echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" align=center>";
										}else{
											echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" align=center>";
										}	
											echo "<td>".$rs2['dcNoteDate']."</td>";
											echo "<td>".$dcNoteIDpopup."</td>";
											echo "<td><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$conidd','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>".$conidd."</u></a></td>";
											echo "<td align=\"center\">บริษัท ไทยเอซ แคปปิตอล จำกัด</td>";	
											echo "<td align=\"left\">".$rs2['dcNoteDescription']."</td>";
											echo "<td align=\"right\">".number_format($rs2['dcNoteAmtNET'],2)."</td>";
											echo "<td align=\"right\">".number_format($rs2['dcNoteAmtVAT'],2)."</td>";
											echo "<td align=\"right\">".number_format($rs2['dcNoteAmtALL'],2)."</td>";
											echo "</tr>";
										
										
									}
									echo "<tr bgcolor=\"#9FB6CD\">";
									echo "<td>รวม $rowquery2 รายการ</td>";
									echo "<td colspan=\"4\" align=\"right\"><b>รวมทั้งสิ้น:  </b></td>";
									echo "<td align=\"right\">".number_format($sum1,2)."</td>";
									echo "<td align=\"right\">".number_format($sum2,2)."</td>";
									echo "<td align=\"right\">".number_format($sum3,2)."</td>";
									echo "</tr>";
								
							}else{
								echo "<tr bgcolor=\"#C6E2FF\"><td colspan=\"8\" align=\"center\"><div style=\"padding-top:10px;\"></div><h2>---------- ไม่มีข้อมูล  ----------</h2></td></tr>";
							}
						}	
						?>
					</table>
				<td>
			</tr>
		</table>
		
		<br/>
		
		<table border="0" cellpadding="1" cellspacing="0" width="95%">
			<tr>
				<td align="left">
					<font color="#FF0000"><b>- รวมทุกกลุ่ม</b></font>
				</td>
			</tr>
			<tr>
				<td>
					<table frame="box" cellpadding="1" cellspacing="1" width="100%" >
						<tr bgcolor="#FFAAAA">
							<td align="center"><b>รวม</b></td>
							<td align="center"><b>จำนวนเงิน</b></td>
							<td align="center"><b>ภาษีมูลค่าเพิ่ม</b></td>
							<td align="center"><b>จำนวนเงินรวม</b></td>
						</tr>
						<?php
						if($type=="search")
						{
							echo "<tr bgcolor=\"#FFCCCC\">";
							echo "<td align=\"right\"><b>รวมทั้งสิ้น:  </b></td>";
							echo "<td align=\"right\">".number_format($sumAll1,2)."</td>";
							echo "<td align=\"right\">".number_format($sumAll2,2)."</td>";
							echo "<td align=\"right\">".number_format($sumAll3,2)."</td>";
							echo "</tr>";
						}	
						?>
					</table>
				<td>
			</tr>
		</table>
</center>
</body>
</html>