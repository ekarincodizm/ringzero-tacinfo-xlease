<?php
include("../../config/config.php");
include("../function/nameMonth.php");
set_time_limit(120);

//$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$nowDate = nowDate(); // วันปัจจุบัน

$arrayDate = explode("-",$nowDate);					
$plusDate = mktime(0,0,0,$arrayDate[1]-1,$arrayDate[2],$arrayDate[0]); // เวลา เดือน วัน ปี
$nowDate = date("Y-m-d",$plusDate); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
			
$nowDay = substr($nowDate,8,2); // วันที่ปัจจุบัน
$nowMonth = substr($nowDate,5,2); // เดือนปัจจุบัน
$nowYear = substr($nowDate,0,4); // ปีปัจจุบัน

$click = pg_escape_string($_POST["click"]);
$selectType = pg_escape_string($_POST["selectType"]); // ประเภทการค้นหา

if($selectType == "selectDay")
{ // ถ้าเลือกแบบ ช่วง
	$Sdate = pg_escape_string($_POST["Sdate"]); // วันที่เริ่ม
	$Edate = pg_escape_string($_POST["Edate"]); // วันที่สิ้นสุด
}
elseif($selectType == "selectMonth")
{ // ถ้าเลือกแบบ รายเดือน
	$selectDay = pg_escape_string($_POST["Sdate"]); // วันที่เลือก
	$selectMonth = pg_escape_string($_POST["month2"]); // เดือนที่เลือก
	$selectYear = pg_escape_string($_POST["year2"]); // ปีที่เลือก
}
elseif($selectType == "selectYear")
{ // ถ้าเลือกแบบ รายปี
	$selectDay = pg_escape_string($_POST["Sdate"]); // วันที่เลือก
	$selectMonth = pg_escape_string($_POST["month2"]); // เดือนที่เลือก
	$selectYear = pg_escape_string($_POST["year3"]); // ปีที่เลือก
}

