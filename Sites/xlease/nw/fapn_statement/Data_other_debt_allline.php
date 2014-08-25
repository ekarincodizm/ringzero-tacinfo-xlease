<!-- หนี้อื่นๆที่ค้างอยู่ -->
<?php 
session_start();
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ

if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
{ 
	//ตรวจสอบว่าพนักงานท่านนี้สามารถขอยกเว้นหนี้ได้หรือไม่
	$qrychk=pg_query("select * from \"f_usermenu\" where id_user='$id_user' and id_menu='TM17' and status='TRUE'");
	$numchk=pg_num_rows($qrychk); //ถ้ามีค่า > 0 แสดงว่ามีสิทธิ์ใช้ส่วนขอยกเว้นหนี้ได้
	
	$page="fapn_statement"; //กำหนดว่ามาจากเมนู "แสดงตารางผ่อนชำระ"
?>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<fieldset>
	<legend><B>หนี้อื่นๆที่ค้างชำระ</B></legend>
	<div align="center">
		<div id="panel4" align="left" style="margin-top:10px">
			<?php
			
				$qry_other = pg_query("select \"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"doerID\",\"doerStamp\",\"debtID\",a.\"contractID\"
				from \"vthcap_contract_creditRef_active\" a
				left join \"thcap_v_otherpay_debt_realother\" b on a.\"contractID\"=b.\"contractID\" 
				where \"contractCredit\"='$contractID' and \"debtStatus\"='1' 
				union
				select \"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"doerID\",\"doerStamp\",\"debtID\",\"contractID\"
				from \"thcap_v_otherpay_debt_realother\" 
				where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"doerStamp\" ASC");
				$row_other = pg_num_rows($qry_other);
			?>
				<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
					<tr  align="center" bgcolor="#097AB0" style="color:#FFFFFF" height="25">
						<th>เลขที่สัญญา</th>
						<th>รหัสประเภทค่าใช้จ่าย</th>
						<th>รายการ</th>
						<th>ค่าอ้างอิงของค่าใช้จ่าย</th>
						<th>วันที่ตั้งหนี้</th>
						<th>จำนวนหนี้</th>
						<th>ผู้ตั้งหนี้</th>
						<th>วันเวลาตั้งหนี้</th>
						<th>หมายเหตุ</th>
						<?php
						if($numchk>0){
						?>
						<th>ตัวเลือก</th>
						<?php
						}
						?>
					</tr>
					<?php
					if($row_other > 0)
					{
						$t = 0;
						$summoney=0;
						while($res_name=pg_fetch_array($qry_other))
						{
							$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
							$typePayRefValue=trim($res_name["typePayRefValue"]);
							$typePayRefDate=trim($res_name["typePayRefDate"]);
							$typePayAmt=trim($res_name["typePayAmt"]);
							$doerID=trim($res_name["doerID"]); 
							$doerStamp=trim($res_name["doerStamp"]);
							$debtID=trim($res_name["debtID"]);
							$contractID2=trim($res_name["contractID"]);
								
							$doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
								
							if($doerID == "000")
							{
								$doerName = "อัตโนมัติโดยระบบ";
							}
							else
							{
								$doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
								while($res_username=pg_fetch_array($doerusername))
								{
									$doerName=$res_username["fullname"];
								}
							}
							
							$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
							while($res_type=pg_fetch_array($qry_type))
							{
								$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
							}
							
							if($t%2==0){
								echo "<tr class=\"odd\" align=center>";
							}else{
								echo "<tr class=\"even\" align=center>";
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
								
								$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
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
							// ตรวจสอบก่อนว่า รายการหนี้นี้มีการขอยกเว้นหนี้อยู่หรือไม่
							$qry_excepDebt = pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' and \"Approve\" is null ");
							$row_excepDebt = pg_num_rows($qry_excepDebt);
							if($row_excepDebt > 0)
							{ // ถ้ามีการขอยกเว้นหนี้อยู่
								$tpDesc = "$tpDesc <font color=\"#FF0000\">(รออนุมัติยกเว้น)</font>";
							}
							
							//echo "<tr bgcolor=#DBF2FD>";
							echo "<td align=left><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID2</u></span></td>";
							echo "<td>$typePayID</td>";
							echo "<td>$tpDesc</td>";
							echo "<td>$typePayRefValue $due</td>";
							echo "<td>$typePayRefDate</td>";
							echo "<td align=right>".number_format($typePayAmt,2)."</td>";
							echo "<td>$doerName</td>";
							echo "<td>$doerStamp</td>";
							echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/show_remark.php?debtID=$debtID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=300')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>";							
							if($numchk>0){
								echo "<td><img src=\"images/del.png\" width=23 height=23 style=\"cursor:pointer\" title=\"ขอยกเว้นหนี้\" onclick=\"javascript:popU('../except_debt/Payments_history.php?ConID=$contractID2&page=$page','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"/></td>";
							}
							echo "</tr>";
							
							$t++;
							$summoney=$summoney+$typePayAmt;
						}
					}
					else
					{
						echo "<tr><td align=\"center\" colspan=\"10\" bgcolor=\"#33FFCC\"><b>ไม่พบหนี้อื่นๆที่ค้างชำระ</b></td></tr>";
					}
				
				echo "</table>";
				echo "<div align=\"right\"><font color=\"#FF0000\"><b>รวมทั้งหมด ".number_format($summoney,2)." บาท</b></font></div>";
			
					?>
		</div>
	</div>
</fieldset>
<?php } ?>
<!-- จบส่วนหนี้อื่นๆที่ค้างอยู่ -->