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
				ประวัติการอนุมัติยกเลิกใบเสร็จ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_hisroty_appdetail.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) 
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>เลขที่สัญญา</td>
				<td>เลขที่ใบเสร็จ</td>
				<td>วันที่จ่าย</td>
				<td>จำนวนเงินที่จ่าย</td>
				<td>ผู้ขอยกเลิก</td>
				<td>วันเวลาที่ทำการขอยกเลิก</td>
				<td>ช่องทางการจ่าย</td>
				<td>Ref ช่องทางการจ่าย</td>
				<td>ผู้อนุมัติ</td>
				<td>วันที่อนุมัติ</td>	
				<td>ผลการอนุมัติ</td>
                <td>เหตุผล</td>			
			</tr>
			<?php			
			$qry_app=pg_query("	
								SELECT a.\"cancelID\",a.\"contractID\", a.\"receiptID\",b.\"fullname\", a.\"approveStatus\",a.\"requestDate\", a.\"approveDate\"
								FROM thcap_temp_receipt_cancel a 
								LEFT JOIN \"Vfuser\" b on a.\"approveUser\" = b.\"id_user\" 
								WHERE a.\"approveStatus\"<>'2'
								ORDER BY a.\"approveDate\" DESC limit 30
							");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$cancelID=$res_app["cancelID"];
				$contract=$res_app["contractID"];
				$receiptID1=$res_app["receiptID"];
				$approveStatus=$res_app["approveStatus"];
				$requestDate=$res_app["requestDate"];
				$approveDate=$res_app["approveDate"];
				$approveUser=$res_app["fullname"];
				
				//หาว่าใบเสร็จนี้ถูกลบหรือยังจากตาราง  thcap_v_receipt_otherpay
				$qrychk=pg_query("SELECT * FROM thcap_v_receipt_otherpay WHERE \"receiptID\"='$receiptID1'");
				$nubchk=pg_num_rows($qrychk);
				//หาข้อมูลที่เหลือออกมาแสดง 
				if($nubchk>0){ //แสดงว่ายังไม่ถูกลบ
					$qry_receipt2=pg_query("	SELECT a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",f.\"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",e.\"byChannelRef\",e.\"byChannel\"
												FROM \"thcap_temp_receipt_cancel\" a
												LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
												LEFT JOIN \"thcap_v_receipt_otherpay\" e on a.\"receiptID\"=e.\"receiptID\"
												LEFT JOIN (		select aa1.\"receiptID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
																from \"thcap_v_receipt_otherpay\" aa1
																left join \"thcap_temp_receipt_cancel\" bb1 on aa1.\"receiptID\" = bb1.\"receiptID\"
																where bb1.\"cancelID\" = '$cancelID'
																group by aa1.\"receiptID\"
														  ) f on f.\"receiptID\" = a.\"receiptID\"
												WHERE a.\"cancelID\"='$cancelID'
												ORDER BY \"approveDate\" DESC
											");				
				}else{ //กรณีถูกลบ
					$qry_receipt2=pg_query("	SELECT a.\"cancelID\",a.\"contractID\",a.\"receiptID\",\"fullname\",\"requestDate\",e.\"receiveDate\",f.\"receiveAmount\",e.\"nameChannel\",e.\"debtID\",e.\"typePayID\",e.\"byChannelRef\",e.\"byChannel\"
												FROM \"thcap_temp_receipt_cancel\" a
												LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
												LEFT JOIN \"thcap_v_receipt_otherpay_cancel\" e on a.\"receiptID\"=e.\"receiptID\"
												LEFT JOIN (		select aa1.\"receiptID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
																from \"thcap_v_receipt_otherpay_cancel\" aa1
																left join \"thcap_temp_receipt_cancel\" bb1 on aa1.\"receiptID\" = bb1.\"receiptID\"
																where bb1.\"cancelID\" = '$cancelID'
																group by aa1.\"receiptID\"
														  ) f on f.\"receiptID\" = a.\"receiptID\"
												WHERE a.\"cancelID\"='$cancelID'
												ORDER BY \"approveDate\" DESC
					
										   ");
				}
				$res_receipt2=pg_fetch_array($qry_receipt2);
				$cancelID=$res_receipt2["cancelID"];
				$contractID=$res_receipt2["contractID"];
				$receiptID=$res_receipt2["receiptID"];
				$receiveDate=$res_receipt2["receiveDate"];
				$receiveAmount=$res_receipt2["receiveAmount"];
				$fullname=$res_receipt2["fullname"];
				$byChannel=$res_receipt2["nameChannel"];
				$debtID=$res_receipt2["debtID"];
				$typePayID=$res_receipt2["typePayID"];
				$byChannelRef=$res_receipt2["byChannelRef"];
				$byChannelsend=$res_receipt2["byChannel"];
				$byChannelshow = "<a onclick=\"javascript:popU('frm_byway_transpay_detail.php?receiptID=$receiptID&bychannel=$byChannelsend','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$byChannelRef</u></a>";
			
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
				}else{
					echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
				}
				
				//หา typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
				
				//นำ receiptID ที่ได้ ไปค้นหาในตารางผ่อนชำระค่าอื่นๆว่ามีหรือไม่ ถ้ามีแสดงว่าใบเสร็จนั้นเป็นของค่าใช้จ่ายอื่นๆ
				// $qrychkrec=pg_query("select \"receiptID\" from thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' and \"debtID\" is not null group by \"receiptID\"");
				// $numchkrec=pg_num_rows($qrychkrec); //if > 0 แสดงว่าเป็นค่าใช้จ่ายอื่นๆ
				
				if($approveStatus == '1'){
					$stautsapp = 'อนุมัติ';
				}else if($approveStatus == '0'){
					$stautsapp = 'ไม่อนุมัติ';
				}else if($approveStatus == '3'){
					$stautsapp = 'ยกเลิกการทำรายการ';
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<?php
				/*if($typePayID==$typeID){
					//ตรวจสอบอีกครั้งว่าเป็นค่างวดที่อยู่ในตาราง 201201 หรือไม่ 
					$qrychkreceiptID=pg_query("select * from thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
					$numchkreceiptID=pg_num_rows($qrychkreceiptID);
					if($numchkreceiptID==0){ //แสดงว่าเป็นค่างวดที่แสดงใบเสร็จแบบค่าอื่นๆ
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ
					}else{
						echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&contractID=$contractID&typepdf=1\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=1 หมายถึงค่างวด
					}
				}else{
					echo "<td><a href=\"../Payments_Other/print_receipt_pdf.php?receiptID=$receiptID&typepdf=2&contractID=$contractID\" target=\"_blank\"><u>$receiptID</u></a></td>"; // typepdf=2 หมายถึงค่าอื่นๆ
				}	*/
				?>
				<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('Channel_detail.php?receiptID=<?php echo $receiptID; ?>&debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $receiptID; ?></u></span></td>
				<td align="center"><?php echo $receiveDate; ?></td>
				<td><?php echo number_format($receiveAmount,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $requestDate; ?></td>
				<td><?php echo $byChannel; ?></td>
				<td><?php echo $byChannelshow; ?></td>
				<td><?php echo $approveUser; ?></td>
				<td><?php echo substr($res_app["approveDate"],0,19); ?></td>
				<td><?php echo $stautsapp; ?></td>
                <td>
                	<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('result_cancelreceipt.php?cancelID=<?php echo $cancelID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer;" />
                </td>
			</tr>
			<?php
			}
			?>
			<tr bgcolor="#D6D6D6">
				<td colspan="12" align="right" >จำนวนแสดง : <?php echo $aprows = pg_num_rows($qry_app); ?>  รายการ</td>
			</tr>
			</table><br>
		</div>
	</td>
</tr>	
</table>