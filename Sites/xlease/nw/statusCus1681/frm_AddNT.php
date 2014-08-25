<?php
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$car = $_GET['car'];

if(empty($car)){
   $car = $_POST['car'];
}

$curretac_nt_date=nowDate();
$currentDate=date('d-m-Y');
$qry_name=pg_query("select * from \"Taxiacc\" where \"CusID\"='$car'");
$numrows = pg_num_rows($qry_name);
if($res_name=pg_fetch_array($qry_name)){
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
<script type="text/javascript">
$(document).ready(function(){
    $("#tac_nt_date").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.tac_nt_amount.focus();
		return false;
	}
	return true;
}
function check_year(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode < 8 || charCode > 8) && (charCode < 48 || charCode > 57) ) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.cutYear.focus();
		return false;
	}
	return true;
}
function checkdata() {
	if(document.form1.tac_nt_date.value=="") {
		alert("กรุณาระบุวันที่ออก NT");
		return false;
	}else if(document.form1.tac_year_start.value==""){
		alert("กรุณาระบุปีที่เริ่มคิด NT");
		document.form1.tac_year_start.focus();
		return false;
	}else if(document.form1.tac_year_end.value==""){
		alert("กรุณาระบุปีที่สิ้นสุดคิด NT");
		document.form1.tac_year_end.focus();
		return false;
	}else if(document.form1.tac_year_start.value == document.form1.tac_year_end.value){
		if(document.form1.tac_month_start.value > document.form1.tac_month_end.value){
			alert("เดือนเริ่มต้นต้องน้อยกว่าเดือนสิ้นสุด");
			return false;
		}
	}else if(document.form1.tac_year_start.value > document.form1.tac_year_end.value){
		alert("ปีเริ่มต้นต้องน้อยกว่าปีสิ้นสุด");
		document.form1.tac_year_start.focus();
		return false;
	}else{
		return true;
	}
	
}

