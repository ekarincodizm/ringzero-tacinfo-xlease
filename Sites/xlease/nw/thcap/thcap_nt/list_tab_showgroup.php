<?php
include("../../../config/config.php");

$nowdate = Date('Y-m-d');
$nowtime=Date('H:i:s');

$val=pg_escape_string($_REQUEST["val"]);
$tabid=pg_escape_string($_REQUEST["tabid"]);
$sort=pg_escape_string($_GET["sort"]);
$order=pg_escape_string($_GET["order"]);
if($order=="asc"){
	$order2="desc";
}else{
	$order2="asc";
}
if($tabid=="0"){
	$condition_chk="";
}
else{
	if($val=='2'){
		$condition_chk="  where (\"thcap_get_contractType\"(\"contractID\") = '$tabid' OR subStr(\"contractID\" ,0,3)='$tabid')";
	}
	else if($val=='1'){
		$condition_chk=" and (\"thcap_get_contractType\"(\"contractID\") = '$tabid' OR subStr(\"contractID\" ,0,3)='$tabid')";
	}
}

?>

<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td>
				<?php
				if($val=="1" || $val=="2"){
				?>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0" class="sort-table">
				<tr bgcolor="#FFFFFF">
					<td  align="left" colspan="9" style="font-weight:bold;">รายงานการค้างชำระ ประจำวันที่ <?php echo "$nowdate เวลา $nowtime"; ?></td>
				</tr>			
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
					<th align="center" id="t1" <?php if($sort=="1") echo "bgcolor=#ff6600";?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=1&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">เลขที่สัญญา</a></th>
					<th align="center" id="t2" <?php if($sort=="2") echo "bgcolor=#ff6600";?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=2&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">ชื่อผู้กู้หลัก</a></th>
					<th align="center" id="t3" <?php if($sort=="3" || $sort==""){echo "bgcolor=#ff6600";}else{ echo "bgcolor=\"#FFB3B3\"";}?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=3&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">จำนวนวัน<br>ที่ค้างชำระ</a></th>
					<th align="center" id="t4" <?php if($sort=="4"){echo "bgcolor=#ff6600";}else{ echo "bgcolor=\"#FFB3B3\"";}?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=4&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">วันที่เริ่ม<br>ผิดนัด</a></th>
					<th align="center" id="t5" <?php if($sort=="5"){echo "bgcolor=#ff6600";}else{ echo "bgcolor=\"#FFB3B3\"";}?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=5&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">จำนวนเงิน<br>ที่ค้างชำระ</a></th>
					<th align="center" id="t9" <?php if($sort=="9") echo "bgcolor=#ff6600";?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=9&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">เงินต้นคงเหลือ</a></th>
					<th align="center" id="t6" <?php if($sort=="6") echo "bgcolor=#ff6600";?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=6&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">วันที่จะครบกำหนด<br>ชำระถัดไป</a></th>
					<th align="center" id="t7" <?php if($sort=="7") echo "bgcolor=#ff6600";?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=7&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">จำนวนเงินที่<br>จะครบกำหนดชำระถัดไป</a></th>
					<th align="center" id="t8" <?php if($sort=="8") echo "bgcolor=#ff6600";?>><a href="frm_Index_nt1.php?val=<?php echo $val?>&pay=<?php echo $pay?>&sort=8&order=<?php echo $order2;?>&page=<?php echo $tabid;?>">สถานะ NT</a></th>
				</tr>
				<?php
				$qry_type=pg_query(" select \"conType\" from \"thcap_contract_type\"");
				while($res_type=pg_fetch_array($qry_type))
					{
					list($type)=$res_type;
						
					if($val=="1"){	//แสดงเฉพาะที่ค้าง
						$qry="select \"contractID\",\"backDueDate\",\"overdue\",\"conNumNTDays\",\"backAmt\",\"LeftPrinciple\",
						\"nextDueDate\",\"nextDueAmt\",\"periods\",\"is_overdue\",\"NT_1_Status\"   from \"thcap_nt1_waitforappv\"  
						where \"overdue\" > \"conNumNTDays\"  and \"overdue\" is not null $condition_chk and \"backAmt\" > 0 
						";
					}else{
						$qry="select \"contractID\",\"backDueDate\",\"overdue\",\"conNumNTDays\",\"backAmt\",\"LeftPrinciple\",
						\"nextDueDate\",\"nextDueAmt\",\"periods\",\"is_overdue\",\"NT_1_Status\"   from \"thcap_nt1_waitforappv\"  
						 $condition_chk ";
					}	
				
					$qry_fr=pg_query($qry);
					$nub=0;
					$i=0;
					while($res_fr=pg_fetch_array($qry_fr)){
						list($contractID,$backduedate,$nubdate,$conNumNTDays,$backAmt,$LeftPrinciple,$nextDueDate,$nextDueAmt,$periods,$is_overdue,$NT_1_Status )=$res_fr;
						
						
						
						//หาว่าเป็นสัญญาประเภทใด
						// $qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
						// list($contype)=pg_fetch_array($qrytype);
						
						//หาว่าค้างเกินกี่วันถึงจะออก NT
						// if($contype=='LOAN'){
							// $qrynt=pg_query("select \"conNumNTDays\" from thcap_mg_contract where \"contractID\"='$contractID'");
						// }else if($contype == 'LEASING' OR $contype == 'HIRE_PURCHASE' OR $contype == 'GUARANTEED_INVESTMENT'){
							// $qrynt=pg_query("select \"conNumNTDays\" from thcap_lease_contract where \"contractID\"='$contractID'");
						// }
						// list($conNumNTDays)=pg_fetch_array($qrynt);
						
						//หาวันที่เริ่มผิดนัดชำระ
						// $qrybackduedate=pg_query("SELECT \"thcap_backDueDate\"('$contractID','$nowdate')");
						// list($backduedate)=pg_fetch_array($qrybackduedate);
						
					   //##########หาจำนวนวันที่ค้างชำระ 
						// if($backduedate==""){
							// $nubdate="";
						// }else{
							// $y1=substr($nowdate,0,4);
							// $m1=substr($nowdate,5,2);
							// $d1=substr($nowdate,8,2);
													
							// $y2=substr($backduedate,0,4);
							// $m2=substr($backduedate,5,2);
							// $d2=substr($backduedate,8,2);
													
							//หาจำนวนวันที่รับชำระจาก นำ "วันปัจจุบันที่ user เลือก" - function thcap_backDueDate()
							// $result_1 = mktime(0, 0, 0, $m1, $d1, $y1); //นำวันเดือนปี 1 มาแปลงเป็นรูปแบบ Unix timestamp
							// $result_2 = mktime(0, 0, 0, $m2, $d2, $y2); //นำวันเดือนปี 2 มาแปลงเป็นรูปแบบ Unix timestamp

							// $result_date = $result_1 - $result_2; //นำวันที่มาลบกัน

							// $nubdate = $result_date / (60 * 60 * 24); //แปลงค่าเวลารูปแบบ Unix timestamp ให้เป็นจำนวนวัน 
							// $nubdate=ceil($nubdate); //ทำให้เป็นจำนวนเต็ม
						// }
						
						if(($nubdate > $conNumNTDays and $val=="1") ||  $val=="2"){ //แสดงเฉพาะวันค้างชำระที่ไม่เท่ากับ 0
							$nub+=1;
							/*
							//หาจำนวนเงินที่ค้างชำระ
							$qrybackAmt=pg_query("SELECT \"thcap_backAmt\"('$contractID','$nowdate')");
							list($backAmt)=pg_fetch_array($qrybackAmt);
					   
							//วันที่จะครบกำหนดชำระถัดไป
							$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contractID','$nowdate')");
							list($nextDueDate)=pg_fetch_array($qrynextDueDate);
						   
							//จำนวนเงินที่จะครบกำหนดชำระถัดไป
							$qrynextDueAmt=pg_query("SELECT \"thcap_nextDueAmt\"('$contractID','$nowdate')");
							list($nextDueAmt)=pg_fetch_array($qrynextDueAmt);
							
							//หาเงินต้นคงเหลือ
							$qryleftprint=pg_query("select \"LeftPrinciple\" from \"thcap_temp_int_201201\" where \"contractID\"='$contractID' order by \"lastReceiveDate\" DESC limit 1");
							list($LeftPrinciple)=pg_fetch_array($qryleftprint);
							*/
							if($sort=="" || $sort=="3"){
								$x[$i]=$nubdate; //sort ตามจำนวนวันที่ค้างชำระ
								$y[$i]=$nubdate."#".$contractID;
							}else if($sort=="1"){ 
								$x[$i]=$contractID; //sort ตามเลขที่สัญญา
								$y[$i]=$contractID."#".$contractID;
							}else if($sort=="2"){
								$x[$i]=$cusname; //sort ตามชื่อผู้กู้หลัก
								$y[$i]=$cusname."#".$contractID;
							}else if($sort=="4"){
								$x[$i]=$backduedate; //sort ตามวันที่เริ่มผิดนัด
								$y[$i]=$backduedate."#".$contractID;
							}else if($sort=="5"){
								$x[$i]=$backAmt; //sort ตามจำนวนเงินที่ค้าง
								$y[$i]=$backAmt."#".$contractID;
							}else if($sort=="6"){
								$x[$i]=$nextDueDate; //sort ตามวันที่จะครบกำหนด
								$y[$i]=$nextDueDate."#".$contractID;
							}else if($sort=="7"){
								$x[$i]=$nextDueAmt; //sort ตามจำนวนเงินที่จะครบกำหนด
								$y[$i]=$nextDueAmt."#".$contractID;
							}else if($sort=="8"){
								$x[$i]=$nubdate; //sort ตามสถานะ NT
								$y[$i]=$nubdate."#".$contractID;
							}else if($sort=="9"){
								$x[$i]=$LeftPrinciple; //sort ตามสถานะ NT
								$y[$i]=$LeftPrinciple."#".$contractID;
							}
							$i++;	
						} //end if
					}//end while ตรวจสอบค่าที่จะเอามา sort
					
					//นำ array ที่ได้มา sort
					if($order=="desc"){
						$condition="asc"; //เรียงจากมากไปน้อย
					}else{
						$condition="desc"; //เรียงจากน้อยไปมาก
					}
					
					$a=sizeof($x);
					if($condition=="asc"){ //เรียงจากน้อยไปมาก
						for($i=0;$i<$a;$i++){
							for($j=$i+1;$j<$a;$j++){
								if($x[$i]>$x[$j]){
									$temp=$x[$j];
									$x[$j]=$x[$i];
									$x[$i]=$temp;
									
									$tempy=$y[$j];
									$y[$j]=$y[$i];
									$y[$i]=$tempy;
								}
							}
						}
					}else{ //เรียงจากมากไปน้อย
						for($i=0;$i<$a;$i++){
							for($j=$i+1;$j<$a;$j++){
								if($x[$i]<$x[$j]){
									$temp=$x[$j];
									$x[$j]=$x[$i];
									$x[$i]=$temp;
									
									$tempy=$y[$j];
									$y[$j]=$y[$i];
									$y[$i]=$tempy;
								}
							}
						}
					}
					for($j=0;$j<$a;$j++){
						list($valchk,$contact)=explode("#",$y[$j]);
						/*
						หลังจากได้ contractID แล้วก็ให้ทำตามขั้นตอนปกติคือการแสดงค่า แต่การแสดงค่านั้นต้องนำ contractID ที่ได้มาหาค่าอีกรอบ
						เนื่องจากในตอนแรกนั้นเราไม่ได้ส่งค่าอื่นๆตามมาใน array ด้วย
						*/
						$type1=pg_query("select \"thcap_get_contractType\"('$contact')");
						$result=pg_fetch_array($type1);
						list($typecontact)=$result;	
						if($typecontact==$type){
						
						/*$qry_fr=pg_query("select a.\"contractID\",\"backDueDate\",(current_date-\"backDueDate\"),\"conNumNTDays\"   from \"thcap_mg_contract\" a 
						left join \"thcap_backDueDatePerDay\" b on a.\"contractID\"=b.\"contractID\" 
						where a.\"contractID\"='$contact'
						union 
						select a.\"contractID\",\"backDueDate\",(current_date-\"backDueDate\"),\"conNumNTDays\" from \"thcap_lease_contract\" a
						left join \"thcap_backDueDatePerDay\" b on a.\"contractID\"=b.\"contractID\" 
						where a.\"contractID\"='$contact'");*/
						
						$qry_fr=pg_query("select \"contractID\",\"backDueDate\",\"cus_main\",\"overdue\",\"conNumNTDays\",\"backAmt\",\"LeftPrinciple\",
						\"nextDueDate\",\"nextDueAmt\",\"periods\",\"is_overdue\",\"NT_1_Status\"   from \"thcap_nt1_waitforappv\"  
						where \"contractID\"='$contact'");
					
						
						list($contact,$backduedate,$cusname,$nubdate,$conNumNTDays,$backAmt,$LeftPrinciple,$nextDueDate,$nextDueAmt,$periods,$is_overdue,$NT_1_Status)=pg_fetch_array($qry_fr);
						//หาว่าเป็นสัญญาประเภทใด
						$qrytype=pg_query("select \"thcap_get_creditType\"('$contact')");
						list($contype)=pg_fetch_array($qrytype);
						
						//หาว่าค้างเกินกี่วันถึงจะออก NT
						// if($contype=='LOAN'){
							// $qrynt=pg_query("select \"conNumNTDays\" from thcap_mg_contract where \"contractID\"='$contact'");
						// }else if($contype == 'LEASING' OR $contype == 'HIRE_PURCHASE' OR $contype == 'GUARANTEED_INVESTMENT'){
							// $qrynt=pg_query("select \"conNumNTDays\" from thcap_lease_contract where \"contractID\"='$contact'");
						// }
						// list($conNumNTDays)=pg_fetch_array($qrynt);
	
						//หาชื่อผู้กู้หลัก
						/*$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contact' and \"CusState\"='0'");
						list($cusname)=pg_fetch_array($qryname);*/
							
						//หาวันที่เริ่มผิดนัดชำระ
						// $qrybackduedate=pg_query("SELECT \"thcap_backDueDate\"('$contact','$nowdate')");
						// list($backduedate)=pg_fetch_array($qrybackduedate);
						
						//##########หาจำนวนวันที่ค้างชำระ 
						// if($backduedate==""){
							// $nubdate="";
						// }else{
							// $y1=substr($nowdate,0,4);
							// $m1=substr($nowdate,5,2);
							// $d1=substr($nowdate,8,2);
														
							// $y2=substr($backduedate,0,4);
							// $m2=substr($backduedate,5,2);
							// $d2=substr($backduedate,8,2);
														
							//หาจำนวนวันที่รับชำระจาก นำ "วันปัจจุบันที่ user เลือก" - function thcap_backDueDate()
							// $result_1 = mktime(0, 0, 0, $m1, $d1, $y1); //นำวันเดือนปี 1 มาแปลงเป็นรูปแบบ Unix timestamp
							// $result_2 = mktime(0, 0, 0, $m2, $d2, $y2); //นำวันเดือนปี 2 มาแปลงเป็นรูปแบบ Unix timestamp

							// $result_date = $result_1 - $result_2; //นำวันที่มาลบกัน

							// $nubdate = $result_date / (60 * 60 * 24); //แปลงค่าเวลารูปแบบ Unix timestamp ให้เป็นจำนวนวัน 
							// $nubdate=ceil($nubdate); //ทำให้เป็นจำนวนเต็ม
						// }
						/*
						//หาจำนวนเงินที่ค้างชำระ
						$qrybackAmt=pg_query("SELECT \"thcap_backAmt\"('$contact','$nowdate')");
						list($backAmt)=pg_fetch_array($qrybackAmt);
					   
						//วันที่จะครบกำหนดชำระถัดไป
						$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contact','$nowdate')");
						list($nextDueDate)=pg_fetch_array($qrynextDueDate);
						   
						//จำนวนเงินที่จะครบกำหนดชำระถัดไป
						$qrynextDueAmt=pg_query("SELECT \"thcap_nextDueAmt\"('$contact','$nowdate')");
						list($nextDueAmt)=pg_fetch_array($qrynextDueAmt);
							
						//หาเงินต้นคงเหลือ
						$qryleftprint=pg_query("select \"LeftPrinciple\" from \"thcap_temp_int_201201\" where \"contractID\"='$contact' order by \"lastReceiveDate\" DESC limit 1");
						list($LeftPrinciple)=pg_fetch_array($qryleftprint);*/
						
						if(($val=="1" and $backAmt > 0) || $val=="2"){
							$i+=1;
							if($typecontactold==$typecontact){}
							else{
								echo "<tr bgcolor=\"00CC66\">";
								echo "<td colspan=9>$type</td></tr>";
								}
							$typecontactold=$typecontact;
							if($i%2==0){
								echo "<tr class=\"odd\" align=center>";
								$color="#FFCCCC";
							}else{
								echo "<tr class=\"even\" align=center>";
								$color="#FFE8E8";
							}
							echo "    
							<td height=25><span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\"><u>$contact</u></span></td>
							<td align=\"left\" width=150>$cusname</td>
							<td bgcolor=$color>$nubdate</td>
							<td bgcolor=$color>$backduedate</td>
							<td align=right bgcolor=$color>".number_format($backAmt,2)."</td>
							<td align=right bgcolor=$color>".number_format($LeftPrinciple,2)."</td>
							<td>$nextDueDate</td>
							<td align=right width=100>".number_format($nextDueAmt,2)."</td>
							";
							if($is_overdue=='1'){ //กรณีค้างเกินที่กำหนด
								//ตรวจสอบว่าเลขที่สัญญานี้รออนุมัติออก NT อยู่หรือไม่
								/*$qrychk=pg_query("select \"NT_1_Status\" from \"thcap_NT1_temp\" where \"contractID\"='$contact' and \"active\"='TRUE'");
								$numchk=pg_num_rows($qrychk);
								list($NT_1_Status)=pg_fetch_array($qrychk);
								*/
								if($NT_1_Status !=""){ //กรณีอยู่ในระหว่างดำเนินการ
									if($NT_1_Status==2){ //แสดงว่ารออนุมัติอยู่
										echo "<td width=110><font color=red>รออนุมัติ</font></td>";
									}else if($NT_1_Status==1){
										echo "<td width=110><font color=red>อนุมัติแล้วรอส่งจดหมาย</font></td>";
									}else if($NT_1_Status==0 ){ //กรณีไม่อนุมัติ อาจจะขอออก NT อีกครั้ง
										if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
											echo "<td><a onclick=\"javascript:popU('apply_nt1_loan.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><font color=red><u>ไม่อนุมัติ</u></font></a></td>";
										}else{
											echo "<td><a onclick=\"javascript:popU('pdf_nt1_loan.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><font color=red><u>ไม่อนุมัติ</u></font></a></td>";								
										}
									}
								}else{ //แสดงว่ายังไม่มีการออก NT
									if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
										echo "<td><a onclick=\"javascript:popU('apply_nt1_loan.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><u>ออก NT</u></a></td>";
									}else{
										
										/*echo "<td><a onclick=\"javascript:popU('pdf_nt1_loan.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><u>ออก NT</u></a></td>";	*/
										$chknextDueDate=pg_query("select  \"nextDueDate\" from \"thcap_nt1_waitforappv\" where \"contractID\"='$contact'");
										$result_chknextDueDate=pg_fetch_array($chknextDueDate);
										list($nextDueDate)=$result_chknextDueDate;
										if($nextDueDate==""){
											echo "<td><a onclick=\"alert('กรุณาตรวจสอบข้อมูลอีกครั้ง สัญญานี้อาจถูกปิดไปแล้ว');\" style=\"cursor: pointer;\"><u>ออก NT</u></a></td>";
										}
										else{
										$typecontract=pg_query("select \"thcap_get_contractType\"('$contact')");
										$result_typecontract=pg_fetch_array($typecontract);
										list($type_contact)=$result_typecontract;	
										if($type_contact=='FL'){
											echo "<td><a onclick=\"javascript:popU('pdf_nt1_FL.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><u>ออก NT</u></a></td>";
										}
										else if($type_contact=='HP'){
											echo "<td><a onclick=\"javascript:popU('pdf_nt1_HP.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><u>ออก NT</u></a></td>";
										
										}
										else{
											echo "<td><a onclick=\"javascript:popU('pdf_nt1_loan.php?contractID=$contact','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor: pointer;\"><u>ออก NT</u></a></td>";
										}
										}
									}
								}
							}else{
								echo "<td width=110>ยังไม่เกินเกณฑ์ <font color=red>($conNumNTDays)</font> วัน</td>";
							}
							echo "</tr>";
						}
					}
					}
					}
					if($nub > 0){
					?>
						<tr bgcolor="#FFCCCC" style="font-weight:bold;">
							<td align="left" colspan="3">ทั้งหมด <?php echo $nub; ?> รายการ</td>
							<td colspan="6" align="right"><a href="pdf_create_nt1.php?val=<?php echo $val;?>&order=<?php echo $order;?>&sort=<?php echo $sort;?>" target="_blank"><img src="images/icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
						</tr>
					<?php
					}else{
						echo "<tr><td colspan=\"6\" align=\"center\">- ไม่พบข้อมูล -</td></tr>  ";
					}
					} // end if val=1
					
					?>
					
				</table>
        </td>
    </tr>
</table>