<table align="center" width="99%">
	<tr>
		<td align="<?php echo $position_sectb_header; ?>">						
			<font color="<?php echo $fontcolor_sectb_header ?>" size="<?php echo $fontsize_sectb_header ?>px"><b><?php echo $sectb_header ?>
			(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historityall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>)
			</b></font>
		</td>
	</tr>
</table>		
<table align="center" bgcolor="<?php echo $bgcolor_sectb; ?>" frame="box" width="99%" cellspacing="<?php echo $cellspacing_sectb; ?>" cellpadding="<?php echo $cellpadding_sectb; ?>">	
    <tr bgcolor="<?php echo $bgcolor_sectb_column; ?>">
		<th width="7%"><a href='frm_approve.php?sort2=dcNoteID&order2=<?php echo $strNewOrder2 ?>'><u>รหัส CreditNote</u></th>
		<th width="10%"><a href='frm_approve.php?sort2=contractID&order2=<?php echo $strNewOrder2 ?>'><u>เลขที่สัญญา</u></th>
		<th width="15%">ชื่อผู้กู้หลัก</th>
		<th width="7%"><a href='frm_approve.php?sort2=dcNoteDate&order2=<?php echo $strNewOrder2 ?>'><u>วันที่มีผล</th>
		<th width="7%"><a href='frm_approve.php?sort2=doerStamp&order2=<?php echo $strNewOrder2 ?>'><u>วันที่ทำรายการ</u></th>
		<th width="15%"><a href='frm_approve.php?sort2=doerID&order2=<?php echo $strNewOrder2 ?>'><u>ผู้ทำรายการ</u></th>
		<th width="10%"><a href='frm_approve.php?sort2=typeChannel&order2=<?php echo $strNewOrder2 ?>'><u>ประเภทเงินที่ขอคืน</u></th>
		<th width="10%"><a href='frm_approve.php?sort2=dcNoteAmtALL&order2=<?php echo $strNewOrder2 ?>'><u>จำนวนเงิน</u></th>
		<th width="10%">ช่องทางการคืนเงิน</th>
		<th width="15%"><a href='frm_approve.php?sort2=appvName&order2=<?php echo $strNewOrder2 ?>'><u>ผู้อนุมัติรายการ</u></th>
		<th width="7%"><a href='frm_approve.php?sort2=appvStamp&order2=<?php echo $strNewOrder2 ?>'><u>วันที่อนุมัติรายการ</u></th>									
		<th width="8%"><a href='frm_approve.php?sort2=dcNoteStatus&order2=<?php echo $strNewOrder2 ?>'><u>สถานะการอนุมัติ</u></th>
		<th width="7%">เพิ่มเติม</th>
	</tr>
	<?php
		// --=========== วนเรียกข้อมูลรายการอนุมัติคืนเงินลูกค้า ================================================================================================--	
		//-- หากมีข้อมูล
		if($row_waitapp2 != 0){	
			$i = 0; //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
			while($re_waitapp2 = pg_fetch_array($qry_waitapp2)){
			//เลขที่สัญญา
				$conid = $re_waitapp2["contractID"];
			//รหัสการคืนเงิน
				$dcNoteID = $re_waitapp2["dcNoteID"];
			// dcNoteRev
				$dcNoteRev = $re_waitapp2["dcNoteRev"];
			//-- หาชื่อผู้กู้หลัก
				$maincus_fullname = $re_waitapp2["dcMainCusName"];
			//-- หาผู้กู้ร่วม
				/*$qry_cocus = pg_query("SELECT \"dcCoCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
				$namecoopall = pg_fetch_result($qry_cocus,0);*/
			//วันที่ทำรายการ
				$doerStamp = $re_waitapp2["doerStamp"];
			//ชื่อผู้ทำรายการ
				$doerID = $re_waitapp2["doerID"];
				$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$doerID'");
				list($doer_fullname) = pg_fetch_array($qry_username);
			//ประเภทเงินที่ขอคืน
											
				$byChannel = $re_waitapp2["byChannel"];	
			//เงินค้ำประกันการชำระหนี้
				$qry_chkchannel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$conid','1')");
				list($chkbyChannelget) = pg_fetch_array($qry_chkchannel);
			//เงินพักรอตัดรายการ
				$qry_chkchannel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$conid','1')");
				list($chkbyChannelhold) = pg_fetch_array($qry_chkchannel);
			//ตรวจสอบว่าเป้นประเภทใด						
				if($chkbyChannelget == $byChannel){	//ถ้าเป็น เงินค้ำประกันการชำระหนี้										
					$qry_channel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$conid','$byChannel')");
					list($byChannel) = pg_fetch_array($qry_channel);
				}else if($chkbyChannelhold == $byChannel){ //ถ้าเป็น เงินพักรอตัดรายการ
					$qry_channel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$conid','$byChannel')");
					list($byChannel) = pg_fetch_array($qry_channel);	
				}
			//รายละเอียดประเภทการขอคืน
				$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel' ");
				list($tpDesc) = pg_fetch_array($qry_txtchannel);
											
			//จำนวนเงิน
				$dcNoteAmtALL = $re_waitapp2["dcNoteAmtALL"];
			//สถานะการอนุมัติ
				IF($re_waitapp2["dcNoteStatus"] == '0'){
					$status = 'ไม่อนุมัติ';
				}else IF($re_waitapp2["dcNoteStatus"] == '1'){
					$status = 'อนุมัติ';
				}else IF($re_waitapp2["dcNoteStatus"] == '2'){
					$status = 'ยกเลิก';
				}else{
					$status = 'ไม่ระบุสถานะ';
				}
											
			//กรณีขอคืนเงิน
				if($re_waitapp2["typeChannelName"]!=""){
					$tpDesc=$re_waitapp2["typeChannelName"];
				}
				
				$debtID = $re_waitapp2["debtID"];
				if($tpDesc == "" && ($debtID != '' || $debtID != NULL)){
					// หารหัสประเภทค่าใช้จ่าย และค่าอ้างอิง
					$qry_typePayID = pg_query("select \"typePayID\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
					$typePayID = pg_fetch_result($qry_typePayID,0);
																					
					// รายละเอียดประเภทค่าใช้จ่าย
					$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
					while($res_type=pg_fetch_array($qry_type))
					{
						$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
					}
					$tpDesc = "$tpDesc";
				}
											
			//ช่องทางการคืนเงิน
				if($re_waitapp2["returnChqNo"]!=""){
					$byChanneltable =$re_waitapp2["byChannel_bank"];
					$qry_returnChqNo = pg_query("SELECT \"BAccount\",\"BName\" FROM \"BankInt\" where  \"BID\" = '$byChanneltable' ");
					list($BAccount,$BName) = pg_fetch_array($qry_returnChqNo);
					$listaccount=$re_waitapp2["byChannelName"].' ธนาคาร  '.$BAccount.'-'.$BName;
										
				}
				else{$listaccount=$re_waitapp2["byChannelName"];}
										
					// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
						$i++;									
						if($i%2==0){
							$bgcolor_TR2 = $bgcolor_TR2_1; // สีพื้นหลังข้อมูล
						}else{
							$bgcolor_TR2 = $bgcolor_TR2_2; // สีพื้นหลังข้อมูล
						} 
						?>
						<tr bgcolor="<?php echo $bgcolor_TR2; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL2; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR2; ?>'" align="center">
							<td align="center"><span onclick="javascript:popU('popup_dncn.php?idapp=<?php echo $dcNoteID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=650')" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo "$dcNoteID"; ?><u></font></td>	
							<td align="left">
								<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
								<font color="red"><u><?php echo "$conid"?><u></font>
							</td>
							<td align="left"><?php echo "$maincus_fullname"?></td>								
							<td align="center"><?php echo $re_waitapp2["dcNoteDate"];?></td>
							<td align="center"><?php echo "$doerStamp"?></td>
							<td align="left"><?php echo "$doer_fullname"?></td>
							<td align="center"><?php echo "$tpDesc"?></td>
							<td align="right"><?php echo number_format("$dcNoteAmtALL",2) ?></td>	
							<td align="center"><?php echo $listaccount;?></td>
							<td align="left"><?php echo $re_waitapp2["appvName"];?></td>
							<td align="center"><?php echo $re_waitapp2["appvStamp"];?></td>
							<td align="center"><?php echo $status; ?></td>
							<td align="center"><img src="<?php echo $rootpath; ?>/nw/thcap/images/detail.gif" style="cursor:pointer;" onclick="detailapp('<?php echo $dcNoteID; ?>','0');"></td>		
						</tr>
					<?php		
						unset($namecoopall);
			}
				}else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"13\"><h2> ไม่ประวัติการอนุมัติ </h2></td></tr>"; }?>			
</table>