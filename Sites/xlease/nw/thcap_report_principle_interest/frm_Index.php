<?php
include("../../config/config.php");

// ============================================================================================
// ตั้งค่าต่างๆ
// ============================================================================================
$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server 
$nowMonth = substr($nowDate,5,2); // เดือนปัจจุบัน
$nowYear = substr($nowDate,0,4); // ปีปัจจุบัน

// ============================================================================================
// รับค่าที่ผู้ใช้งานเลือก
// ============================================================================================
$click = pg_escape_string($_POST["click"]);
$contypechk = $_POST['contype']; //ประเภทสัญญาที่จะให้แสดง

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
// ============================================================================================
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($sendarray==""){
		$sendarray = pg_escape_string($contypechk[$con]);
	}else{
		$sendarray = $sendarray."@".pg_escape_string($contypechk[$con]);
	}
}

IF($click == 'yes'){
	$checkoption = pg_escape_string($_POST["op1"]);
	
	IF($checkoption == 'my'){
		$checked1 = "checked";
		$selectMonth = pg_escape_string($_POST["month"]); // เดือนที่เลือก
		$selectYear = pg_escape_string($_POST["year"]); // ปีที่เลือก
		$where = " EXTRACT(MONTH FROM \"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";
	
	}else if($checkoption == 'y'){
		$checked2 = "checked";
		$selectYear = pg_escape_string($_POST["year"]); // ปีที่เลือก
		$where = " EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";
	}
	
}else{
	if($selectMonth == ""){$selectMonth = $nowMonth;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้เดือนปัจจุบัน
	if($selectYear == ""){$selectYear = $nowYear;} // ถ้ายังไม่ได้เลือกปี ให้ใช้ปีปัจจุบัน
	$checked1 = "checked";
	$where = " EXTRACT(MONTH FROM \"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) รายงานเงินต้นดอกเบี้ยรับ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

	<link type="text/css" rel="stylesheet" href="act.css"></link>

	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			<?php if($checkoption == 'y'){ ?>
					$("#d1").hide();
					$("#d2").show();		
			<?php }else{ ?>
					$("#d1").show();
					$("#d2").show();
			<?php } ?>	
				//เมื่อกด ข้อความ  "แสดงเฉพาะ :" 
			$("#selectcontype").click(function(){
	
			var ele_contype = $("input[name=contype[]]");
			if($("#clear").val()== 'Y'){
				$("#clear").val('N');
			}
			else{
				$("#clear").val('Y');
			}
			if($("#clear").val() == 'Y')
			{  	var num=0;
			//ติ้ก ถูกทั้งหมด
				for (i=0; i< ele_contype.length; i++)
				{
					$(ele_contype[i]).attr ( "checked" ,"checked" );
				}
			}
			else
			{ 	//เอาติ้ก ถูก ออก ทั้งหมด
				for (i=0; i< ele_contype.length; i++)
				{
					$(ele_contype[i]).removeAttr('checked');
				}
			}
	
		});
			
		});
		
	
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		function option(){
			if(document.getElementById("op1").checked == true){	
				$("#d1").show();
				$("#d2").show();
			}else if(document.getElementById("op2").checked == true){	
				$("#d1").hide();
				$("#d2").show();		
			}
		}
	</script>
</head>
<body>

<center>
<h2>(THCAP) รายงานเงินต้นดอกเบี้ยรับ</h2>
</center>

