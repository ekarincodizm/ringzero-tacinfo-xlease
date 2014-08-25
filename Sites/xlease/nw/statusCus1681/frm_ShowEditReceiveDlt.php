<?php
session_start();
include("../../config/config.php");
include("../function/nameMonth.php");

// $tacXlsRecID = $_GET['tacXlsRecID']; //เลขที่ใบเสร็จ
// $tacID= $_GET['tacID']; //เลขที่สัญญา
// $tacXlsRecID_Old = $_GET['tacXlsRecID_Old']; //เลขที่ใบเสร็จเก่าก่อนแก้ไข
// $tacID_Old = $_GET['tacID_Old']; //เลขที่สัญญาเก่าก่อนแก้ไข
$makerID = $_GET['req_user']; //ผู้ทำรายการก่อนแก้ไข

$tacXlsRecID1 = $_GET['tacXlsRecID']; //เลขที่ใบเสร็จ
$tacID1= $_GET['tacID']; //เลขที่สัญญา
$tacXlsRecID_Old1 = $_GET['tacXlsRecID_Old']; //เลขที่ใบเสร็จเก่าก่อนแก้ไข
$tacID_Old1 = $_GET['tacID_Old']; //เลขที่สัญญาเก่าก่อนแก้ไข
$readonly = $_GET["readonly"]; //สำหรับ user ดูข้อมูลเท่านั้น

//ตรวจสอบว่าพนักงานเป็นสาขาใด
$qryoffice=pg_query("select office_id from \"fuser\" where \"id_user\"='$makerID'");
list($office_id)=pg_fetch_array($qryoffice);

$currentDate=nowDate();
$ycurent=substr($currentDate,0,4);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติแก้ไขข้อมูล</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<?php
$qryname=pg_query("select * from \"Taxiacc\" where \"CusID\"='$tacID_Old1'");
$numrows = pg_num_rows($qryname);

