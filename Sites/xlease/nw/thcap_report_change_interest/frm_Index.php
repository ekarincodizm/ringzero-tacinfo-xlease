<?php
include("../../config/config.php");

$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server 
$nowDay = substr($nowDate,8,2); // วันที่ปัจจุบัน
$nowMonth = substr($nowDate,5,2); // เดือนปัจจุบัน
$nowYear = substr($nowDate,0,4); // ปีปัจจุบัน

$click = $_POST["click"];
$selectType = $_POST["selectType"]; // ประเภทการค้นหา

if($selectType == "selectDay")
{ // ถ้าเลือกแบบ วัน/เดือน/ปี
	$selectDay = $_POST["day1"]; // วันที่เลือก
	$selectMonth = $_POST["month1"]; // เดือนที่เลือก
	$selectYear = $_POST["year1"]; // ปีที่เลือก
}
elseif($selectType == "selectMonth")
{ // ถ้าเลือกแบบ รายเดือน
	$selectDay = $_POST["day1"]; // วันที่เลือก
	$selectMonth = $_POST["month2"]; // เดือนที่เลือก
	$selectYear = $_POST["year2"]; // ปีที่เลือก
}
elseif($selectType == "selectYear")
{ // ถ้าเลือกแบบ รายปี
	$selectDay = $_POST["day1"]; // วันที่เลือก
	$selectMonth = $_POST["month1"]; // เดือนที่เลือก
	$selectYear = $_POST["year3"]; // ปีที่เลือก
}
elseif($selectType == "selectContract")
{ // ถ้าเลือกแบบ รายสัญญา
	$BoxContract = $_POST["BoxContract"]; // เลขที่สัญญาที่ค้นหา
}

