<?php // Start Program
set_time_limit(0);
include("../../config/config.php");

if(!empty($_POST['tb_search'])){$tb_search = pg_escape_string($_POST['tb_search']);} // ทะเบียนรถ
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
if(!empty($_POST['Car_Type'])){$Cr_Type = pg_escape_string($_POST['Car_Type']);} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ระบบแจ้งเตือนรถหมดอายุและถอดป้าย</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
	<script language=javascript>
		function popU(U,N,T) {
			newWindow = window.open(U, N, T);
		}
	</script>
</head>
<body>
	<center>
		<h1>ระบบแจ้งเตือนรถหมดอายุและถอดป้าย</h1>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><!-- Start Of Row -->
				<td>
					<fieldset><legend><b>แสดงรายการรถหมดอายุและถอดป้าย</b></legend>
						<form method="post" action="" name="f_list" id="f_list">
							<div style="float:left"><b>ค้นหาทะเบียนรถ</b> <input type="text" name="tb_search" id="tb_search" size="30" value="<?php echo $tb_search; ?>"></div>
							<div style="float:right">
							<b>ประเภทรถ</b>
								<select name = "Car_Type">
									<option value="All"<?php if($Cr_Type == "All"){ echo "Selected"; } ?> >ทั้งหมด</option>
									<option value="Taxi"<?php if($Cr_Type == "Taxi"){ echo "Selected"; } ?> >รถแท๊กซี่</option>
									<option value="Car"<?php if($Cr_Type == "Car"){ echo "Selected"; } ?> >รถบ้าน</option>
								</select>
							<b>เดือน</b>
								<select name="mm" id="mm">
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
								<select name="yy" id="yy">
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
											}// End Of If
											$year_b += 1;
											$s_b +=1;
										}// End Of While Loop
									?>
								</select>
								<input type="submit" name="s_data" id="s_data" value="ค้นหา" style="cursor:pointer;" onClick="$('#divshow').html('กำลังค้นหาข้อมูล...');" />
							</div>
						</form>
						<div style="clear:both"></div>
					
						<div id="divshow">
							<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
								<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
									<td align="center">IDNO</td>
									<td align="center">ชื่อ</td>
									<td align="center">ทะเบียน</td>
									<td align="center">วันที่เริ่ม</td>
									<td align="center">วันที่หมดอายุ</td>
									<td></td>
								</tr>
							<?php
								if( isset($mm) and isset($yy) )
								{
									$qry_name=pg_query("
														SELECT
															a.\"IDNO\",
															a.\"CusID\",
															c.\"full_name\",
															c.\"full_address\",
															b.\"CarID\",
															b.\"C_REGIS\",
															b.\"C_StartDate\",
															CASE WHEN b.\"C_StartDate\" <= '2005-12-26' THEN (b.\"C_StartDate\" + INTERVAL '12 year')::date ELSE (b.\"C_StartDate\" + INTERVAL '9 year')::date END AS \"C_EndDate\",
															(select \"printID\" from \"car_expire_print\" where \"IDNO\" = a.\"IDNO\" and \"CarID\" = b.\"CarID\" and \"expireDate\" = CASE WHEN b.\"C_StartDate\" <= '2005-12-26' THEN (b.\"C_StartDate\" + INTERVAL '12 year')::date ELSE (b.\"C_StartDate\" + INTERVAL '9 year')::date END)
														FROM
															\"Fp\" a, \"Fc\" b, \"VSearchCusCorp\" c
														WHERE
															a.\"asset_id\" = b.\"CarID\" AND
															a.\"CusID\" = c.\"CusID\" AND
															b.\"C_REGIS\" LIKE '%$tb_search%' AND
															CASE WHEN b.\"C_StartDate\" <= '2005-12-26' THEN
																EXTRACT(MONTH FROM (b.\"C_StartDate\" + INTERVAL '12 year')::date ) = '$mm' AND
																EXTRACT(YEAR FROM (b.\"C_StartDate\" + INTERVAL '12 year')::date ) = '$yy'
															ELSE
																EXTRACT(MONTH FROM (b.\"C_StartDate\" + INTERVAL '9 year')::date ) = '$mm' AND
																EXTRACT(YEAR FROM (b.\"C_StartDate\" + INTERVAL '9 year')::date ) = '$yy'
															END AND
															a.\"IDNO\" IN(SELECT (select e.\"IDNO\" from \"Fp\" e where e.\"asset_id\" = d.\"asset_id\" order by e.\"P_STDATE\" desc limit 1) FROM \"Fp\" d GROUP BY d.\"asset_id\")
														ORDER BY
															\"C_EndDate\", a.\"IDNO\"
													");
									
									$rows = pg_num_rows($qry_name);
									$Count_Show = 0;
									while($res_name=pg_fetch_array($qry_name))
									{
										$IDNO = $res_name["IDNO"];
										$full_name = $res_name["full_name"];
										$CarID = $res_name["CarID"];
										$C_REGIS = $res_name["C_REGIS"];
										$C_StartDate = $res_name["C_StartDate"];
										$C_EndDate = $res_name["C_EndDate"];
										$printID = $res_name["printID"];
										
										// แยประเภทรถ
										$Str_Get_Char = " SELECT '$C_REGIS' like 'ท%' or '$C_REGIS' like 'ม%' ";
										$Result = pg_query($Str_Get_Char);
										$Data = pg_fetch_result($Result,0); // return boolean ถ้าได้ true จะเป็นรถ taxi
										
										if($Cr_Type == "Taxi" && $Data != 't') // ถ้าเลือกให้แสดงรถ Taxi แต่ ทะเบียนรถที่ได้ไม่ใช้รถ Taxi
										{
											continue; // ข้ามรายการนี้ไป ให้วนรอบต่อไปเลย
										}
										elseif($Cr_Type == "Car" && $Data == 't') // ถ้าเลือกให้แสดงรถบ้าน แต่ ทะเบียนรถที่ได้เป็นรถ Taxi
										{
											continue; // ข้ามรายการนี้ไป ให้วนรอบต่อไปเลย
										}	
										elseif($Cr_Type == "All") // ถ้าให้แสดงทั้งหมด
										{
											// ปล่อยให้ทำงานต่อไป
										}
										
										if($printID == "") // ถ้ายังไม่เคยพิมพ์
										{
											$printText = "<font style=\"cursor:pointer;\" color=\"red\" onclick=\"javascript:popU('save_and_print.php?IDNO=$IDNO&CarID=$CarID&expireDate=$C_EndDate','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=512,height=256')\">รอพิมพ์</font>";
										}
										else // ถ้าเคยพิมพ์ไปแล้ว
										{
											$printText = "<font style=\"cursor:pointer;\" color=\"blue\" onclick=\"javascript:popU('popup_reprint.php?printID=$printID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=512,height=256')\">พิมพ์แล้ว</font>";
										}
										
										$Count_Show++;
										
										if($Count_Show%2==0){
											echo "<tr class=\"odd\">";// Start Of Row
										}else{
											echo "<tr class=\"even\">";// Start Of Row 
										}
							?>
										<td align="center"><font style="cursor:pointer;" onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno=<?php echo "$IDNO";?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=700')"><u><?php echo $IDNO; ?></u></font></td>
										<td align="left"><?php echo "$full_name"; ?></td>
										<td align="center"><?php echo "$C_REGIS"; ?></td>
										<td align="center"><?php echo "$C_StartDate"; ?></td>
										<td align="center"><?php echo "$C_EndDate"; ?></td>
										<td align="center"><?php echo "$printText"; ?></td>
							 <?php
										echo "</tr>";
									} // End Of While Loop Here
								}

								if($Count_Show > 0)
								{
								?>
									<tr bgcolor="#ffffff" style="font-size:11px;">
										<td align="left" colspan="6"><b>ทั้งหมด</b> <?php echo number_format($Count_Show,0); ?> <b>รายการ</b></td>
									</tr>
								<?php
								}
								else
								{
								?>
									<tr bgcolor="#ffffff" style="font-size:11px;">
										<td align="center" colspan="6"><b>--ไม่พบข้อมูล--</b></td>
									</tr>
								<?php
								}
								?>
							</table><!-- End Of Table In Here -->
						</div>
					</fieldset>
				</td>
			</tr>
		</table><!-- End Of Table Out Here  -->	
	</center>
</body>
</html>