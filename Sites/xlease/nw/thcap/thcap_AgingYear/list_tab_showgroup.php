<?php
require_once("../../../config/config.php");

$tab_id = $_GET['tabid']; //ปีที่ต้องการ
$datepicker = $_GET['datepicker'];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

//นำค่า array ของประเภทสัญญามาต่อกันเป็นเงื่อนไข เพื่อนำไปค้นหาปีที่ต้องนำมาแสดงในรายงาน
$contypeyear="";
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=''){
		if($contypeyear == ""){
			$contypeyear = "\"conType\"='$contypechk[$con]'";
		}else{
			$contypeyear = $contypeyear." OR \"conType\"='$contypechk[$con]'";
		}	
	}
}
if($contypeyear!=""){
	$contypeyear="and ($contypeyear)";
}


echo "
<table frame=\"box\" width=\"100%\" align=\"center\" border=\"0\" cellSpacing=\"1\" cellPadding=\"1\" bgcolor=\"#F0F0F0\">
	<tr bgcolor=\"#79BCFF\" align=\"center\">
		<th>ลำดับที่</th>
		<th>เลขที่สัญญา</th>
		<th>รายชื่อลูกหนี้</th>
		<th>ยังไม่ถึงกำหนดชำระ<br>(ไม่ค้างชำระ)</th>
		<th>เกินกำหนด น้อยกว่า 3 เดือน</th>
		<th>เกินกำหนด 3 เดือน - 6 เดือน</th>
		<th>เกินกำหนด 6 เดือน - 12 เดือน</th>
		<th>เกินกว่า 12 เดือน</th>
		<th>ปรับโครงสร้างหนี้</th>
		<th>อยู่ระหว่างดำเนินคดี</th>
	</tr>