if($selectType == ""){$selectType = "selectDay";} // ประเภทการค้นหา Default ให้เป็นการเลือกแบบ วัน/เดือน/ปี
if($selectDay == ""){$selectDay = $nowDay;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้วันที่ปัจจุบัน
if($selectMonth == ""){$selectMonth = $nowMonth;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้เดือนปัจจุบัน
if($selectYear == ""){$selectYear = $nowYear;} // ถ้ายังไม่ได้เลือกปี ให้ใช้ปีปัจจุบัน
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการปรับอัตราดอกเบี้ย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#BoxContract").autocomplete({ // ช่องค้นหาเลขที่สัญญา
				source: "s_contractID.php",
				minLength:1
			});
		});
		
		function chktype()
		{	
			if(document.getElementById("selectDay").checked == true)
			{	
				document.getElementById("txtDay1").style.visibility = 'visible';
				document.getElementById("txtMonth1").style.visibility = 'visible';
				document.getElementById("txtYear1").style.visibility = 'visible';
				document.frm1.day1.style.visibility = 'visible';
				document.frm1.month1.style.visibility = 'visible';
				document.frm1.year1.style.visibility = 'visible';
				document.frm1.submit1.style.visibility = 'visible';
				
				document.getElementById("txtMonth2").style.visibility = 'hidden';
				document.getElementById("txtYear2").style.visibility = 'hidden';
				document.frm1.month2.style.visibility = 'hidden';
				document.frm1.year2.style.visibility = 'hidden';
				document.frm1.submit2.style.visibility = 'hidden';
				
				document.getElementById("txtYear3").style.visibility = 'hidden';
				document.frm1.year3.style.visibility = 'hidden';
				document.frm1.submit3.style.visibility = 'hidden';
				
				document.getElementById("txtCon").style.visibility = 'hidden';
				document.frm1.BoxContract.style.visibility = 'hidden';
				document.frm1.submit4.style.visibility = 'hidden';
			}
			else if(document.getElementById("selectMonth").checked == true)
			{
				document.getElementById("txtDay1").style.visibility = 'hidden';
				document.getElementById("txtMonth1").style.visibility = 'hidden';
				document.getElementById("txtYear1").style.visibility = 'hidden';
				document.frm1.day1.style.visibility = 'hidden';
				document.frm1.month1.style.visibility = 'hidden';
				document.frm1.year1.style.visibility = 'hidden';
				document.frm1.submit1.style.visibility = 'hidden';
				
				document.getElementById("txtMonth2").style.visibility = 'visible';
				document.getElementById("txtYear2").style.visibility = 'visible';
				document.frm1.month2.style.visibility = 'visible';
				document.frm1.year2.style.visibility = 'visible';
				document.frm1.submit2.style.visibility = 'visible';
				
				document.getElementById("txtYear3").style.visibility = 'hidden';
				document.frm1.year3.style.visibility = 'hidden';
				document.frm1.submit3.style.visibility = 'hidden';
				
				document.getElementById("txtCon").style.visibility = 'hidden';
				document.frm1.BoxContract.style.visibility = 'hidden';
				document.frm1.submit4.style.visibility = 'hidden';
			}
			else if(document.getElementById("selectYear").checked == true)
			{
				document.getElementById("txtDay1").style.visibility = 'hidden';
				document.getElementById("txtMonth1").style.visibility = 'hidden';
				document.getElementById("txtYear1").style.visibility = 'hidden';
				document.frm1.day1.style.visibility = 'hidden';
				document.frm1.month1.style.visibility = 'hidden';
				document.frm1.year1.style.visibility = 'hidden';
				document.frm1.submit1.style.visibility = 'hidden';
				
				document.getElementById("txtMonth2").style.visibility = 'hidden';
				document.getElementById("txtYear2").style.visibility = 'hidden';
				document.frm1.month2.style.visibility = 'hidden';
				document.frm1.year2.style.visibility = 'hidden';
				document.frm1.submit2.style.visibility = 'hidden';
				
				document.getElementById("txtYear3").style.visibility = 'visible';
				document.frm1.year3.style.visibility = 'visible';
				document.frm1.submit3.style.visibility = 'visible';
				
				document.getElementById("txtCon").style.visibility = 'hidden';
				document.frm1.BoxContract.style.visibility = 'hidden';
				document.frm1.submit4.style.visibility = 'hidden';
			}
			else if(document.getElementById("selectContract").checked == true)
			{
				document.getElementById("txtDay1").style.visibility = 'hidden';
				document.getElementById("txtMonth1").style.visibility = 'hidden';
				document.getElementById("txtYear1").style.visibility = 'hidden';
				document.frm1.day1.style.visibility = 'hidden';
				document.frm1.month1.style.visibility = 'hidden';
				document.frm1.year1.style.visibility = 'hidden';
				document.frm1.submit1.style.visibility = 'hidden';
				
				document.getElementById("txtMonth2").style.visibility = 'hidden';
				document.getElementById("txtYear2").style.visibility = 'hidden';
				document.frm1.month2.style.visibility = 'hidden';
				document.frm1.year2.style.visibility = 'hidden';
				document.frm1.submit2.style.visibility = 'hidden';
				
				document.getElementById("txtYear3").style.visibility = 'hidden';
				document.frm1.year3.style.visibility = 'hidden';
				document.frm1.submit3.style.visibility = 'hidden';
				
				document.getElementById("txtCon").style.visibility = 'visible';
				document.frm1.BoxContract.style.visibility = 'visible';
				document.frm1.submit4.style.visibility = 'visible';
			}
		}
		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
	</script>
	
</head>
<body>

<center>
<h2>(THCAP) รายงานการปรับอัตราดอกเบี้ย</h2>
</center>

