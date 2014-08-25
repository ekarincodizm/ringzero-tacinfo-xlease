<?php include("../../config/config.php");
$select_Search=pg_escape_string($_GET["date1"]);
$select_bid=pg_escape_string($_GET["bankint"]);
$datefrom=pg_escape_string($_GET["datefrom"]);
$dateto=pg_escape_string($_GET["dateto"]);
$datepicker=pg_escape_string($_GET["datepicker"]);
$month=pg_escape_string($_GET["month"]);
$year=pg_escape_string($_GET["year"]);
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" >
	<tr>
	<td align="right">
		<img src="images/print.gif" height="20px"> <a href="javascript:popU('frm_pdf.php?datel=<?php echo $select_Search;?>&bankint=<?php echo $select_bid;?>&datefrom=<?php echo $datefrom;?>&dateto=<?php echo $dateto;?>&datepicker=<?php echo $datepicker;?>&month=<?php echo $month;?>&year=<?php echo $year;?>')"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
	</td>
</tr>
</table>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">

<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
   <td>วันที่รายการมีผล</td>
    <td>รายละเอียดการทำรายการ</td>
	<td>เลขที่เช็ค</td>
	<td>จำนวนเงินที่หักบัญชี</td>
    <td>จำนวนเงินเข้าบัญชี</td>
    <td>ยอดคงเหลือ</td>
    <td>หมายเลข</td>
	<td>สาขาที่ให้บริการ</td>
	<td>วันที่สร้างรายการ</td>
	<td>รหัสเงินโอน</td>
	<td>เช็คที่นำเข้าครั้งนั้น</td>
</tr>
<?php	
			$n=0;			
			if($select_bid!="")
			{
				$n++;
				$sumnub++;
				if($select_Search=='1'){  //ตามวันที่ 
					$query=pg_query("select \"sbr_receivedate\", \"sbr_details\", \"sbr_chqno\", \"sbr_amtwithdraw\", \"sbr_amtdeposit\", \"sbr_amtoutstanding\", \"sbr_counterservice\", \"sbr_bankbranch\", \"revtranferID\",
									date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and sbr_receivedate='$datepicker' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ");
				}
				else if($select_Search=='2'){ //ตามเดือน
					$query=pg_query("select \"sbr_receivedate\", \"sbr_details\", \"sbr_chqno\", \"sbr_amtwithdraw\", \"sbr_amtdeposit\", \"sbr_amtoutstanding\", \"sbr_counterservice\", \"sbr_bankbranch\", \"revtranferID\",
									date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and EXTRACT(MONTH FROM \"sbr_receivedate\") = '$month' AND EXTRACT(YEAR FROM \"sbr_receivedate\") = '$year' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ");
				}
				else if($select_Search=='3'){ //ตามช่วง
					$query=pg_query("select \"sbr_receivedate\", \"sbr_details\", \"sbr_chqno\", \"sbr_amtwithdraw\", \"sbr_amtdeposit\", \"sbr_amtoutstanding\", \"sbr_counterservice\", \"sbr_bankbranch\", \"revtranferID\",
									date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and sbr_receivedate between '$datefrom' and '$dateto' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ");
				}
				$n=0;
				while($resvc=pg_fetch_array($query))
				{
					$n++;
					$sbr_receivedate = $resvc['sbr_receivedate']; //วันที่รายการมีผล
					$sbr_details = $resvc['sbr_details']; //รายละเอียดการทำรายการ
					$sbr_chqno = $resvc['sbr_chqno']; //เลขที่เช็ค
					$sbr_amtwithdraw = $resvc['sbr_amtwithdraw'];//จำนวนเงินที่หักบัญชี
					$sbr_amtdeposit = $resvc['sbr_amtdeposit']; //จำนวนเงินเข้าบัญชี
					$sbr_amtoutstanding = $resvc['sbr_amtoutstanding']; //ยอดคงเหลือ
					$sbr_counterservice = $resvc['sbr_counterservice']; //หมายเลข
					$sbr_bankbranch=$resvc['sbr_bankbranch']; //สาขาที่ให้บริการ
					$datecreate=$resvc['datecreate']; //วันที่สร้างรายการ
					$revTranID=$resvc['revtranferID']; //รหัสเงินโอน
					$sumwith+=$sbr_amtwithdraw;
					$sumdep+=$sbr_amtdeposit;
					
					// หารหัสเช็ค chqkeeperID
					$qry_revChqID = pg_query("select \"revChqID\",\"chqKeeperID\" from finance.thcap_receive_transfer where \"revTranID\" = '$revTranID' ");
					$revChqID = pg_fetch_result($qry_revChqID,0);
					$chqKeeperID = pg_fetch_result($qry_revChqID,1);
	
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
					}else{
						echo "<tr class=\"even\" align=\"center\">";
					}
				?> 
				<td><?php echo $sbr_receivedate; ?></td>
				<td align="left"><?php echo $sbr_details; ?></td>
				<td><?php echo $sbr_chqno; ?></td>
				<td align="right"><?php echo number_format($sbr_amtwithdraw,2); ?></td>
				<td align="right"><?php echo number_format($sbr_amtdeposit,2); ?></td>
				<td><?php echo number_format($sbr_amtoutstanding,2); ?></td>
				<td><?php echo $sbr_counterservice; ?></td>
				<td><?php echo $sbr_bankbranch; ?></td>
				<td><?php echo $datecreate; ?></td>
				<td><?php echo "<a onclick=\"javascript:popU('../thcap/frm_transpay_detail.php?revTranID=$revTranID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$revTranID</u></a>"; ?></td>
				
				<td><?php if($revChqID!="" and $chqKeeperID!="") { echo "<a onclick=\"javascript:popU('../thcap/Channel_detail_chq_list.php?revChqID=$revChqID&chqKeeperID=$chqKeeperID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=680')\" style=\"cursor:pointer;\" ><img src=\"images/detail.gif\"/></a>"; }?></td>
				</tr>
				<?php
				}
			}
			if(($n==0)){
				echo "<tr><td colspan=11 height=50 align=center><b>--ไม่มีข้อมูล--</b></td></tr>";
			}else{
				echo "<tr style=\"font-weight:bold\" bgcolor=\"#FFCCCC\" align=\"right\">
						<td colspan=3>รวม</td>
						<td>".number_format($sumwith,2)."</td>
						<td>".number_format($sumdep,2)."</td>
						<td colspan=6></td>
						</tr>";
			}
			?>
</table>



