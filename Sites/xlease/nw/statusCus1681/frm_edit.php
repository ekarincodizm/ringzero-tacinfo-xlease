<?php
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$car = $_GET['car'];

if(empty($car)){
   $car = $_POST['car'];
}

$currentDate=date('d-m-Y');
$qry_name=pg_query("select * from \"Taxiacc\" where \"CusID\"='$car'");
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
<script type="text/javascript">
$(document).ready(function(){
    $("#NTDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#checkDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#radiostop").datepicker({
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
		document.form1.cutAccount.focus();
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
	if(document.getElementById("statusDate2").checked) {
		if(document.getElementById("checkDate").value==""){
			alert("กรุณาระบุวันที่เช็คศูนย์");
			return false;
		}
	}else if(document.form1.cutAccount.value=="" || document.form1.cutAccount.value=="0.00" || document.form1.cutAccount.value==0){
		if(document.form1.cutYear.value!=""){
			alert("ต้องไม่ระบุปี ค.ศ.ที่ตัดหนี้ ");
			document.form1.cutYear.value="";
			return false;
		}
	}else if(parseFloat(document.getElementById("cutAccount").value.replace(/\,/g,"")) < parseFloat(document.getElementById("ntrec").value.replace(/\,/g,""))){
		alert(parseFloat(document.getElementById("cutAccount").value));
		alert(parseFloat(document.getElementById("ntrec").value));
		alert("หนี้สูญรับคืนต้องน้อยกว่าหรือเท่ากับ ตัดหนี้สูญ");
		document.form1.ntrec.focus();
		return false;
	}else if(document.form1.cutAccount.value!="" || document.form1.cutAccount.value!="0.00" || document.form1.cutAccount.value!=0){
		if(document.form1.cutYear.value==""){
			alert("กรุณาระบุปี ค.ศ.ที่ตัดหนี้ ");
			document.form1.cutYear.focus();
			return false;
		}
	}else{
		return true;
	}
	
}
function check_search(){
	if(document.getElementById("statusDate1").checked){
		document.getElementById("checkDate").disabled =true;
		document.getElementById("checkDate").value ="";
	}else if(document.getElementById("statusDate2").checked){
		document.getElementById("checkDate").disabled =false;
	}
}
</script>
<?php
if($numrows ==0){
	echo "<h2>ไม่พบข้อมูล</h2>";
}else{
?>
<form name="form1" method="post" action="process_update.php">
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
	<td width="150" style="font-weight:bold;"align="right">สถานะ NT :</td>
    <td bgcolor="#FFFFFF">
		<input type="radio" name="statusNT" value="9" <?php if($statusNT==9){ echo "checked";}?>>ไม่เข้าข่าย&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="statusNT" value="0" <?php if($statusNT==0){ echo "checked";}?>>เข้าข่ายแต่ยังไม่ออกหรือไม่ทราบ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="statusNT" value="1" <?php if($statusNT==1){ echo "checked";}?>>ออกแล้ว
	</td>
</tr>
<tr>
	<td align="right"style="font-weight:bold;">วันที่ออก NT(ค.ศ.):</td>
    <td bgcolor="#FFFFFF"><input type="text" id="NTDate" name="NTDate" value="<?php echo $NTDate?>" size="15" style="text-align: center;" ></td>
</tr>
<tr>
	<td align="right"style="font-weight:bold;">วันที่เลิกสัญญา:</td>
    <td bgcolor="#FFFFFF"><input type="text" id="radiostop" name="radiostop" value="<?php echo $radiostop?>" size="15" style="text-align: center;" ></td>
</tr>
<tr>
	<td align="right"style="font-weight:bold;">ตัดหนี้สูญจำนวน :</td>
	<td bgcolor="#FFFFFF"><input type="text" style="text-align:right;" name="cutAccount" id="cutAccount" value="<?php echo $cutAccount;?>" onkeypress="return check_number(event);"> บาท&nbsp;&nbsp;&nbsp;<b>ปี ค.ศ.ที่ตัดหนี้ :</b><input type="text" name="cutYear" value="<?php echo $cutYear;?>" onkeypress="return check_year(event);" maxlength="4" size="10"></td>
</tr>
<tr>
	<?php
	if($ntrec==""){
		$ntrec="0.00";
	}
	?>
	<td align="right"style="font-weight:bold;">หนี้สูญรับคืน :</td>
	<td bgcolor="#FFFFFF"><input type="text" style="text-align:right;" name="ntrec" id="ntrec" value="<?php echo $ntrec;?>" onkeypress="return check_number(event);"> บาท</td>
</tr>
<tr><td align="right"style="font-weight:bold;">Lock วิทยุ :</td>
	<td bgcolor="#FFFFFF">
		<input type="radio" name="statusLock" value="0" <?php if($statusLock==0){ echo "checked";}?>>ไม่ Lock&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="statusLock" value="1" <?php if($statusLock==1){ echo "checked";}?>>Lock&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="statusLock" value="9" <?php if($statusLock==9){ echo "checked";}?>>ยังไม่ทราบว่า Lock หรือไม่
	</td>
</tr>
<tr><td align="right"style="font-weight:bold;">วันที่เช็คศูนย์ :</td>
	<td bgcolor="#FFFFFF">
		<?php
			if($checkDate=="" || $checkDate=="1900-01-01"){
				$checkDate2=nowDate();
			}else{
				$checkDate2=$checkDate;
			}
		?>
		<input type="radio" name="statusDate" id="statusDate1" value="0" <?php if($checkDate == '1900-01-01'){ echo "checked";}?> onclick="check_search()">ยังไม่เช็ค&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="statusDate" id="statusDate2" value="1" <?php if($checkDate != '1900-01-01'){ echo "checked";}?> onclick="check_search()">เช็คแล้ว&nbsp;<input type="text" id="checkDate" name="checkDate" value="<?php echo $checkDate2; ?>" size="15" style="text-align: center;" <?php if($checkDate == '1900-01-01'){ echo "disabled";}?>>
	</td>
</tr>
</table>
<table width="40%" align="center" border="0">
<?php 
if($C_REGIS == "ป้ายแดง"){
	echo "<tr><td align=\"center\"style=\"font-weight:bold;\" ><font color=red size=3>ไม่สามารถบันทึกได้ เนื่องจากยังไม่มีทะเบียนรถ</font></td></tr>
";
}
?>
<tr height="50">
<td align="center"><input type="hidden" name="CusID" value="<?php echo $CusID;?>"><input type="submit" value="บันทึกรายการ" onclick="return checkdata();" <?php if($C_REGIS == "ป้ายแดง"){ echo "disabled";}?>></td>
</tr>
</table>
</form>
<?php }?>
