<?php
include("../../config/config.php");

//$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server // วันเวลาปัจจุบัน

$nowDate = nowDate(); // วันปัจจุบัน

$arrayDate = explode("-",$nowDate);					
$plusDate = mktime(0,0,0,$arrayDate[1]-1,$arrayDate[2],$arrayDate[0]); // เวลา เดือน วัน ปี
$nowDate = date("Y-m-d",$plusDate); // วันที่ DEFAULT แบบ ปี-เดือน-วัน
			
$defaultDay = substr($nowDate,8,2); // วันที่ DEFAULT
$defaultMonth = substr($nowDate,5,2); // เดือน DEFAULT
$defaultYear = substr($nowDate,0,4); // ปี DEFAULT

$click = $_POST["click"];

$selectMonth = $_POST["month"]; // เดือนที่เลือก
$selectYear = $_POST["year"]; // ปีที่เลือก

if($selectMonth == ""){$selectMonth = $defaultMonth;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้เดือน DEFAULT
if($selectYear == ""){$selectYear = $defaultYear;} // ถ้ายังไม่ได้เลือกปี ให้ใช้ปี DEFAULT
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานดอกเบี้ยประจำเดือน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
	</script>
	
</head>
<body>

<center>
<h2>(THCAP) รายงานดอกเบี้ยประจำเดือน</h2>
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
								<font id="txtMonth">เดือน :</font>
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
								<font id="txtYear">ปี :</font>
								<select name="year">
									<?php
										for($y=$defaultYear; $y>=($defaultYear-10); $y--)
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
								<input type="submit" value="ค้นหา" name="submit">
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btnPrint" value="พิมพ์ PDF" onclick="javascript:popU('print_pdf.php?type=m&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btnExcel" value="ออก EXCEL" onclick="javascript:popU('frm_excel.php?month=<?php echo $selectMonth; ?>&year=<?php echo $selectYear ; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
								<input type="button" id="btnyear" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?month=<?php echo $selectMonth; ?>&year=<?php echo $selectYear ; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1160,height=800')" style="cursor:pointer;">

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
							<th>เลขที่สัญญา</th>
							<th>ประเภทสินเชื่อ</th>
							<th>ชื่อนามสกุลผู้กู้หลัก</th>
							<th>ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด</th>
						</tr>
						<?php
							$qry_main = pg_query("select distinct a.\"contractID\",
												(select b.\"conType\" from \"thcap_contract\" b where b.\"contractID\" = a.\"contractID\") as \"conType\",
												\"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') as \"newInterest\"
												from \"thcap_temp_int_201201\" a
												where substr(a.\"receiveDate\"::character varying,'1','4')::integer = '$selectYear'
												and substr(a.\"receiveDate\"::character varying,'6','2')::integer = '$selectMonth'
												and \"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') > '0.00'
												order by \"conType\", \"contractID\" ");
							
							$row_main = pg_num_rows($qry_main);
							if($row_main > 0)
							{
								$i = 0;
								$sumInterest = 0; // ยอดรวมของดอกเบี้ยแต่ละประเภท
								$allInterest = 0; // ดอกเบี้ยรวมทุกประเภท
								
								while($res = pg_fetch_array($qry_main))
								{
									$i++;
									$contractID = $res["contractID"]; // เลขที่สัญญา
									$conType = $res["conType"]; // ประเภทสินเชื่อ
									$newInterest = $res["newInterest"]; // ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด ของเดือนและปีที่เลือก
									
									if($i == 1){$spitConType = $conType;}
									
									//ค้นหาชื่อผู้กู้หลัก
									$qry_namemain = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='0'");
									if($resnamemain = pg_fetch_array($qry_namemain)){
										$name3 = trim($resnamemain["thcap_fullname"]);
									}
									
									if($spitConType != $conType)
									{
										echo "<tr bgcolor=\"CCCCFF\" style=\"font-size:11px;\">";
										echo "<td colspan=\"3\" align=\"right\"><b>ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType</b></td>";
										echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
										echo "</tr>";
										$sumInterest = 0;
										$spitConType = $conType;
									}
									
									if($i%2==0){
										echo "<tr class=\"odd\">";
									}else{
										echo "<tr class=\"even\">";
									}
									
									echo "<td align=\"center\">$contractID</td>";
									echo "<td align=\"center\">$conType</td>";
									echo "<td align=\"left\">$name3</td>";
									echo "<td align=\"right\">".number_format($newInterest,2)."</td>";
									echo "</tr>";
									
									$sumInterest += $newInterest;
									$allInterest += $newInterest;
								}
								
								// ดอกเบี้ยรวม ของประเภทสินเชื่อสุดท้าย
								echo "<tr bgcolor=\"CCCCFF\" style=\"font-size:11px;\">";
								echo "<td colspan=\"3\" align=\"right\"><b>ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType</b></td>";
								echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
								echo "</tr>";
								
								// ดอกเบี้ยรวมทุกประเภท
								echo "<tr bgcolor=\"AAFFAA\" style=\"font-size:13px;\">";
								echo "<td colspan=\"3\" align=\"right\"><b>รวมดอกเบี้ยที่เกิดขึ้นทุกประเภท</b></td>";
								echo "<td align=\"right\"><b>".number_format($allInterest,2)."</b></td>";
								echo "</tr>";
							}
							else
							{
								echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"4\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
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
	
	if(parseInt(strRows) > 0)
	{ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnPrint").style.visibility = 'visible';
		document.getElementById("btnExcel").style.visibility = 'visible';
		document.getElementById("btnyear").style.visibility = 'visible';
	}
	else
	{ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnPrint").style.visibility = 'hidden';
		document.getElementById("btnExcel").style.visibility = 'hidden';
		document.getElementById("btnyear").style.visibility = 'hidden';
	}
});
</script>

</html>