<?php
session_start();
set_time_limit (0); 
ini_set("memory_limit","256M"); 

include("../../config/config.php");
include("../function/checknull.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) รันเปรียบเทียบข้อมูล 1681 เก่า', '$add_date')");
//ACTIONLOG---
  
$condition1=$_POST["condition1"];
$condition2=$_POST["condition2"];
$condition3=$_POST["condition3"];
$condition4=$_POST["condition4"];

if($condition1==1){
	$txtcon1="<b>:</b>แสดงเฉพาะที่ยังไม่ได้เช็คศูนย์<b>:</b>";
}
if($condition2==1){
	$txtcon2="<b>:</b>แสดงเฉพาะสัญญาที่ยังไม่ยกเิลิก<b>:</b>";
}
if($condition3==1){
	$txtcon3="<b>:</b>แสดงเฉพาะสัญญาที่มียอดค้างชำระ<b>:</b>";
}
if($condition4==1){
	$txtcon4="<b>:</b>แสดงเฉพาะสัญญาที่เข้าข่ายออก NT<b>:</b>";
}
$txtcon="$txtcon1 $txtcon2 $txtcon3 $txtcon4";
$currentDate=date('d-m-Y');
$shownum=$_POST["shownum"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสถานะบัญชีลูกค้า 1681</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>   
</head>
<body>
<fieldset>
	<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
	<tr>
		<td colspan="15" align="center">
			<div align="center"><h2>สถานะบัญชีลูกค้า 1681</h2></div>
		</td>
	</tr>
	<tr><td colspan="15"><?php echo $txtcon;?></td></tr>
	<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25">
		<th>ลำดับที่</th>
		<th>สัญญาเลขที่</th>
		<th>สัญญารับโอนไป</th>
		<th>รหัสเครื่องวิทยุ</th>
		<th>รหัสวิทยุ</th>
		<th>ทะเบียนรถยนต์</th>
		<th>คำนำหน้า</th>
		<th>ชื่อลูกค้า</th>
		<th>นามสกุลลูกค้า</th>
		<th>ยอดค้างชำระ</th>
		<th>ยอดที่ไม่มีใบแจ้งหนี้</th>
		<th>ยังไม่ออก NT</th>
		<th>วันเริ่มหยุด Inv</th>
		<th>วันเลิกสัญญา</th>
		<th>ตัดหนี้สูญไปแล้ว</th>
		<th>หนี้สูญรับคืน</th>
		<th>วันที่ล็อค</th>
		<th>เช็คศูนย์วิทยุแล้ว</th>
		
	</tr>
	<?php
		pg_query("BEGIN WORK");
		$status = 0;
		//ดึงข้อมูลในตาราง  TacCusDtl ทั้งหมด โดยแสดงตามเงื่อนไขที่กำหนด
		$sql=mssql_query("select Top $shownum a.CusID,a.PreName,a.Name,a.SurName,b.RadioONID,b.RentPrice,b.RadioOff,convert(varchar,b.RadioOffDate,103) as RadioOffDate,b.RadioID,a.CarRegis from TacCusDtl a
		left join TacRadio b on a.CusID=b.CusID 
		where RadioONID <> '0' order by a.CusID",$conn); 
		$i=1;
		while($res = mssql_fetch_array($sql)){
			$CusID=trim(iconv('WINDOWS-874','UTF-8',$res["CusID"]));
			$PreName=trim(iconv('WINDOWS-874','UTF-8',$res["PreName"]));
			$Name=trim(iconv('WINDOWS-874','UTF-8',$res["Name"]));
			$SurName=trim(iconv('WINDOWS-874','UTF-8',$res["SurName"]));
			$RadioONID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioONID"]));
			$RadioOff=trim(iconv('WINDOWS-874','UTF-8',$res["RadioOff"]));
			$RadioOffDate=trim(iconv('WINDOWS-874','UTF-8',$res["RadioOffDate"]));
			$RentPrice=$res["RentPrice"];
			$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioID"]));
			$CarRegis2=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));
			if($CarRegis2==""){
				$CarRegis="-";
			}else{
				$CarRegis=$CarRegis2;
			}
			
			if($CarRegis2=="-" or $CarRegis2=="--" or $CarRegis2=="---"){
				$CarRegis2="null";
			}else{
				$CarRegis2=checknull($CarRegis2);
			}
			$fullname="$PreName$Name  $SurName";
			
			//update ทะเบียนรถ,รหัสวิทยุ,ชื่อลูกค้า
			$qrytaxiacc=pg_query("select * from \"Taxiacc\" where \"CusID\"='$CusID'");
			$restxia=pg_fetch_array($qrytaxiacc);
			$numxia=pg_num_rows($qrytaxiacc);
			if($numxia>0){
				$update="update \"Taxiacc\" set \"carregis\"=$CarRegis2,\"RadioID\"='$RadioID',\"fullname\"='$fullname' where \"CusID\"='$CusID'";
				if($resup=pg_query($update)){	
				}else{
					$status++;
				}
			}
			
			
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
			
			//ค้นหาในตารางที่สร้างใหม่ คือ ตาราง Taxiacc ใน pg
			$query_taxiacc=pg_query("select * from \"Taxiacc\" where \"CusID\"='$CusID'");
			$num_taxi=pg_num_rows($query_taxiacc);
			if($num_taxi==0){ //พบว่าไม่มีข้อมูลให้ Insert ใน Taxiacc
				if($numnull >= 7){ 
					$statusNT="0"; //ยอดที่ยังไม่ชำระถ้ามากกว่าหรือเท่ากับ 6 ให้สถานะเป็นเข้าข่ายออก NT
				}else{
					$statusNT="9";  //ยอดที่ยังไม่ชำระถ้าน้อยกว่า 6 ให้สถานะเป็นไม่เข้าข่ายออก NT
				}
				$cutAccount="0";
				$statusLock="9";
				$checkDate="1900-01-01";
				$ntrec=0;
				$radiostop="1900-01-01";
				$ins_taxi="insert into \"Taxiacc\" (\"CusID\",\"statusNT\",\"cutAccount\",\"statusLock\",\"checkDate\",\"ntrec\",\"radiostop\",\"carregis\",\"RadioID\",\"fullname\") values 
				('$CusID','$statusNT','$cutAccount','$statusLock','$checkDate','$ntrec','$radiostop',$CarRegis2,'$RadioID','$fullname')";
				if($result_taxi=pg_query($ins_taxi)){
				}else{
					$status++;
				}
			}else{ //กรณีพบค่าให้ทำการอัพเดทข้อมูลที่สถานะเปลี่ยนไป
				if($numnull >= 7){ //ยอดที่ยังไม่ชำระถ้ามากกว่าหรือเท่ากับ 6 ให้เปลี่ยนสถานะเป็นเข้าข่ายออก NT
					$query_taxiacc3=pg_query("select * from \"Taxiacc\" where \"CusID\"='$CusID'");
					$res_taxi2=pg_fetch_array($query_taxiacc3);
					$statusNT2=$res_taxi2["statusNT"];
					
					if($statusNT2=="9"){
						$update2="update \"Taxiacc\" set \"statusNT\"='0' where \"CusID\"='$CusID'";
						if($resup2=pg_query($update2)){	
						}else{
							$status++;
						}
					}
				}
				
				$query_taxiacc2=pg_query("select * from \"Taxiacc\" where \"CusID\"='$CusID'");
				$res_taxi=pg_fetch_array($query_taxiacc2);
				$statusNT=$res_taxi["statusNT"];
				$cutAccount=$res_taxi["cutAccount"];
				$statusLock=$res_taxi["statusLock"];
				$checkDate=$res_taxi["checkDate"];
				$NTDate=$res_taxi["NTDate"];
				$ntrec=$res_taxi["ntrec"];
				if($ntrec==""){
					$ntrec="0.00";
				}
				$radiostop=$res_taxi["radiostop"];
				if($radiostop==""){
					$radiostop="1900-01-01";
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
			if($i%2 ==0){
				$color="#DBF2FD";
			}else{
				$color="#C9EBFC";
			}
			
			if(($condition1==1 and $checkDate=='1900-01-01' and $condition2==0 and $condition3==0 and $condition4==0)||
			($condition2==1 and $RadioOff=='0' and $condition1==0 and $condition3==0 and $condition4==0)||
			($condition3==1 and $sumprice !="0.00" and $condition1==0 and $condition2==0 and $condition4==0)||
			($condition4==1 and $statusNT==0 and $condition1==0 and $condition2==0 and $condition3==0)||
			
			($condition1==1 and $checkDate=='1900-01-01' and $condition2==1 and $RadioOff=='0' and $condition3==0 and $condition4==0)||
			($condition1==1 and $checkDate=='1900-01-01' and $condition3==1 and $sumprice !="0.00" and $condition2==0 and $condition4==0)||
			($condition1==1 and $checkDate=='1900-01-01' and $condition4==1 and $statusNT==0 and $condition2==0 and $condition3==0)||
			($condition1==1 and $checkDate=='1900-01-01' and $condition2==1 and $RadioOff=='0' and $condition3==1 and $sumprice !="0.00" and $condition4==0)||
			($condition1==1 and $checkDate=='1900-01-01' and $condition2==1 and $RadioOff=='0' and $condition4==1 and $statusNT==0 and $condition4==0)||
			($condition1==1 and $checkDate=='1900-01-01' and $condition3==1 and $sumprice !="0.00" and $condition4==1 and $statusNT==0 and $condition2==0)||
			
			($condition2==1 and $RadioOff=='0' and $condition3==1 and $sumprice !="0.00" and $condition1==0 and $condition4==0)||
			($condition2==1 and $RadioOff=='0' and $condition4==1 and $statusNT==0 and $condition1==0 and $condition3==0)||
			($condition2==1 and $RadioOff=='0' and $condition3==1 and $sumprice !="0.00" and $condition4==1 and $statusNT==0 and $condition1==0)||
			
			($condition3==1 and $sumprice !="0.00" and $condition4==1 and $statusNT==0 and $condition1==0 and $condition2==0)||
			(($condition1==1 and $checkDate=='1900-01-01') and ($condition2==1 and $RadioOff=='0') and ($condition3==1 and $sumprice !="0.00") and ($condition4==1 and $statusNT==0))||
			($condition1==0 and $condition2==0 and $condition3==0 and $condition4==0)){
				echo "<tr height=25 bgcolor=$color>";
				echo "<td align=center>$i</td>";
				echo "<td align=center>$CusID</td>";
				echo "<td align=center>$CusIDNew</td>";
				echo "<td align=center>$RadioONID</td>";
				echo "<td align=center>$RadioID</td>";
				echo "<td align=center>$CarRegis</td>";
				echo "<td>$PreName</td>";
				echo "<td>$Name</td>";
				echo "<td>$SurName</td>";
				echo "<td align=right>$sumprice</td>";
				echo "<td align=right>$moneynotnt</td>";
				
				if($statusNT==1){
					$txtstatus="<center>$NTDate</center>";
				}else if($statusNT==0){
					$txtstatus="เข้าข่ายแต่ยังไม่ออกหรือไม่ทราบ";
				}else if($statusNT==9){
					$txtstatus="ไม่เข้าข่ายออก NT";
				}
				echo "<td>$txtstatus</td>";
					
				if($RadioOff=='1'){
					$offdate=$RadioOffDate;
					$doff=substr($offdate,0,2);
					$moff=substr($offdate,3,2);
					$yoff=substr($offdate,6,4);
					$yoff=$yoff-543;
					$offdate2=$yoff."-".$moff."-".$doff;
				}else{
					$offdate2="<font color=red>ยังไม่ยกเลิก</font>";
				}
				echo "<td align=center>$offdate</td>";
				
				if($radiostop=="1900-01-01"){
					$radiostoptxt="ยังไ่ม่ยกเลิก";
				}else{
					$radiostoptxt=$radiostop;
				}
				echo "<td align=center>$radiostoptxt</td>";
					
				if($cutAccount=="0"){
					$cutAccount="<font color=red>ไม่ได้ตัด</font>";
				}else{
					$cutAccount=number_format($cutAccount,2);
				}
				echo "<td align=right>$cutAccount</td>";
				echo "<td align=right>".number_format($ntrec,2)."</td>";
					
				if($statusLock=="0"){
					$txtlock="ไม่ได้ล็อค";
				}else if($statusLock=="1"){
					$txtlock="ล็อคแล้ว";
				}else if($statusLock=="9"){
					$txtlock="ยังไม่ทราบว่าล็อคหรือไม่";
				}
				echo "<td align=center>$txtlock</td>";
					
				if($checkDate=="1900-01-01"){
					$checkDate="ยังไม่เช็ค";
				}
				echo "<td align=center>$checkDate</td>";
				echo "</tr>";
				$i++;
			}
		}
		if($status == 0){
			pg_query("COMMIT");	
		}else{
			pg_query("ROLLBACK");
		}
		if($i==1){
			echo "<tr><td height=60 colspan=15 align=center bgcolor=#E9F8FE><b>ไม่พบรายการ</b></td></tr>";
		}
	?>
	<tr><td height="50" colspan="15"><input type="button" value="กลับ" onclick="window.location='frm_Index.php'"></td></tr>
	</table>
</fieldset> 
</body>
</html>