if($selectType == ""){$selectType = "selectYear";} // ประเภทการค้นหา Default ให้เป็นการเลือกแบบ วัน/เดือน/ปี
if($selectDay == ""){$selectDay = $nowDay;} // ถ้ายังไม่ได้เลือกวัน ให้ใช้วันที่ Default
if($selectMonth == ""){$selectMonth = $nowMonth;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้เดือน Default
if($selectYear == ""){$selectYear = $nowYear;} // ถ้ายังไม่ได้เลือกปี ให้ใช้ปี Default
if($Sdate == ""){$Sdate = substr($nowDate,0,10);}
if($Edate == ""){$Edate = substr($nowDate,0,10);}

$selectStyle = pg_escape_string($_POST["selectStyle"]); // รูปแบบการแสดง
if($selectStyle == ""){$selectStyle = "allStyle";} // ถ้ายังไม่ได้เลือก รูปแบบการแสดง ให้เลือก แสดงการตั้งหนี้ทั้งหมด

$selectCon = pg_escape_string($_POST["selectCon"]); // ประเภทการดูเลขที่สัญญา
$txtContract = pg_escape_string($_POST["txtContract"]); // เลขที่สัญญา
if($selectCon == ""){$selectCon = "allCon";} // ถ้ายังไม่ได้เลือก ให้เลือกแสดงทั้งหมด
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานตั้งหนี้ดอกเบี้ย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#Sdate").datepicker({
				showOn: 'button',
				buttonImage: 'images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
			
			$("#Edate").datepicker({
				showOn: 'button',
				buttonImage: 'images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
			
			$("#txtContract").autocomplete({
				source: "s_contract.php",
				minLength:1
			});
			
			var selectType; // เลือกช่วงเวลา
			var selectStyle; // รูปแบบการแสดง
			var selectCon; // เลือกเลขที่สัญญา
			var txtContract; // เลขที่สัญญา
			
			$('#submit3').click(function(){
				selectType = 'selectYear';
				var year3 = document.getElementById('year3').value;
				txtContract = document.getElementById('txtContract').value;
				
				if(document.getElementById('allCon').checked == true){selectCon = 'allCon';}
				else if(document.getElementById('someCon').checked == true){selectCon = 'someCon';}
			
				if(document.getElementById('allStyle').checked == true){selectStyle = 'allStyle';}
				else if(document.getElementById('receiptStyle').checked == true){selectStyle = 'receiptStyle';}
				else if(document.getElementById('autoStyle').checked == true){selectStyle = 'autoStyle';}
				
				$("#submit3").attr('disabled',true);
				$("#panel").text('กำลังค้นหาข้อมูล...');
				$("#panel").load("frm_data.php?selectType="+selectType+"&selectStyle="+selectStyle+"&year3="+year3+"&selectCon="+selectCon+"&txtContract="+txtContract);
				$("#submit3").attr('disabled',false);
			});
			
			$('#submit2').click(function(){
				selectType = 'selectMonth';
				var month2 = document.getElementById('month2').value;
				var year2 = document.getElementById('year2').value;
				txtContract = document.getElementById('txtContract').value;
				
				if(document.getElementById('allCon').checked == true){selectCon = 'allCon';}
				else if(document.getElementById('someCon').checked == true){selectCon = 'someCon';}
			
				if(document.getElementById('allStyle').checked == true){selectStyle = 'allStyle';}
				else if(document.getElementById('receiptStyle').checked == true){selectStyle = 'receiptStyle';}
				else if(document.getElementById('autoStyle').checked == true){selectStyle = 'autoStyle';}
				
				$("#submit2").attr('disabled',true);
				$("#panel").text('กำลังค้นหาข้อมูล...');
				$("#panel").load("frm_data.php?selectType="+selectType+"&selectStyle="+selectStyle+"&month2="+month2+"&year2="+year2+"&selectCon="+selectCon+"&txtContract="+txtContract);
				$("#submit2").attr('disabled',false);
			});
			
			$('#submit1').click(function(){
				selectType = 'selectDay';
				var Sdate = document.getElementById('Sdate').value;
				var Edate = document.getElementById('Edate').value;
				txtContract = document.getElementById('txtContract').value;
				
				if(document.getElementById('allCon').checked == true){selectCon = 'allCon';}
				else if(document.getElementById('someCon').checked == true){selectCon = 'someCon';}
			
				if(document.getElementById('allStyle').checked == true){selectStyle = 'allStyle';}
				else if(document.getElementById('receiptStyle').checked == true){selectStyle = 'receiptStyle';}
				else if(document.getElementById('autoStyle').checked == true){selectStyle = 'autoStyle';}
				
				$("#submit1").attr('disabled',true);
				$("#panel").text('กำลังค้นหาข้อมูล...');
				$("#panel").load("frm_data.php?selectType="+selectType+"&selectStyle="+selectStyle+"&Sdate="+Sdate+"&Edate="+Edate+"&selectCon="+selectCon+"&txtContract="+txtContract);
				$("#submit1").attr('disabled',false);
			});
		});
		
		function chktype()
		{	
			if(document.getElementById("selectDay").checked == true)
			{	
				document.getElementById("d1").style.visibility = 'visible';
				document.getElementById("txtSdate").style.visibility = 'visible';
				document.getElementById("txtEdate").style.visibility = 'visible';
				document.frm1.Sdate.style.visibility = 'visible';
				document.frm1.Edate.style.visibility = 'visible';
				document.frm1.submit1.style.visibility = 'visible';
				document.frm1.submit1.style.visibility = 'visible';
				
				document.getElementById("txtMonth2").style.visibility = 'hidden';
				document.getElementById("txtYear2").style.visibility = 'hidden';
				document.frm1.month2.style.visibility = 'hidden';
				document.frm1.year2.style.visibility = 'hidden';
				document.frm1.submit2.style.visibility = 'hidden';
				
				document.getElementById("txtYear3").style.visibility = 'hidden';
				document.frm1.year3.style.visibility = 'hidden';
				document.frm1.submit3.style.visibility = 'hidden';
			}
			else if(document.getElementById("selectMonth").checked == true)
			{
				document.getElementById("d1").style.visibility = 'hidden';
				document.getElementById("txtSdate").style.visibility = 'hidden';
				document.getElementById("txtEdate").style.visibility = 'hidden';
				document.frm1.Sdate.style.visibility = 'hidden';
				document.frm1.Edate.style.visibility = 'hidden';
				document.frm1.submit1.style.visibility = 'hidden';
				
				document.getElementById("txtMonth2").style.visibility = 'visible';
				document.getElementById("txtYear2").style.visibility = 'visible';
				document.frm1.month2.style.visibility = 'visible';
				document.frm1.year2.style.visibility = 'visible';
				document.frm1.submit2.style.visibility = 'visible';
				
				document.getElementById("txtYear3").style.visibility = 'hidden';
				document.frm1.year3.style.visibility = 'hidden';
				document.frm1.submit3.style.visibility = 'hidden';
			}
			else if(document.getElementById("selectYear").checked == true)
			{
				document.getElementById("d1").style.visibility = 'hidden';
				document.getElementById("txtSdate").style.visibility = 'hidden';
				document.getElementById("txtEdate").style.visibility = 'hidden';
				document.frm1.Sdate.style.visibility = 'hidden';
				document.frm1.Edate.style.visibility = 'hidden';
				document.frm1.submit1.style.visibility = 'hidden';
				
				document.getElementById("txtMonth2").style.visibility = 'hidden';
				document.getElementById("txtYear2").style.visibility = 'hidden';
				document.frm1.month2.style.visibility = 'hidden';
				document.frm1.year2.style.visibility = 'hidden';
				document.frm1.submit2.style.visibility = 'hidden';
				
				document.getElementById("txtYear3").style.visibility = 'visible';
				document.frm1.year3.style.visibility = 'visible';
				document.frm1.submit3.style.visibility = 'visible';
			}
		}
		
		function chkCon()
		{	
			if(document.getElementById("allCon").checked == true)
			{	
				document.getElementById("txtContract").value = '';
				document.getElementById("txtContract").readOnly = true;
				document.getElementById("txtContract").style.backgroundColor="#CCCCCC";
			}
			else if(document.getElementById("someCon").checked == true)
			{
				document.getElementById("txtContract").readOnly = false;
				document.getElementById("txtContract").style.backgroundColor="#CCFFCC";
			}
		}
		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
	</script>
	
</head>
<body>

<center>
<h2>(THCAP) รายงานตั้งหนี้ดอกเบี้ย</h2>
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
								<input type="radio" name="selectType" id="selectYear" value="selectYear" <?php if($selectType == "selectYear"){echo "checked";}?> onchange="chktype()" > แสดงแบบเป็นรายปี
							</td>
							<td width="50"></td>
							<td align="left">
								<font id="txtYear3">ปี :</font>
								<select name="year3" id="year3">
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
											if($y == 2012){break;} // ปีที่ให้เลือก ต่ำสุดได้แค่ปี 2012 เท่านั้น
										}
									?>
								</select>
								&nbsp;&nbsp;&nbsp;
								<input type="button" value="ค้นหา" name="submit3" id="submit3">
							</td>
						</tr>
					
						<tr>
							<td align="left">
								<input type="radio" name="selectType" id="selectMonth" value="selectMonth" <?php if($selectType == "selectMonth"){echo "checked";}?> onchange="chktype()" > แสดงแบบเป็นรายเดือน
							</td>
							<td width="50"></td>
							<td align="left">
								<font id="txtMonth2">เดือน :</font>
								<select name="month2" id="month2">
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
								<select name="year2" id="year2">
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
											if($y == 2012){break;} // ปีที่ให้เลือก ต่ำสุดได้แค่ปี 2012 เท่านั้น
										}
									?>
								</select>
								&nbsp;&nbsp;&nbsp;
								<input type="button" value="ค้นหา" name="submit2" id="submit2">
							</td>
						</tr>
						
						<tr>
							<td align="left">
								<input type="radio" name="selectType" id="selectDay" value="selectDay" <?php if($selectType == "selectDay"){echo "checked";} ?> onchange="chktype()" > แสดงแบบเป็นช่วง
							</td>
							<td width="50"></td>
							<td align="left">
								<div name="d1" id="d1">
									<font id="txtSdate">เริ่มต้น :</font>
									<input type="text" name="Sdate" id="Sdate" size="10" value="<?php echo $Sdate; ?>">
									&nbsp;&nbsp;&nbsp;
									<font id="txtEdate">สิ้นสุด :</font>
									<input type="text" name="Edate" id="Edate" size="10" value="<?php echo $Edate; ?>">
									&nbsp;&nbsp;&nbsp;
									<input type="button" value="ค้นหา" name="submit1" id="submit1">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</fieldset>
			
			<fieldset><legend><B>รูปแบบการแสดง</B></legend>
				<center>
					<table>
						<tr>
							<td align="left">
								<input type="radio" name="selectStyle" id="allStyle" value="allStyle" <?php if($selectStyle == "allStyle"){echo "checked";}?> onchange="chkCon()" > แสดงการตั้งหนี้ทั้งหมด
							</td>
							<td width="30"></td>
						</tr>
						<tr>
							<td align="left">
								<input type="radio" name="selectStyle" id="receiptStyle" value="receiptStyle" <?php if($selectStyle == "receiptStyle"){echo "checked";}?> onchange="chkCon()" > แสดงเฉพาะที่ออกโดยใบเสร็จ
							</td>
							<td width="30"></td>
						</tr>
						<tr>
							<td align="left">
								<input type="radio" name="selectStyle" id="autoStyle" value="autoStyle" <?php if($selectStyle == "autoStyle"){echo "checked";}?> onchange="chkCon()" > แสดงเฉพาะที่สร้างอัตโนมัติโดยระบบ
							</td>
							<td width="30"></td>
						</tr>
					</table>
				</center>
			</fieldset>
			
			<fieldset><legend><B>เลือกเลขที่สัญญา</B></legend>
				<center>
					<table>
						<tr>
							<td align="left">
								<input type="radio" name="selectCon" id="allCon" value="allCon" <?php if($selectCon == "allCon"){echo "checked";}?> onchange="chkCon()" > แสดงทุกสัญญา
							</td>
							<td width="30"></td>
							<td align="left"></td>
						</tr>
					
						<tr>
							<td align="left">
								<input type="radio" name="selectCon" id="someCon" value="someCon" <?php if($selectCon == "someCon"){echo "checked";}?> onchange="chkCon()" > ระบุเลขที่สัญญา
							</td>
							<td width="30"></td>
							<td align="left">
								<input type="text" name="txtContract" id="txtContract" value="<?php echo $txtContract ?>" size="30">
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
							<th>วันที่ตั้งหนี้</th>
							<th>เลขที่สัญญา</th>
							<th>ชื่อผู้กู้หลัก</th>
							<th>เงินต้น</th>
							<th>อัตราดอกเบี้ย</th>
							<th>วันที่เริ่มคิด<br>ดอกเบี้ยรายการนี้</th>
							<th>วันที่สิ้นสุดการคิด<br>ดอกเบี้ยรายการนี้</th>
							<th>จำนวนวันที่คิด<br>ดอกเบี้ยเพิ่ม</th>
							<th>โดย</th>
							<th>จำนวนดอกเบี้ยที่ถูกตั้ง</th>
						</tr>
						<?php
							if($selectCon == "someCon")
							{
								$where_other = "and \"contractID\" = '$txtContract' ";
							}
							else
							{
								$where_other = "";
							}
							
							if($selectStyle == "receiptStyle")
							{
								$where_other .= "and \"isReceiveReal\" > '0' ";
							}
							elseif($selectStyle == "autoStyle")
							{
								$where_other .= "and \"isReceiveReal\" = '0' ";
							}
							
							if($selectType == "selectDay")
							{
								$qry_main = pg_query("select * from \"vthcap_interestGain\"
													where \"newInterest\" > '0'
													and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
													and \"genDate\" >= '$Sdate'
													and \"genDate\" <= '$Edate'
													$where_other
													order by \"genDate\" ");
							}
							elseif($selectType == "selectMonth")
							{
								$qry_main = pg_query("select * from \"vthcap_interestGain\"
													where \"newInterest\" > '0'
													and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
													and substr(\"genDate\"::character varying,6,2) = '$selectMonth'
													and substr(\"genDate\"::character varying,1,4) = '$selectYear'
													$where_other
													order by \"genDate\" ");
							}
							elseif($selectType == "selectYear")
							{
								$qry_main = pg_query("select * from \"vthcap_interestGain\"
													where \"newInterest\" > '0'
													and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
													and substr(\"genDate\"::character varying,1,4) = '$selectYear'
													$where_other
													order by \"genDate\" ");
							}
							
							$row_main = pg_num_rows($qry_main);
							if($row_main > 0)
							{
								$i = 0;
								$allNewInterest = 0; // ยอดรวมทั้งหมด
								$sunNewInterestForMonth = 0; // ยอดรวมของแต่ละเดือน
								
								while($res = pg_fetch_array($qry_main))
								{
									$i++;
									$genDate = $res["genDate"]; // วันที่ตั้งหนี้
									$contractID = $res["contractID"]; // เลขที่สัญญา
									$MainCusName = $res["MainCusName"]; // ชื่อผู้กู้หลัก
									$lastPrinciple = $res["lastPrinciple"]; // เงินต้น
									$interestRate = $res["interestRate"]; // อัตราดอกเบี้ย
									$startIntDate = $res["startIntDate"]; // วันที่เริ่มคิดดอกเบี้ยรายการนี้
									$endIntDate = $res["endIntDate"]; //วันที่สิ้นสุดการคิดดอกเบี้ยรายการนี้
									$numIntDays = $res["numIntDays"]; // จำนวนวันที่คิดดอกเบี้ยเพิ่ม
									$isReceiveReal = $res["isReceiveReal"]; // ถ้า isReceiveReal > 0 คือ ด้วยใบเสร็จ = 0 คือด้วยระบบ
									$newInterest = $res["newInterest"]; // จำนวนดอกเบี้ยที่ถูกตั้ง
									
									$allNewInterest += $newInterest; // ยอดรวมทั้งหมด
									
									if($i == 1){$nowMonth = substr($genDate,5,2);}
									
									if($isReceiveReal == 0)
									{
										$txt_isReceiveReal = "สร้างอัตโนมัติโดยระบบ";
									}
									elseif($isReceiveReal > 0)
									{
										$txt_isReceiveReal = "ออกโดยใบเสร็จ";
									}
									else
									{
										$txt_isReceiveReal = "";
									}
									
									// ถ้าเลือกแบบ ปี ให้แสดงยอดรวมของแต่ละเดือนด้วย
									if($selectType == "selectYear" && $nowMonth != substr($genDate,5,2))
									{
										echo "<tr bgcolor=\"#BBBBFF\">";
										echo "<td align=\"right\" colspan=\"9\"><b>รวมของเดือน ".nameMonthTH($nowMonth)."</b></td>";
										echo "<td align=\"right\"><b>".number_format($sunNewInterestForMonth,2)."</b></td>";
										echo "</tr>";
										
										$sunNewInterestForMonth = 0;
									}
									
									$sunNewInterestForMonth += $newInterest; // ยอดรวมของแต่ละเดือน
									
									if($isReceiveReal == 0)
									{
										echo "<tr style=\"font-size:11px; background-color:#CCCCCC;\">";
									}
									else
									{
										if($i%2==0){
											echo "<tr class=\"odd\">";
										}else{
											echo "<tr class=\"even\">";
										}
									}
									
									echo "<td align=\"center\">$genDate</td>";
									echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>
											$contractID</u></span></td>";
									echo "<td align=\"left\">$MainCusName</td>";
									echo "<td align=\"right\">".number_format($lastPrinciple,2)."</td>";
									echo "<td align=\"right\">$interestRate</td>";
									echo "<td align=\"center\">$startIntDate</td>";
									echo "<td align=\"center\">$endIntDate</td>";
									echo "<td align=\"center\">$numIntDays</td>";
									echo "<td align=\"center\">$txt_isReceiveReal</td>";
									echo "<td align=\"right\">".number_format($newInterest,2)."</td>";
									echo "</tr>";
									
									$nowMonth = substr($genDate,5,2); // เดือนที่แสดงข้อมูล
								}
								
								if($selectType == "selectYear")
								{
									echo "<tr bgcolor=\"#BBBBFF\">";
									echo "<td align=\"right\" colspan=\"9\"><b>รวมของเดือน ".nameMonthTH($nowMonth)."</b></td>";
									echo "<td align=\"right\"><b>".number_format($sunNewInterestForMonth,2)."</b></td>";
									echo "</tr>";
									
									$sunNewInterestForMonth = 0;
								}
								
								echo "<tr bgcolor=\"#CCFFCC\">";
								echo "<td align=\"right\" colspan=\"9\"><b>ยอดรวมทั้งสิ้น</b></td>";
								echo "<td align=\"right\"><b>".number_format($allNewInterest,2)."</b></td>";
								echo "</tr>";
								
								echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"10\" align=\"center\">ข้อมูลทั้งหมดจำนวน $row_main รายการ *โดยข้อมูลที่แสดงจะแสดงเฉพาะรายการตั้งแต่ปี 2012 เป็นต้นไป</td></tr>";
							}
							else
							{
								echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"10\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
							}
						?>
					</table>
			<?php
				}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<div id="panel"></div>
		</td>
	</tr>
</table>
<input type="hidden" name="click" value="yes">
</form>

</body>

<script type="text/javascript">
$(document).ready(function(){
	chktype();
	chkCon();
});
</script>

</html>