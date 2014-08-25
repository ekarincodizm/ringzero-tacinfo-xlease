<?php
echo "<div style=\"padding-top:50px\"></div>";
?>
<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="12" align="left" style="font-weight:bold;">
				ประวัติการยกเลิกใบเสร็จที่เกี่ยวข้องโดยอัตโนมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_autocancel_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>)
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>เลขที่สัญญา</td>
				<td>เลขที่ใบเสร็จ</td>
				<td>วันที่จ่าย</td>
				<td>จำนวนเงินที่จ่าย</td>
				<td>ผู้ขอยกเลิก</td>
				<td>วันเวลาที่ทำการขอยกเลิก</td>
				<td>ผู้อนุมัติ</td>
				<td>วันที่อนุมัติ</td>
				<td>ใบเสร็จหลักที่ขอยกเลิก</td>	
			</tr>
			<?php			
			$qry_app=pg_query("	
								select distinct a.\"receiptID\",
									(select c.\"contractID\" from thcap_v_receipt_otherpay_cancel c where a.\"receiptID\" = c.\"receiptID\" limit 1) as \"contractID\",
									(select d.\"receiveDate\" from thcap_v_receipt_otherpay_cancel d where a.\"receiptID\" = d.\"receiptID\" limit 1) as \"receiveDate\",
									(select sum(e.\"debtAmt\") from thcap_v_receipt_otherpay_cancel e where a.\"receiptID\" = e.\"receiptID\") as \"sumDebtAmt\",
									(select f.\"cancelID\" from thcap_temp_receipt_otherpay_cancel f where a.\"receiptID\" = f.\"receiptID\" limit 1) as \"cancelID\",
									(select g.\"requestUser\" from thcap_temp_receipt_cancel g
										where g.\"cancelID\" = (select h.\"cancelID\" from thcap_temp_receipt_otherpay_cancel h where a.\"receiptID\" = h.\"receiptID\" and h.\"cancelID\" is not null limit 1)) as \"requestUser\",
									(select i.\"requestDate\" from thcap_temp_receipt_cancel i
										where i.\"cancelID\" = (select j.\"cancelID\" from thcap_temp_receipt_otherpay_cancel j where a.\"receiptID\" = j.\"receiptID\" and j.\"cancelID\" is not null limit 1)) as \"requestDate\",
									(select k.\"approveUser\" from thcap_temp_receipt_cancel k
										where k.\"cancelID\" = (select l.\"cancelID\" from thcap_temp_receipt_otherpay_cancel l where a.\"receiptID\" = l.\"receiptID\" and l.\"cancelID\" is not null limit 1)) as \"approveUser\",
									(select m.\"approveDate\" from thcap_temp_receipt_cancel m
										where m.\"cancelID\" = (select n.\"cancelID\" from thcap_temp_receipt_otherpay_cancel n where a.\"receiptID\" = n.\"receiptID\" and n.\"cancelID\" is not null limit 1)) as \"approveDate\",
									(select o.\"receiptID\" from thcap_temp_receipt_cancel o
										where o.\"cancelID\" = (select p.\"cancelID\" from thcap_temp_receipt_otherpay_cancel p where a.\"receiptID\" = p.\"receiptID\" and p.\"cancelID\" is not null limit 1)) as \"MainReceiptID\"
								from thcap_temp_receipt_otherpay_cancel a
								where a.\"receiptID\" not in(select b.\"receiptID\" from thcap_temp_receipt_cancel b where b.\"approveStatus\" = '1') and a.\"cancelID\" is not null
								order by \"approveDate\" DESC limit 30
							");
			$nub=pg_num_rows($qry_app);
			$i = 0;
			while($res_app=pg_fetch_array($qry_app))
			{
				$contractID = $res_app["contractID"];
				$receiptID = $res_app["receiptID"];
				$receiveDate = $res_app["receiveDate"];
				$receiveAmount = $res_app["sumDebtAmt"];
				$requestUser = $res_app["requestUser"];
				$requestDate = $res_app["requestDate"];
				$approveUser = $res_app["approveUser"];
				$approveDate = $res_app["approveDate"];
				$MainReceiptID = $res_app["MainReceiptID"]; // ใบเสร็จหลักที่ขอยกเลิก
				
				$qry_requestName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$requestUser' ");
				$requestName = pg_fetch_result($qry_requestName,0);
				
				$qry_approveName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$approveUser' ");
				$approveName = pg_fetch_result($qry_approveName,0);
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
				}else{
					echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
				}
			?>
				<td align="center"><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('Channel_detail.php?receiptID=<?php echo $receiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $receiptID; ?></u></span></td>
				<td align="center"><?php echo $receiveDate; ?></td>
				<td align="right"><?php echo number_format($receiveAmount,2); ?></td>
				<td align="left"><?php echo $requestName; ?></td>
				<td align="center"><?php echo $requestDate; ?></td>
				<td align="left"><?php echo $approveName; ?></td>
				<td align="center"><?php echo substr($approveDate,0,19); ?></td>
				<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('Channel_detail.php?receiptID=<?php echo $MainReceiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $MainReceiptID; ?></u></span></td>
			</tr>
			<?php
			}
			?>
			<tr bgcolor="#D6D6D6">
				<td colspan="9" align="right" >จำนวนแสดง : <?php echo $aprows = pg_num_rows($qry_app); ?>  รายการ</td>
			</tr>
			</table><br>
		</div>
	</td>
</tr>	
</table>