<form name="frm1" method="post" action="frm_Index.php">
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>เลือกช่วงเวลา</B></legend>
				<center>
					<table>
						<tr>
							<td align="left">
								<input type="radio" name="selectType" id="selectDay" value="selectDay" <?php if($selectType == "selectDay"){echo "checked";} ?> onchange="chktype()" > ประจำ วัน/เดือน/ปี
							</td>
							<td width="50"></td>
							<td align="left">
								<font id="txtDay1">วันที่ :</font>
								<select name="day1">
									<?php
										for($d=1; $d<=31; $d++)
										{
											if(strlen($d) == 1){$d = "0".$d;}
											if($d == $selectDay)
											{
												echo "<option value=\"$d\" selected>$d</option>";
											}
											else
											{
												echo "<option value=\"$d\">$d</option>";
											}
										}
									?>
								</select>
								&nbsp;&nbsp;&nbsp;
								<font id="txtMonth1">เดือน :</font>
								<select name="month1">
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
								<font id="txtYear1">ปี :</font>
								<select name="year1">
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
								&nbsp;&nbsp;&nbsp;
								<input type="submit" value="ค้นหา" name="submit1">
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btnPrint1" value="พิมพ์" onclick="javascript:popU('print_pdf.php?type=d&day=<?php echo "$selectDay"; ?>&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
							</td>
						</tr>
						
						<tr>
							<td align="left">
								<input type="radio" name="selectType" id="selectMonth" value="selectMonth" <?php if($selectType == "selectMonth"){echo "checked";}?> onchange="chktype()" > รายเดือน-ปี
							</td>
							<td width="50"></td>
							<td align="left">
								<font id="txtMonth2">เดือน :</font>
								<select name="month2">
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
								<font id="txtYear2">ปี :</font>
								<select name="year2">
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
								&nbsp;&nbsp;&nbsp;
								<input type="submit" value="ค้นหา" name="submit2">
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btnPrint2" value="พิมพ์" onclick="javascript:popU('print_pdf.php?type=m&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
							</td>
						</tr>
						
						<tr>
							<td align="left">
								<input type="radio" name="selectType" id="selectYear" value="selectYear" <?php if($selectType == "selectYear"){echo "checked";}?> onchange="chktype()" > รายปี
							</td>
							<td width="50"></td>
							<td align="left">
								<font id="txtYear3">ปี :</font>
								<select name="year3">
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
								&nbsp;&nbsp;&nbsp;
								<input type="submit" value="ค้นหา" name="submit3">
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btnPrint3" value="พิมพ์" onclick="javascript:popU('print_pdf.php?type=y&year=<?php echo $selectYear; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
							</td>
						</tr>
						
						<tr>
							<td align="left">
								<input type="radio" name="selectType" id="selectContract" value="selectContract" <?php if($selectType == "selectContract"){echo "checked";}?> onchange="chktype()" > รายสัญญา
							</td>
							<td width="50"></td>
							<td align="left">
								<font id="txtCon">เลขที่สัญญา :</font>
								<input type="text" name="BoxContract" id="BoxContract" size="30" value="<?php echo $BoxContract; ?>">
								&nbsp;&nbsp;&nbsp;
								<input type="submit" value="ค้นหา" name="submit4">
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btnPrint4" value="พิมพ์" onclick="javascript:popU('print_pdf.php?type=c&contractID=<?php echo $BoxContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
							</td>
						</tr>
					</table>
				</center>
			</fieldset>
			
			<?php
				if($click == "yes")
				{
			?>
					<table width="100%">
						<tr align="center" bgcolor="#79BCFF">
							<th>วันเวลาที่มีผล</th>
							<th>เลขที่สัญญา</th>
							<th>ชื่อผู้กู้หลัก</th>
							<th>ดอกเบี้ยในระบบ (เดิม)</th>
							<th>ดอกเบี้ยในระบบ (ใหม่)</th>
						</tr>
						<?php
							if($selectType == "selectDay")
							{
								$qry_main = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
													(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
													a.\"conIntCurRate\" as \"newRate\"
													from \"thcap_mg_contract_current\" a
													where substr(a.\"effectiveDate\"::character varying,9,2) = '$selectDay'
													and substr(a.\"effectiveDate\"::character varying,6,2) = '$selectMonth'
													and substr(a.\"effectiveDate\"::character varying,1,4) = '$selectYear'
													and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
													order by a.\"effectiveDate\", a.\"rev\" ");
							}
							elseif($selectType == "selectMonth")
							{
								$qry_main = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
													(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
													a.\"conIntCurRate\" as \"newRate\"
													from \"thcap_mg_contract_current\" a
													where substr(a.\"effectiveDate\"::character varying,6,2) = '$selectMonth'
													and substr(a.\"effectiveDate\"::character varying,1,4) = '$selectYear'
													and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
													order by a.\"effectiveDate\", a.\"rev\" ");
							}
							elseif($selectType == "selectYear")
							{
								$qry_main = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
													(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
													a.\"conIntCurRate\" as \"newRate\"
													from \"thcap_mg_contract_current\" a
													where substr(a.\"effectiveDate\"::character varying,1,4) = '$selectYear'
													and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
													order by a.\"effectiveDate\", a.\"rev\" ");
							}
							elseif($selectType == "selectContract")
							{
								$qry_main = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
													(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
													a.\"conIntCurRate\" as \"newRate\"
													from \"thcap_mg_contract_current\" a
													where a.\"contractID\" = '$BoxContract'
													and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
													order by a.\"effectiveDate\", a.\"rev\" ");
							}
							
							$row_main = pg_num_rows($qry_main);
							if($row_main > 0)
							{
								$i = 0;
								
								while($res = pg_fetch_array($qry_main))
								{
									$i++;
									$effectiveDate = $res["effectiveDate"]; // วันเวลาที่มีผล
									$contractID = $res["contractID"]; // เลขที่สัญญา
									$oldRate = $res["oldRate"]; // ดอกเบี้ยในระบบ (เดิม)
									$newRate = $res["newRate"]; // ดอกเบี้ยในระบบ (ใหม่)
									
									//ค้นหาชื่อผู้กู้หลัก
									$qry_namemain = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='0'");
									if($resnamemain = pg_fetch_array($qry_namemain)){
										$name3 = trim($resnamemain["thcap_fullname"]);
									}
									
									if($i%2==0){
										echo "<tr class=\"odd\">";
									}else{
										echo "<tr class=\"even\">";
									}
									
									echo "<td align=\"center\">$effectiveDate</td>";
									echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></span></td>";
									echo "<td align=\"center\">$name3</td>";
									echo "<td align=\"center\">$oldRate %</td>";
									echo "<td align=\"center\">$newRate %</td>";
									echo "</tr>";
								}
							}
							else
							{
								echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"6\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
							}
						?>
					</table>
			<?php
				}
			?>
		</td>
	</tr>
</table>
<input type="hidden" name="click" value="yes">
</form>

</body>

<script type="text/javascript">
$(document).ready(function(){	
	var strRows = '<?php echo $row_main; ?>';
	var strType = '<?php echo $selectType; ?>'; // ประเภทที่เลือก
	
	if(strType == 'selectDay' && parseInt(strRows) > 0){ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnPrint1").style.visibility = 'visible';
		document.getElementById("btnPrint2").style.visibility = 'hidden';
		document.getElementById("btnPrint3").style.visibility = 'hidden';
		document.getElementById("btnPrint4").style.visibility = 'hidden';
	}
	else if(strType == 'selectMonth' && parseInt(strRows) > 0){ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnPrint1").style.visibility = 'hidden';
		document.getElementById("btnPrint2").style.visibility = 'visible';
		document.getElementById("btnPrint3").style.visibility = 'hidden';
		document.getElementById("btnPrint4").style.visibility = 'hidden';
	}
	else if(strType == 'selectYear' && parseInt(strRows) > 0){ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnPrint1").style.visibility = 'hidden';
		document.getElementById("btnPrint2").style.visibility = 'hidden';
		document.getElementById("btnPrint3").style.visibility = 'visible';
		document.getElementById("btnPrint4").style.visibility = 'hidden';
	}
	else if(strType == 'selectContract' && parseInt(strRows) > 0){ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnPrint1").style.visibility = 'hidden';
		document.getElementById("btnPrint2").style.visibility = 'hidden';
		document.getElementById("btnPrint3").style.visibility = 'hidden';
		document.getElementById("btnPrint4").style.visibility = 'visible';
	}
	else{ // ถ้าไม่มีข้อมูลที่ค้นหา ให้ซ้อนปุ่มพิมพ์
		document.getElementById("btnPrint1").style.visibility = 'hidden';
		document.getElementById("btnPrint2").style.visibility = 'hidden';
		document.getElementById("btnPrint3").style.visibility = 'hidden';
		document.getElementById("btnPrint4").style.visibility = 'hidden';
	}
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	chktype();
});
</script>

</html>