<fieldset>				
	<div>
		<div align="right"><a href="excel_Aging.php?datepicker=<?php echo "$nowdate"; ?>&contype=<?php echo $sendpdf; ?>&typeshow='<?php echo $typeshow;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(Export Excel)</span></a><a href="pdf_Aging.php?datepicker=<?php echo "$nowdate"; ?>&contype=<?php echo $sendpdf; ?>&typeshow='<?php echo $typeshow;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
		<div align="left"><b>ข้อมูล ณ วันที่ <?php echo "$nowdate"; ?></b></div>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0" class="sort-table">
		<thead>
		<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
			<th>ลำดับที่</th>
			<th>เลขที่สัญญา</th>
			<th>รายชื่อลูกหนี้</th>
			<th>ไม่ค้างชำระ</th>
			<th>01-30</th>
			<th>31-60</th>
			<th>61-90</th>
			<th>91-120</th>
			<th>121-150</th>
			<th>151-180</th>
			<th>181-210</th>
			<th>211-240</th>
			<th>241-270</th>
			<th>271-300</th>
			<th>301-330</th>
			<th>331-360</th>
			<th>เกินกว่า 360</th>
		</tr>
		</thead>
		
		<?php
		//วนตามประเภทสัญญาที่เลือก	
		$sump1 = 0;	
		$sump2 = 0;
		$sump3 = 0;
		$sump4 = 0;
		$sump5 = 0;
		$sump6 = 0;
		$sump7 = 0;
		$sump8 = 0;
		$sump9 = 0;
		$sump10 = 0;
		$sump11 = 0;
		$sump12 = 0;
		$sump13 = 0;
		$sump14 = 0;
		for($con = 0;$con < sizeof($contypechk) ; $con++){
			//แสดงประเภทอยู่ด้านบนข้อมูล
			echo "<tr bgcolor=\"#FFD39B\"><td colspan=\"17\"><b>$contypechk[$con]</b></td></tr>";
			
			$i=0;
			$sumprinciple0 = 0;	
			$sumprinciple = 0;
			$sumprinciple2 = 0;
			$sumprinciple3 = 0;
			$sumprinciple4 = 0;
			$sumprinciple5 = 0;
			$sumprinciple6 = 0;
			$sumprinciple7 = 0;
			$sumprinciple8 = 0;
			$sumprinciple9 = 0;
			$sumprinciple10 = 0;
			$sumprinciple11 = 0;
			$sumprinciple12 = 0;
			$sumprinciple13 = 0;
			$chk=0; //สำหรับตรวจสอบว่าสัญญาประเภทนั้นมีข้อมูลหรือไม่
			
			//แสดงทุำกสัญญา
			$qrymg=pg_query("	SELECT 
									\"contractID\" 
								FROM 
									thcap_contract 
								WHERE 
									(\"conClosedDate\" is NULL OR \"conClosedDate\" > '$nowdate') AND 
									\"conDate\" <= '$nowdate' AND 
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
				
				//หาเงินต้นคงเหลือของแต่ละสัญญา LOAN ด้วย function thcap_getPrinciple
				$qryprinciple=pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$nowdate')");
				list($principle)=pg_fetch_array($qryprinciple);

				if($principle > '0'){ //ไม่ต้องนำค่าที่เป็น 0.00 มาแสดง
					$chk+=1;
					
					//หาชื่อลูกหนี้
					$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
					list($cusname)=pg_fetch_array($qryname);

					// ชื่อประเภทสินเชื่อแบบเต็ม
					$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contractID') ");
					list($contype) = pg_fetch_array($qry_chk_con_type);
					
					//หาชื่อลูกหนี้
					$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
					list($cusname)=pg_fetch_array($qryname);
					

					
					//นำเข้า function เพื่อหาวันที่ค้าง
					if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
						$qrybackduedate=pg_query("SELECT ('$nowdate'-\"thcap_backDueDate\"('$contractID','$nowdate'))+1");
						list($backduedate)=pg_fetch_array($qrybackduedate);
						$nubdate = $backduedate;
					}else if($contype=='LEASING' OR $contype=='HIRE_PURCHASE'){
						$backduedate=1; //จะไม่เข้าเงื่อนไข $backduedate=="" เนื่องจากของสัญญาประเภทนี้ไม่ได้หาค่า $backduedate
						$qrybackdueday=pg_query("SELECT \"thcap_get_lease_backdays\"('$contractID','$nowdate','1')");
						list($nubdate)=pg_fetch_array($qrybackdueday);
					}
					if($backduedate==""){ //ไม่พบวันค้างชำระ
						$condition="00";
						$principle0=$principle;
						if($principle0!=""){
							$principle000=number_format($principle0,2);
						}else{
							$principle000="";
						}
					}else{						
						if($nubdate == 0){
							$condition="00";
							$principle0=$principle;
							if($principle0!=""){
								$principle000=number_format($principle0,2);
							}else{
								$principle000="";
							}
						}else if($nubdate>=1 and $nubdate <31){
							$condition="01-30";
							$principle1=$principle;
							if($principle1!=""){
								$principle101=number_format($principle1,2);
							}else{
								$principle101="";
							}
						}else if($nubdate>30 and $nubdate <61){
							$condition="31-60";
							$principle2=$principle;
							if($principle2!=""){
								$principle22=number_format($principle2,2);
							}else{
								$principle22="";
							}
						}else if($nubdate>60 and $nubdate <91){
							$condition="61-90";
							$principle3=$principle;
							if($principle3!=""){
								$principle33=number_format($principle3,2);
							}else{
								$principle33="";
							}
						}else if($nubdate>90 and $nubdate <121){
							$condition="91-120";
							$principle4=$principle;
							if($principle4!=""){
								$principle44=number_format($principle4,2);
							}else{
								$principle44="";
							}
						}else if($nubdate>120 and $nubdate <151){
							$condition="121-150";
							$principle5=$principle;
							if($principle5!=""){
								$principle55=number_format($principle5,2);
							}else{
								$principle55="";
							}
						}else if($nubdate>150 and $nubdate <181){
							$condition="151-180";
							$principle6=$principle;
							if($principle6!=""){
								$principle66=number_format($principle6,2);
							}else{
								$principle66="";
							}
						}else if($nubdate>180 and $nubdate <211){
							$condition="181-210";
							$principle7=$principle;
							if($principle7!=""){
								$principle77=number_format($principle7,2);
							}else{
								$principle77="";
							}
						}else if($nubdate>210 and $nubdate <241){
							$condition="211-240";
							$principle8=$principle;
							if($principle8!=""){
								$principle88=number_format($principle8,2);
							}else{
								$principle88="";
							}
						}else if($nubdate>240 and $nubdate <271){
							$condition="241-270";
							$principle9=$principle;	
							if($principle9!=""){
								$principle99=number_format($principle9,2);
							}else{
								$principle99="";
							}
						}else if($nubdate>270 and $nubdate <301){
							$condition="271-300";
							$principle10=$principle;
							if($principle10!=""){
								$principle100=number_format($principle10,2);
							}else{
								$principle100="";
							}									
						}else if($nubdate>300 and $nubdate <331){
							$condition="301-330";
							$principle11=$principle;
							if($principle11!=""){
								$principle111=number_format($principle11,2);
							}else{
								$principle111="";
							}									
						}else if($nubdate>330 and $nubdate <361){
							$condition="331-360";
							$principle12=$principle;	
							if($principle12!=""){
								$principle122=number_format($principle12,2);
							}else{
								$principle122="";
							}
						}else if($nubdate>360){
							$condition="361";
							$principle13=$principle;
							if($principle13!=""){
								$principle133=number_format($principle13,2);
							}else{
								$principle133="";
							}									
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
						<td align=right>
					";
						if($condition=="00"){echo $principle000;}else{ $principle0=0;}
						echo "</td><td align=right>";
						if($condition=="01-30"){echo $principle101;}else{ $principle1=0;}
						echo "</td><td align=right>";
						if($condition=="31-60"){echo $principle22;}else{ $principle2=0;}
						echo "</td><td align=right>";
						if($condition=="61-90"){echo $principle33;}else{ $principle3=0;}
						echo "</td><td align=right>";
						if($condition=="91-120"){echo $principle44;}else{ $principle4=0;}
						echo "</td><td align=right>";
						if($condition=="121-150"){echo $principle55;}else{ $principle5=0;}
						echo "</td><td align=right>";
						if($condition=="151-180"){echo $principle66;}else{ $principle6=0;}
						echo "</td><td align=right>";
						if($condition=="181-210"){echo $principle77;}else{ $principle7=0;}
						echo "</td><td align=right>";
						if($condition=="211-240"){echo $principle88;}else{ $principle8=0;}
						echo "</td><td align=right>";
						if($condition=="241-270"){echo $principle99;}else{ $principle9=0;}
						echo "</td><td align=right>";
						if($condition=="271-300"){echo $principle100;}else{ $principle10=0;}
						echo "</td><td align=right>";
						if($condition=="301-330"){echo $principle111;}else{ $principle11=0;}
						echo "</td><td align=right>";
						if($condition=="331-360"){echo $principle122;}else{ $principle12=0;}
						echo "</td><td align=right>";
						if($condition=="361"){echo $principle133;}else{ $principle13=0;}
						echo "</td></tr>";
						
					$allsum+=$principle;
					$sumprinciple0+=$principle0;
					$sumprinciple+=$principle1;
					$sumprinciple2+=$principle2;
					$sumprinciple3+=$principle3;
					$sumprinciple4+=$principle4;
					$sumprinciple5+=$principle5;
					$sumprinciple6+=$principle6;
					$sumprinciple7+=$principle7;
					$sumprinciple8+=$principle8;
					$sumprinciple9+=$principle9;
					$sumprinciple10+=$principle10;
					$sumprinciple11+=$principle11;
					$sumprinciple12+=$principle12;
					$sumprinciple13+=$principle13;
					
					$sump1+=$principle0;
					$sump2+=$principle1;
					$sump3+=$principle2;
					$sump4+=$principle3;
					$sump5+=$principle4;
					$sump6+=$principle5;
					$sump7+=$principle6;
					$sump8+=$principle7;
					$sump9+=$principle8;
					$sump10+=$principle9;
					$sump11+=$principle10;
					$sump12+=$principle11;
					$sump13+=$principle12;
					$sump14+=$principle13;
					
					if($sumprinciple0!=""){$sumprincipleshow0=number_format($sumprinciple0,2);}else{$sumprincipleshow0="-";}
					if($sumprinciple!=""){$sumprincipleshow=number_format($sumprinciple,2);}else{$sumprincipleshow="-";}
					if($sumprinciple2!=""){$sumprincipleshow2=number_format($sumprinciple2,2);}else{$sumprincipleshow2="-";}
					if($sumprinciple3!=""){$sumprincipleshow3=number_format($sumprinciple3,2);}else{$sumprincipleshow3="-";}
					if($sumprinciple4!=""){$sumprincipleshow4=number_format($sumprinciple4,2);}else{$sumprincipleshow4="-";}
					if($sumprinciple5!=""){$sumprincipleshow5=number_format($sumprinciple5,2);}else{$sumprincipleshow5="-";}
					if($sumprinciple6!=""){$sumprincipleshow6=number_format($sumprinciple6,2);}else{$sumprincipleshow6="-";}
					if($sumprinciple7!=""){$sumprincipleshow7=number_format($sumprinciple7,2);}else{$sumprincipleshow7="-";}
					if($sumprinciple8!=""){$sumprincipleshow8=number_format($sumprinciple8,2);}else{$sumprincipleshow8="-";}
					if($sumprinciple9!=""){$sumprincipleshow9=number_format($sumprinciple9,2);}else{$sumprincipleshow9="-";}
					if($sumprinciple10!=""){$sumprincipleshow10=number_format($sumprinciple10,2);}else{$sumprincipleshow10="-";}
					if($sumprinciple11!=""){$sumprincipleshow11=number_format($sumprinciple11,2);}else{$sumprincipleshow11="-";}
					if($sumprinciple12!=""){$sumprincipleshow12=number_format($sumprinciple12,2);}else{$sumprincipleshow12="-";}
					if($sumprinciple13!=""){$sumprincipleshow13=number_format($sumprinciple13,2);}else{$sumprincipleshow13="-";}
				
					unset($condition);
					unset($principle);
					unset($nubdate);
					
				} //end if
			}
			
			//กรณีประเภทใดไม่มีข้อมูลให้สรุปว่า ไม่มีข้อมูลก่อนขึ้นประเภทใหม่
			if($numcontract==0 or $chk==0){
				echo "<tr><td colspan=17 height=30 align=center><b>--ไม่พบข้อมูล--</b></td></tr>";
			}else{	
				echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>รวมของสัญญาประเภท $contypechk[$con]</b></td>";
				echo "<td>$sumprincipleshow0</td>";
				echo "<td>$sumprincipleshow</td>";
				echo "<td>$sumprincipleshow2</td>";
				echo "<td>$sumprincipleshow3</td>";
				echo "<td>$sumprincipleshow4</td>";
				echo "<td>$sumprincipleshow5</td>";
				echo "<td>$sumprincipleshow6</td>";
				echo "<td>$sumprincipleshow7</td>";
				echo "<td>$sumprincipleshow8</td>";
				echo "<td>$sumprincipleshow9</td>";
				echo "<td>$sumprincipleshow10</td>";
				echo "<td>$sumprincipleshow11</td>";
				echo "<td>$sumprincipleshow12</td>";
				echo "<td>$sumprincipleshow13</td><tr>";
			}
			$sumprincipleshow0 = 0;	
			$sumprincipleshow = 0;
			$sumprincipleshow2 = 0;
			$sumprincipleshow3 = 0;
			$sumprincipleshow4 = 0;
			$sumprincipleshow5 = 0;
			$sumprincipleshow6 = 0;
			$sumprincipleshow7 = 0;
			$sumprincipleshow8 = 0;
			$sumprincipleshow9 = 0;
			$sumprincipleshow10 = 0;
			$sumprincipleshow11 = 0;
			$sumprincipleshow12 = 0;
			$sumprincipleshow13 = 0;			
		}//end ประเภทสัญญา

		?>
		<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
			<td></td>
			<td></td>
			<td></td>
			<td>ไม่ค้างชำระ</td>
			<td>01-30</td>
			<td>31-60</td>
			<td>61-90</td>
			<td>91-120</td>
			<td>121-150</td>
			<td>151-180</td>
			<td>181-210</td>
			<td>211-240</td>
			<td>241-270</td>
			<td>271-300</td>
			<td>301-330</td>
			<td>331-360</td>
			<td>เกินกว่า 360</td>
		</tr>
		<?php			
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>รวมทั้งสิ้น</b></td>";
		echo "<td>".number_format($sump1,2)."</td>";
		echo "<td>".number_format($sump2,2)."</td>";
		echo "<td>".number_format($sump3,2)."</td>";
		echo "<td>".number_format($sump4,2)."</td>";
		echo "<td>".number_format($sump5,2)."</td>";
		echo "<td>".number_format($sump6,2)."</td>";
		echo "<td>".number_format($sump7,2)."</td>";
		echo "<td>".number_format($sump8,2)."</td>";
		echo "<td>".number_format($sump9,2)."</td>";
		echo "<td>".number_format($sump10,2)."</td>";
		echo "<td>".number_format($sump11,2)."</td>";
		echo "<td>".number_format($sump12,2)."</td>";
		echo "<td>".number_format($sump13,2)."</td>";
		echo "<td>".number_format($sump14,2)."</td><tr>";
		
		if($allsum>0){ //ป้องกันการ error จากการหารด้วย 0
			$percent0 = number_format($sump1/$allsum*100,2);
			$percent1 = number_format($sump2/$allsum*100,2);
			$percent2 = number_format($sump3/$allsum*100,2);
			$percent3 = number_format($sump4/$allsum*100,2);
			$percent4 = number_format($sump5/$allsum*100,2);
			$percent5 = number_format($sump6/$allsum*100,2);
			$percent6 = number_format($sump7/$allsum*100,2);
			$percent7 = number_format($sump8/$allsum*100,2);
			$percent8 = number_format($sump9/$allsum*100,2);
			$percent9 = number_format($sump10/$allsum*100,2);
			$percent10 = number_format($sump11/$allsum*100,2);
			$percent11 = number_format($sump12/$allsum*100,2);
			$percent12 = number_format($sump13/$allsum*100,2);
			$percent13 = number_format($sump14/$allsum*100,2);
		}
		
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>สัดส่วน</b></td>";
		echo "<td>$percent0 %</td>";
		echo "<td>$percent1 %</td>";
		echo "<td>$percent2 %</td>";
		echo "<td>$percent3 %</td>";
		echo "<td>$percent4 %</td>";
		echo "<td>$percent5 %</td>";
		echo "<td>$percent6 %</td>";
		echo "<td>$percent7 %</td>";
		echo "<td>$percent8 %</td>";
		echo "<td>$percent9 %</td>";
		echo "<td>$percent10 %</td>";
		echo "<td>$percent11 %</td>";
		echo "<td>$percent12 %</td>";
		echo "<td>$percent13 %</td><tr>";	
		
		echo "<tr bgcolor=#FFCCCC><td colspan=14></td><td  align=center colspan=2><b>ลูกหนี้ทั้งสิ้น</b></td><td align=right><b>".number_format($allsum,2)."</b></td></tr>";
		
		if($numcontract==0){
			echo "<tr><td colspan=17 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
		}
		?>
		</table>
	</div>
</fieldset>
