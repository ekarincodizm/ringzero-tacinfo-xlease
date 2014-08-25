<!-- หนี้อื่นๆที่ค้างอยู่ -->
<?php 
session_start();
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ

if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
{ 
	//ตรวจสอบว่าพนักงานท่านนี้สามารถขอยกเว้นหนี้ได้หรือไม่
	$qrychk=pg_query("select ta_get_usermenu_rights('TM17','$id_user')");
	$numchk=pg_fetch_result($qrychk,0); //ถ้ามีค่า = 1 แสดงว่ามีสิทธิ์ใช้ส่วนขอยกเว้นหนี้ได้
	
	//ตรวจสอบว่าพนักงานท่านนี้สามารถขอตั้งหนี้ได้หรือไม่
	$qrychkdebt=pg_query("select ta_get_usermenu_rights('TM14','$id_user')");
	$numchkdebt=pg_fetch_result($qrychkdebt,0); //ถ้ามีค่า = 1 แสดงว่ามีสิทธิ์ใช้ส่วนขอตั้งหนี้ได้
	
	$page="thcap_installments"; //กำหนดว่ามาจากเมนู "แสดงตารางผ่อนชำระ"
	
	$qry_right = pg_query("select ta_get_usermenu_rights('TM32','$id_user')");
	$numchk_letter = pg_fetch_result($qry_right,0); //ถ้ามีค่า = 1 แสดงว่ามีสิทธิ์ใช้ส่วนส่งจดหมาย
	
	//ตรวจสอบว่าพนักงานท่านนี้สามารถขอคืนเงินลูกค้าได้หรือไม่
	$qry_dncn = pg_query("select ta_get_usermenu_rights('TMC02','$id_user')");
	$numchk_dncn = pg_fetch_result($qry_dncn,0); //ถ้ามีค่า = 1 แสดงว่ามีสิทธิ์
?>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function dncn(){
	popU('../thcap_dncn/frm_Index.php?conid=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=800');
}
</script>

<fieldset>
	<legend><B>หนี้อื่นๆที่ค้างชำระ</B></legend>
    <div style="text-align:right;">
	<?php 
	if($numchk_letter == 1) {
	?>
	<input type="button"  value="ส่งจดหมาย" onclick="javascript:popU('../thcap/frm_lt.php?contractID=<?php echo $contractID;?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"  title="ส่งจดหมาย" />
	<?php } ?>
	<?php
	if($numchkdebt == 1){
	?>
	<input type="button"  value="ขอตั้งหนี้" onclick="javascript:popU('../thcap/frm_setDebtLoanTime.php?contractID=<?php echo $contractID;?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"  title="ขอตั้งหนี้" />
	<?php
	}
	
	if($numchk == 1){
	?>
	<input type="button"  value="ขอยกเว้นหนี้" onclick="javascript:popU('../except_debt/Payments_history.php?ConID=<?php echo $contractID;?>&page=<?php echo $page;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"  title="ขอยกเว้นหนี้" />
	<?php
	}
	if($numchk_dncn == 1){?>
		<input type="button" name="dncn_btn" id="dncn_btn" value="ขอคืนเงินลูกค้า" onclick="dncn();" />
	<?php }?>
	
	<input type="button" name="calc_tax_btn" id="calc_tax_btn" value="คำนวณภาษีหัก ณ ที่จ่าย" onclick="calc_tax();" /></div>
	<div align="center">
		<div id="panel4" align="left" style="margin-top:10px">
			<?php
			
				$qry_other = pg_query("select \"typePayID\", \"typePayRefValue\", \"typePayRefDate\", \"debtDueDate\", \"typePayAmt\", \"doerID\", \"doerStamp\", \"debtID\", \"typePayLeft\"
										from public.\"thcap_v_otherpay_debt_realother_current\" where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
				$row_other = pg_num_rows($qry_other);
				if($row_other > 0)
				{
					$qry_sun_other = pg_query("select sum(\"typePayLeft\") as \"summoney\" from public.\"thcap_v_otherpay_debt_realother_current\" where \"contractID\"='$contractID' and \"debtStatus\"='1' ");
					while($res_sum = pg_fetch_array($qry_sun_other))
					{
						$summoney = $res_sum["summoney"]; // เงินรวม
					}
					//echo "<b>รวมทั้งหมด ".number_format($summoney,2)." บาท</b>";
				}
			?>
				<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
					<tr  align="center" bgcolor="#097AB0" style="color:#FFFFFF" height="25">
						<th>รหัสค่าใช้จ่าย</th>
						<th>รายการ</th>
						<th>ค่าอ้างอิงของค่าใช้จ่าย</th>
						<th>วันที่ตั้งหนี้</th>
						<th>วันที่ครบกำหนด</th>
						<th>จำนวนหนี้</th>
						<th>ผู้ตั้งหนี้</th>
						<th>วันเวลาตั้งหนี้</th>
						<th>หมายเหตุ</th>
					</tr>
					<?php
					if($row_other > 0)
					{
						$t = 0;
						while($res_name=pg_fetch_array($qry_other))
						{
							$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
							$typePayRefValue=trim($res_name["typePayRefValue"]);
							$typePayRefDate=trim($res_name["typePayRefDate"]);
							$debtDueDate=trim($res_name["debtDueDate"]);//วันที่ครบกำหนด
							$typePayAmt=trim($res_name["typePayAmt"]);
							$doerID=trim($res_name["doerID"]); 
							$doerStamp=trim($res_name["doerStamp"]);
							$debtID=trim($res_name["debtID"]); // รหัสหนี้
							$typePayLeft=trim($res_name["typePayLeft"]); // จำนวนหนี้ที่ค้างชำระ
							//$contractID=trim($res_name["contractID"]);
								
							$doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
								
							if($doerID == "000")
							{
								$doerName = "อัตโนมัติโดยระบบ";
							}
							else
							{
								$doerusername=pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\"='$doerID'");
								while($res_username=pg_fetch_array($doerusername))
								{
									$doerName=$res_username["fullname"];
								}
							}
							
							$qry_type=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
							while($res_type=pg_fetch_array($qry_type))
							{
								$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
							}
							
							// ตรวจสอบก่อนว่า รายการหนี้นี้มีการขอยกเว้นหนี้อยู่หรือไม่
							$qry_excepDebt = pg_query("select \"debtID\" from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' and \"Approve\" is null ");
							$row_excepDebt = pg_num_rows($qry_excepDebt);
							if($row_excepDebt > 0)
							{ // ถ้ามีการขอยกเว้นหนี้อยู่
								$tpDesc = "$tpDesc <font color=\"#FF0000\">(รออนุมัติยกเว้น)</font>";
							}
							
							if($t%2==0){
								echo "<tr class=\"odd1\">";
							}else{
								echo "<tr class=\"even1\">";
							}
							
							$due = ""; // กำหนดวันดิวเป็นค่าว่าง เพื่อไม่ให้เก็บค่าเก่ามาใช้
							
							if($typePayID == "1003")
							{
								//-----------------ตัดส่วนเกินออก
								$search = strpos($typePayRefValue,"-");
								if($search)
								{
									$subtypePayRefValue = explode("-", $typePayRefValue);
									$typePayRefValue = $subtypePayRefValue[0];
								}
								//-----------------จบการตัดส่วนเกินออก
								
								$qry_due=pg_query("select \"ptDate\" from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
								while($res_due=pg_fetch_array($qry_due))
								{
									$ptDate=trim($res_due["ptDate"]); // วันดิว
									$due = "($ptDate)";
								}
							}
							else
							{
								$due = "";
							}
							
							//echo "<tr bgcolor=#DBF2FD>";
							echo "<td align=center>$typePayID</td>";
							echo "<td align=center>$tpDesc</td>";
							echo "<td align=center>$typePayRefValue $due</td>";
							echo "<td align=center>$typePayRefDate</td>";
							echo "<td align=center>$debtDueDate</td>";
							echo "<td align=right>".number_format($typePayLeft,2)."</td>";
							echo "<td align=center>$doerName</td>";
							echo "<td align=center>$doerStamp</td>";
							?>
							<td align="center"><span onclick="javascript:popU('show_remark.php?debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=300')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
							<?php
							echo "</tr>";
							
							$t++;
						}
					}
					else
					{
						echo "<tr><td align=\"center\" COLSPAN=\"9\" bgcolor=\"#33FFCC\"><b>ไม่พบหนี้อื่นๆที่ค้างชำระ</b></td></tr>";
					}
				
				echo "</table>";
				echo "<div align=\"right\"><font color=\"#FF0000\"><b>รวมทั้งหมด ".number_format($summoney,2)." บาท</b></font></div>";
			
					?>
		</div>
	</div>
</fieldset>
<?php } ?>
<!-- จบส่วนหนี้อื่นๆที่ค้างอยู่ -->