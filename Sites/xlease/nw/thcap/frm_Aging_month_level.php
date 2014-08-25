<fieldset>				
	<div>
		<div align="right"><a href="excel_Aging_month.php?datepicker=<?php echo "$nowdate"; ?>&contype=<?php echo $sendpdf; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(Export Excel)</span></a><a href="pdf_Aging_month.php?datepicker=<?php echo "$nowdate"; ?>&contype=<?php echo $sendpdf; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
		<div align="left">
			<input type="button" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('thcap_AgingYear/frm_showgroup.php?datepicker=<?php echo "$nowdate"; ?>&contype=<?php echo $sendpdf; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=800')" style="cursor:pointer;">
			<br><?php if($afterIncome == "on"){echo "<font size=\"3\">ลูกหนี้<b><u>หลังหัก</u></b>รายได้ตั้งพักรอการรับรู้</font>";}else{echo "<font size=\"3\">ลูกหนี้<b><u>รวม</u></b>รายได้ตั้งพักรอการรับรู้</font>";} ?>
			<br><b>ข้อมูล ณ วันที่ <?php echo "$nowdate"; ?></b>
		</div>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
		<thead>
		<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
			<th>ลำดับ</th>
			<th>เลขที่สัญญา</th>
			<th>ลูกหนี้</th>
			<th bgcolor=#F6CEF5>วันเริ่มผิดนัด</th>
			<th>จัดชั้นปกติ</th>
			<th>จัดชั้นกล่าวถึง<br>เป็นพิเศษ</th>
			<th>จัดชั้นต่ำกว่า<br>มาตราฐาน</th>
			<th>จัดชั้นสงสัย</th>
			<th>จัดชั้นสงสัยจะสุญ</th>
			<th>ปรับโครงสร้างหนี้</th>
			<th>ดำเนินคดี</th>
			<th bgcolor=#E3F6CE>รายได้ตั้งพัก</th>
			<th bgcolor=#FFE5B4>มูลค่าหลักประกัน</th>
			<th bgcolor=#FFE5B4>มูลค่าหลักประกัน (เงินสด)</th>
		</tr>
		</thead>
		
		<?php
		//วนตามประเภทสัญญาที่เลือก	
		$sump0 = 0;
		$sump1 = 0;	
		$sump2 = 0;
		$sump3 = 0;
		$sump4 = 0;
		$sump5 = 0;
		$sump6 = 0;
		$sumpgetInterestLeftAcc = 0;
		
		for($con = 0;$con < sizeof($contypechk) ; $con++){
			//แสดงประเภทอยู่ด้านบนข้อมูล
			echo "<tr bgcolor=\"#FFD39B\"><td colspan=\"14\"><b>$contypechk[$con]</b></td></tr>";
			
			$i=0;
			$sumprinciple0 = 0;	
			$sumprinciple1 = 0;
			$sumprinciple2 = 0;
			$sumprinciple3 = 0;
			$sumprinciple4 = 0;
			$sumprinciple5 = 0;
			$sumprinciple6 = 0;
			$sumgetInterestLeftAcc = 0;
			
			$chk=0; //สำหรับตรวจสอบว่าสัญญาประเภทนั้นมีข้อมูลหรือไม่
			
			//แสดงข้อมูล
			$qrymg=pg_query("	SELECT 
									\"contractID\" 
								FROM 
									thcap_contract 
								WHERE 
									(\"conClosedDate\" is NULL OR \"conClosedDate\" > '$nowdate') AND 
									\"conStartDate\" <= '$nowdate' AND 
									\"conType\" = '$contypechk[$con]' AND
									\"conCredit\" IS NULL
								ORDER BY \"contractID\" ASC"
			);
			$numcontract=pg_num_rows($qrymg);			
			while($result=pg_fetch_array($qrymg)){
				$contractID=$result["contractID"];

				// หาว่าสัญญานี้ยังต้องแสดงอยู่อีกหรือไม่ หากปิดหรือขาย หรือยึดแล้วไม่ต้องแสดงอีก
				$qry_conclosedate=pg_query("SELECT \"thcap_checkcontractcloseddate\"('$contractID','$nowdate')");
				list($conclosedate)=pg_fetch_array($qry_conclosedate);
				
				if($conclosedate == ''){ // ถ้าพบว่าสัญญายังไม่ปิดบัญชี คือ แสดงได้
					$chk+=1;
					
					//หาชื่อลูกหนี้
					$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
					list($cusname)=pg_fetch_array($qryname);
				
					//หาลูกหนี้หักดอกเบี้ยตั้งพักถ้ามีของแต่ละสัญญา  ด้วย function thcap_get_all_debtamt_acc
					if($afterIncome == "on"){$ptype = ", '2'";}else{$ptype = "";}
					$qryprinciple=pg_query("SELECT \"thcap_get_all_debtamt_acc\"('$contractID','$nowdate' $ptype)");
					list($principle)=pg_fetch_array($qryprinciple);
					
					//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
					$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$nowdate')");
					list($issue)=pg_fetch_array($qryissue);
					if($issue==1){
						$nubmonth='issue';
					}
					//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
					$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$nowdate')");
					list($structure)=pg_fetch_array($qrystructure);
					if($structure==1){
						$nubmonth='structure';
					}
				
					// หาวันที่ผิดนัดชำระ
					$qry_defaultdate=pg_query("SELECT \"thcap_get_all_backdate_db\"('$contractID','$nowdate')");
					list($defaultdate)=pg_fetch_array($qry_defaultdate);
					
					// หาประเภทสินเชื่อ
					$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
					list($contype)=pg_fetch_array($qrytype);
					
					// หารายได้ตั้งพักรอรับรู้
					if(	$contype=='HIRE_PURCHASE' or 
						$contype=='LEASING' or
						$contype=='FACTORING' or
						$contype=='PROMISSORY_NOTE' or
						$contype=='SALE_ON_CONSIGNMENT' ) {
							
						$getInterestLeftAcc=pg_query("							
								SELECT 
									SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่รับรู้รายได้ไปแล้ว
								FROM
									\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
								LEFT JOIN 
									\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
								WHERE 
									\"contractID\"='$contractID' AND
									\"accdate\" >= '2013-01-01' AND
									(
										(\"voucherID_realize\" IS NULL) OR
										(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$nowdate'::date)
									)
						");
						list($getInterestLeftAcc)=pg_fetch_array($getInterestLeftAcc);
					} else{
						// กรณีที่ไม่ใช่สัญญาข้างต้น รายได้รอรับรู้ ไม่มี
						$getInterestLeftAcc = 0.00;
					}
					$getInterestLeftAccshow = number_format($getInterestLeftAcc,2);
					
					// หามูลค่าหลักประกัน
					$qry_collateral=pg_query("							
							SELECT 
								tc_clvalue, tc_clvalue_cash
							FROM
								\"public\".\"thcap_contract_collateral_2013\"
							WHERE 
								\"contractID\"='$contractID'
					");
					list($collateral, $collateral_cash)=pg_fetch_array($qry_collateral);
					$collateralshow = number_format($collateral,2);
					$collateral_cashshow = number_format($collateral_cash,2);
					
					if($issue==0 and $structure==0){
						//นำเข้า function เพื่อหาจำนวนเดือนที่ค้าง
						$qrybackduedate=pg_query("SELECT \"thcap_get_all_backmonths_db\"('$contractID','$nowdate')");
						list($nubmonth)=pg_fetch_array($qrybackduedate);
					}

					if($nubmonth=='structure' or ($issue==1 and $structure==1)){ //อยู่ระหว่างปรับโครงสร้างหนี้
						$condition="5";
						$principle5=$principle;
						if($principle5!=""){
							$principle55=number_format($principle5,2);
						}else{
							$principle55="";
						}
					}else if($nubmonth=='issue'){ //อยู่ระหว่างดำเนินคดี 
						$condition="6";
						$principle6=$principle;
						if($principle6!=""){
							$principle66=number_format($principle6,2);
						}else{
							$principle66="";
						}
					}else if($nubmonth <= 1){ // ลูกหนี้จัดชั้นปกติ (<= 1)
						$condition="0";
						$principle0=$principle;
						if($principle0!=""){
							$principle00=number_format($principle0,2);
						}else{
							$principle00="";
						}
					}else if($nubmonth > 1 and $nubmonth <= 3){ // ลูกหนี้จัดชั้นกล่าวถึงเป็นพิเศษ 1 < x <= 3
						$condition="1";
						$principle1=$principle;
						if($principle1!=""){
							$principle11=number_format($principle1,2);
						}else{
							$principle11="";
						}
					}else if($nubmonth > 3 and $nubmonth <= 6){ // ลูกหนี้จัดชั้นต่ำกว่ามาตราฐาน 3 < x <= 6
						$condition="2";
						$principle2=$principle;
						if($principle2!=""){
							$principle22=number_format($principle2,2);
						}else{
							$principle22="";
						}
					}else if($nubmonth > 6 and $nubmonth <= 12){ // เกินกำหนด 6 เดือน - 12 เดือน
						$condition="3";
						$principle3=$principle;
						if($principle3!=""){
							$principle33=number_format($principle3,2);
						}else{
							$principle33="";
						}
					}else if($nubmonth > 12){ // เกินกว่า 12 เดือน
						$condition="4";
						$principle4=$principle;
						if($principle4!=""){
							$principle44=number_format($principle4,2);
						}else{
							$principle44="";
						}
					}
					
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
					}else{
						echo "<tr class=\"even\" align=\"center\">";
					}
					
					echo "
						<td>$i</td>
						<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\">
						<u>$contractID</u></span><br></td>
						<td align=left>$cusname</td>
						<td align=center bgcolor=#F6CEF5>
					";
						echo $defaultdate." (".number_format($nubmonth,2).")"."</td><td align=right>";
						
						if($condition=="0"){echo $principle00;}else{ $principle0=0;}
						echo "</td><td align=right>";
						if($condition=="1"){echo $principle11;}else{ $principle1=0;}
						echo "</td><td align=right>";
						if($condition=="2"){echo $principle22;}else{ $principle2=0;}
						echo "</td><td align=right>";
						if($condition=="3"){echo $principle33;}else{ $principle3=0;}
						echo "</td><td align=right>";
						if($condition=="4"){echo $principle44;}else{ $principle4=0;}
						echo "</td><td align=right>";
						if($condition=="5"){echo $principle55;}else{ $principle5=0;}
						echo "</td><td align=right>";
						if($condition=="6"){echo $principle66;}else{ $principle6=0;}
						echo "</td>";
						
						echo "<td align=right bgcolor=#E3F6CE>$getInterestLeftAccshow</td>";
						echo "<td align=right bgcolor=#FFE5B4>$collateralshow</td>";
						echo "<td align=right bgcolor=#FFE5B4>$collateral_cashshow</td>";
						
						echo "</tr>";
						
					$allsum+=$principle;
					$sumprinciple0+=$principle0;
					$sumprinciple1+=$principle1;
					$sumprinciple2+=$principle2;
					$sumprinciple3+=$principle3;
					$sumprinciple4+=$principle4;
					$sumprinciple5+=$principle5;
					$sumprinciple6+=$principle6;
					$sumgetInterestLeftAcc+=$getInterestLeftAcc;
					$sumcollateral+=$collateral;
					$sumcollateral_cash+=$collateral_cash;
					
					
											$collateralshow = number_format($collateral,2);
					$collateral_cashshow = number_format($collateral_cash,2);
					
					$sump0+=$principle0;
					$sump1+=$principle1;
					$sump2+=$principle2;
					$sump3+=$principle3;
					$sump4+=$principle4;
					$sump5+=$principle5;
					$sump6+=$principle6;
					$sumpgetInterestLeftAcc+=$getInterestLeftAcc;
					$sumpcollateral+=$collateral;
					$sumpcollateral_cash+=$collateral_cash;
					
					if($sumprinciple0!=""){$sumprincipleshow0=number_format($sumprinciple0,2);}else{$sumprincipleshow0="-";}
					if($sumprinciple1!=""){$sumprincipleshow1=number_format($sumprinciple1,2);}else{$sumprincipleshow="-";}
					if($sumprinciple2!=""){$sumprincipleshow2=number_format($sumprinciple2,2);}else{$sumprincipleshow2="-";}
					if($sumprinciple3!=""){$sumprincipleshow3=number_format($sumprinciple3,2);}else{$sumprincipleshow3="-";}
					if($sumprinciple4!=""){$sumprincipleshow4=number_format($sumprinciple4,2);}else{$sumprincipleshow4="-";}
					if($sumprinciple5!=""){$sumprincipleshow5=number_format($sumprinciple5,2);}else{$sumprincipleshow5="-";}
					if($sumprinciple6!=""){$sumprincipleshow6=number_format($sumprinciple6,2);}else{$sumprincipleshow6="-";}
					
					$sumgetInterestLeftAccshow=number_format($sumgetInterestLeftAcc,2);
					$sumcollateralshow=number_format($sumcollateral,2);
					$sumcollateral_cashshow=number_format($sumcollateral_cash,2);
				
					unset($condition);
					unset($principle);
					unset($nubmonth);
					
				} //end if
			}
			
			//กรณีประเภทใดไม่มีข้อมูลให้สรุปว่า ไม่มีข้อมูลก่อนขึ้นประเภทใหม่
			if($numcontract==0 or $chk==0){
				echo "<tr><td colspan=14 height=30 align=center><b>--ไม่พบข้อมูล--</b></td></tr>";
			}else{	
				echo "<tr align=right bgcolor=#ABDBFE><td colspan=4 align=center><b>รวมของสัญญาประเภท $contypechk[$con]</b></td>";
				echo "<td>$sumprincipleshow0</td>";
				echo "<td>$sumprincipleshow1</td>";
				echo "<td>$sumprincipleshow2</td>";
				echo "<td>$sumprincipleshow3</td>";
				echo "<td>$sumprincipleshow4</td>";
				echo "<td>$sumprincipleshow5</td>";
				echo "<td>$sumprincipleshow6</td>";
				echo "<td bgcolor=#E3F6CE>$sumgetInterestLeftAccshow</td>";
				echo "<td bgcolor=#FFE5B4>$sumcollateralshow</td>";
				echo "<td bgcolor=#FFE5B4>$sumcollateral_cashshow</td>";
				echo "</tr>";
			}
			$sumprincipleshow0 = 0;	
			$sumprincipleshow1 = 0;
			$sumprincipleshow2 = 0;
			$sumprincipleshow3 = 0;
			$sumprincipleshow4 = 0;
			$sumprincipleshow5 = 0;
			$sumprincipleshow6 = 0;
			$sumgetInterestLeftAccshow = 0;
			$sumcollateralshow = 0;
			$sumcollateral_cashshow = 0;
		}//end ประเภทสัญญา

		?>
		<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<th>จัดชั้นปกติ</th>
			<th>จัดชั้นกล่าวถึง<br>เป็นพิเศษ</th>
			<th>จัดชั้นต่ำกว่า<br>มาตราฐาน</th>
			<th>จัดชั้นสงสัย</th>
			<th>จัดชั้นสงสัยจะสุญ</th>
			<th>ปรับโครงสร้างหนี้</th>
			<th>ดำเนินคดี</th>
			<th>รายได้ตั้งพัก</th>
			<th>มูลค่าหลักประกัน</th>
			<th>มูลค่าหลักประกัน (เงินสด)</th>
		</tr>
		<?php			
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=4 align=center><b>รวมทั้งสิ้น</b></td>";
		echo "<td>".number_format($sump0,2)."</td>";
		echo "<td>".number_format($sump1,2)."</td>";
		echo "<td>".number_format($sump2,2)."</td>";
		echo "<td>".number_format($sump3,2)."</td>";
		echo "<td>".number_format($sump4,2)."</td>";
		echo "<td>".number_format($sump5,2)."</td>";
		echo "<td>".number_format($sump6,2)."</td>";
		echo "<td>".number_format($sumpgetInterestLeftAcc,2)."</td>";
		echo "<td>".number_format($sumpcollateral,2)."</td>";
		echo "<td>".number_format($sumpcollateral_cash,2)."</td>";
		
		if($allsum>0){ //ป้องกันการ error จากการหารด้วย 0
			$percent0 = number_format($sump0/$allsum*100,2);
			$percent1 = number_format($sump1/$allsum*100,2);
			$percent2 = number_format($sump2/$allsum*100,2);
			$percent3 = number_format($sump3/$allsum*100,2);
			$percent4 = number_format($sump4/$allsum*100,2);
			$percent5 = number_format($sump5/$allsum*100,2);
			$percent6 = number_format($sump6/$allsum*100,2);
		}
		
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=4 align=center><b>สัดส่วน</b></td>";
		echo "<td>$percent0 %</td>";
		echo "<td>$percent1 %</td>";
		echo "<td>$percent2 %</td>";
		echo "<td>$percent3 %</td>";
		echo "<td>$percent4 %</td>";
		echo "<td>$percent5 %</td>";
		echo "<td>$percent6 %</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		
		echo "<tr bgcolor=#FFCCCC><td colspan=8></td><td  align=center colspan=2><b>ลูกหนี้ทั้งสิ้น</b></td><td align=right><b>".number_format($allsum,2)."</b></td></tr>";
		
		if($numcontract==0){
			echo "<tr><td colspan=14 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
		}
		?>
		</table>
	</div>
</fieldset>
