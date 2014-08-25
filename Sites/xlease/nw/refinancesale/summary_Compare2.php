<?php
session_start();
include("../../config/config.php");
$condition=$_POST["con_invitecompare"];

$txtcon = "เปรียบเทียบการชักชวนและจับคู่";
if($condition == 1){
	/*เดือนแรกที่เปรียบเทียบ*/
	$month1 = $_POST["m_1_1"];
	$year1 = $_POST["y_1_1"];
	
	/*เดือนที่สองที่เปรียบเทียบ*/
	$month2 = $_POST["m_1_2"];
	$year2 = $_POST["y_1_2"];
	
	$startDate1 = $year1."-".$month1."-01"." "."00:00:00"; 
	$startDate2 = $year2."-".$month2."-01"." "."00:00:00"; 
	
	/*เดือนแรกที่เปรียบเทียบ*/
	if($month1 == "04" || $month1 == "06" || $month1 == "09" || $month1 == "11"){
		$endDate1 = $year1."-".$month1."-30"." "."23:59:59"; 
	}else if($month1 == "02" and ($year1%4 == 0)){
		$endDate1 = $year1."-".$month1."-29"." "."23:59:59"; 
	}else if($month1 == "02" and ($year1%4 != 0)){
		$endDate1 = $year1."-".$month1."-28"." "."23:59:59"; 
	}else if($month1 == "01" || $month1 == "03" || $month1 == "05" || $month1 == "07" || $month1 == "08" || $month1 == "10" || $month1 == "12"){
		$endDate1 = $year1."-".$month1."-31"." "."23:59:59"; 
	}
	
	/*เดือนที่สองที่เปรียบเทียบ*/
	if($month2 == "04" || $month2 == "06" || $month2 == "09" || $month2 == "11"){
		$endDate2 = $year2."-".$month2."-30"." "."23:59:59"; 
	}else if($month2 == "02" and ($year2%4 == 0)){
		$endDate2 = $year2."-".$month2."-29"." "."23:59:59"; 
	}else if($month2 == "02" and ($year2%4 != 0)){
		$endDate2 = $year2."-".$month2."-28"." "."23:59:59"; 
	}else if($month2 == "01" || $month2 == "03" || $month2 == "05" || $month2 == "07" || $month2 == "08" || $month2 == "10" || $month2 == "12"){
		$endDate2 = $year2."-".$month2."-31"." "."23:59:59"; 
	}
	
	/*เดือนแรกที่เปรียบเทียบ*/
	if($month1 == "01"){
		$txtmonth1="เดือนมกราคม $year1";
	}else if($month1 == "02"){
		$txtmonth1="เดือนกุมภาพันธ์ $year1";
	}else if($month1 == "03"){
		$txtmonth1="เดือนมีนาคม $year1";
	}else if($month1 == "04"){
		$txtmonth1="เดือนเมษายน $year1";
	}else if($month1 == "05"){
		$txtmonth1="เดือนพฤษภาคม $year1";
	}else if($month1 == "06"){
		$txtmonth1="เดือนมิถุนายน $year1";
	}else if($month1 == "07"){
		$txtmonth1="เดือนกรกฎาคม $year1";
	}else if($month1 == "08"){
		$txtmonth1="เดือนสิงหาคม $year1";
	}else if($month1 == "09"){
		$txtmonth1="เดือนกันยายน $year1";
	}else if($month1 == "10"){
		$txtmonth1="เดือนตุลาคม $year1";
	}else if($month1 == "11"){
		$txtmonth1="เดือนพฤศจิกายน $year1";
	}else if($month1 == "12"){
		$txtmonth1="เดือนธันวาคม $year1";
	}
	
	/*เดือนที่สองที่เปรียบเทียบ*/
	if($month2 == "01"){
		$txtmonth2="เดือนมกราคม $year1";
	}else if($month2 == "02"){
		$txtmonth2="เดือนกุมภาพันธ์ $year1";
	}else if($month2 == "03"){
		$txtmonth2="เดือนมีนาคม $year1";
	}else if($month2 == "04"){
		$txtmonth2="เดือนเมษายน $year1";
	}else if($month2 == "05"){
		$txtmonth2="เดือนพฤษภาคม $year1";
	}else if($month2 == "06"){
		$txtmonth2="เดือนมิถุนายน $year1";
	}else if($month2 == "07"){
		$txtmonth2="เดือนกรกฎาคม $year1";
	}else if($month2 == "08"){
		$txtmonth2="เดือนสิงหาคม $year1";
	}else if($month2 == "09"){
		$txtmonth2="เดือนกันยายน $year1";
	}else if($month2 == "10"){
		$txtmonth2="เดือนตุลาคม $year1";
	}else if($month2 == "11"){
		$txtmonth2="เดือนพฤศจิกายน $year1";
	}else if($month2 == "12"){
		$txtmonth2="เดือนธันวาคม $year1";
	}
	$txthead="เปรียบเทียบระหว่างเดือน";
}else if($condition == 2){
	$month1 = $_POST["m_2_1"];
	$year1 = $_POST["y_2_1"];
	
	$month2 = $_POST["m_2_2"];
	$year2 = $_POST["y_2_2"];
	
	/*เดือนแรกที่เปรียบเทียบ*/
	if($month1 == "1"){
		$startDate1 = $year1."-01-01"." "."00:00:00"; 
		$endDate1 = $year1."-03-31"." "."23:59:59"; 
		$txtmonth1="ไตรมาสที่ 1<br>(เดือนมกราคม - มีนาคม $year1) ";
	}else if($month1 == "2"){
		$startDate1 = $year1."-04-01"." "."00:00:00"; 
		$endDate1 = $year1."-06-30"." "."23:59:59"; 
		$txtmonth1="ไตรมาสที่ 2<br>(เดือนเมษายน - มิถุนายน $year1)";
	}else if($month1 == "3"){
		$startDate1 = $year1."-07-01"." "."00:00:00"; 
		$endDate1 = $year1."-09-30"." "."23:59:59"; 
		$txtmonth1="ไตรมาสที่ 3<br>(เดือนกรกฎาคม - กันยายน $year1)";
	}else if($month1 == "4"){
		$startDate1 = $year1."-10-01"." "."00:00:00"; 
		$endDate1 = $year1."-12-31"." "."23:59:59"; 
		$txtmonth1="ไตรมาสที่ 4<br>(เดือนตุลาคม - ธันวาคม $year1)";
	}
	
	/*เดือนที่สองที่เปรียบเทียบ*/
	if($month2 == "1"){
		$startDate2 = $year2."-01-01"." "."00:00:00"; 
		$endDate2 = $year2."-03-31"." "."23:59:59"; 
		$txtmonth2="ไตรมาสที่ 1<br>(เดือนมกราคม - มีนาคม $year2) ";
	}else if($month2 == "2"){
		$startDate2 = $year2."-04-01"." "."00:00:00"; 
		$endDate2 = $year2."-06-30"." "."23:59:59"; 
		$txtmonth2="ไตรมาสที่ 2<br>(เดือนเมษายน - มิถุนายน $year2)";
	}else if($month2 == "3"){
		$startDate2 = $year2."-07-01"." "."00:00:00"; 
		$endDate2 = $year2."-09-30"." "."23:59:59"; 
		$txtmonth2="ไตรมาสที่ 3<br>(เดือนกรกฎาคม - กันยายน $year2)";
	}else if($month2 == "4"){
		$startDate2 = $year2."-10-01"." "."00:00:00"; 
		$endDate2 = $year2."-12-31"." "."23:59:59"; 
		$txtmonth2="ไตรมาสที่ 4<br>(เดือนตุลาคม - ธันวาคม $year2)";
	}
	$txthead="เปรียบเทียบระหว่างไตรมาส";
}else if($condition == 3){
	$year1 = $_POST["y_3_1"];
	$year2 = $_POST["y_3_2"];
	
	$startDate1 = $year1."-01-01"." "."00:00:00"; 
	$endDate1 = $year1."-12-31"." "."23:59:59"; 
	
	$startDate2 = $year2."-01-01"." "."00:00:00"; 
	$endDate2 = $year2."-12-31"." "."23:59:59"; 
	
	$txtmonth1="ปี ค.ศ. $year1";
	$txtmonth2="ปี ค.ศ. $year2";
	$txthead="เปรียบเทียบระหว่างปี ค.ศ.";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เปรียบเทียบจำนวนการชักชวนและจับคู่</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
</head>
<body>
 
<table width="780" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper" style="width:760px;">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='summary_Compare.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div> 
				<fieldset><legend><B><?php echo $txtcon;?></B></legend>
					<form method="post" name="form1" action="print_Compare.php" target="_blank">
					<div style="text-align:center;"><h2><?php echo $txthead?></h2><input type="hidden" name="txthead" value="<?php echo $txthead?>"><input type="hidden" name="condition" value="<?php echo $condition?>"</div>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" style="margin-top:20px" align="center" bgcolor="#00CCFF">
						<tr align="center" height="25" bgcolor="#A8D3FF">
							<th rowspan="2" width="25">ที่</th>
							<th rowspan="2">ชื่อ - สุกล</th>
							<th colspan="2"><?php echo $txtmonth1?><input type="hidden" name="txtmonth1" value="<?php echo $txtmonth1?>"></th>
							<th colspan="2"><?php echo $txtmonth2?><input type="hidden" name="txtmonth2" value="<?php echo $txtmonth2?>"></th>
							
						</tr>
						<tr align="center" height="25" bgcolor="#EFB4BC">
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
								
								/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน ของค่าแรก---*/
								$qry_invite1=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate1' and '$endDate1') group by \"IDNO\""); 
								$num_invite1=pg_num_rows($qry_invite1);
								
								/*---หาจำนวนการชวน 1 สัญญา ถือเป็น 1 การชักชวน ของค่าที่สอง---*/
								$qry_invite2=pg_query("select \"IDNO\" from refinance.\"invite\" where \"id_user\" = '$id_user' and (\"inviteDate\" between '$startDate2' and '$endDate2') group by \"IDNO\""); 
								$num_invite2=pg_num_rows($qry_invite2);
								
								/*------หาจำนวนการจับคู่ ของค่าแรก-----*/
								$qry_match1=pg_query("SELECT * FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
								where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate1' and '$endDate1')"); 
								$num_match1=pg_num_rows($qry_match1);
								
								/*------หาจำนวนการจับคู่ ของค่าที่สอง-----*/
								$qry_match2=pg_query("SELECT * FROM refinance.\"match_invite\" A
								left join refinance.\"invite\" B on A.\"inviteID\" = B.\"inviteID\"
								where B.\"id_user\" = '$id_user' and (A.\"matchDate\" between '$startDate2' and '$endDate2')"); 
								$num_match2=pg_num_rows($qry_match2);
							
								echo "<tr bgcolor=#EDF8FE align=center height=25>";
								echo "<td>$i</td>";
								echo "<td align=left>$fullname</td>";
								echo "<td >$num_invite1</td>";
								echo "<td>$num_match1</td>";
								echo "<td >$num_invite2</td>";
								echo "<td>$num_match2</td>";
								echo "</tr>";
								
								$suminvite1= $suminvite1 + $num_invite1;
								$summatch1 = $summatch1 + $num_match1;
								$suminvite2= $suminvite2 + $num_invite2;
								$summatch2 = $summatch2 + $num_match2;
							
							$i++;
							}
						?>
						<tr bgcolor="#EFB4BC" align="center"><td colspan="2" height="25" align="right" bgcolor="#A8D3FF"><b>รวม</b></td>
							<td><b><?php echo $suminvite1;?></b><input type="hidden" name="startDate1" value="<?php echo $startDate1;?>"></td>
							<td><b><?php echo $summatch1;?></b><input type="hidden" name="startDate2" value="<?php echo $startDate2;?>"></td>
							<td><b><?php echo $suminvite2;?></b><input type="hidden" name="endDate1" value="<?php echo $endDate1;?>"></td>
							<td><b><?php echo $summatch2;?></b><input type="hidden" name="endDate2" value="<?php echo $endDate2;?>"></td>
						</tr>
						</table>
						<?php 
						if($num_user != 0){
						?>
						<div align="right" style="padding:10px 0px;"><input type="image" src="images/print.gif"></div>
						<?php }?>						
					</form>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>