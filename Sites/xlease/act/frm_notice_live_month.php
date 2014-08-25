<?php 
set_time_limit(0);
include("../config/config.php"); 
$gdate = pg_escape_string($_POST['gdate']);

if(empty($gdate)) $gdate = date("Y/m/d");

if(empty($_POST['mm'])){}else{$mm = pg_escape_string($_POST['mm']);}
if(empty($_POST['yy'])){}else{$yy = pg_escape_string($_POST['yy']);}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>     
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
<tr>
<td>
	<div class="header"><h1>รายงานการแจ้งประกันภัย รายเดือน</h1></div>
	<div class="wrapper">
		<fieldset><legend><b>ประกันภัยคุ้มครองหนี้</b></legend>
		<form method="post" action="" name="f_list" id="f_list">
		<div align="right">
			<b>เดือน</b>
				<select name="mm">
				<?php
				if(empty($mm)){
					$nowmonth = date("m");
				}else{
					$nowmonth = $mm;
				}
				$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
				for($i=0; $i<12; $i++){
					$a+=1;
					if($a > 0 AND $a <10) $a = "0".$a;
					if($nowmonth != $a){
						echo "<option value=\"$a\">$month[$i]</option>";
					}else{
						echo "<option value=\"$a\" selected>$month[$i]</option>";
					}
					
				}
				?>    
				</select>
			<b>ปี</b> 
				<select name="yy">
				<?php
				if(empty($yy)){
					$nowyear = date("Y");
				}else{
					$nowyear = $yy;
				}
				$year_a = $nowyear + 10; 
				$year_b =  $nowyear - 10;

				$s_b = $year_b+543;

				while($year_b <= $year_a){
					if($nowyear != $year_b){
						echo "<option value=\"$year_b\">$s_b</option>";
					}else{
						echo "<option value=\"$year_b\" selected>$s_b</option>";
					}
					$year_b += 1;
					$s_b +=1;
				}
				?>
				</select><input type="submit" name="submit" value="ค้นหา">
		</div>
		</form>

		<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
			<td align="center">บริษัทประกัน</td>
			<td align="center">เลขกรมธรรม์</td>
			<td align="center">IDNO</td>
			<td align="center">ชื่อ</td>
			<td align="center">ทะเบียน</td>
			<td align="center">วันที่เริ่มคุ้มครอง</td>
			<td align="center">ค่าเบี้ยประกัน</td>
		</tr>
   
		<?php
		if( isset($mm) AND isset($yy) ){
			$startMonthDay = $yy."-".$mm."-01";
			$endMonthDay = $yy."-".$mm."-".pg_gen_numdaysinmonth($mm, $yy);

			$qry_inf=pg_query("select \"InsCompany\" from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
			while($res_inf=pg_fetch_array($qry_inf)){
				$company = $res_inf["InsCompany"];    
			  
				$summary = 0;
				
				$qry_if=pg_query("select \"Company\", \"IDNO\", \"StartDate\", \"InsLIDNO\", \"InsID\", \"Premium\" from \"insure\".\"InsureLive\" WHERE \"Company\" = '$company' AND ( \"StartDate\" BETWEEN '$startMonthDay' AND '$endMonthDay' ) AND \"Cancel\"='FALSE' ORDER BY \"StartDate\" ASC");
				$rows = pg_num_rows($qry_if);
				while($res_if=pg_fetch_array($qry_if)){
					$InsLIDNO = $res_if["InsLIDNO"];
					$Company = $res_if["Company"];
					$InsID = $res_if["InsID"];
					$IDNO = $res_if["IDNO"];
					$StartDate = $res_if["StartDate"];
					$Premium = $res_if["Premium"];
						$summary+=$Premium;
					
					$qry_name=pg_query("select \"full_name\", \"C_REGIS\" from insure.\"VInsLiveDetail\" WHERE \"InsLIDNO\"='$InsLIDNO'");
					if($res_name=pg_fetch_array($qry_name)){
						$full_name = $res_name["full_name"];
						//$asset_type = $res_name["asset_type"];   
						$C_REGIS = $res_name["C_REGIS"];
						//$car_regis = $res_name["car_regis"];   
					}
					
					//if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
					
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
			?>
					<td align="center"><?php echo "$Company"; ?></td>
					<td align="left"><?php echo "$InsID"; ?></td>
					<td align="left"><?php echo "$IDNO"; ?></td>
					<td align="left"><?php echo "$full_name"; ?></td>
					<td align="left"><?php echo "$C_REGIS"; ?></td>
					<td align="center"><?php echo "$StartDate"; ?></td>
					<td align="right"><?php echo number_format($Premium,2); ?></td>
				</tr>
			<?php        
				}
				if($rows > 0){
			 ?>
				<tr>
					<td colspan="7" bgcolor="#C0C0C0"><td>
				</tr>
				<tr bgcolor="#ffffff" style="font-size:11px;">
					<td align="left"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
					<td align="right" colspan="6"><b>รวมเงิน</b> <?php echo number_format($summary,2); ?></td>
				</tr>                                                                      
				<tr><td colspan="7" bgcolor="#C0C0C0"><td></tr>    
				<tr><td colspan="7" bgcolor="#ffffff"><br><td></tr>
			<?php  
				$allrow=$allrow+$rows;
				}		
			}
			if($allrow>0){
				echo "<div align=\"right\"><a href=\"frm_notice_live_month_print.php?yy=$yy&mm=$mm\" target=\"_blank\"><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"\"> <b>สั่งพิมพ์</b></a></div>";
			}
		}
		?>
		</table>
	</div>
	</td>
</tr>
</table>

</body>
</html>