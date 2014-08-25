<?php
include("../../config/config.php");
$car = pg_escape_string($_GET['car']);

if(empty($car)){
   $car = pg_escape_string($_POST['car']);
}

$currentDate=nowDate();

$qry_name=pg_query("select \"CusID\", \"statusNT\", \"cutAccount\", \"statusLock\", \"checkDate\", \"radiostop\", \"NTDate\", \"cutYear\", \"ntrec\"
					from \"Taxiacc\" where \"CusID\"='$car'");
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

if($numrows ==0){
	echo "<h2>ไม่พบข้อมูล</h2>";
}else{
?>
<hr width="80%" color="#CCCCCC">
<?php
	$sql1 = pg_query("select \"CusID\" FROM \"Cancel_Radio\" where \"CusID\" = '$car'");
	$rows1 = pg_num_rows($sql1);

	if($rows1 > 0){	
		echo "<center><h2><font color=\"red\">สัญญาวิทยุนี้ยกเลิกแล้ว</font></h2></center>";
	}
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
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
		
		echo "<tr bgcolor=#DBF2FD>";
		echo "<td align=center>$CusID</td>";
		echo "<td align=center>$CusIDNew</td>";
		echo "<td align=center>$RadioONID</td>";
		echo "<td align=center>$RadioID</td>";
		if($numcar>0){
			echo "<td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><u>$CarRegis</u></a></td>";
		}else{
			echo "<td align=center>$CarRegis</td>";
		}		echo "<td>$fullname</td>";
		echo "</tr>";
		
		?>
		</table>
	</td>
</tr>
</table>

<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
<tr>
	<td colspan="6" bgcolor="#FFCCCC"><b>รายละเอียดรับชำระแทน 1681</b></td>
</tr>
<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25" align="center">
	<td><b>ลำดับที่</b></td>
	<td><b>เลขที่ใบเสร็จ</b></td>
	<td><b>วันที่รับชำระ</b></td>
	<td><b>จำนวนเงินทั้งหมด (บาท)</b></td>
	<td><b>เดือนที่ชำระค่าวิทยุ</b></td>
	<td><b>เลขที่ใบเสร็จ TAC</b></td>
</tr>
<?php
	$querytac=pg_query("select distinct(\"tacXlsRecID\"),\"tacTempDate\" from \"tacReceiveTemp\" where \"tacID\"='$CusID' order by \"tacTempDate\" DESC");
	$numtac=pg_num_rows($querytac);
	$i=1;
	while($restac=pg_fetch_array($querytac)){
		$tacXlsRecID=$restac["tacXlsRecID"];
		$tacTempDate=$restac["tacTempDate"];
		
		$querydetail=pg_query("select sum(\"tacMoney\") as summoney,min(\"tacMonth\") as monthmin,max(\"tacMonth\") as monthmax,\"tacOldRecID\" from \"tacReceiveTemp\" where \"tacID\"='$CusID' and \"tacXlsRecID\"='$tacXlsRecID' group by \"tacOldRecID\"");
		if($resdetail=pg_fetch_array($querydetail)){
			$tacMoney=number_format($resdetail["summoney"],2);
			$tacMonthMin=$resdetail["monthmin"];
			$ymin=substr($tacMonthMin,0,4);
			$dmin=substr($tacMonthMin,5,2);
			$tacMonthMin=$ymin."/".$dmin;
			
			$tacMoneyMax=$resdetail["monthmax"];
			$ymax=substr($tacMoneyMax,0,4);
			$dmax=substr($tacMoneyMax,5,2);
			$tacMoneyMax=$ymax."/".$dmax;
			
			$tacOldRecID=$resdetail["tacOldRecID"]; if($tacOldRecID=="") $tacOldRecID="ไม่มี";
		}
		if($i%2 == 0){
			$color="#DBF2FD";
		}else{
			$color="#AAE0FB";
		}
		echo "<tr align=center bgcolor=$color>";
		echo "<td>$i</td>";
		echo "<td>$tacXlsRecID</td>";
		echo "<td align=center>$tacTempDate</td>";
		echo "<td align=right>$tacMoney</td>";
		echo "<td>$tacMonthMin - $tacMoneyMax</td>";
		echo "<td>$tacOldRecID</td>";
		echo "</tr>";
		$i++;
	}
	if($numtac==0){
		echo "<tr align=center bgcolor=#DBF2FD>";
		echo "<td colspan=6 height=50><b>-ไม่พบข้อมูล-</b></td>";
		echo "</tr>";
	}
?>
</table>
<?php }?>