";	
		
	if($tab_id==0){		
		//หาปีที่เกี่ยวข้องทั้งหมดมาแสดง
		$qry_year=pg_query("SELECT distinct(EXTRACT(YEAR FROM \"conDate\")) FROM thcap_contract 
				WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' $contypeyear
				ORDER BY EXTRACT(YEAR FROM \"conDate\")");
		while($resyear=pg_fetch_array($qry_year)){
			list($contractyear)=$resyear;
			echo "<tr bgcolor=\"#FFCCCC\" align=\"center\" height=\"30\"><td colspan=10><b>-- ปี $contractyear --</b></td></tr>";
			
			//วนตามประเภทสัญญาที่เลือก	
			$sump0 = 0;
			$sump1 = 0;	
			$sump2 = 0;
			$sump3 = 0;
			$sump4 = 0;
			$sump5 = 0;
			$sump6 = 0;
			//วนตามประเภทสัญญาที่เลือก	
			for($con = 0;$con < sizeof($contypechk) ; $con++){
				//แสดงประเภทอยู่ด้านบนข้อมูล
				echo "<tr bgcolor=\"#FFD39B\"><td colspan=\"10\"><b>$contypechk[$con]</b></td></tr>";
				
				$i=0;
				$sumprinciple0 = 0;	
				$sumprinciple1 = 0;
				$sumprinciple2 = 0;
				$sumprinciple3 = 0;
				$sumprinciple4 = 0;
				$sumprinciple5 = 0;
				$sumprinciple6 = 0;
				$chk=0; //สำหรับตรวจสอบว่าสัญญาประเภทนั้นมีข้อมูลหรือไม่
				
				//แสดงข้อมูล
				$qrymg=pg_query("SELECT \"contractID\",\"conLoanAmt\" FROM thcap_contract 
				WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' AND \"conType\" = '$contypechk[$con]' AND EXTRACT(YEAR FROM \"conDate\")='$contractyear' ORDER BY \"contractID\" ASC");
				$numcontract=pg_num_rows($qrymg);	
				
				while($result=pg_fetch_array($qrymg)){
					$contractID=$result["contractID"];
					$conLoanAmt=$result["conLoanAmt"];
									
					//หาชื่อลูกหนี้
					$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
					list($cusname)=pg_fetch_array($qryname);
					
					//หาเงินต้นคงเหลือของแต่ละสัญญา  ด้วย function thcap_getPrinciple
					$qryprinciple=pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$datepicker')");
					list($principle)=pg_fetch_array($qryprinciple);
					
					if($principle > '0'){ //ไม่ต้องนำค่าที่เป็น 0.00 มาแสดง
						$chk+=1;
						
						//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
						$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
						list($issue)=pg_fetch_array($qryissue);
						if($issue==1){
							$nubmonth='issue';
						}
						//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
						$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
						list($structure)=pg_fetch_array($qrystructure);
						if($structure==1){
							$nubmonth='structure';
						}
						
						if($issue==0 and $structure==0){
							//นำเข้า function เพื่อหาจำนวนเดือนที่ค้าง
							$qrybackduedate=pg_query("SELECT \"thcap_get_all_backmonths\"('$contractID','$datepicker')");
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
						}else if($nubmonth == 0){ //ไม่พบวันค้างชำระ
							$condition="0";
							$principle0=$principle;
							if($principle0!=""){
								$principle000=number_format($principle0,2);
							}else{
								$principle000="";
							}
						}else if($nubmonth<3){ //เกินกำหนด น้อยกว่า 3 เดือน
							$condition="1";
							$principle1=$principle;
							if($principle1!=""){
								$principle101=number_format($principle1,2);
							}else{
								$principle101="";
							}
						}else if($nubmonth>=3 and $nubmonth <=6){ //เกินกำหนด 3 เดือน - 6 เดือน
							$condition="2";
							$principle2=$principle;
							if($principle2!=""){
								$principle22=number_format($principle2,2);
							}else{
								$principle22="";
							}
						}else if($nubmonth>6 and $nubmonth <=12){ //เกินกำหนด 6 เดือน - 12 เดือน
							$condition="3";
							$principle3=$principle;
							if($principle3!=""){
								$principle33=number_format($principle3,2);
							}else{
								$principle33="";
							}
						}else if($nubmonth>12){ //เกินกว่า 12 เดือน
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
							<td><span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\">
							<u>$contractID</u></span><br></td>
							<td align=left>$cusname</td>
							<td align=right>
						";
							if($condition=="0"){echo $principle000;}else{ $principle0=0;}
							echo "</td><td align=right>";
							if($condition=="1"){echo $principle101;}else{ $principle1=0;}
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
							echo "</td><td align=right>";
							echo "</td></tr>";
							
						$allsum+=$principle;
						$sumprinciple0+=$principle0;
						$sumprinciple1+=$principle1;
						$sumprinciple2+=$principle2;
						$sumprinciple3+=$principle3;
						$sumprinciple4+=$principle4;
						$sumprinciple5+=$principle5;
						$sumprinciple6+=$principle6;
						
						$sump0+=$principle0;
						$sump1+=$principle1;
						$sump2+=$principle2;
						$sump3+=$principle3;
						$sump4+=$principle4;
						$sump5+=$principle5;
						$sump6+=$principle6;
						
						if($sumprinciple0!=""){$sumprincipleshow0=number_format($sumprinciple0,2);}else{$sumprincipleshow0="-";}
						if($sumprinciple1!=""){$sumprincipleshow1=number_format($sumprinciple1,2);}else{$sumprincipleshow="-";}
						if($sumprinciple2!=""){$sumprincipleshow2=number_format($sumprinciple2,2);}else{$sumprincipleshow2="-";}
						if($sumprinciple3!=""){$sumprincipleshow3=number_format($sumprinciple3,2);}else{$sumprincipleshow3="-";}
						if($sumprinciple4!=""){$sumprincipleshow4=number_format($sumprinciple4,2);}else{$sumprincipleshow4="-";}
						if($sumprinciple5!=""){$sumprincipleshow5=number_format($sumprinciple5,2);}else{$sumprincipleshow5="-";}
						if($sumprinciple6!=""){$sumprincipleshow6=number_format($sumprinciple6,2);}else{$sumprincipleshow6="-";}
					
						unset($condition);
						unset($principle);
						unset($nubmonth);
						
					} //end if
				}
			
				//กรณีประเภทใดไม่มีข้อมูลให้สรุปว่า ไม่มีข้อมูลก่อนขึ้นประเภทใหม่
				if($numcontract==0 or $chk==0){
					echo "<tr><td colspan=10 height=30 align=center><b>--ไม่พบข้อมูล--</b></td></tr>";
				}else{	
					echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>รวมของสัญญาประเภท $contypechk[$con]</b></td>";
					echo "<td>$sumprincipleshow0</td>";
					echo "<td>$sumprincipleshow1</td>";
					echo "<td>$sumprincipleshow2</td>";
					echo "<td>$sumprincipleshow3</td>";
					echo "<td>$sumprincipleshow4</td>";
					echo "<td>$sumprincipleshow5</td>";
					echo "<td>$sumprincipleshow6</td></tr>";
				}
				$sumprincipleshow0 = 0;	
				$sumprincipleshow1 = 0;
				$sumprincipleshow2 = 0;
				$sumprincipleshow3 = 0;
				$sumprincipleshow4 = 0;
				$sumprincipleshow5 = 0;
				$sumprincipleshow6 = 0;
			}//ปิดการวนประเภทสัญญา
			?>
			<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
				<td></td>
				<td></td>
				<td></td>
				<th>ยังไม่ถึงกำหนดชำระ<br>(ไม่ค้างชำระ)</th>
				<th>เกินกำหนด น้อยกว่า 3 เดือน</th>
				<th>เกินกำหนด 3 เดือน - 6 เดือน</th>
				<th>เกินกำหนด 6 เดือน - 12 เดือน</th>
				<th>เกินกว่า 12 เดือน</th>
				<th>ปรับโครงสร้างหนี้</th>
				<th>อยู่ระหว่างดำเนินคดี</th>
			</tr>
			<?php			
			echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>รวมทั้งสิ้น</b></td>";
			echo "<td>".number_format($sump0,2)."</td>";
			echo "<td>".number_format($sump1,2)."</td>";
			echo "<td>".number_format($sump2,2)."</td>";
			echo "<td>".number_format($sump3,2)."</td>";
			echo "<td>".number_format($sump4,2)."</td>";
			echo "<td>".number_format($sump5,2)."</td>";
			echo "<td>".number_format($sump6,2)."</td>";
			
			if($allsum>0){ //ป้องกันการ error จากการหารด้วย 0
				$percent0 = number_format($sump0/$allsum*100,2);
				$percent1 = number_format($sump1/$allsum*100,2);
				$percent2 = number_format($sump2/$allsum*100,2);
				$percent3 = number_format($sump3/$allsum*100,2);
				$percent4 = number_format($sump4/$allsum*100,2);
				$percent5 = number_format($sump5/$allsum*100,2);
				$percent6 = number_format($sump6/$allsum*100,2);
			}
			
			echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>สัดส่วน</b></td>";
			echo "<td>$percent0 %</td>";
			echo "<td>$percent1 %</td>";
			echo "<td>$percent2 %</td>";
			echo "<td>$percent3 %</td>";
			echo "<td>$percent4 %</td>";
			echo "<td>$percent5 %</td>";
			echo "<td>$percent6 %</td>";
			
			echo "<tr bgcolor=#FFF0F5><td colspan=7></td><td  align=center colspan=2><b>ปี $contractyear ลูกหนี้ทั้งสิ้น</b></td><td align=right><b>".number_format($allsum,2)."</b></td></tr>";
			
			//clear data before next year
			unset($percent0);
			unset($percent1);
			unset($percent2);
			unset($percent3);
			unset($percent4);
			unset($percent5);
			unset($percent6);
			unset($allsum);
		}//ปิืดการวนปี
	}else{ //กรณีมีการเลือกปี
		//วนตามประเภทสัญญาที่เลือก	
		$sump0 = 0;
		$sump1 = 0;	
		$sump2 = 0;
		$sump3 = 0;
		$sump4 = 0;
		$sump5 = 0;
		$sump6 = 0;
		//วนตามประเภทสัญญาที่เลือก	
		for($con = 0;$con < sizeof($contypechk) ; $con++){
			//แสดงประเภทอยู่ด้านบนข้อมูล
			echo "<tr bgcolor=\"#FFD39B\"><td colspan=\"10\"><b>$contypechk[$con]</b></td></tr>";
			
			$i=0;
			$sumprinciple0 = 0;	
			$sumprinciple1 = 0;
			$sumprinciple2 = 0;
			$sumprinciple3 = 0;
			$sumprinciple4 = 0;
			$sumprinciple5 = 0;
			$sumprinciple6 = 0;
			$chk=0; //สำหรับตรวจสอบว่าสัญญาประเภทนั้นมีข้อมูลหรือไม่
			
			//แสดงข้อมูล
			$qrymg=pg_query("SELECT \"contractID\",\"conLoanAmt\" FROM thcap_contract 
			WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' AND \"conType\" = '$contypechk[$con]' AND EXTRACT(YEAR FROM \"conDate\")='$tab_id' ORDER BY \"contractID\" ASC");
			$numcontract=pg_num_rows($qrymg);	
			
			while($result=pg_fetch_array($qrymg)){
				$contractID=$result["contractID"];
				$conLoanAmt=$result["conLoanAmt"];
								
				//หาชื่อลูกหนี้
				$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
				list($cusname)=pg_fetch_array($qryname);
				
				//หาเงินต้นคงเหลือของแต่ละสัญญา  ด้วย function thcap_getPrinciple
				$qryprinciple=pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$datepicker')");
				list($principle)=pg_fetch_array($qryprinciple);
				
				if($principle > '0'){ //ไม่ต้องนำค่าที่เป็น 0.00 มาแสดง
					$chk+=1;
					
					//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
					$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
					list($issue)=pg_fetch_array($qryissue);
					if($issue==1){
						$nubmonth='issue';
					}
					//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
					$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
					list($structure)=pg_fetch_array($qrystructure);
					if($structure==1){
						$nubmonth='structure';
					}
					
					if($issue==0 and $structure==0){
						//นำเข้า function เพื่อหาจำนวนเดือนที่ค้าง
						$qrybackduedate=pg_query("SELECT \"thcap_get_all_backmonths\"('$contractID','$datepicker')");
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
					}else if($nubmonth == 0){ //ไม่พบวันค้างชำระ
						$condition="0";
						$principle0=$principle;
						if($principle0!=""){
							$principle000=number_format($principle0,2);
						}else{
							$principle000="";
						}
					}else if($nubmonth<3){ //เกินกำหนด น้อยกว่า 3 เดือน
						$condition="1";
						$principle1=$principle;
						if($principle1!=""){
							$principle101=number_format($principle1,2);
						}else{
							$principle101="";
						}
					}else if($nubmonth>=3 and $nubmonth <=6){ //เกินกำหนด 3 เดือน - 6 เดือน
						$condition="2";
						$principle2=$principle;
						if($principle2!=""){
							$principle22=number_format($principle2,2);
						}else{
							$principle22="";
						}
					}else if($nubmonth>6 and $nubmonth <=12){ //เกินกำหนด 6 เดือน - 12 เดือน
						$condition="3";
						$principle3=$principle;
						if($principle3!=""){
							$principle33=number_format($principle3,2);
						}else{
							$principle33="";
						}
					}else if($nubmonth>12){ //เกินกว่า 12 เดือน
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
						<td><span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\">
						<u>$contractID</u></span><br></td>
						<td align=left>$cusname</td>
						<td align=right>
					";
						if($condition=="0"){echo $principle000;}else{ $principle0=0;}
						echo "</td><td align=right>";
						if($condition=="1"){echo $principle101;}else{ $principle1=0;}
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
						echo "</td><td align=right>";
						echo "</td></tr>";
						
					$allsum+=$principle;
					$sumprinciple0+=$principle0;
					$sumprinciple1+=$principle1;
					$sumprinciple2+=$principle2;
					$sumprinciple3+=$principle3;
					$sumprinciple4+=$principle4;
					$sumprinciple5+=$principle5;
					$sumprinciple6+=$principle6;
					
					$sump0+=$principle0;
					$sump1+=$principle1;
					$sump2+=$principle2;
					$sump3+=$principle3;
					$sump4+=$principle4;
					$sump5+=$principle5;
					$sump6+=$principle6;
					
					if($sumprinciple0!=""){$sumprincipleshow0=number_format($sumprinciple0,2);}else{$sumprincipleshow0="-";}
					if($sumprinciple1!=""){$sumprincipleshow1=number_format($sumprinciple1,2);}else{$sumprincipleshow="-";}
					if($sumprinciple2!=""){$sumprincipleshow2=number_format($sumprinciple2,2);}else{$sumprincipleshow2="-";}
					if($sumprinciple3!=""){$sumprincipleshow3=number_format($sumprinciple3,2);}else{$sumprincipleshow3="-";}
					if($sumprinciple4!=""){$sumprincipleshow4=number_format($sumprinciple4,2);}else{$sumprincipleshow4="-";}
					if($sumprinciple5!=""){$sumprincipleshow5=number_format($sumprinciple5,2);}else{$sumprincipleshow5="-";}
					if($sumprinciple6!=""){$sumprincipleshow6=number_format($sumprinciple6,2);}else{$sumprincipleshow6="-";}
				
					unset($condition);
					unset($principle);
					unset($nubmonth);
					
				} //end if
			}
		
			//กรณีประเภทใดไม่มีข้อมูลให้สรุปว่า ไม่มีข้อมูลก่อนขึ้นประเภทใหม่
			if($numcontract==0 or $chk==0){
				echo "<tr><td colspan=10 height=30 align=center><b>--ไม่พบข้อมูล--</b></td></tr>";
			}else{	
				echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>รวมของสัญญาประเภท $contypechk[$con]</b></td>";
				echo "<td>$sumprincipleshow0</td>";
				echo "<td>$sumprincipleshow1</td>";
				echo "<td>$sumprincipleshow2</td>";
				echo "<td>$sumprincipleshow3</td>";
				echo "<td>$sumprincipleshow4</td>";
				echo "<td>$sumprincipleshow5</td>";
				echo "<td>$sumprincipleshow6</td></tr>";
			}
			$sumprincipleshow0 = 0;	
			$sumprincipleshow1 = 0;
			$sumprincipleshow2 = 0;
			$sumprincipleshow3 = 0;
			$sumprincipleshow4 = 0;
			$sumprincipleshow5 = 0;
			$sumprincipleshow6 = 0;
		}//ปิดการวนประเภทสัญญา
		?>
		<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
			<td></td>
			<td></td>
			<td></td>
			<th>ยังไม่ถึงกำหนดชำระ<br>(ไม่ค้างชำระ)</th>
			<th>เกินกำหนด น้อยกว่า 3 เดือน</th>
			<th>เกินกำหนด 3 เดือน - 6 เดือน</th>
			<th>เกินกำหนด 6 เดือน - 12 เดือน</th>
			<th>เกินกว่า 12 เดือน</th>
			<th>ปรับโครงสร้างหนี้</th>
			<th>อยู่ระหว่างดำเนินคดี</th>
		</tr>
		<?php			
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>รวมทั้งสิ้น</b></td>";
		echo "<td>".number_format($sump0,2)."</td>";
		echo "<td>".number_format($sump1,2)."</td>";
		echo "<td>".number_format($sump2,2)."</td>";
		echo "<td>".number_format($sump3,2)."</td>";
		echo "<td>".number_format($sump4,2)."</td>";
		echo "<td>".number_format($sump5,2)."</td>";
		echo "<td>".number_format($sump6,2)."</td>";
		
		if($allsum>0){ //ป้องกันการ error จากการหารด้วย 0
			$percent0 = number_format($sump0/$allsum*100,2);
			$percent1 = number_format($sump1/$allsum*100,2);
			$percent2 = number_format($sump2/$allsum*100,2);
			$percent3 = number_format($sump3/$allsum*100,2);
			$percent4 = number_format($sump4/$allsum*100,2);
			$percent5 = number_format($sump5/$allsum*100,2);
			$percent6 = number_format($sump6/$allsum*100,2);
		}
		
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=3 align=center><b>สัดส่วน</b></td>";
		echo "<td>$percent0 %</td>";
		echo "<td>$percent1 %</td>";
		echo "<td>$percent2 %</td>";
		echo "<td>$percent3 %</td>";
		echo "<td>$percent4 %</td>";
		echo "<td>$percent5 %</td>";
		echo "<td>$percent6 %</td>";
		
		echo "<tr bgcolor=#FFCCCC><td colspan=7></td><td  align=center colspan=2><b>ปี $tab_id ลูกหนี้ทั้งสิ้น</b></td><td align=right><b>".number_format($allsum,2)."</b></td></tr>";
		
		//clear data before next year
		unset($percent0);
		unset($percent1);
		unset($percent2);
		unset($percent3);
		unset($percent4);
		unset($percent5);
		unset($percent6);
		unset($allsum);		
	}
	echo"
	</tr>	  
</table>
";
?>