</script>
<?php
if($numrows ==0){
	echo "<h2>ไม่พบข้อมูล</h2>";
}else{
	$qry_nt=pg_query("select * from tac_old_nt where tac_cusid='$CusID'");
	$numrowsnt = pg_num_rows($qry_nt);
	if($numrowsnt != 0){
		if($res_nt=pg_fetch_array($qry_nt)){
			$tac_nt_running=trim($res_nt["tac_nt_running"]); 
			$tac_cusid=trim($res_nt["tac_cusid"]); 
			$tac_nt_date=trim($res_nt["tac_nt_date"]);
			$tac_nt_start=trim($res_nt["tac_nt_start"]); 
			$tac_month_start=substr($tac_nt_start,5,2);
			$tac_year_start=substr($tac_nt_start,0,4);
			
			$tac_nt_end=trim($res_nt["tac_nt_end"]); 
			$tac_month_end=substr($tac_nt_end,5,2);
			$tac_year_end=substr($tac_nt_end,0,4);

			$tac_nt_amount=trim($res_nt["tac_nt_amount"]); 
			$tac_nt_amount=number_format($tac_nt_amount,2);
		}
	}
?>
<form name="form1" method="post" action="process_nt.php">
<hr width="80%" color="#CCCCCC"><br>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center">
<tr>
	<td colspan="3">
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
		<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25">
			<th>สัญญาเลขที่</th>
			<th>สัญญารับโอนไป</th>
			<th>รหัสเครื่องวิทยุ</th>
			<th>รหัสวิทยุ</th>
			<th>ทะเบียนรถยนต์</th>
			<th>ชื่อ-นามสกุลลูกค้า</th>
			<th>ยอดค้างชำระ</th>
			<th>ยอดที่ไม่มีใบแจ้งหนี้</th>
			<th>วันเริ่มหยุด Inv</th>	
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
			$CarRegis=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));
		
			$dd=substr($RadioOffDate,0,2);
			$mm=substr($RadioOffDate,3,2);
			$yy=substr($RadioOffDate,6,4);
			$yy=$yy+543;
			$RadioOffDate=$dd."-".$mm."-".$yy;
			
			//หายอดที่ค้างชำระ query ค่า RecNo ที่เป็นค่า null ออกมา 
			$querynull=mssql_query("select sum(PriceUnit) as sumprice,count(PriceUnit) as numnull from TacInvoice where CusID='$CusID' and InvType='REN' and RecNO ='' and convert(varchar,CancelInvDate,103)='01/01/1900'",$conn);
			$numqnull=mssql_num_rows($querynull);
			if($numqnull == 0){
				$sumprice="0.00";
			}else{
				$resnull = mssql_fetch_array($querynull);				
				$sumprice=$resnull["sumprice"];
				$numnull=$resnull["numnull"];
				if($sumprice==""){	
					$sumprice="0.00";
				}else{
					$sumprice=number_format($sumprice,2);
				}
			}
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
			//ยอดที่ไม่มีใบแจ้งหนี้ ค้นหาวันที่ InvFixDate ล่าสุด แล้วนำไปเปรียบเทียบกับวันที่ปัจจุบันว่าห่างกันกี่เดือน
			$querynotnt=mssql_query("select convert(varchar,InvFixDate,103) as InvFixDate from TacInvoice where CusID='$CusID' and InvType='REN' order by InvFixDate DESC",$conn);
			$numnotnt=mssql_num_rows($querynotnt);
			if($numnotnt==0){
				$moneynotnt="ไม่มียอด";
			}else{
				$resnotnt = mssql_fetch_array($querynotnt);
				$InvFixDate=$resnotnt["InvFixDate"];
				
				//นำเดือนและปีมาคำนวณหายอดที่ไม่มีใบแจ้งหนี้
				$m1=substr($RadioOffDate,3,2);
				if($m1=="01" || $m1=="02" || $m1=="03" || $m1=="04" || $m1=="05" || $m1=="06" || $m1=="07" || $m1=="08" || $m1=="09"){
					$m1=substr($m1,1);
				}
				$y1=substr($RadioOffDate,6,4);
				
				$m2=substr($currentDate,3,2);
				if($m2=="01" || $m2=="02" || $m2=="03" || $m2=="04" || $m2=="05" || $m2=="06" || $m2=="07" || $m2=="08" || $m2=="09"){
					$m2=substr($m2,1);
				}
				$y2=substr($currentDate,6,4);
				$y2=$y2+543;
				
				if($y1 == $y2){
					$m=$m2 - $m1;
					$y=0;
				}else{
					$m=(12-$m1)+$m2;
					$y=$y2-$y1;
					if($y==1){
						$y=0;
					}else{
						$y=$y-1;
					}
				}
	
				$numFixDate=$m+($y*12);
				$moneynotnt=320*$numFixDate;
				$moneynotnt=number_format($moneynotnt,2);
			}
		}
		echo "<tr bgcolor=#DBF2FD>";
		echo "<td align=center>$CusID</td>";
		echo "<td align=center>$CusIDNew</td>";
		echo "<td align=center>$RadioONID</td>";
		echo "<td align=center>$RadioID</td>";
		echo "<td align=center>$CarRegis</td>";
		echo "<td>$fullname</td>";
		echo "<td align=right>$sumprice</td>";
		echo "<td align=right>$moneynotnt</td>";
		if($RadioOff=='1'){
			$offdate=$RadioOffDate;
		}else{
			$offdate="<font color=red>ยังไม่ยกเลิก</font>";
		}
		$doff=substr($offdate,0,2);
		$moff=substr($offdate,3,2);
		$yoff=substr($offdate,6,4);
		$yoff=$yoff-543;
		$offdate2=$yoff."-".$moff."-".$doff;
		echo "<td align=center>$offdate2</td>";
		echo "</tr>";
		?>
		</table>
	</td>
