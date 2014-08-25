<?php 
if($contractID != ""){ // ถ้ามีการส่งค่ามา  // header
?>
<fieldset>	
	<legend><B>ตารางการรับรู้รายได้เช่าซื้อ - เช่าทางการเงิน</B></legend>
		<div style="float:right;">
			<?php
			if($contractID != "")
			{
			?>
				<div align="left">
				<button id="btnprint" onclick="javascript:popU('../thcap/pdf_realize.php?contractID=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
				พิมพ์&nbsp;<img src="../thcap/images/icon_pdf.gif" width="16" height="16" align="absmiddle" /></button></div>
			<?php
			}
			?>
			</div>
		<div style="clear:both;"></div>
	<div align="center">
		<div id="panel4" align="left" >
			<table  width="100%" cellspacing="1" cellpadding="1" bgcolor="#E0EEEE">
				<tr bgcolor="#C1CDCD" align="center" height="25">
					<th>งวดที่</th>
					<th>วันที่คิด<br>ยอดบัญชี</th>
					<th>วันที่ครบ<br>กำหนดชำระ</th>
					<th>วันที่รับชำระ</th>
					<th>เลขที่ใบเสร็จ</th>
					<th>ค่าเช่า</th>
					<th>ภาษี<br>มูลค่าเพิ่ม</th>
					<th bgcolor="#FFCCCC">เงินต้น<br>คงเหลือ<br>ก่อนชำระ</th>
					<th bgcolor="#FFCCCC">ดอกเบี้ย<br>คงเหลือ<br>ก่อนชำระ</th>
					<th bgcolor="#FFCCCC">ดอกเบี้ย<br>คงเหลือ<br>ทางบัญชี<br>ก่อนรายการ</th>
					<th bgcolor="#C1FFC1">เงินต้น<br>ตัดจ่าย</th>
					<th bgcolor="#C1FFC1">ดอกเบี้ย<br>ที่เกิดขึ้น<br>ในรอบรายการนี้</th>
					<th bgcolor="#C1FFC1">ดอกเบี้ย<br>ตัดจ่าย</th>
					<th bgcolor="#BBFFFF">เงินต้น<br>คงเหลือ<br>หลังชำระ</th>
					<th bgcolor="#BBFFFF">ดอกเบี้ย<br>คงเหลือ<br>หลังชำระ</th>
					<th bgcolor="#BBFFFF">ดอกเบี้ย<br>คงเหลือ<br>ทางบัญชี<br>หลังรายการ</th>
				</tr>	
				<?php
				//นำข้อมูลจาก view มาแสดง โดยหากงวดใดมีการจ่ายหลายครั้ง ใน view ก็จะต้องเก็บเป็นหลาย record เช่นกัน ดังนั้นเวลาแสดงจึงแสดงวนลูปปกติ
				$qry_realize = pg_query("
											SELECT *,date(\"receiveDate\") as \"receiduedate\" 
											FROM 
												account.thcap_acc_filease_realize_eff_acc_present
											WHERE
												\"contractID\" = '$contractID'
											ORDER BY 
												\"duedate\",
												\"DueNo\",
												\"receiveDate\" 
				");
				$numrows = pg_num_rows($qry_realize);
						
				$i=0;
				while($res_realize = pg_fetch_array($qry_realize))
				{		
					$DueNo = $res_realize["DueNo"]; // งวดที่  
					$accdate  = $res_realize["accdate"]; // วันที่คิดยอดบัญชี
					$duedate = $res_realize["duedate"]; // วันที่ครบกำหนดชำระ
					$receiveDate = $res_realize["receiduedate"]; // วันที่รับชำระ 
					$receiptID = $res_realize["receiptID"]; // เลขที่ใบเสร็จ 
					$debtnet = number_format($res_realize["debtnet"],2); // ค่าเช่า 
					$debtvat = number_format($res_realize["debtvat"],2); // ภาษีมูลค่าเพิ่ม 
					$totalpriciple_before = number_format($res_realize["totalpriciple_before"],2); // เงินต้นคงเหลือก่อนชำระ
					$totalinterest_before = number_format($res_realize["totalinterest_before"],2); // ดอกเบี้ยคงเหลือก่อนชำระ
					$totalaccinterest_before = number_format($res_realize["totalaccinterest_before"],2); //ดอกเบี้ยคงเหลือทางบัญชีหลังรายการ
					$priciple_cut = number_format($res_realize["priciple_cut"],2); // เงินต้นที่ถูกตัดจ่าย
					$recinterest_cut = number_format($res_realize["recinterest_cut"],2); // จำนวนดอกเบี้ยที่เกิดขึ้นในรอบรายการนี้
					$interest_cut = number_format($res_realize["interest_cut"],2); // ดอกเบี้ยที่ถูกตัดจ่าย	
					$totalpriciple_left = number_format($res_realize["totalpriciple_left"],2); // เงินต้นคงเหลือหลังชำระ
					$totalinterest_left = number_format($res_realize["totalinterest_left"],2); //ดอกเบี้ยคงเหลือหลังชำระ
					$totalaccinterest_left = number_format($res_realize["totalaccinterest_left"],2); //ดอกเบี้ยคงเหลือทางบัญชีหลังรายการ
					

					echo "<tr bgcolor=\"#FFFFFF\" align=\"right\" height=25>";	
					
					$color="#FFF0F5";
					$color2="#F0FFF0";
					$color3="#E0FFFF";			
					$color4="#FFCCCC";
					$color5="#C1FFC1";
					$color6="#BBFFFF";
					
					if($DueNo==-1){
						// ถ้ารายการนี้เป็นยอดปิดสิ้นเดือน ไม่ต้องแสดงเลขงวด หรือข้อมูลอื่นๆบางอย่าง
						echo "
							<td align=center></td>
							<td bgcolor=#C0C0C0 align=center>$accdate</td>
							<td align=center></td>
							<td align=center></td>
							<td align=center></td>
							<td></td>
							<td></td>
							<td bgcolor=$color></td>
							<td bgcolor=$color></td>
							<td bgcolor=$color>$totalaccinterest_before</td>
							<td bgcolor=$color2></td>
							<td bgcolor=$color2>$recinterest_cut</td>
							<td bgcolor=$color2></td>
							<td bgcolor=$color3></td>
							<td bgcolor=$color3></td>
							<td bgcolor=$color3>$totalaccinterest_left</td>
						</tr>";
					} else{
					
						//กรณีที่มีการจ่ายหลายครั้งใน 1 งวด ไม่ต้องแสดงเลขงวดซ้ำ
						if($DueNo_old==$DueNo){ 
							$DueNo="";
						}
						
						// ถ้ารายการนี้เป็นรอบงวดธรรมดาให้แสดงข้อมูลปกติ
						echo "
							<td bgcolor=#FFF380 align=center>$DueNo</td>
							<td bgcolor=#FFF380 align=center>$accdate</td>
							<td bgcolor=#FFF380 align=center>$duedate</td>
							<td bgcolor=#FFF380 align=center>$receiveDate</td>
							<td bgcolor=#FFF380 align=center>$receiptID</td>
							<td bgcolor=#FFF380>$debtnet</td>
							<td bgcolor=#FFF380>$debtvat</td>
							<td bgcolor=$color4>$totalpriciple_before</td>
							<td bgcolor=$color4>$totalinterest_before</td>
							<td bgcolor=$color4>$totalaccinterest_before</td>
							<td bgcolor=$color5>$priciple_cut</td>
							<td bgcolor=$color5>$recinterest_cut</td>
							<td bgcolor=$color5>$interest_cut</td>
							<td bgcolor=$color6>$totalpriciple_left</td>
							<td bgcolor=$color6>$totalinterest_left</td>
							<td bgcolor=$color6>$totalaccinterest_left</td>
						</tr>";
					}

					$DueNo_old=$DueNo;
					$i++;
				}
				if($numrows == 0){ //ถ้าไม่มีข้อมูล
					echo "<tr bgcolor=\"#FFFFFF\" height=50><td align=\"center\" colspan=\"8\">ไม่พบรายการ</td></tr>";
				}
				?>
			</table>
		</div>
	</div>
</fieldset>	
<?php				
}
?>