<form name="frm1" method="post" action="frm_Index.php">
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>เลือกช่วงเวลา</B></legend>
					<table  width="100%" >
						<tr>
							<td align="center" height="50">
							<input type="radio" id="op1" name="op1" value="my" <?php echo "$checked1";  ?> onchange="option();">แสดงเฉพาะเดือน-ปี						
							<input type="radio" id="op2" name="op1" value="y" <?php echo "$checked2"; ?>  onchange="option();">แสดงเฉพาะปี
							</td>
						</tr>
						<tr align="center" >						
							<td>
								<span  id="d1" >
									เดือน :
									<select name="month">
										<?php
											for($m=1; $m<=12; $m++)
											{
												if(strlen($m) == 1){$m = "0".$m;}
												if($m == $selectMonth)
												{
													echo "<option value=\"$m\" selected>$m</option>";
												}
												else
												{
													echo "<option value=\"$m\">$m</option>";
												}
											}
										?>
									</select>
									&nbsp;&nbsp;&nbsp;
								</span>
								<span  id="d2" >
									ปี :
									<select name="year">
										<?php
											for($y=$nowYear; $y>=($nowYear-10); $y--)
											{
												if($y == $selectYear)
												{
													echo "<option value=\"$y\" selected>$y</option>";
												}
												else
												{
													echo "<option value=\"$y\">$y</option>";
												}
											}
										?>
									</select>
								</span>
							</td>					
						</tr>
						<tr>
							<td align="center">
								<input type="hidden" id="clear" value="Y"/>
								<span id="selectcontype" style="cursor:pointer;"><u><font color="#0000CC"><B>แสดงเฉพาะ :</B></font></u></span>
								 
									<?php 
									//แสดงประเภทสัญญา
										$qry_contype = pg_query("SELECT \"conType\" as contype FROM thcap_contract_type 
										ORDER BY contype ASC");
											$con=0;
										  while($re_contype = pg_fetch_array($qry_contype)){
											$con++;
											$contype = $re_contype['contype'];
											if($contypechk != ""){
												if(in_array($contype,$contypechk)){ $checked = "checked"; }else{ $checked = "";}
											}else{
												$checked = "checked";
											}
												echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" $checked>$contype ";
										  }			
									?>					
							</td>				
						</tr>
						<tr>
							<td align="center">
								<div style="padding-top:10px;"></div>
								<input type="hidden" name="click" value="yes">
								<input type="submit" value="ค้นหา" style="width:70px;height:30px;">		
							</td>
						</tr>
					</table>
			</fieldset>
			
			<?php
				if($click == "yes")
				{
			?>
					<table width="100%">
						<tr >
							<td align="right" colspan="6">
								<input type="button" id="btnprint" value="พิมพ์ PDF" onclick="javascript:popU('frm_pdf.php?op1=<?php echo "$checkoption"; ?>&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>&contype=<?php echo $sendarray; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
								<input type="button" id="btnprintex" value="พิมพ์ EXCEL" onclick="javascript:popU('frm_excel.php?op1=<?php echo "$checkoption"; ?>&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>&contype=<?php echo $sendarray; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">														
								<input type="button" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?op1=<?php echo "$checkoption"; ?>&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>&contype=<?php echo $sendarray; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1160,height=800')" style="cursor:pointer;">
							</td>
						</tr>
						<tr align="center" bgcolor="#79BCFF">
							<th>วันที่รับชำระ</th>
							<th>เลขที่สัญญา</th>
							<th>เลขที่ใบเสร็จ</th>
							<th>จำนวนเงินที่รับชำระ</th>
							<th>เงินต้นรับชำระ</th>
							<th>ดอกเบี้ยรับชำระ</th>
						</tr>
						<?php
						//วนตามประเภทสัญญาที่เลือก	
						$sumAmountAllcon = 0; // จำนวนเงินที่รับชำระรวมทั้งหมด
						$sumPricipleAllcon = 0; // เงินต้นรับชำระรวมทั้งหมด
						$sumInterestAllcon = 0; // ดอกเบี้ยรับชำระรวมทั้งหมด
						for($con = 0;$con < sizeof($contypechk) ; $con++){
							echo "<tr bgcolor=#FFE4B5><td colspan=6>ประเภทสัญญา $contypechk[$con]</td></tr>"; //แสดง header ว่าเป็นสัญญาประเภทใด
							
							$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contypechk[$con]') ");
							$chk_con_type = pg_fetch_result($qry_chk_con_type,0);
							
							// ============================================================================================
							// ตรวจสอบข้อมูลแยกตามประเภทสัญญา
							// ============================================================================================
							if($chk_con_type == "LOAN" || $chk_con_type == "JOINT_VENTURE" || $chk_con_type == "PERSONAL_LOAN"){
								// ตรวจสอบข้อมูลเงินต้นดอกเบี้ยรับของสัญญาประเภท LOAN
								$qry_main = pg_query("SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"receiveAmount\",\"receivePriciple\",\"receiveInterest\",\"conType\"
								FROM \"thcap_temp_int_201201\" a
								LEFT JOIN \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\"
								WHERE  $where AND \"conType\"='$contypechk[$con]' AND \"isReceiveReal\" = '1' order by \"receiptID\" ");
								$row_main = pg_num_rows($qry_main);
								if($row_main > 0)
								{
									$i = 0;
									$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมตามประเภท
									$sumPricipleAll = 0; // เงินต้นรับชำระ รวมตามประเภท
									$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมตามประเภท
									
									$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
									
									while($res = pg_fetch_array($qry_main))
									{
										$i++;
										$receiveDate = $res["DATEE"]; // วันที่รับชำระ
										$contractID = $res["contractID"]; // เลขที่สัญญา
										$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
										$receiveAmount = $res["receiveAmount"]; // จำนวนเงินที่รับชำระ
										$receivePriciple = $res["receivePriciple"]; // เงินต้นรับชำระ
										$receiveInterest = $res["receiveInterest"]; // ดอกเบี้ยรับชำระ
										
										// จำนวนเงินรวมแยกตามประเภทสัญญา
										$sumAmountAll += $receiveAmount;
										$sumPricipleAll += $receivePriciple;
										$sumInterestAll += $receiveInterest;
										
										// จำนวนเงินรวมทั้งหมดทุกสัญญาทุกประเภท
										$sumAmountAllcon += $receiveAmount;
										$sumPricipleAllcon += $receivePriciple;
										$sumInterestAllcon += $receiveInterest;
										
										if($i%2==0){
											echo "<tr class=\"odd\">";
										}else{
											echo "<tr class=\"even\">";
										}
										
										echo "<td align=\"center\">$receiveDate</td>";
										echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>";
										echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"red\"><u>$receiptID</u></font></span></td>";
										echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>";
										echo "<td align=\"right\">".number_format($receivePriciple,2)."</td>";
										echo "<td align=\"right\">".number_format($receiveInterest,2)."</td>";
										echo "</tr>";
									}
									echo "<tr bgcolor=\"#FFBBBB\">";
									echo "<td align=\"right\" colspan=\"3\">รวมเงินสัญญาประเภท $contypechk[$con]</td>";
									echo "<td align=\"right\">".number_format($sumAmountAll,2)."</td>";
									echo "<td align=\"right\">".number_format($sumPricipleAll,2)."</td>";
									echo "<td align=\"right\">".number_format($sumInterestAll,2)."</td>";
									echo "</tr>";
								}
								else
								{
									echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
								}
							}else if($chk_con_type == "HIRE_PURCHASE" OR $chk_con_type == "LEASING"){
								// ตรวจสอบข้อมูลเงินต้นดอกเบี้ยรับของสัญญาประเภท HIRE_PURCHASE หรือ LEASING
								$qry_main = pg_query("SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"debt_cut\",\"priciple_cut\",\"interest_cut\",\"conType\"
								FROM \"account\".\"thcap_acc_filease_realize_eff_present\" a
								LEFT JOIN \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\"
								WHERE  $where AND \"conType\"='$contypechk[$con]' order by \"receiptID\" ");
								$row_main = pg_num_rows($qry_main);
								if($row_main > 0)
								{
									$i = 0;
									$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมตามประเภท
									$sumPricipleAll = 0; // เงินต้นรับชำระ รวมตามประเภท
									$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมตามประเภท
									
									$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
									
									while($res = pg_fetch_array($qry_main))
									{
										$i++;
										$receiveDate = $res["DATEE"]; // วันที่รับชำระ
										$contractID = $res["contractID"]; // เลขที่สัญญา
										$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
										$debt_cut = $res["debt_cut"]; // จำนวนเงินที่รับชำระ
										$priciple_cut = $res["priciple_cut"]; // เงินต้นรับชำระ
										$interest_cut = $res["interest_cut"]; // ดอกเบี้ยรับชำระ
										
										$sumAmountAll += $debt_cut;
										$sumPricipleAll += $priciple_cut;
										$sumInterestAll += $interest_cut;
										
										$sumAmountAllcon += $debt_cut;
										$sumPricipleAllcon += $priciple_cut;
										$sumInterestAllcon += $interest_cut;
										
										if($i%2==0){
											echo "<tr class=\"odd\">";
										}else{
											echo "<tr class=\"even\">";
										}
										
										echo "<td align=\"center\">$receiveDate</td>";
										echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>";
										echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"red\"><u>$receiptID</u></font></span></td>";
										echo "<td align=\"right\">".number_format($debt_cut,2)."</td>";
										echo "<td align=\"right\">".number_format($priciple_cut,2)."</td>";
										echo "<td align=\"right\">".number_format($interest_cut,2)."</td>";
										echo "</tr>";
									}
									echo "<tr bgcolor=\"#FFBBBB\">";
									echo "<td align=\"right\" colspan=\"3\">รวมเงินสัญญาประเภท $contypechk[$con]</td>";
									echo "<td align=\"right\">".number_format($sumAmountAll,2)."</td>";
									echo "<td align=\"right\">".number_format($sumPricipleAll,2)."</td>";
									echo "<td align=\"right\">".number_format($sumInterestAll,2)."</td>";
									echo "</tr>";
								}
								else
								{
									echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
								}
							}
						}
						echo "<tr bgcolor=\"#FA8072\">";
						echo "<td align=\"right\" colspan=\"3\"><b>รวมสัญญาทุกประเภท</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumAmountAllcon,2)."</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumPricipleAllcon,2)."</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumInterestAllcon,2)."</b></td>";
						echo "</tr>";
						?>
					</table>
			<?php
				}
			?>
		</td>
	</tr>
</table>
</form>

</body>

<script type="text/javascript">
$(document).ready(function(){
	var strRows = '<?php echo $row_main; ?>';
	if(parseInt(strRows) > 0){ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnprint").style.visibility = 'visible';
		document.getElementById("btnprintex").style.visibility = 'visible';
	}
	else{ // ถ้าไม่มีข้อมูลที่ค้นหา ให้ซ้อนปุ่มพิมพ์
		document.getElementById("btnprint").style.visibility = 'hidden';
		document.getElementById("btnprintex").style.visibility = 'hidden';
	}
});
</script>

</html>