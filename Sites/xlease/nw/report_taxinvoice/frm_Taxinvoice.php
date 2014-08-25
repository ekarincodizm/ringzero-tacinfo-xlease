<?php
	include("../../config/config.php");

	$chk_year=date("Y");
	$selectMonth=$_POST['slbxSelectMonth'];
	$selectYear=$_POST['slbxSelectYear'];
	$searchPoint=$selectYear."-".$selectMonth;
	$type=$_GET['type'];
	if($selectMonth=="")
	{
		$selectMonth = date("m");
	}
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>thcap รายงานภาษีขาย</title>
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
	<b><h1>(THCAP)รายงานภาษีขาย</h1></b>
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
		<table border="0" cellpadding="1" cellspacing="0" width="95%">
			<tr>
				<td align="left">
					<font color="#6C7B8B"><b>- ใบกำกับภาษี</b></font>
				</td>
				<td align="right">
					<img src="../thcap/thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_excel.php?date=<?php echo $searchPoint; ?>')"><b><u>พิมพ์รายงาน (Excel)</u></b></a>	
					<img src="image/print.gif" height="20px"><a href="javascript:popU('frm_pdf.php?date=<?php echo $searchPoint; ?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table frame="box" cellpadding="1" cellspacing="1" width="100%" >
						<tr bgcolor="#9FB6CD">
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
							$sql2="	SELECT distinct * FROM \"thcap_v_taxinvoice_otherpay\" 
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
											echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" align=center>";
										}else{
											echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" align=center>";
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
									echo "<tr bgcolor=\"#9FB6CD\">";
									echo "<td colspan=\"2\"  >รวม $rowquery2 รายการ</td>";
									echo "<td colspan=\"4\" align=\"right\"><b>รวมทั้งสิ้น:  </b></td>";
									echo "<td align=\"right\">".number_format($sum1,2)."</td>";
									echo "<td align=\"right\">".number_format($sum2,2)."</td>";
									echo "<td align=\"right\">".number_format($sum3,2)."</td>";
									echo "<td></td>";
									echo "</tr>";
								
							}else{
								echo "<tr bgcolor=\"#C6E2FF\"><td colspan=\"9\" align=\"center\"><div style=\"padding-top:10px;\"></div><h2>---------- ไม่มีข้อมูล  ----------</h2></td></tr>";
							}
						}	
						?>
					</table>
				<td>
			</tr>
			<tr>
				<td colspan="2">
					<div style="padding-top:25px;"></div>
					<?php include("frm_taxinvoice_cancel.php"); ?>
				</td>
			</tr>
			
		</table>
		
</center>
</body>
</html>