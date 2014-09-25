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
			<th>ลำดับที่</th>
			<th>เลขที่สัญญา</th>
			<th>ลูกหนี้</th>
			<th bgcolor=#F6CEF5>วันที่เริ่มผิดนัดชำระ</th>
			<th>ยังไม่ถึงกำหนดชำระ<br>(ไม่ค้างชำระ)</th>
			<th>เกินกำหนด น้อยกว่า 3 เดือน</th>
			<th>เกินกำหนด 3 เดือน - 6 เดือน</th>
			<th>เกินกำหนด 6 เดือน - 12 เดือน</th>
			<th>เกินกว่า 12 เดือน</th>
			<th>ปรับโครงสร้างหนี้</th>
			<th>ดำเนินคดี</th>

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
		for($con = 0;$con < sizeof($contypechk) ; $con++){
			//แสดงประเภทอยู่ด้านบนข้อมูล
			echo "<tr bgcolor=\"#FFD39B\"><td colspan=\"11\"><b>$contypechk[$con]</b></td></tr>";
			
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
			$qrymg=pg_query("	SELECT 
									\"contractID\"
								FROM 
									thcap_contract
								WHERE 
									(\"conClosedDate\" is NULL OR \"conClosedDate\" > '$nowdate') AND 
									\"conStartDate\" <= '$nowdate' AND 
									\"conType\" = '$contypechk[$con]' AND
									\"conCredit\" IS NULL
								ORDER BY \"contractID\" ASC
			");
			$numcontract=pg_num_rows($qrymg);			
			while($result=pg_fetch_array($qrymg)){
				$contractID=$result["contractID"];
				
				
				
				// กำหนดค่าเริ่มต้น และล้างค่ารอบก่อน
				$principle = 0.00;
				$principle0 = 0.00;
				$principle1 = 0.00;
				$principle2 = 0.00;
				$principle3 = 0.00;
				$principle4 = 0.00;
				$principle5 = 0.00;
				$principle6 = 0.00;
				$backamt_all = 0.00;
				$defaultdate = "";

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
					
					// หาประเภทสัญญา
					$sql_tpye_func = pg_query("select \"thcap_get_creditType\"('$contractID')");
					$type_func = pg_fetch_array($sql_tpye_func);
					list($credittype) = $type_func;

					// หาวันที่เริ่มค้างชำระ
					$qry_defaultdate=pg_query("SELECT \"thcap_get_all_backdate_db\"('$contractID','$nowdate')");
					list($defaultdate)=pg_fetch_array($qry_defaultdate);
					
					// หาวันที่สิ้นสุดสัญญา
					$qry_conenddate=pg_query("SELECT \"thcap_get_conEndDate\"('$contractID','$nowdate')");
					list($conenddate)=pg_fetch_array($qry_conenddate);
					
					// จำนวนหนี้ที่ค้างชำระ (ก่อนภาษีมูลค่าเพิ่ม)
					if (		$credittype == "LOAN" OR 
								$credittype == "JOINT_VENTURE" OR
								$credittype == "PERSONAL_LOAN"){
								
						// หายอดค้างชำระทั้งหมด
						$qry_backamt_all=pg_query("SELECT \"thcap_get_all_backamt_db\"('$contractID','$nowdate')");
						list($backamt_all)=pg_fetch_array($qry_backamt_all);
						
						// หาจำนวนเงินที่ถึงกำหนดชำระใน 3 งวดล่าสุดที่เพิ่งผ่านมา เนื่องจากจะต้องเป็นจำนวนเต็มเสมอถึงค้างได้เกิน 3 เดือน
						$qry_debtamt_3m=pg_query("	
													SELECT
														SUM(\"ptMinPay\")
													FROM
														account.\"thcap_mg_payTerm\"
													WHERE
														\"contractID\" = '$contractID' AND
														\"ptDate\" >= ('$nowdate'::date - INTERVAL '3 month')::date AND
														\"ptDate\" <= '$nowdate'
						");
						list($debtamt_3m)=pg_fetch_array($qry_debtamt_3m);
						
						$qry_debtamt_6m=pg_query("	
													SELECT
														SUM(\"ptMinPay\")
													FROM
														account.\"thcap_mg_payTerm\"
													WHERE
														\"contractID\" = '$contractID' AND
														\"ptDate\" >= ('$nowdate'::date - INTERVAL '6 month')::date AND
														\"ptDate\" < ('$nowdate'::date - INTERVAL '3 month')::date
						");
						list($debtamt_6m)=pg_fetch_array($qry_debtamt_6m);
						
						$qry_debtamt_12m=pg_query("	
													SELECT
														SUM(\"ptMinPay\")
													FROM
														account.\"thcap_mg_payTerm\"
													WHERE
														\"contractID\" = '$contractID' AND
														\"ptDate\" >= ('$nowdate'::date - INTERVAL '12 month')::date AND
														\"ptDate\" < ('$nowdate'::date - INTERVAL '6 month')::date
						");
						list($debtamt_12m)=pg_fetch_array($qry_debtamt_12m);
						
					} else if (	$credittype == "HIRE_PURCHASE" OR 
								$credittype == "LEASING" OR
								$credittype == "SALE_ON_CONSIGNMENT" OR 
								$credittype == "FACTORING" OR 
								$credittype == "PROMISSORY_NOTE" OR
								$credittype == "GUARANTEED_INVESTMENT"){
						
						// หายอดค้างชำระทั้งหมด
						$qry_backamt_all=pg_query("	
														SELECT
															SUM(\"debtnet\")
														FROM
															account.\"thcap_acc_filease_realize_eff_acc_present_y\"
														WHERE
															\"contractID\" = '$contractID' AND
															\"duedate\" <= '$nowdate' AND
															(\"receiveDate\" IS NULL OR \"receiveDate\" > '$nowdate') -- ยอดที่ยังไม่ชำระ หรือชำระหลังจากวันดังกล่าว คือยอดที่ ณ วันนั้นยังค้างชำระ
						");
						list($backamt_all)=pg_fetch_array($qry_backamt_all);
						
						// หาจำนวนเงินที่ถึงกำหนดชำระใน 3 งวดล่าสุดที่เพิ่งผ่านมา เนื่องจากจะต้องเป็นจำนวนเต็มเสมอถึงค้างได้เกิน 3 เดือน
						$qry_debtamt_3m=pg_query("	
													SELECT
														SUM(\"debtnet\")
													FROM
														account.\"thcap_acc_filease_realize_eff_acc_present_y\"
													WHERE
														\"contractID\" = '$contractID' AND
														\"duedate\" >= ('$nowdate'::date - INTERVAL '3 month')::date AND
														\"duedate\" <= '$nowdate'
						");
						list($debtamt_3m)=pg_fetch_array($qry_debtamt_3m);
						
						$qry_debtamt_6m=pg_query("	
													SELECT
														SUM(\"debtnet\")
													FROM
														account.\"thcap_acc_filease_realize_eff_acc_present_y\"
													WHERE
														\"contractID\" = '$contractID' AND
														\"duedate\" >= ('$nowdate'::date - INTERVAL '6 month')::date AND
														\"duedate\" < ('$nowdate'::date - INTERVAL '3 month')::date
						");
						list($debtamt_6m)=pg_fetch_array($qry_debtamt_6m);
						
						$qry_debtamt_12m=pg_query("	
													SELECT
														SUM(\"debtnet\")
													FROM
														account.\"thcap_acc_filease_realize_eff_acc_present_y\"
													WHERE
														\"contractID\" = '$contractID' AND
														\"duedate\" >= ('$nowdate'::date - INTERVAL '12 month')::date AND
														\"duedate\" < ('$nowdate'::date - INTERVAL '6 month')::date
						");
						list($debtamt_12m)=pg_fetch_array($qry_debtamt_12m);
					}

					// ตั้งค่าเริ่มต้นสำหรับสถานะว่าสัญญาดังกล่าวนี้ อยู่ระหว่างฟ้อง หรือภายหลังปรับโครงสร้างหนี้แล้วหรือไม่
					$issue = 0;
					$structure = 0;
						
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
					
					if(	$issue==0 or 
						($structure==1 && $issue==1) // [เงื่อนไขพิเศษ] กรณีที่ในตัวเลือกนี้หากเป็นสัญญาที่ปรับโครงสร้างหนี้จะให้กระจายตัวแบบปกติด้วย เสมือนว่าเป็นสัญญาทั่วไป
					){
						//นำเข้า function เพื่อหาจำนวนเดือนที่ค้าง
						$qrybackduedate=pg_query("SELECT \"thcap_get_all_backmonths_db\"('$contractID','$nowdate')");
						list($nubmonth)=pg_fetch_array($qrybackduedate);
					}

					if($nubmonth=='issue'){ // !!!!!!!!!!!!!!!!!!!! อยู่ระหว่างดำเนินคดี !!!!!!!!!!!!!!!!!!!!
						$condition="6";
						$principle6=$principle;
						
					}else if($nubmonth == 0){ //ไม่พบวันค้างชำระ แสดงว่าแสดงทั้งจำนวนเสมอ
						$condition="0";
						$principle0=$principle;

					}else if($nubmonth <= 3){ //เกินกำหนด น้อยกว่า 3 เดือน
						$condition="1";

						/* =====================================================================================================
							หาจำนวนหนี้ที่ค้างชำระ 3 เดือน และที่ยังไม่ครบกำหนดชำระดังนี้
								* จำนวนหนี้ที่ค้างชำระไม่เกิน 3 เดือน = จำนวนหนี้ส่วนที่ค้างชำระ
								* จำนวนหนี้ที่ไม่ค้างชำระ = จำนวนหนี้ทั้งหมด - จำนวนหนี้ส่วนที่ค้างชำระ
						===================================================================================================== */
						$principle0 = $principle - $backamt_all;
						$principle1 = $backamt_all;

					}else if($nubmonth > 3 and $nubmonth <= 6){ //เกินกำหนด 3 เดือน - 6 เดือน
						$condition="2";

						/* =====================================================================================================
							หาจำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 3 เดือน แต่ไม่เกิน 6 เดือน และที่ยังไม่ครบกำหนดชำระดังนี้
								เนื่องจากในลักษณะนี้จะไม่เหมือนกับลักษณะก่อนหน้าจะใช้แบบตรงๆ ตามด้านล่างนี้ไม่ได้

									- จำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 3 เดือน แต่ไม่เกิน 6 เดือน = จำนวนหนี้ส่วนที่ค้างชำระ - จำนวนหนี้ส่วนที่ค้างชำระไม่เกิน 3 เดือน
									- จำนวนหนี้ที่ค้างชำระไม่เกิน 3 เดือน = จำนวนหนี้ส่วนที่ค้างชำระไม่เกิน 3 เดือน
									- จำนวนหนี้ที่ไม่ค้างชำระ = จำนวนหนี้ทั้งหมด - จำนวนหนี้ส่วนที่ค้างชำระ

						===================================================================================================== */

						$principle0 = $principle - $backamt_all;
						$principle1 = $debtamt_3m;
						$principle2 = $backamt_all - $debtamt_3m;

					}else if($nubmonth > 6 and $nubmonth <= 12){ //เกินกำหนด 6 เดือน - 12 เดือน
						$condition="3";
						
						/* =====================================================================================================
							หาจำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 3 เดือน แต่ไม่เกิน 6 เดือน และที่ยังไม่ครบกำหนดชำระดังนี้
								เนื่องจากในลักษณะนี้จะไม่เหมือนกับลักษณะก่อนหน้าจะใช้แบบตรงๆ ตามด้านล่างนี้ไม่ได้

									- จำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 6 เดือน แต่ไม่เกิน 12 เดือน = จำนวนหนี้ส่วนที่ค้างชำระ - จำนวนหนี้ส่วนที่ค้างชำระไม่เกิน 3 เดือน - จำนวนหนี้ส่วนที่ค้างชำระรเกิน 3 เดือน -ไม่เกิน 6 เดือน
									- จำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 3 เดือน แต่ไม่เกิน 6 เดือน = จำนวนหนี้ส่วนที่ค้างชำระรเกิน 3 เดือน -ไม่เกิน 6 เดือน
									- จำนวนหนี้ที่ค้างชำระไม่เกิน 3 เดือน = จำนวนหนี้ส่วนที่ค้างชำระไม่เกิน 3 เดือน
									- จำนวนหนี้ที่ไม่ค้างชำระ = จำนวนหนี้ทั้งหมด - จำนวนหนี้ส่วนที่ค้างชำระ

						===================================================================================================== */
						
						$principle0 = $principle - $backamt_all;
						$principle1 = $debtamt_3m;
						$principle2 = $debtamt_6m;
						$principle3 = $backamt_all - $debtamt_3m - $debtamt_6m;

					}else if($nubmonth > 12){ //เกินกว่า 12 เดือน
						$condition="4";
						
						/* =====================================================================================================
							หาจำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 3 เดือน แต่ไม่เกิน 6 เดือน และที่ยังไม่ครบกำหนดชำระดังนี้
								เนื่องจากในลักษณะนี้จะไม่เหมือนกับลักษณะก่อนหน้าจะใช้แบบตรงๆ ตามด้านล่างนี้ไม่ได้

									* จำนวนหนี้ที่ค้างมากกว่า 12 เดือน = ำนวนหนี้ส่วนที่ค้างชำระ - จำนวนหนี้ส่วนที่ค้างชำระไม่เกิน 3 เดือน - จำนวนหนี้ส่วนที่ค้างชำระรเกิน 3 เดือน -ไม่เกิน 6 เดือน - จำนวนหนี้ส่วนที่ค้างชำระรเกิน 6 เดือน -ไม่เกิน 12 เดือน
									- จำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 6 เดือน แต่ไม่เกิน 12 เดือน = จำนวนหนี้ส่วนที่ค้างชำระรเกิน 6 เดือน -ไม่เกิน 12 เดือน
									- จำนวนหนี้ที่ค้างชำระ *ตั้งแต่* 3 เดือน แต่ไม่เกิน 6 เดือน = จำนวนหนี้ส่วนที่ค้างชำระรเกิน 3 เดือน -ไม่เกิน 6 เดือน
									- จำนวนหนี้ที่ค้างชำระไม่เกิน 3 เดือน = จำนวนหนี้ส่วนที่ค้างชำระไม่เกิน 3 เดือน
									- จำนวนหนี้ที่ไม่ค้างชำระ = จำนวนหนี้ทั้งหมด - จำนวนหนี้ส่วนที่ค้างชำระ

						===================================================================================================== */
						
						$principle0 = $principle - $backamt_all;
						$principle1 = $debtamt_3m;
						$principle2 = $debtamt_6m;
						$principle3 = $debtamt_12m;
						$principle4 = $backamt_all - $debtamt_3m - $debtamt_6m - $debtamt_12m;
					}
					
					// ในกรณีที่สัญญานั้นๆ ได้ครบกำหนดทั้งหมดตามสัญญาแล้ว หนี้ที่เกิดขึ้นใหม่ เช่นดอกเบี้ยค้างรับ จะต้องถูกนำไปรวมอยู่ในช่อง MIN(ช่องค้างชำระ)
					if($conenddate <= "$nowdate"){
						if($principle1 != "") {
							$principle1 += $principle0;
							$principle0 = "";
						}else if($principle2 != ""){
							$principle2 += $principle0;
							$principle0 = "";
						}else if($principle3 != ""){
							$principle3 += $principle0;
							$principle0 = "";
						}else if($principle4 != ""){
							$principle4 += $principle0;
							$principle0 = "";
						}
					}
					
					if($principle0!=""){
						$principle00=number_format($principle0,2);
					}else{
						$principle00="";
					}
					if($principle1!=""){
						$principle11=number_format($principle1,2);
					}else{
						$principle11="";
					}
					if($principle2!=""){
						$principle22=number_format($principle2,2);
					}else{
						$principle22="";
					}
					if($principle3!=""){
						$principle33=number_format($principle3,2);
					}else{
						$principle33="";
					}
					if($principle4!=""){
						$principle44=number_format($principle4,2);
					}else{
						$principle44="";
					}
					if($principle5!=""){
						$principle55=number_format($principle5,2);
					}else{
						$principle55="";
					}
					if($principle6!=""){
						$principle66=number_format($principle6,2);
					}else{
						$principle66="";
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
					
					/*
						ในกรณีที่ค้างกี่เดือนก็จะต้องแยก portion ตามจำนวนที่ค้างกระจายไปตามเดือนส่วนของหนี้นั้นๆ
					*/
					if($condition=="0" || $condition=="1" || $condition=="2" || $condition=="3" || $condition=="4"){echo $principle00;}else{ $principle0=0;}
					echo "</td><td align=right>";
					if($condition=="1" || $condition=="2" || $condition=="3" || $condition=="4"){echo $principle11;}else{ $principle1=0;}
					echo "</td><td align=right>";
					if($condition=="2" || $condition=="3"|| $condition=="4"){echo $principle22;}else{ $principle2=0;}
					echo "</td><td align=right>";
					if($condition=="3" || $condition=="4"){echo $principle33;}else{ $principle3=0;}
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
					if($sumprinciple1!=""){$sumprincipleshow1=number_format($sumprinciple1,2);}else{$sumprincipleshow1="-";}
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
				echo "<tr><td colspan=11 height=30 align=center><b>--ไม่พบข้อมูล--</b></td></tr>";
			}else{	
				echo "<tr align=right bgcolor=#ABDBFE><td colspan=4 align=center><b>รวมของสัญญาประเภท $contypechk[$con]</b></td>";
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
		}//end ประเภทสัญญา

		?>
		<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
			<td></td>
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
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=4 align=center><b>รวมทั้งสิ้น</b></td>";
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
		
		echo "<tr align=right bgcolor=#ABDBFE><td colspan=4 align=center><b>สัดส่วน</b></td>";
		echo "<td>$percent0 %</td>";
		echo "<td>$percent1 %</td>";
		echo "<td>$percent2 %</td>";
		echo "<td>$percent3 %</td>";
		echo "<td>$percent4 %</td>";
		echo "<td>$percent5 %</td>";
		echo "<td>$percent6 %</td>";
		
		echo "<tr bgcolor=#FFCCCC><td colspan=8></td><td  align=center colspan=2><b>ลูกหนี้ทั้งสิ้น</b></td><td align=right><b>".number_format($allsum,2)."</b></td></tr>";
		
		if($numcontract==0){
			echo "<tr><td colspan=11 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
		}
		?>
		</table>
	</div>
</fieldset>
