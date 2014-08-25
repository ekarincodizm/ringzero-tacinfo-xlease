<?php
include("../../config/config.php");
include("../function/nameMonth.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

function search_day($month , $year) // function ในการจำนวนวันในเดือนนั้นๆ
{
	$select_day = pg_query("select \"gen_numdaysinmonth\"('$month' , '$year')");
	$this_day = pg_fetch_array($select_day);
	list($ans_day) = $this_day; // นำวันที่สิ้นเดือนของเดือนนั้นๆมาเก็บไว้ในตัวแปร $ans_day
	
	return $ans_day;
}

$user_id = $_SESSION['av_iduser']; // รหัสพนักงาน
$rdo_search = pg_escape_string($_GET["chk_search"]);//1-เลือก เดือน ปี / 2- เลือก ปี
// ปี
$by_year_year = pg_escape_string($_GET["sele_by_year_year"]);
// เดือน ปี
$by_month_month = pg_escape_string($_GET["sele_by_month_month"]);
$by_month_year = pg_escape_string($_GET["sele_by_month_year"]);

if($rdo_search=='1')
{
	if(strlen($by_month_month) == 1){$by_month_month = "0$by_month_month";}
	$namemonth=nameMonthTH($by_month_month);
	$title=" เดือน  ".$namemonth." ปี ".$by_month_year;
	
	$by_month_day = search_day($by_month_month , $by_month_year); // วันที่ ณ สิ้นเดือน
	$by_month_date = "$by_month_year-$by_month_month-$by_month_day"; // ปีเดือนวัน ณ สิ้นเดือน
	
	//--- gen function เพื่อสร้างข้อมูล
		pg_query("BEGIN");
		$status = 0;
		
		$sqlaction = pg_query("select warehouse.thcap_update_wh_r_cash_account('$by_month_date', '$user_id')");
		if($sqlaction){}else{$status++;}

		if($status == 0){pg_query("COMMIT");} else{pg_query("ROLLBACK");}
	//--- จบการ gen function เพื่อสร้างข้อมูล
	
	//select ข้อมูล
	$qry = pg_query("select * from warehouse.thcap_wh_r_cash_account_details where EXTRACT(MONTH FROM \"thcap_wh_r_cash_account_date\") = '$by_month_month' 
	AND EXTRACT(YEAR FROM \"thcap_wh_r_cash_account_date\") = '$by_month_year' order by \"thcap_wh_r_cash_account_date\" ");
}
else if($rdo_search=='2')
{
	$title=" ปี ".$by_year_year;
	
	$by_year_day = search_day("12" , $by_year_year); // วันที่ ณ สิ้นปี
	$by_year_date = "$by_year_year-12-$by_year_day"; // ปีเดือนวัน ณ สิ้นปี
	
	//--- gen function เพื่อสร้างข้อมูล
		pg_query("BEGIN");
		$status = 0;
		
		$sqlaction = pg_query("select warehouse.thcap_update_wh_r_cash_account('$by_year_date', '$user_id')");
		if($sqlaction){}else{$status++;}

		if($status == 0){pg_query("COMMIT");} else{pg_query("ROLLBACK");}
	//--- จบการ gen function เพื่อสร้างข้อมูล
	
	//select ข้อมูล
	$qry = pg_query("select * from warehouse.thcap_wh_r_cash_account_details where EXTRACT(YEAR FROM \"thcap_wh_r_cash_account_date\") = '$by_year_year' order by \"thcap_wh_r_cash_account_date\"");
}



?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<fieldset><legend><B>รายละเอียด</B></legend>
	<center>
		<h2>บริษัท ไทยเอซ แคปปิตอล จำกัด</h2>
		<h2>(THCAP) รายงานเงินสดประจำวัน (ทางระบบบัญชี)</h2>
		<h2>ณ  <?php echo $title; ?></h2>
	
	<table width="90%" cellpadding="5" cellspacing="1" border="0" bgcolor="#D0D0D0">
		<tr height="25" bgcolor="#79BCFF">
			<th width="5%">รายการ</th>
			<th width="10%">วันที่รายงานเงินสด</th>
			<th width="10%">เงินสดคงเหลือยกมา</th>
			<th width="12%">จำนวนเงินสดรวมรับทั้งวัน</th>
			<th width="12%">จำนวนเงินสดรวมจ่ายทั้งวัน</th>
			<th width="12%" bgcolor="#C1FFC1">เงินสดคงเหลือ ณ สิ้นวัน</th>
			<th width="10%">เงินสดที่เปลี่ยนแปลง</th>
			<th width="7%">รายละเอียด</th>
		</tr>
		<?php
			$i=0;
			$numrow=pg_num_rows($qry);
			while($res_qry = pg_fetch_array($qry))
			{	$i++;
				$thcap_wh_r_cash_account_date =$res_qry["thcap_wh_r_cash_account_date"] ;// วันที่รายงานเงินสด
				$thcap_wh_r_cash_account_yesterdayamt=$res_qry["thcap_wh_r_cash_account_yesterdayamt"]  ;//  เงินสดคงเหลือยกมา
				$thcap_wh_r_cash_account_sumrecamt=$res_qry["thcap_wh_r_cash_account_sumrecamt"] ;// จำนวนเงินสดรวมรับทั้งวัน
				$thcap_wh_r_cash_account_sumpayamt=$res_qry["thcap_wh_r_cash_account_sumpayamt"]  ;//  จำนวนเงินสดรวมจ่ายทั้งวัน
				$thcap_wh_r_cash_account_todayamt=$res_qry["thcap_wh_r_cash_account_todayamt"]  ;//  เงินสดคงเหลือ ณ สิ้นวัน
				$thcap_wh_r_cash_account_changeamt=$res_qry["thcap_wh_r_cash_account_changeamt"]  ;//  เงินสดที่เปลี่ยนแปลง (วันนี้ลบด้วยเมื่อวาน)
				
				//ตรวจสอบว่า  เงินสดคงเหลือ ณ สิ้นวัน < 0.00 หรือไม่ ถ้า จริง จะแสดง แถวนั้นเป็นสี แดงอ่อน
				
				if(number_format($thcap_wh_r_cash_account_todayamt,2) < 0.00){
					$bgcolor="#FF6A6A";
					echo "<tr bgcolor=$bgcolor  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor ='$bgcolor';\">";
				}else{
					$bgcolor="#C1FFC1";
					if($i%2==0)
					{	
						echo "<tr bgcolor=\"#EDF8FE\"  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EDF8FE';\">";
					}
					else
					{	
					echo "<tr bgcolor=\"#D5EFFD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#D5EFFD';\">";
				}
				}
				echo "<td align=center>$i</td>";
				echo "<td align=center>$thcap_wh_r_cash_account_date</td>"; 
				echo "<td align=right>".number_format($thcap_wh_r_cash_account_yesterdayamt,2)."</td>";
				echo "<td align=right>".number_format($thcap_wh_r_cash_account_sumrecamt,2)."</td>";
				echo "<td align=right>".number_format($thcap_wh_r_cash_account_sumpayamt,2)."</td>";
				echo "<td align=right bgcolor=$bgcolor onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor ='$bgcolor';\" >".number_format($thcap_wh_r_cash_account_todayamt,2)."</td>";
				echo "<td align=right>".number_format($thcap_wh_r_cash_account_changeamt,2)."</td>";
				echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_list_show_account.php?datepicker=$thcap_wh_r_cash_account_date','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700');\" style=\"cursor:pointer;\"></td>";
			}
			if($numrow==0){
				echo "<tr height=50><td colspan=\"8\" align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
			}
			
		
		?>
	
	
	</table>
	
	<br><br>
	<table width="90%">
		<tr>
			<td align="left">..................................</td>
			<td align="right">..................................</td>
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ลงชื่อ ผู้ตรวจสอบ</td>
			<td align="right">ลงชื่อ ผู้อนุมัติ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
	
	</center>
</fieldset>