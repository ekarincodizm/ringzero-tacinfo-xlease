<!-- หนี้อื่นๆที่ค้างอยู่ -->
<?php if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
{ ?>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function create_request(debtID)
{
	$("#panel_create_request").load("create_request.php?debtID="+debtID+"&contractID=<?php echo $contractID; ?>");
}
</script>

<fieldset>
	<legend><B>เลือกรายการที่จะขอส่วนลด</B></legend>
	<div align="center">
		<div id="panel4" align="left" style="margin-top:10px">
			<?php
			
				$qry_other = pg_query("select \"typePayID\", \"typePayRefValue\", \"typePayRefDate\", \"typePayAmt\", \"doerID\", \"doerStamp\", \"debtID\"
										from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
				$row_other = pg_num_rows($qry_other);
				if($row_other > 0)
				{
					$qry_sun_other = pg_query("select sum(\"typePayAmt\") as \"summoney\" from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$contractID' and \"debtStatus\"='1' ");
					while($res_sum = pg_fetch_array($qry_sun_other))
					{
						$summoney = $res_sum["summoney"]; // เงินรวม
					}
					//echo "<b>รวมทั้งหมด ".number_format($summoney,2)." บาท</b>";
				}
			?>
				<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
					<tr  align="center" bgcolor="#097AB0" style="color:#FFFFFF" height="25">
						<th>รหัสประเภท<br>ค่าใช้จ่าย</th>
						<th>รายการ</th>
						<th>ค่าอ้างอิงของค่าใช้จ่าย</th>
						<th>วันที่ตั้งหนี้</th>
						<th>จำนวนหนี้</th>
						<th>ผู้ตั้งหนี้</th>
						<th>วันเวลาตั้งหนี้</th>
						<th>หมายเหตุ</th>
						<th>ทำรายการ</th>
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
							$typePayAmt=trim($res_name["typePayAmt"]);
							$doerID=trim($res_name["doerID"]); 
							$doerStamp=trim($res_name["doerStamp"]);
							$debtID=trim($res_name["debtID"]);
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
							
							if($t%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
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
							
							// ตรวจสอบก่อนว่า หนี้นั้นๆมีการขออนุมัติอยู่หรือไม่
							$qry_chkHaveData = pg_query("select \"debtID\" from account.\"thcap_dncn_discount\" where \"debtID\" = '$debtID' and \"dcNoteStatus\" = '8' ");
							$row_chkHaveData = pg_num_rows($qry_chkHaveData);
							
							if($row_chkHaveData > 0)
							{
								$chkHaveData = "haveData"; // มีการขออนุมัติอยู่แล้ว
							}
							else
							{
								$chkHaveData = "noHaveData"; // ยังไม่มีการขออนุมัติ
							}
							
							//echo "<tr bgcolor=#DBF2FD>";
							echo "<td align=center>$typePayID</td>";
							echo "<td align=left>$tpDesc</td>";
							echo "<td align=center>$typePayRefValue $due</td>";
							echo "<td align=center>$typePayRefDate</td>";
							echo "<td align=right>".number_format($typePayAmt,2)."</td>";
							echo "<td align=left>$doerName</td>";
							echo "<td align=center>$doerStamp</td>";
							?>
								<td align="center"><span onclick="javascript:popU('../thcap/show_remark.php?debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=300')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
							<?php
							if($chkHaveData == "haveData")
							{
								echo "<td align=center>รออนุมัติอยู่</td>";
							}
							else
							{
							?>
								<td align="center"><input type="button" value="เลือก" onClick="create_request(<?php echo $debtID; ?>)"></td>
							<?php
							}
							echo "</tr>";
							
							$t++;
						}
					}
					else
					{
						echo "<tr><td align=\"center\" COLSPAN=\"9\" bgcolor=\"#33FFCC\"><b>ไม่พบหนี้ที่ค้างชำระ</b></td></tr>";
					}
				
				echo "</table>";
				echo "<div align=\"right\"><font color=\"#FF0000\"><b>รวมทั้งหมด ".number_format($summoney,2)." บาท</b></font></div>";
			
					?>
		</div>
	</div>
</fieldset>
<?php } ?>
<!-- เลือกรายการที่จะขอส่วนลด -->

<div name="panel_create_request" id="panel_create_request" style="padding:10px 0px"></div>