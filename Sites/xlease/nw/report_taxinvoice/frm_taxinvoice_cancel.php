<?php 
session_start(); 	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>thcap รายงานภาษีขาย</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>

<body>
<center>
		<table border="0" cellpadding="1" cellspacing="0" width="100%">
			<tr>
				<td align="left">
					<font color="#6C7B8B"><b>- ใบกำกับภาษีที่ถูกยกเลิก</b></font>
				</td>
				<td align="right">
					<img src="../thcap/thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_excel.php?date=<?php echo $searchPoint; ?>&cancel=t')"><b><u>พิมพ์รายงาน (ยกเลิก)  (Excel)</u></b></a>	
					<img src="image/print.gif" height="20px"><a href="javascript:popU('frm_pdf.php?date=<?php echo $searchPoint; ?>&cancel=t')"><b><u>พิมพ์รายงาน (ยกเลิก) (PDF)</u></b></a>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table frame="box" cellpadding="1" cellspacing="1" width="100%" >
						<tr bgcolor="#BBBBBB">
							<td  align="center">วันที่ออก</td>
							<td  align="center">เลขที่ใบกำกับภาษี</td>
							<td  align="center">เลขที่สัญญา</td>
							<td  align="center">ชื่อผู้กู้หลัก</td>
							<td  align="center">รหัสรายการ</td>
							<td  align="center">รายละเอียดรายการ</td>
							<td  align="center">จำนวนเงิน</td>
							<td  align="center">ภาษีมูลค่าเพิ่ม</td>
							<td  align="center">รวมรับชำระ</td>
						</tr>
						<?php
						$sum1=0;
						$sum2=0;
						$sum3=0;
						if($type=="search")
						{
							$sql2="	SELECT distinct * FROM \"thcap_v_taxinvoice_otherpay_cancel\" 
									where cast(\"taxpointDate\" as varchar) like '$searchPoint%'
									ORDER BY \"taxpointDate\"
									";
							$dbquery2=pg_query($sql2);
							$rowquery2 = pg_num_rows($dbquery2);
							IF($rowquery2){
									$i=0;
									while($rs2=pg_fetch_assoc($dbquery2))
									{
										$i++;
										$sum1=$sum1+$rs2['netAmt'];
										$sum2=$sum2+$rs2['vatAmt'];
										$sum3=$sum3+$rs2['debtAmt'];
										$conidd = $rs2['contractID'];
										
										$taxinvoiceIDpopup = "<a onclick=\"javascript:popU('../thcap/Channel_detail_v.php?receiptID=".$rs2['taxinvoiceID']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor:pointer;\" ><u>".$rs2['taxinvoiceID']."</u></a>";
										
										if($i%2==0){
											echo "<tr bgcolor=#DDDDDD onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\" align=center>";
										}else{
											echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
										}	
											echo "<td>".$rs2['taxpointDate']."</td>";
											echo "<td>".$taxinvoiceIDpopup."</td>";
											echo "<td><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$conidd','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>".$conidd."</u></a></td>";
											echo "<td align=\"left\">".$rs2['cusFullname']."</td>";	
											echo "<td>".$rs2['typePayID']."</td>";
											echo "<td align=\"left\">".$rs2['tpDesc']." ".$rs2['tpFullDesc']." ".$rs2['typePayRefValue']."</td>";
											echo "<td align=\"right\">".number_format($rs2['netAmt'],2)."</td>";
											echo "<td align=\"right\">".number_format($rs2['vatAmt'],2)."</td>";
											echo "<td align=\"right\">".number_format($rs2['debtAmt'],2)."</td>";
											echo "</tr>";
										
										
									}
									echo "<tr bgcolor=\"#BBBBBB\">";
									echo "<td colspan=\"2\"  >รวม $rowquery2 รายการ</td>";
									echo "<td colspan=\"4\" align=\"right\"><b>รวมทั้งสิ้น:  </b></td>";
									echo "<td align=\"right\">".number_format($sum1,2)."</td>";
									echo "<td align=\"right\">".number_format($sum2,2)."</td>";
									echo "<td align=\"right\">".number_format($sum3,2)."</td>";
									echo "<td></td>";
									echo "</tr>";
								
							}else{
								echo "<tr bgcolor=\"#EEEEEE\"><td colspan=\"9\" align=\"center\"><div style=\"padding-top:10px;\"></div><h2>---------- ไม่มีข้อมูล  ----------</h2></td></tr>";
							}
						}	
						?>
					</table>
				<td>
			</tr>
		</table>
</center>
</body>
</html>