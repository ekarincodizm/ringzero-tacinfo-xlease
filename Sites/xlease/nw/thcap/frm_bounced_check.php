	<?php	//แสดงรายการว่า ในวันนั้น มีรายการการเช็คเด้งเท่าไร
	$qrybadchq=pg_query("select \"revChqID\",\"bankChqNo\",\"revChqDate\",\"BankName\",\"bankOutBranch\",\"revChqToCCID\",\"bankChqAmt\"
						,\"revChqStatus\",\"bankChqDate\",\"chqSubmitTimes\",\"giveTakerID\",\"giveTakerDate\",\"chqKeeperID\"
						from finance.\"V_thcap_receive_cheque_keeper_cheManage\" 
						where \"bankRevResult\"='3' and \"giveTakerDate\"='$datemain' and \"BID\"='$acctype[$loop]' 
						and \"chqKeeperID\" not in(select \"chqKeeperID\" from finance.\"thcap_receive_transfer\" where  \"chqKeeperID\" is not null)
						order by \"chqKeeperID\"
						");
				
					$numrowsbadchq=pg_num_rows($qrybadchq);
					if($numrowsbadchq>0){
						echo "<tr>
						<td colspan=12>
						<table width=\"100%\" border=\"0\" style=\"border-style: dashed; border-width: 1px; border-color:#698B69; margin-bottom:5px\">
						<tr bgcolor=#FF6A6A><td colspan=12 ><b>วันที่ $datemain  เช็คเด้งที่ยังไม่ได้ผูก </b></td></tr>
						<tr bgcolor=#CD5C5C >
							<td align=\"center\" width=80>เลขที่เช็ค</td>
							<td align=\"center\">เลขที่สัญญา</td>
							<td align=\"center\" width=\"150\">ชื่อ-สกุลลูกค้า</td>
							<td align=\"center\">วันที่บนเช็ค</td>
							<td align=\"center\" width=\"120\">ธนาคารที่ออกเช็ค</td>
							<td align=\"center\" width=\"100\">วันที่นำเช็คเข้าธนาคาร</td>
							<td align=\"center\" width=\"50\" >เช็คเด้งครั้งที่</td>
							<td align=\"center\">ผู้นำเช็คเข้าธนาคาร</td>
							<td align=\"center\">ยอดเช็ค</td>
						";	
						if($emplevel<=1){							
							echo "<td align=\"center\">รายการพิเศษ</td>";
						}
						echo "</tr>";
						
						while($resbad=pg_fetch_array($qrybadchq)){
							$revChqID = $resbad["revChqID"]; //รหัสเช็ค
							$bankChqNo=$resbad["bankChqNo"]; //เลขที่เช็ค
							$revChqDate = $resbad["revChqDate"]; //วันที่รับเช็ค
							$bankName = $resbad["BankName"]; //ธนาคาร
							$bankOutBranch = $resbad["bankOutBranch"]; //สาขา
							$contractid = $resbad["revChqToCCID"]; //เลขที่สัญญา
							$bankChqAmt = $resbad["bankChqAmt"]; //จำนวนเงิน
							$revChqStatus=$resbad["revChqStatus"]; //สถานะเช็ค
							$bankChqDate=$resbad["bankChqDate"]; //วันที่สั่งจ่าย/วันที่บนเช็ค
							$chqSubmitTimes=$resbad["chqSubmitTimes"];//จำนวนครั้งที่เช็คเด้ง
							$giveTakerID=$resbad["giveTakerID"];//ผู้นำเช็คเข้าธนาคาร
							$giveTakerDate=$resbad["giveTakerDate"];//วันที่นำเช็คเข้าธนาคาร
							$chqKeeperID=$resbad["chqKeeperID"];
						
							//ผู้นำเช็คเข้า
							$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$giveTakerID' ");
							$nameuser = pg_fetch_array($query_fullname);
							$givetakerName =$nameuser["fullname"];
							//หาชื่อลูกค้า
							$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
							list($cusid,$fullname) = pg_fetch_array($qry_cusname);
							echo "<tr bgcolor=\"#FA8072\" align=center>";							
							?>					
							<td><?php echo $bankChqNo; ?><input type="hidden" name="chqKeeperID[]" value="<?php echo $chqKeeperID;?>"></td>
							<td>
								<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
							<font color="blue"><U><?php echo $contractid; ?></U></font></a>
							</td>
							<td align="left"><?php echo $fullname; ?></td>
							<td><?php echo $bankChqDate; ?><input type="hidden" name="revChqID[]" id="t<?php echo $i; ?>" value="<?php echo $revChqID;?>"></td>
							<td align="left"><?php echo $bankName; ?></td>
							<td align="center"><?php echo $giveTakerDate; ?></td>
							<td align="center"><?php echo $chqSubmitTimes; ?></td>
							<td align="center"><?php echo $givetakerName; ?></td>
							<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
							<?php 
							if($emplevel<=1){						
								echo "<td align=center><img src=\"images/refresh.png\" width=24 height=24 onclick=\"returnchq_bounced('$revChqID')\" style=\"cursor:pointer;\"  title=\"ย้อนกลับไป ยืนยันนำเช็คเข้าธนาคาร\"></td>";
							}
							?>
							</tr>
						
						
						
				<?php	}echo "</table>";
					}
					
				