</tr>
<tr>
	<?php
		if($tac_nt_date==""){
			$tac_nt_date=$curretac_nt_date;
		}
	?>
	<td align="right"style="font-weight:bold;">วันที่ออก NT(ค.ศ.):</td>
    <td bgcolor="#FFFFFF"><input type="text" id="<?php if($tac_nt_running != ""){ echo "tac_date";}else{ echo "tac_nt_date";}?>" name="tac_nt_date" value="<?php echo $tac_nt_date?>" size="15" style="text-align: center;" <?php if($tac_nt_running != ""){ echo "readonly";}?>></td>
</tr>
<tr>
	<td align="right"style="font-weight:bold;">เริ่มตั้งแต่เดือน :</td>
	<td bgcolor="#FFFFFF">
	<?php if($tac_nt_running==""){?>
		<select name="tac_month_start">
			<option value="01" <?php if($tac_month_start=="01"){ echo "selected";}?>>มกราคม</option>
			<option value="02" <?php if($tac_month_start=="02"){ echo "selected";}?>>กุมภาพันธ์</option>
			<option value="03" <?php if($tac_month_start=="03"){ echo "selected";}?>>มีนาคม</option>
			<option value="04" <?php if($tac_month_start=="04"){ echo "selected";}?>>เมษายน</option>
			<option value="05" <?php if($tac_month_start=="05"){ echo "selected";}?>>พฤษภาคม</option>
			<option value="06" <?php if($tac_month_start=="06"){ echo "selected";}?>>มิถุนายน</option>
			<option value="07" <?php if($tac_month_start=="07"){ echo "selected";}?>>กรกฎาคม</option>
			<option value="08" <?php if($tac_month_start=="08"){ echo "selected";}?>>สิงหาคม</option>
			<option value="09" <?php if($tac_month_start=="09"){ echo "selected";}?>>กันยายน</option>
			<option value="10" <?php if($tac_month_start=="10"){ echo "selected";}?>>ตุลาคม</option>
			<option value="11" <?php if($tac_month_start=="11"){ echo "selected";}?>>พฤศจิกายน</option>
			<option value="12" <?php if($tac_month_start=="12"){ echo "selected";}?>>ธันวาคม</option>
		</select>
	<?php 
	}else{
		if($tac_month_start=="01"){
			$txtmonth="มกราคม";
		}else if($tac_month_start=="02"){
			$txtmonth="กุมภาพันธ์";
		}else if($tac_month_start=="03"){
			$txtmonth="มีนาคม";
		}else if($tac_month_start=="04"){
			$txtmonth="เมษายน";
		}else if($tac_month_start=="05"){
			$txtmonth="พฤษภาคม";
		}else if($tac_month_start=="06"){
			$txtmonth="มิถุนายน";
		}else if($tac_month_start=="07"){
			$txtmonth="กรกฎาคม";
		}else if($tac_month_start=="08"){
			$txtmonth="สิงหาคม";
		}else if($tac_month_start=="09"){
			$txtmonth="กันยายน";
		}else if($tac_month_start=="10"){
			$txtmonth="ตุลาคม";
		}else if($tac_month_start=="11"){
			$txtmonth="พฤศจิกายน";
		}else if($tac_month_start=="12"){
			$txtmonth="ธันวาคม";
		}
		echo "<input type=\"text\" name=\"tac_month_start\" value=\"$txtmonth\" size=\"15\" style=\"text-align:center\" readonly>";
	}?>
		<b>ปี ค.ศ.</b><input type="text" name="tac_year_start" value="<?php echo $tac_year_start;?>" onkeypress="return check_year(event);" maxlength="4" size="10" <?php if($tac_nt_running != ""){ echo "readonly";}?>>
		&nbsp;<b>สิ้นสุดเดือน :</b>
	<?php if($tac_nt_running==""){?>
		<select name="tac_month_end">
			<option value="01" <?php if($tac_month_end=="01"){ echo "selected";}?>>มกราคม</option>
			<option value="02" <?php if($tac_month_end=="02"){ echo "selected";}?>>กุมภาพันธ์</option>
			<option value="03" <?php if($tac_month_end=="03"){ echo "selected";}?>>มีนาคม</option>
			<option value="04" <?php if($tac_month_end=="04"){ echo "selected";}?>>เมษายน</option>
			<option value="05" <?php if($tac_month_end=="05"){ echo "selected";}?>>พฤษภาคม</option>
			<option value="06" <?php if($tac_month_end=="06"){ echo "selected";}?>>มิถุนายน</option>
			<option value="07" <?php if($tac_month_end=="07"){ echo "selected";}?>>กรกฎาคม</option>
			<option value="08" <?php if($tac_month_end=="08"){ echo "selected";}?>>สิงหาคม</option>
			<option value="09" <?php if($tac_month_end=="09"){ echo "selected";}?>>กันยายน</option>
			<option value="10" <?php if($tac_month_end=="10"){ echo "selected";}?>>ตุลาคม</option>
			<option value="11" <?php if($tac_month_end=="11"){ echo "selected";}?>>พฤศจิกายน</option>
			<option value="12" <?php if($tac_month_end=="12"){ echo "selected";}?>>ธันวาคม</option>
		</select>
	<?php 
	}else{
		if($tac_month_end=="01"){
			$txtmonth2="มกราคม";
		}else if($tac_month_end=="02"){
			$txtmonth2="กุมภาพันธ์";
		}else if($tac_month_end=="03"){
			$txtmonth2="มีนาคม";
		}else if($tac_month_end=="04"){
			$txtmonth2="เมษายน";
		}else if($tac_month_end=="05"){
			$txtmonth2="พฤษภาคม";
		}else if($tac_month_end=="06"){
			$txtmonth2="มิถุนายน";
		}else if($tac_month_end=="07"){
			$txtmonth2="กรกฎาคม";
		}else if($tac_month_start=="08"){
			$txtmonth2="สิงหาคม";
		}else if($tac_month_end=="09"){
			$txtmonth2="กันยายน";
		}else if($tac_month_end=="10"){
			$txtmonth2="ตุลาคม";
		}else if($tac_month_end=="11"){
			$txtmonth2="พฤศจิกายน";
		}else if($tac_month_end=="12"){
			$txtmonth2="ธันวาคม";
		}
		echo "<input type=\"text\" name=\"tac_month_end\" value=\"$txtmonth2\" size=\"15\" style=\"text-align:center\" readonly>";
	}?>
		<b>ปี ค.ศ.</b><input type="text" name="tac_year_end" value="<?php echo $tac_year_end;?>" onkeypress="return check_year(event);" maxlength="4" size="10" <?php if($tac_nt_running != ""){ echo "disabled";}?>>
	</td>
</tr>
<tr>
	<?php
		if($tac_nt_amount==""){
			$tac_nt_amount="0.00";
		}
	?>
	<td align="right"style="font-weight:bold;">จำนวนเงินที่ต้องจ่าย(บาท) :</td>
	<td bgcolor="#FFFFFF"><input type="text" style="text-align:right;" name="tac_nt_amount" id="tac_nt_amount" value="<?php echo $tac_nt_amount;?>" onkeypress="return check_number(event);" <?php if($tac_nt_running != ""){ echo "disabled";}?>> (จุดทศนิยม 2 ตำแหน่ง)</td>
</tr>
</table>
<table width="40%" align="center" border="0">

<tr height="50">
<td align="center"><input type="hidden" name="CusID" value="<?php echo $CusID;?>"><?php if($tac_nt_running ==""){?><input type="submit" value="ออก NT" onclick="return checkdata();"><?php }else{?><input type="button" value="Reprint NT" onclick="window.open('pdf_print_nt.php?tac_nt_running=<?php echo $tac_nt_running;?>')"><?php }?></td>
</tr>
</table>
</form>
<?php }?>
