<?php
session_start();
include("../../config/config.php");
$condition=$_POST["con_invitecompare"];

if($condition == 1){
	$txtcon = "สรุปจำนวนการชักชวนและจับคู่ประจำสัปดาห์";
	$month = $_POST["m_1"];
	$year = $_POST["y_1"];
	
	$startDate1 = $year."-".$month."-01"." "."00:00:00"; //สัปดาห์ที่ 1
	$endDate1 = $year."-".$month."-07"." "."23:59:59"; //สัปดาห์ที่ 1

	$startDate2 = $year."-".$month."-08"." "."00:00:00"; //สัปดาห์ที่ 2
	$endDate2 = $year."-".$month."-14"." "."23:59:59"; //สัปดาห์ที่ 2
	
	$startDate3 = $year."-".$month."-15"." "."00:00:00"; //สัปดาห์ที่ 3
	$endDate3 = $year."-".$month."-21"." "."23:59:59"; //สัปดาห์ที่ 3
	
	$startDate4 = $year."-".$month."-22"." "."00:00:00"; //สัปดาห์ที่ 4
	
	if($month == "04" || $month == "06" || $month == "09" || $month == "11"){
		$endDate4 = $year."-".$month."-30"." "."23:59:59"; //สัปดาห์ที่ 4
	}else if($month == "02" and ($year%4 == 0)){
		$endDate4 = $year."-".$month."-29"." "."23:59:59"; //สัปดาห์ที่ 4
	}else if($month == "02" and ($year%4 != 0)){
		$endDate4 = $year."-".$month."-28"." "."23:59:59"; //สัปดาห์ที่ 4
	}else{
		$endDate4 = $year."-".$month."-31"." "."23:59:59"; //สัปดาห์ที่ 4
	}
}else if($condition == 2){
	$txtcon = "สรุปจำนวนการชักชวนและจับคู่ประจำเดือน";
	$month = $_POST["m_2"];
	$year = $_POST["y_2"];
	
	$startDate = $year."-".$month."-01"." "."00:00:00"; 
	if($month == "04" || $month == "06" || $month == "09" || $month == "11"){
		$endDate = $year."-".$month."-30"." "."23:59:59"; 
	}else if($month == "02" and ($year%4 == 0)){
		$endDate = $year."-".$month."-29"." "."23:59:59"; 
	}else if($month == "02" and ($year%4 != 0)){
		$endDate = $year."-".$month."-28"." "."23:59:59"; 
	}else{
		$endDate = $year."-".$month."-31"." "."23:59:59"; 
	}
}else if($condition == 3){
	$txtcon = "สรุปจำนวนการชักชวนและจับคู่ประจำไตรมาส";
	$month = $_POST["m_3"];
	$year = $_POST["y_3"];
	
	if($month == "1"){
		$startDate = $year."-01-01"." "."00:00:00"; 
		$endDate = $year."-03-31"." "."23:59:59"; 
		$txtmonth="ไตรมาสที่ 1 (เดือนมกราคม - มีนาคม $year) ";
	}else if($month == "2"){
		$startDate = $year."-04-01"." "."00:00:00"; 
		$endDate = $year."-06-30"." "."23:59:59"; 
		$txtmonth="ไตรมาสที่ 2 (เดือนเมษายน - มิถุนายน $year)";
	}else if($month == "3"){
		$startDate = $year."-07-01"." "."00:00:00"; 
		$endDate = $year."-09-30"." "."23:59:59"; 
		$txtmonth="ไตรมาสที่ 3 (เดือนกรกฎาคม - กันยายน $year)";
	}else if($month == "4"){
		$startDate = $year."-10-01"." "."00:00:00"; 
		$endDate = $year."-12-31"." "."23:59:59"; 
		$txtmonth="ไตรมาสที่ 4 (เดือนตุลาคม - ธันวาคม $year)";
	}
}else if($condition == 4){
	$txtcon = "สรุปจำนวนการชักชวนและจับคู่ประจำปี ค.ศ.";
	$year = $_POST["y_4"];
	$startDate = $year."-01-01"." "."00:00:00"; 
	$endDate = $year."-12-31"." "."23:59:59"; 
	$txtmonth="ปี ค.ศ. $year";
}
if($condition == 1 || $condition == 2){
	if($month == "01"){
		$txtmonth="เดือนมกราคม $year";
	}else if($month == "02"){
		$txtmonth="เดือนกุมภาพันธ์ $year";
	}else if($month == "03"){
		$txtmonth="เดือนมีนาคม $year";
	}else if($month == "04"){
		$txtmonth="เดือนเมษายน $year";
	}else if($month == "05"){
		$txtmonth="เดือนพฤษภาคม $year";
	}else if($month == "06"){
		$txtmonth="เดือนมิถุนายน $year";
	}else if($month == "07"){
		$txtmonth="เดือนกรกฎาคม $year";
	}else if($month == "08"){
		$txtmonth="เดือนสิงหาคม $year";
	}else if($month == "09"){
		$txtmonth="เดือนกันยายน $year";
	}else if($month == "10"){
		$txtmonth="เดือนตุลาคม $year";
	}else if($month == "11"){
		$txtmonth="เดือนพฤศจิกายน $year";
	}else if($month == "12"){
		$txtmonth="เดือนธันวาคม $year";
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>สรุปจำนวนการชักชวนและจับคู่</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
</head>
<body>
 <form method="post" name="form1" action="print_summaryInviteMatch.php" target="_blank">
<table width="780" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper" style="width:760px;">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='summary_InviteMatch.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div> 
				<fieldset><legend><B><?php echo $txtcon;?></B><input type="hidden" name="txtcon" value="<?php echo $txtcon?>"></legend>
					<div style="text-align:center;"><h2>ประจำ<?php echo $txtmonth?><input type="hidden" name="txtmonth" value="<?php echo $txtmonth?>"><input type="hidden" name="condition" value="<?php echo $condition?>"></h2></div>
					<?php 
					if($condition == 1){
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" style="margin-top:20px" align="center" bgcolor="#00CCFF">
						<tr align="center" height="25" bgcolor="#A8D3FF">
							<th rowspan="2" width="25">ที่</th>
							<th rowspan="2">ชื่อ - สุกล</th>
							<th colspan="2">สัปดาห์ที่ 1</th>
							<th colspan="2">สัปดาห์ที่ 2</th>
							<th colspan="2">สัปดาห์ที่ 3</th>
							<th colspan="2">สัปดาห์ที่ 4</th>
						</tr>
						<tr align="center" height="25" bgcolor="#EFB4BC">
							<th>จำนวน<br>การชักชวน</th>
							<th>จำนวน<br>การจับคู่</th>
							<th>จำนวน<br>การชักชวน</th>
							<th>จำนวน<br>การจับคู่</th>
							<th>จำนวน<br>การชักชวน</th>
							<th>จำนวน<br>การจับคู่</th>
							<th>จำนวน<br>การชักชวน</th>
							<th>จำนวน<br>การจับคู่</th>
						</tr>
						<?php
							$qry_user=pg_query("select A.\"id_user\",B.\"fullname\" from refinance.\"user_invite\" A
							left join \"Vfuser\" B on A.\"id_user\" = B.\"id_user\" where \"status_use\" = 'TRUE' ORDER BY A.\"id_user\""); 
							$num_user = pg_num_rows($qry_user);
							$i=1;
							while($resuser=pg_fetch_array($qry_user)){
								$id_user = $resuser["id_user"];
								$fullname = $resuser["fullname"];
								
							/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน---*/
								//สัปดาห์ที่ 1 
								$qry_invite1=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate1' and '$endDate1') group by \"IDNO\""); 
								$num_invite1=pg_num_rows($qry_invite1);
								
								//สัปดาห์ที่ 2
								$qry_invite2=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate2' and '$endDate2') group by \"IDNO\""); 
								$num_invite2=pg_num_rows($qry_invite2);
								
								//สัปดาห์ที่ 3
								$qry_invite3=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate3' and '$endDate3') group by \"IDNO\""); 
								$num_invite3=pg_num_rows($qry_invite3);
								
								//สัปดาห์ที่ 4
								$qry_invite4=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate4' and '$endDate4') group by \"IDNO\""); 
								$num_invite4=pg_num_rows($qry_invite4);
								
							/*------หาจำนวนการจับคู่-----*/
								//สัปดาห์ที่ 1
								$qry_match1=pg_query("SELECT * FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
								where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate1' and '$endDate1')"); 
								$num_match1=pg_num_rows($qry_match1);
								
								//สัปดาห์ที่ 2
								$qry_match2=pg_query("SELECT * FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
								where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate2' and '$endDate2')"); 
								$num_match2=pg_num_rows($qry_match2);
								
								//สัปดาห์ที่ 3
								$qry_match3=pg_query("SELECT * FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
								where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate3' and '$endDate3')"); 
								$num_match3=pg_num_rows($qry_match3);
								
								//สัปดาห์ที่ 4
								$qry_match4=pg_query("SELECT * FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
								where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate4' and '$endDate4')"); 
								$num_match4=pg_num_rows($qry_match4);
								
								echo "<tr bgcolor=#EDF8FE align=center height=25>";
								echo "<td>$i</td>";
								echo "<td align=left>$fullname</td>";
								echo "<td >$num_invite1</td>";
								echo "<td>$num_match1</td>";
								echo "<td>$num_invite2</td>";
								echo "<td>$num_match2</td>";
								echo "<td>$num_invite3</td>";
								echo "<td>$num_match3</td>";
								echo "<td>$num_invite4</td>";
								echo "<td>$num_match4</td>";
								echo "</tr>";
								
								$suminvite1= $suminvite1 + $num_invite1;
								$summatch1 = $summatch1 + $num_match1;
								
								$suminvite2= $suminvite2 + $num_invite2;
								$summatch2 = $summatch2 + $num_match2;
								
								$suminvite3= $suminvite3 + $num_invite3;
								$summatch3 = $summatch3 + $num_match3;
								
								$suminvite4= $suminvite4 + $num_invite4;
								$summatch4 = $summatch4 + $num_match4;
							$i++;
							}
						?>
						<tr bgcolor="#EFB4BC" align="center"><td colspan="2" height="25" align="right" bgcolor="#A8D3FF"><b>รวม</b></td>
							<td><b><?php echo $suminvite1;?></b></td>
							<td><b><?php echo $summatch1;?></b></td>
							<td><b><?php echo $suminvite2;?></b></td>
							<td><b><?php echo $summatch2;?></b></td>
							<td><b><?php echo $suminvite3;?></b></td>
							<td><b><?php echo $summatch3;?></b></td>
							<td><b><?php echo $suminvite4;?></b></td>
							<td><b><?php echo $summatch4;?></b>
								<input type="hidden" name="startDate1" value="<?php echo $startDate1?>">
								<input type="hidden" name="startDate2" value="<?php echo $startDate2?>">
								<input type="hidden" name="startDate3" value="<?php echo $startDate3?>">
								<input type="hidden" name="startDate4" value="<?php echo $startDate4?>">
								<input type="hidden" name="endDate1" value="<?php echo $endDate1?>">
								<input type="hidden" name="endDate2" value="<?php echo $endDate2?>">
								<input type="hidden" name="endDate3" value="<?php echo $endDate3?>">
								<input type="hidden" name="endDate4" value="<?php echo $endDate4?>">
							</td>
						</tr>
					</table>
					<?php 
					}else{
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" style="margin-top:20px" align="center" bgcolor="#00CCFF">
						<tr align="center" height="25" bgcolor="#A8D3FF">
							<th width="25">ที่</th>
							<th>ชื่อ - สุกล</th>
							<th>จำนวนการชักชวน</th>
							<th>จำนวนที่จับคู่สำเร็จ</th>						
						</tr>
						<?php
						$qry_user=pg_query("select A.\"id_user\",B.\"fullname\" from refinance.\"user_invite\" A
						left join \"Vfuser\" B on A.\"id_user\" = B.\"id_user\" where \"status_use\" = 'TRUE' ORDER BY A.\"id_user\""); 
						$num_user = pg_num_rows($qry_user);
						$i=1;
						while($resuser=pg_fetch_array($qry_user)){
							$id_user = $resuser["id_user"];
							$fullname = $resuser["fullname"];
							
							/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน---*/
							$qry_invite=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate' and '$endDate') group by \"IDNO\""); 
							$num_invite=pg_num_rows($qry_invite);
							
							/*------หาจำนวนการจับคู่-----*/
							$qry_match=pg_query("SELECT * FROM refinance.\"match_invite\" A
							left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
							where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate' and '$endDate')"); 
							$num_match=pg_num_rows($qry_match);
							
							echo "<tr bgcolor=#EDF8FE align=center height=25>";
							echo "<td>$i</td>";
							echo "<td align=left>$fullname</td>";
							echo "<td >$num_invite</td>";
							echo "<td>$num_match</td>";
							echo "</tr>";
								
							$suminvite= $suminvite + $num_invite;
							$summatch = $summatch + $num_match;
							$i++;
						}
						?>
						<tr bgcolor="#EFB4BC" align="center"><td colspan="2" height="25" align="right" bgcolor="#A8D3FF"><b>รวม</b></td>
							<td><b><?php echo $suminvite;?></b></td>
							<td><b><?php echo $summatch;?></b><input type="hidden" name="startDate" value="<?php echo $startDate?>"><input type="hidden" name="endDate" value="<?php echo $endDate?>"></td>
						</tr>
					</table>
					<?php 
					}
					if($num_user != 0){
					?>	
					<div align="right" style="padding:10px 0px;"><input type="image" src="images/print.gif"></div>
					<?php }?>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
</form>
</body>
</html>