if($numrows ==0){
	echo "<h2>ไม่พบข้อมูลสัญญา: $tacID1</h2>";
}else{
?>
<div align="center"><h2>อนุมัติแก้ไขสัญญา <?php echo $tacID_Old;?> เลขที่ใบเสร็จ <?php echo $tacXlsRecID_Old;?></h2></div>
<div align="right"><u><input type="button" value="X ปิด" onclick="window.close();"></u></div>
<div style="width:1200px;">
<?php
for($i=0;$i<2;$i++){
	if($i==0){
		$float="left";
		$txt="ข้อมูลเก่า";
		$color="#A9A9A9";
		$color2="#E8E8E8";
		$color3="#CDC0B0";
		$color4="#FFEFDB";
		
		$tacID=$tacID_Old1;
		$tacXlsRecID=$tacXlsRecID_Old1;
		
		$qry_con=pg_query("SELECT \"tacOldRecID\", \"tacTempDate\" FROM \"tacReceiveTemp\" 
		WHERE \"tacID\"='$tacID' and \"tacXlsRecID\"='$tacXlsRecID'");
		if($rescon=pg_fetch_array($qry_con)){
			$tacOldRecID=$rescon["tacOldRecID"];
			$tacTempDate=$rescon["tacTempDate"];
		}
	}else{
		$float="right";
		$txt="ข้อมูลใหม่";
		$color="#097AB0";
		$color2="#DBF2FD";
		$color3="#FFCCCC";
		$color4="#FFECEC";
		
		$tacID=$tacID1;
		$tacXlsRecID=$tacXlsRecID1;
		
		$qry_con=pg_query("SELECT \"tacOldRecID\", \"tacTempDate\",\"statusApp\" FROM \"tacReceiveTemp_waitedit\" 
		WHERE \"tacID\"='$tacID' and \"tacXlsRecID\"='$tacXlsRecID' and  \"statusApp\" IN('2','3')
		GROUP BY \"tacOldRecID\", \"tacTempDate\",\"statusApp\"");
		if($rescon=pg_fetch_array($qry_con)){
			$tacOldRecID=$rescon["tacOldRecID"];
			$tacTempDate=$rescon["tacTempDate"];
			$statusApp=$rescon["statusApp"];
		}
	}
	
		//ดึงข้อมูลรถมาแสดง
		$qry_name=pg_query("select * from \"Taxiacc\" where \"CusID\"='$tacID'");
		$numrows = pg_num_rows($qry_name);
		while($res_name=pg_fetch_array($qry_name)){
			$CusID=trim($res_name["CusID"]); 
			$statusNT=trim($res_name["statusNT"]); 
			$cutAccount=trim($res_name["cutAccount"]); $cutAccount=number_format($cutAccount,2);
			$statusLock=trim($res_name["statusLock"]); 
			$checkDate=trim($res_name["checkDate"]); 
			$radiostop=trim($res_name["radiostop"]);
			if($radiostop=="1900-01-01"){
				$radiostop="";
			}
			
			$NTDate=trim($res_name["NTDate"]); 
			if($NTDate=="1900-01-01"){
				$NTDate="";
			}

			$cutYear=trim($res_name["cutYear"]);
			if($cutYear==0){
				$cutYear="";
			}
			$ntrec=trim($res_name["ntrec"]); $ntrec=number_format($ntrec,2);
		}
	?>
	<div style="float:<?php echo $float;?>;width:600px;">
		<table width="100%" cellSpacing="1" cellPadding="1" border="0" bgcolor="#F0F0F0" align="center">
			<tr><td colspan="3"><b>(<?php echo $txt;?>)</b></td></tr>
			<?php
			if($statusApp=="3"){
				echo "<tr bgcolor=\"$color4\"><td colspan=\"3\" align=\"center\" height=\"100\"><h2>ขอลบข้อมูล</h2></td></tr>";
			}else{
				?>
				<tr>
					<td colspan="3">
						<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
						<tr bgcolor="<?php echo $color;?>" style="color:#FFFFFF" height="25">
							<th>สัญญาเลขที่</th>
							<th>สัญญารับโอนไป</th>
							<th>รหัสเครื่องวิทยุ</th>
							<th>รหัสวิทยุ</th>
							<th>ทะเบียนรถยนต์</th>
							<th>ชื่อ-นามสกุลลูกค้า</th>
						</tr>
						<?php
						$sql=mssql_query("select a.CusID,a.PreName,a.Name,a.SurName,b.RadioONID,b.RentPrice,b.RadioOff,convert(varchar,b.RadioOffDate,103) as RadioOffDate,b.RadioID,a.CarRegis from TacCusDtl a
						left join TacRadio b on a.CusID=b.CusID 
						where RadioONID <> '0' and a.CusID='$CusID' order by a.CusID",$conn); 
						if($res = mssql_fetch_array($sql)){
							$PreName=trim(iconv('WINDOWS-874','UTF-8',$res["PreName"]));
							$Name=trim(iconv('WINDOWS-874','UTF-8',$res["Name"]));
							$SurName=trim(iconv('WINDOWS-874','UTF-8',$res["SurName"]));
							$fullname=$PreName.$Name." ".$SurName;
							$RadioONID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioONID"]));
							$RadioOff=trim(iconv('WINDOWS-874','UTF-8',$res["RadioOff"]));
							$RadioOffDate=trim(iconv('WINDOWS-874','UTF-8',$res["RadioOffDate"]));
							$RentPrice=$res["RentPrice"];
							$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioID"]));
							$CarRegissql=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));
							
							$dd=substr($RadioOffDate,0,2);
							$mm=substr($RadioOffDate,3,2);
							$yy=substr($RadioOffDate,6,4);
							$yy=$yy+543;
							$RadioOffDate=$dd."-".$mm."-".$yy;
							
							//หาสัญญารับโอนไป อันดับแรกต้องหาก่อนว่า RadioONID นี้มีทั้งหมดกี่สัญญา แล้วดูว่าปัจจุบันอยู่ที่สัญญาเท่าไหร่ แล้วให้นำสัญญาถัดไปมาแสดง
							$query_ONID=mssql_query("select CusID,RadioOff from TacRadio where RadioONID='$RadioONID' order by EffectDate ASC");
							$num_ONID=mssql_num_rows($query_ONID);
							$x=1;
							$CusIDNew="";
							if($num_ONID != 0){
								while($res_ONID=mssql_fetch_array($query_ONID)){
									$CusID2=trim(iconv('WINDOWS-874','UTF-8',$res_ONID["CusID"]));
									$RadioOff2=trim(iconv('WINDOWS-874','UTF-8',$res_ONID["RadioOff"]));
										
									if($CusID==$CusID2){  //กรณีไม่ใช่ record สุดท้าย จะสามารถกำหนดค่า $CusIDNew = record ถัดไปได้
										$y=$x+1;
									}
									if($y==$x){ //แทนค่า $CusIDNew = record ถัดไป
										$CusIDNew=$CusID2;
									}
										
									$x++;
								}
								if($CusIDNew==""){ //กรณีมีแค่ 1 record หรือ เป็น record สุดท้าย จะพบว่ายังไม่โอนไปที่ไหนเลย
									$CusIDNew="<font color=red>ยังไม่โอน</font>";
								}
							}else{
								$CusIDNew="<font color=red>ไม่พบข้อมูล</font>";
							}
						}
						//หาทะเบียนรถยนต์จาก Taxiacc
						$qrycarregis=pg_query("select carregis from \"Taxiacc\" where \"CusID\"='$CusID'");
						list($carregis)=pg_fetch_array($qrycarregis);

						if($carregis==""){
							$CarRegis=$CarRegissql;
						}else{
							$CarRegis=$carregis;
						}
						
						//หาเลขที่สัญญาล่าสุดของทะเบียนรถคันนี้
						$CarRegiscutspace=ereg_replace('[[:space:]]+', '', trim($CarRegis)); //ตัดช่องว่างออก
						$qrycarregis=pg_query("select \"IDNO\" from \"Fp\" a
						left join \"Fc\" b on a.\"asset_id\"=b.\"CarID\"
						left join \"FGas\" c on a.\"asset_id\"=c.\"GasID\"
						where replace(replace(\"C_REGIS\",' ',''),'-','')='$CarRegiscutspace'
						or replace(replace(\"car_regis\",' ',''),'-','')='$CarRegiscutspace' 
						order by \"P_STDATE\" DESC limit 1");
						$numcar=pg_num_rows($qrycarregis);
						list($IDNO)=pg_fetch_array($qrycarregis);
						
						echo "<tr bgcolor=$color2>";
						echo "<td align=\"center\"><span onclick=\"javascript:popU('frm_PaymentChk.php?car=$CusID','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" title=\"รายละเอียดรับชำระแทน 1681\" style=\"cursor:pointer\"><u>$CusID</u></span></td>";
						echo "<td align=center>$CusIDNew</td>";
						echo "<td align=center>$RadioONID</td>";
						echo "<td align=center>$RadioID</td>";
						if($numcar>0){
							echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><u>$CarRegis</u></a></td>";
						}else{
							echo "<td align=center>$CarRegis</td>";
						}
						echo "<td>$fullname</td>";
						echo "</tr>";
						
						
						//ค้นหาเลขที่สัญญาและเลขที่ใบเสร็จเก่าก่อนแก้ไข
						$qry_old=pg_query("SELECT \"tacID\",\"tacXlsRecID\",\"tacTempDate\",\"tacOldRecID\" FROM \"tacReceiveTemp\" 
						WHERE \"tacID\"='$tacID_Old1' AND  \"tacXlsRecID\"='$tacXlsRecID_Old1' limit (1)");
						list($tacID2,$tacXlsRecID2,$tacTempDate2,$tacOldRecID2)=pg_fetch_array($qry_old);

						if($tacOldRecID2==""){
							$txtold="ไม่มี";
						}else{
							$txtold=$tacOldRecID2;
						}
						?>
						<tr><td colspan="6" bgcolor="<?php echo $color3;?>" height="25"><b>ข้อมูลการชำระ</b></td></tr>
						<tr bgcolor="<?php echo $color4;?>"><td colspan="6">
							<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="<?php echo $color4;?>">
								<tr>
									<td>
										<table cellpadding="3" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="50"></td>
											<td width="120" align="right"><b>เลขที่สัญญา</b></td><td width="10">:</td><td><input type="text" name="tacID" id="tacID" value="<?php echo $CusID;?>" readonly <?php if($tacID!=$tacID2){ echo "style=\"background-color:#FFDEAD;\" "; } ?>></td>
										</tr>
										<tr>
											<td width="50"></td>
											<td width="120" align="right"><b>เลขที่ใบเสร็จ</b></td><td width="10">:</td>
											<td>
												<input type="text" name="tacXlsRecID" id="tacXlsRecID" value="<?php echo $tacXlsRecID; ?>" readonly <?php if($tacXlsRecID!=$tacXlsRecID2){ echo "style=\"background-color:#FFDEAD;\""; } ?>>							
											</td>
										</tr>
										<tr>
											<td></td>
											<td align="right"><b>วันที่ชำระ</b></td><td width="10">:</td><td><input type="text" id="tacTempDate" name="tacTempDate" value="<?php echo $tacTempDate;?>" size="15" style="text-align: center;<?php if($tacTempDate!=$tacTempDate2){ echo "background-color:#FFDEAD;"; }?>"  readonly></td>
										</tr>
										</table>
									</td>
								</tr>
							</table>
						</tr>
						<tr bgcolor="<?php echo $color3;?>">
							<td colspan="6">
							<b>เลขที่ใบเสร็จ TAC :</b> <input type="text" name="tacOldRecID" id="tacOldRecID" value="<?php echo $tacOldRecID;?>" readonly <?php if($tacOldRecID!=$tacOldRecID2){ echo "style=\"background-color:#FFDEAD;\" "; } ?>>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<table width="100%" style="background-color:<?php echo $color4;?>; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
								<?php 
								if($i==0){
									$qry_all=pg_query("SELECT * FROM \"tacReceiveTemp\" 
									WHERE \"tacID\"='$tacID' and \"tacXlsRecID\"='$tacXlsRecID'");								
								}else{
									$qry_all=pg_query("SELECT * FROM \"tacReceiveTemp_waitedit\" 
									WHERE \"tacID\"='$tacID' and \"tacXlsRecID\"='$tacXlsRecID' and \"statusApp\" IN ('2','3') order by auto_id");								
								}
								$numall=pg_num_rows($qry_all);
								$sumtacMoney=0;
								$j=1;
								while($resall=pg_fetch_array($qry_all)){
									$tacOldRecID=trim($resall["tacOldRecID"]);
									$tacTempDate=trim($resall["tacTempDate"]);
									$tacMoney=trim($resall["tacMoney"]);					
									$tacMonth=trim($resall["tacMonth"]);
									$tacSerial=trim($resall["tacSerial"]);
									list($y,$m,$d)=explode("-",$tacMonth);
									
									$sumtacMoney=$sumtacMoney+$tacMoney;
									
									//ดึงข้อมูลจากตารางจริงมาเปรียบเทียบ
									if($tacSerial2==""){
										$txtcon="";
									}else{
										$txtcon="and \"tacSerial\" > '$tacSerial2'";
									}
									$qrychk=pg_query("SELECT * FROM \"tacReceiveTemp\" 
									WHERE \"tacID\"='$tacID_Old1' AND \"tacXlsRecID\"='$tacXlsRecID_Old1' $txtcon order by \"tacSerial\" limit(1)");
									
									if($reschk=pg_fetch_array($qrychk)){
										$tacOldRecID2=trim($reschk["tacOldRecID"]);
										$tacTempDate2=trim($reschk["tacTempDate"]);
										$tacMoney2=trim($reschk["tacMoney"]);					
										$tacMonth2=trim($reschk["tacMonth"]);
										$tacSerial2=trim($reschk["tacSerial"]);
										list($y2,$m2,$d2)=explode("-",$tacMonth2);
									}
								?>
								<tr>
									<td>
										<b>เงินที่จ่าย (บาท)</b> <input type="text" value="<?php echo $tacMoney;?>" style="text-align:right;<?php if($tacMoney!=$tacMoney2){ echo "background-color:#FFDEAD;"; }?>" readonly> 
										<b>เดือนที่จ่าย </b>
											<?php
												$name=nameMonthTH($m);
												$name2=nameMonthTH($m2);
											?>
											<input type="text" value="<?php echo $name; ?>" style="text-align:center;<?php if($m!=$m2){ echo "background-color:#FFDEAD;"; }?>" readonly>
											
										<b>ปีที่จ่าย (ค.ศ.)<b><input type="text" name="yearPay<?php echo $j?>" id="yearPay<?php echo $j?>" onkeypress="return check_year(event);" maxlength="4" size="10" value="<?php echo $y?>" style="text-align:center;<?php if($y!=$y2){ echo "background-color:#FFDEAD;"; }?>" readonly>
									</td>
								</tr>
								<?php 
								$j++;
								} 
								
								?>
								<input type="hidden" name="numall" id="counter" value="<?php echo $numall;?>">
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			<?php 
			} 
			?>
		</table>
	
		<?php
		if($statusApp!="3"){
		?>
			<table width="100%" align="center" border="0">
			<tr height="50">
				<td width="">ยอดรับชำระใบนี้รวม : <span id="divsummery" style="font-weight:bold;"><?php echo number_format($sumtacMoney,2);?></span> บาท</td>
			</tr>
			</table>
		<?php
		}
		?>
	</div>
<?php
	unset($tacID);
	unset($tacXlsRecID);
	unset($tacSerial2);
} //end for
?>
</div>
<div style="clear:both;"></div>
<?php IF($readonly !== 'readonly'){ ?>
<div style="padding-top:50px;text-align:center;">

<!--input type="button" value="อนุมัติ" id="submitButton" > 
<input type="button" value="ไม่อนุมัติ" id="nosubmitButton"></div-->
<form method="post" action="api.php">
	<input type="hidden" name="cusid" id="cusid" value="<?php echo $tacID1;?>">
	<input type="hidden" name="tacXlsRecID" id="tacXlsRecID" value="<?php echo $tacXlsRecID1;?>">
	<input type="hidden" name="tacID_Old" id="tacID_Old" value="<?php echo $tacID_Old1;?>">
	<input type="hidden" name="tacXlsRecID_Old" id="tacXlsRecID_Old" value="<?php echo $tacXlsRecID_Old1;?>">	
	<input type="hidden" name="statusApp_now" id="statusApp_now" value="<?php echo $statusApp;?>">		
	<input type="hidden" name="method" id="method" value="approve">	
	<input name="appv" type="submit" value="อนุมัติ" onClick="return confirmappv('1')"/>
	<input name="unappv" type="submit" value="ไม่อนุมัติ" onClick="return confirmappv('0')"/>
</form>
<?php } ?>
<script type="text/javascript">

$(document).ready(function(){
	$("#submitButton").focus();
});
function confirmappv(no){
	if(no=='1'){
		if(confirm("ยืนยันการอนุมัติ")==true){
			return true;}
		else{return false;}
	}
	else{
		if(confirm("ยืนยันการไม่อนุมัติ")==true){
			return true;}
	else{return false;}
	}
}
</script>
<?php }?>
</body>
</html>
