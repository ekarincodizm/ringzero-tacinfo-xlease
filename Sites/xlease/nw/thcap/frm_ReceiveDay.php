<?php
include("../../config/config.php");

$db1="ta_mortgage_datastore";

$month=$_POST["month"];
$year=$_POST["year"];
$chkanalyze=$_POST["chkanalyze"];
$typepay=$_POST["typepay"];
$typepay2=$_POST["typepay"];
if($month=="" and $year==""){ //หาเดือนก่อนหน้านี้
	$currentdate=nowDate();
	$nowm=substr($currentdate,5,2);
	$year=substr($currentdate,0,4);
	$m1=substr($nowm,0,1);
	$m2=substr($nowm,1,1);

	if($m1==0){
		if($m2==1){
			$month=12;
			$year=$year-1;
		}else{
			$month=$m2-1;
		}
	}else{
		$month=$nowm-1;
		$nub=strlen($month);
		if($nub==1){
			$month='0'.$month;
		}
	}
}

$val=$_POST["val"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) สมุดรายวันรับเงิน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
<link type="text/css" rel="stylesheet" href="act.css"></link>
    
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	if($('#chkanalyze') .attr( 'checked')==true){ //ถ้าคลิกแจกแจงอยู่ให้เมนูเลือกประเภทแสดงด้วย
		$("#typepay").show();
	}else{
		$("#typepay").val('');
		$("#typepay").hide();
	}
	
	$("#chkanalyze").click(function(){ 
		if($('#chkanalyze') .attr( 'checked')==true){
			$("#typepay").show();
		}else{
			$("#typepay").hide();
			$("#typepay").val('');
		}
	});

});
</script>
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body>
<form method="post" name="form1" action="#">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) สมุดรายวันรับเงิน</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>รายงานสมุดรายวันรับเงิน</B></legend>
				<div align="center">
					<div class="ui-widget">
						<p align="center">
							<b>เดือน</b>
							<select name="month">
								<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
								<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
								<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
								<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
								<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
								<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
								<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
								<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
								<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
								<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
								<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
								<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
							</select>
							<label><b>ปี ค.ศ.</b></label>
							<input type="text" id="year" name="year" value="<?php echo $year; ?>" size="10" style="text-align:center" maxlength="4">
							<input type="checkbox" name="chkanalyze" id="chkanalyze" value="1" <?php if($chkanalyze=="1") echo "checked";?>/><b>แจกแจง</b>
							<select name="typepay" id="typepay">
								<option value="">ทั้งหมด</option>
								<?php
								$qrytype=pg_query("select \"tpID\",\"tpDesc\" from account.\"thcap_typePay\" order by \"tpID\"");
								while($restype=pg_fetch_array($qrytype)){
									list($tpID2,$tpDesc2)=$restype;
								?>
								<option value="<?php echo $tpID2;?>" <?php if($typepay==$tpID2) echo "selected";?>><?php echo $tpID2."-".$tpDesc2;?></option>
								<?php
								}
								?>
							</select>

							<input type="hidden" name="val" value="1"/>
							<input type="submit" id="btn00" value="เริ่มค้น"/>
						</p>
						<?php
						if($val=="1"){ //กรณีมีการ คลิกปุ่ม "เริ่มค้น"
							if($chkanalyze=="1"){ //กรณีมีการแจกแจง								
								if($typepay==""){ //กรณีเลือกแสดงทั้งหมด
									$qryreceipt=pg_query("select \"contractID\",a.\"receiptID\",date(\"receiveDate\") as \"receiveDate\",\"tpDesc\" as \"tpdesc\",
									\"netAmt\" as \"netamt\",\"vatAmt\" as \"vatamt\",\"debtAmt\" as \"debtamt\",\"debtID\" as \"debtid\",\"nameChannel\" as \"channel\",
									\"cusFullname\",\"typePayID\"
									from thcap_v_receipt_otherpay a
									left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\"
									where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' order by \"receiveDate\",a.\"receiptID\"");							
								}else{
									//ดึงข้อมูลใน thcap_v_receipt_otherpay มาแสดง
									$qryreceipt=pg_query("select \"contractID\",a.\"receiptID\",date(\"receiveDate\") as \"receiveDate\",\"tpDesc\" as \"tpdesc\",
									\"netAmt\" as \"netamt\",\"vatAmt\" as \"vatamt\",\"debtAmt\" as \"debtamt\",\"debtID\" as \"debtid\",\"nameChannel\" as \"channel\",
									\"cusFullname\" ,\"typePayID\"
										from thcap_v_receipt_otherpay a
										left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\"
										where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and \"typePayID\"='$typepay'
										order by \"receiveDate\",a.\"receiptID\"");	
									$numqry2=pg_num_rows($qryreceipt);
																		
									//กรณีเลือก type ที่ไม่มีอยู่ใน thcap_v_receipt_otherpay แสดงว่าอาจมีการเลือก ชำระเงินต้น หรือ ดอกเบี้ยของสัญญาที่เป็น LOAN
									if($numqry2==0){
										$qryreceipt=pg_query("select a.\"contractID\",a.\"receiptID\",date(a.\"receiveDate\") as \"receiveDate\",\"receivePriciple\",\"receiveInterest\",\"receiveAmount\",\"nameChannel\" as \"channel\",\"cusFullname\"
										FROM thcap_temp_int_201201 a
										left join thcap_v_receipt_otherpay b on a.\"receiptID\"=b.\"receiptID\"
										left join thcap_temp_receipt_details c on a.\"receiptID\"=c.\"receiptID\"
										where EXTRACT(MONTH FROM a.\"receiveDate\")='$month' and EXTRACT(YEAR FROM a.\"receiveDate\")='$year' and a.\"receiptID\" is not null order by a.\"receiveDate\",a.\"receiptID\"");								
										$numqry=pg_num_rows($qryreceipt);
										if($numqry>0){
											$chk=1;
										}
									}
								}
							}else{
								//กรณีไม่ได้เลือกคลิกปุ่มแจกแจง แสดงว่าแสดงข้อมูลทั้งหมด
								$qryreceipt=pg_query("select \"contractID\",a.\"receiptID\",date(\"receiveDate\") as \"receiveDate\",\"tpDesc\" as \"tpdesc\",
								\"netAmt\" as \"netamt\",\"vatAmt\" as \"vatamt\",\"debtAmt\" as \"debtamt\",\"debtID\" as \"debtid\",\"nameChannel\" as \"channel\",
								\"cusFullname\" ,\"typePayID\"
								from thcap_v_receipt_otherpay a
								left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\"
								where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' order by \"receiveDate\",a.\"receiptID\"");							
							}
							
							$numreceipt=pg_num_rows($qryreceipt);
						?>
						<div>
						<div align="right"><a href="thcap_receipt_month_pdf.php?&month=<?php echo "$month"; ?>&year=<?php echo "$year"; ?>&chkanalyze=<?php echo "$chkanalyze";?>&typepay=<?php echo $typepay;?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
						<thead>
						<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
							<th width="80">วันที่รับชำระ</th>
							<th width="100">เลขที่ใบเสร็จ<br>รับเงิน</th>
							<th width="150">ชื่อผู้กู้หลัก</th>
							<th width="100">เลขที่สัญญา</th>
							<th width="120">ประเภทค่าใช้จ่าย</th>
							<th>รายได้</th>
							<th>VAT</th>
							<th>จำนวนเงิน<br>ที่รับชำระ</th>
						</tr>
						</thead>
						<?php
						$i=0;
						$sum_amt = 0;
						$sum_all = 0;
						$sum_allnet = 0;
						$sum_allvat = 0;
						$sumnet = 0;
						$sumvat = 0;
						$sum_alltotalnet=0;
						$sum_alltotalvat=0;
						$sum_alltotal=0;
						$old_doerID="";
						$old_receiptID="";
						$old_receiveDate="";
						$old_cusname="";
						$nub=0;
						$p=0; //สำหรับตรวจสอบว่าถ้าเป็น record เดียวไม่ต้องแสดงผลรวม
						while($result=pg_fetch_array($qryreceipt)){
							$showtype=0; //กำหนดค่าเริ่มต้นให้ทุกรายการแสดง
							
							$nub+=1;
							$receiveDate=$result["receiveDate"];
							$receiptID=$result["receiptID"];
							$cusfullname=$result["cusfullname"];
							$contractID=$result["contractID"];
							$tpdesc=$result["tpdesc"]; 
							$netamt=$result["netamt"]; //ค่าใช้จ่ายนั้นๆก่อนภาษีมูลค่าเพิ่ม
							$netamt2=number_format($netamt,2);
							$vatamt=$result["vatamt"]; //ภาษีมูลค่าเพิ่ม
							$vatamt2=number_format($vatamt,2);
							$debtAmt=$result["debtamt"]; //netamt+vatamt
							$debtAmt2=number_format($debtAmt,2);
							$debtid=$result["debtid"];
							$channel=$result["channel"];
							
							if($typepay==""){ //กรณีเลือกแสดงทั้งหมด
								$typePayID=$result["typePayID"]; //ประเภทการจ่ายของแต่ละสัญญา
							}
							
							//กรณีมาจากตาราง  thcap_temp_int_201201 (สัญญาประเภท LOAN)
							$receivePriciple=$result["receivePriciple"]; //เงินต้น
							$receiveInterest=$result["receiveInterest"]; //ดอกเบี้ย
							$receiveAmount=$result["receiveAmount"]; //รวมชำระทั้งหมด
							
							//ตรวจสอบว่าเป็นสัญญาประเภทใด
							$contype=pg_creditType($contractID); 
							
							//หารหัสผ่อนชำระตามสัญญากู้ว่าใช้รหัสอะไร
							$paytype=pg_getminpaytype($contractID); //รหัสผ่อนชำระตามสัญญากู้ของแต่ละสัญญา
							
							//ถ้าเป็นสัญญาประเภท LOAN แสดงว่ามี type เงินต้นและดอกเบี้ย
							if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){ 
								$paytype_a=pg_getprincipletype($contractID);//หารหัสผ่อนชำระตามสัญญากู้-คืนเงินต้น เช่น 1001
								$paytype_b=pg_getinteresttype($contractID);//หารหัสผ่อนชำระตามสัญญากู้-ดอกเบี้ย เช่น 1002
							}

							if($paytype_a!="" and $paytype_b !=""){
								if($typepay==$paytype_a){ //กรณี type ที่เลือกเป็นการจ่ายคืนเงินต้น
									//หาชื่อประเภทการจ่าย
									$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typepay'");
									list($tpdescname)=pg_fetch_array($qrytypename);
									$tpdesc=$tpdescname;
									
									//กำหนดจำนวนเงินให้นำมาจากตาราง 2012 แทนค่าเดิม
									$netamt2=number_format($receivePriciple,2); 
									$debtAmt2=number_format($receivePriciple,2);
									
									if($receivePriciple=='0'){ 
										$showtype=2; //ถ้าเงินต้นไม่มี จะไม่แสดงข้อมูลนี้
										continue;
									}else{
										$showtype=1; //กำหนดให้แสดงข้อมูลนั้น
									}
								}else if($typepay==$paytype_b){ //กรณี type ที่เลือกเป็นการจ่ายดอกเบี้ย
									$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typepay'");
									list($tpdescname)=pg_fetch_array($qrytypename);
									$tpdesc=$tpdescname;
									
									//กำหนดจำนวนเงินให้นำมาจากตาราง 2012 แทนค่าเดิม
									$netamt2=number_format($receiveInterest,2);
									$debtAmt2=number_format($receiveInterest,2);
									
									if($receiveInterest=='0'){
										$showtype=2; //ถ้าดอกเบี้ยไม่มี กำหนดไม่ให้แสดง record นี้
										continue;
									}else{
										$showtype=1; //กำหนดให้แสดง record นี้
									}
								}
							}
						//กรณีกำหนดให้แสดงรายการ
						if($showtype!="2"){ 
							//กรณีเลือกแจกแจง, รายการที่เลือกไม่เท่ากับ "ทั้งหมด" ,รายการที่เลือกไม่เท่ากับผ่อนชำระตามสัญญา, รายการที่เลือกไม่เท่ากับชำระเงินต้น, รายการที่เลือกไม่เท่ากับดอกเบี้ย ,ชำระเงินต้นไม่เท่ากับค่าว่าง และดอกเบี้ยไม่เท่ากับค่าว่าง
							if($chk=="1" and $typepay!="" and $typepay!=$paytype and $typepay!=$paytype_a and $typepay!=$paytype_b and $paytype_a!="" and $paytype_b!=""){
								$showtype=2; //กำหนดไม่ให้แสดง record นี้
								continue;
							}
							
							//ถ้า cusfullname เป็นค่าว่างให้ไปค้นหาชื่อจาก mysql มีโอกาสพบค่าว่างได้เนื่องจากเลขที่ใบเสร็จเก่าอาจยังไม่ได้เก็บชื่อลูกค้าทำให้ไม่พบข้อมูลใน pg
							if($cusfullname==""){
							$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
							$resname=pg_fetch_array($qryname);
								$cusfullname=$resname["thcap_fullname"];
							}else{
								$cusfullname=$cusfullname;
							}
							
							if($receiptID!=$old_receiptID){
								$i+=1;
							}else{
								$p++;
							}
							
							if($i%2==0){
								$color="class=\"odd\"";
							}else{
								$color="class=\"even\"";
							}
							
							//ถ้าเลขใบเสร็จไม่เหมือนกัน ให้แสดงรวมเงินในบรรทัดสุดท้าย
							if(($receiptID != $old_receiptID) && $nub != 1){
								if($i%2==0){
									$color2="class=\"even\"";
								}else{
									$color2="class=\"odd\"";
								}

							}
							//กรณีรวมเงินแต่ละเลขที่ใบเสร็จ
							if($receiptID!=$old_receiptID and $old_receiptID!="" and $showtype!="2"){
								//แสดงช่องทางการชำระทั้งหมด
								$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
								$sumamt=0;
								while($resstar=pg_fetch_array($qryredstar)){
									$chan=$resstar["byChannel"];
									$amt=$resstar["ChannelAmt"];														
									
									if($chan=="999"){
										$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
									}else{
										//นำไปค้นหาในตาราง BankInt
										$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
										$ressearch=pg_fetch_array($qrysearch);
										list($BAccount,$BName)=$ressearch;
										$txtchannel3="ช่องทาง : $BAccount-$BName";
									}
									
									echo "<tr $color2><td colspan=5></td><td colspan=2 align=right bgcolor=#FBFFEC>$txtchannel3</td><td align=right bgcolor=#FBFFEC>".number_format($amt,2)."</td></tr>";
								}
								
								//****************หารวมเงินใบเสร็จ							
								$qrysumtotal=pg_query("select a.\"receiptID\",sum(\"netAmt\"),sum(\"vatAmt\"),sum(\"debtAmt\")
								from thcap_v_receipt_otherpay a 
								left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and a.\"receiptID\" = '$old_receiptID'	group by a.\"receiptID\"");
								list($receipt2,$sumnetamt,$sumvatamt,$sumall_amt)=pg_fetch_array($qrysumtotal);
								
								if(($typepay==$paytype_a || $typepay==$paytype_b) and $paytype_a!="" and $paytype_b !=""){ //กรณีเลือก type ชำระเงินต้น หรือ ดอกเบี้ย
									$sumnet=$sumnet;
									$sumvat=$sumvat;
									$sum_amt=$sum_amt;
								}else{
									$sumnet=$sumnetamt;
									$sumvat=$sumvatamt;
									$sum_amt=$sumall_amt;
								}
								//***********จบหาเงินรวมใบเสร็จ
								
								echo "<tr $color2>
									<td colspan=5 align=right><b>รวมเงินใบเสร็จนี้</b></td>
									<td align=right bgcolor=#FBFFEC><b>".number_format($sumnet,2)."</b></td>
									<td align=right bgcolor=#FBFFEC><b>".number_format($sumvat,2)."</b></td>
									<td align=right bgcolor=#FBFFEC><b>".number_format($sum_amt,2)."</b></td>
								</tr>";
								
								$p=0;
							}
							//ถ้าไม่ใช่วันเดียวกันให้แสดงยอดรวมต่อวัน
							if(($receiveDate != $old_receiveDate) && $nub != 1 and $showtype!="2"){
								if($sum_all>0){
									echo "<tr>
									<td class=\"sum\" align=\"center\"><a href=\"thcap_receipt_day_pdf.php?receiveDate=$old_receiveDate&chkanalyze=$chkanalyze&typepay=$typepay\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
									<td colspan=4 class=\"sum\" align=right><b>ยอดรวมต่อวัน</b></td>
									<td align=right class=\"sum\"><b>".number_format($sum_allnet,2)."</b></td>
									<td align=right class=\"sum\"><b>".number_format($sum_allvat,2)."</b></td>
									<td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b></td>
									</tr>";
									$sum_allnet=0;
									$sum_allvat=0;
									$sum_all=0;
								}
							}
							
							echo "<tr $color align=\"center\">";
							
							if($receiptID==$old_receiptID){//ถ้าเลขที่ใบเสร็จเหมือนกันจะไม่แสดงเลขที่ใบเสร็จซ้ำ
								$receiptID2="";
							}else{ //ถ้าเลขที่ใบเสร็จไม่เหมือนกันจะแสดงเลขที่ใบเสร็จปัจจุบัน
								$receiptID2=$receiptID;
								$sumnet = 0;
								$sumvat = 0;
								$sum_amt = 0;
							}
							
							if($old_receiveDate==$receiveDate){ //ถ้าวันเหมือนกันจะไม่แสดงวันซ้ำ
								$receiveDate2="";
							}else{ //ถ้าวันไม่เหมือนกันจะแสดงวันปัจจุบัน
								$receiveDate2=$receiveDate;
							}
							
							//ถ้าชื่อลูกค้าและใบเสร็จใบเดียวกันจะไม่แสดงชื่อลูกค้าซ้ำ
							if($old_cusname==$cusfullname and $receiptID==$old_receiptID){
								$cusfullname2="";
								
							}else{
								$cusfullname2=$cusfullname;
							}
										
							if($showtype==1){ //กรณีเลือกประเภทเป็นชำระคืนเงินต้นหรือดอกเบี้ย ให้แสดงข้อมูลนี้
								echo "
									<td valign=top>$receiveDate2</td>
									<td valign=top>$receiptID2</td>
									<td align=left valign=top>$cusfullname2</td>
									<td valign=top>$contractID</td>
									<td align=left valign=top>$tpdesc</td>
									<td align=right valign=top>$netamt2</td>
									<td align=right valign=top>$vatamt2</td>
									<td align=right valign=top>$debtAmt2</td>
									</tr>
								";
								
								$old_doerID=$doerID;
								$old_receiptID=$receiptID;
								$old_receiveDate=$receiveDate;
								$old_cusname=$cusfullname;
								$sum_amt+=$debtAmt;
								$sumnet+=$netamt;
								$sumvat+=$vatamt;
								
								if($typepay==$paytype_b and $paytype_b !=""){
									$sum_amt+=$receiveAmount;
									$sumnet+=$receiveAmount;
									$sumvat+=$vatamt;
									$sum_all+=$receiveInterest;
									$sum_allnet+=$receiveInterest;
									$sum_alltotalnet+=$receiveInterest;
									$sum_alltotal+=$receiveInterest;
								}else if($typepay==$paytype_a and $paytype_a!=""){
									$sum_amt+=$receiveAmount;
									$sumnet+=$receiveAmount;
									$sumvat+=$vatamt;
									$sum_all+=$receivePriciple;
									$sum_allnet+=$receivePriciple;
									$sum_alltotalnet+=$receivePriciple;
									$sum_alltotal+=$receivePriciple;
								}else{
									$sum_allnet+=$netamt;
									$sum_all+=$debtAmt;
									$sum_alltotalnet+=$netamt;
									$sum_alltotal+=$debtAmt;
								}
								$sum_allvat+=$vatamt;
								
								$sum_alltotalvat+=$vatamt;
							}
							
							if($showtype=="0"){ //กำหนดให้แสดง record 					
								//กรณีเป็นแบบแจกแจง และเป็นการชำระเงินต้น
								if($chkanalyze=="1" and ((($typepay==$paytype || $typePayID==$paytype) and ($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN')) || ($typepay==$paytype_a and $paytype_a!="" and ($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN')) || ($typepay==$paytype_b and $paytype_b!="" and ($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN')))){ 
									$qry_2012=pg_query("select \"receivePriciple\",\"receiveInterest\",\"receiveAmount\" FROM thcap_temp_int_201201 where \"receiptID\"='$receiptID'");
									list($receivePriciple,$receiveInterest,$receiveAmount)=pg_fetch_array($qry_2012);
					
									$netamt2=number_format($receivePriciple,2);
									$debtAmt2=number_format($receivePriciple,2);
									if($receivePriciple=='0'){
										$show=1; //กรณีเงินต้นเป็น 0 จะไม่แสดงข้อมูล
									}else{
										$show=0; //เงินต้นมีค่า จะแสดงข้อมูล
									}
								}else{  
									$show=0; //กรณีไม่ใช่แบบแจกแจงให้แสดงข้อมูลปกติ
								}
						
								if($show==0 and ($typepay!=$paytype_b OR $paytype_b=="")){ //แสดงข้อมูลทั่วไป ยกเว้นที่เลือกประเภท "ดอกเบี้ย" และถ้าเงินต้นเป็น 0 จะไม่แสดง record ส่วนนี้ด้วย
									if(($paytype==$typePayID || $paytype==$typepay2) and $chkanalyze=="1"){ //กรณีเป็นการแจกแจง และเลือกแสดงผ่อนชำระให้แจกแจงเงินต้นด้วย
										$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$paytype'");
										list($tpdescname)=pg_fetch_array($qrytypename);
										$tpdesc=$tpdescname;
									}
									
									echo "
										<td valign=top>$receiveDate2</td>
										<td valign=top>$receiptID2</td>
										<td align=left valign=top>$cusfullname2</td>
										<td valign=top>$contractID</td>
										<td align=left valign=top>$tpdesc</td>
										<td align=right valign=top>$netamt2</td>
										<td align=right valign=top>$vatamt2</td>
										<td align=right valign=top>$debtAmt2</td>
										</tr>
									";
								}
								
								//ค่าดอกเบี้ย แสดงค่านี้ก็ต่อเมื่อเลือกแบบแจกแจง เลือกแสดงทั้งหมด,ผ่อนชำระตามสัญญากู้ และ ดอกเบี้ย
								if($chkanalyze=="1" and $debtid=="" and ($typepay=="" || $typepay==$paytype || $typepay==$paytype_b)){ 
									//หาชื่อเต็มของ type ดอกเบี้ย
									$qrytypename=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$paytype_b'");
									list($tpdescname)=pg_fetch_array($qrytypename);
									$tpdesc=$tpdescname;
									
									if($receiveInterest > 0){ //ถ้าดอกเบี้ยมีค่า  (ถ้าไม่มีค่าจะไม่แสดงแถวนี้)
										if($show==0 and ($typepay=="" || $typepay==$paytype)){ //กรณีเงินต้นมีค่า และเลือกประเภท  "ทั้งหมด" และ "ผ่อนชำระตามสัญญากู้"
											echo "<tr $color align=\"center\">
													<td valign=top></td>
													<td valign=top></td>
													<td align=left valign=top></td>
													<td valign=top></td>
													<td align=left valign=top>$tpdesc</td>
													<td align=right valign=top>".number_format($receiveInterest,2)."</td>
													<td align=right valign=top>$vatamt2</td>
													<td align=right valign=top>".number_format($receiveInterest,2)."</td>
												</tr>
											";
										}else{ //กรณีที่เงินต้นไม่มีค่า ในแถวของดอกเบี้ยจะแสดงทุกคอลัมน์
											echo "<tr $color align=\"center\">
													<td valign=top>$receiveDate2</td>
													<td valign=top>$receiptID2</td>
													<td align=left valign=top>$cusfullname2</td>
													<td valign=top>$contractID</td>
													<td align=left valign=top>$tpdesc</td>
													<td align=right valign=top>".number_format($receiveInterest,2)."</td>
													<td align=right valign=top>$vatamt2</td>
													<td align=right valign=top>".number_format($receiveInterest,2)."</td>
												</tr>
											";
										}
									}
									$sumnetdeb=number_format(($receivePriciple+$receiveInterest),2);
									
								} //จบเงื่อนไขแสดงดอกเบี้ย
								$old_doerID=$doerID;
								$old_receiptID=$receiptID;
								$old_receiveDate=$receiveDate;
								$old_cusname=$cusfullname;
								$sum_amt+=$debtAmt;
								$sumnet+=$netamt;
								$sumvat+=$vatamt;
								
								if($typepay==$paytype_b and $paytype_b!=""){ //กรณีเลือกแสดงประเภทดอกเบี้ย
									$sum_amt+=$receiveAmount;
									$sumnet+=$receiveAmount;
									$sumvat+=$vatamt;
									$sum_all+=$receiveInterest;
									$sum_allnet+=$receiveInterest;
									$sum_alltotalnet+=$receiveInterest;
									$sum_alltotal+=$receiveInterest;
								}else if($typepay==$paytype_a and $paytype_a!=""){ //กรณีเลือกแสดงประเภทเงินต้น
									$sum_amt+=$receiveAmount;
									$sumnet+=$receiveAmount;
									$sumvat+=$vatamt;
									$sum_all+=$receivePriciple;
									$sum_allnet+=$receivePriciple;
									$sum_alltotalnet+=$receivePriciple;
									$sum_alltotal+=$receivePriciple;
								}else{
									$sum_allnet+=$netamt;
									$sum_all+=$debtAmt;
									$sum_alltotalnet+=$netamt;
									$sum_alltotal+=$debtAmt;
								}
								
								$sum_allvat+=$vatamt;
								
								$sum_alltotalvat+=$vatamt;
							} //จบเงื่อนไข showtype==0
							
							if($showtype==1){
								$showtype=2;
							}	
						}//end while
}					
						if($numreceipt==0){
							echo "<tr><td colspan=9 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบรายการรับชำระ-</b></td></tr>";
						}else{	
							if($p>0){
								//แสดงช่องทางการชำระทั้งหมด
								$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
								$sumamt=0;
								while($resstar=pg_fetch_array($qryredstar)){
									$chan=$resstar["byChannel"];
									$amt=$resstar["ChannelAmt"];														
									
									if($chan=="999"){
										$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
									}else{
										//นำไปค้นหาในตาราง BankInt
										$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
										$ressearch=pg_fetch_array($qrysearch);
										list($BAccount,$BName)=$ressearch;
										$txtchannel3="ช่องทาง : $BAccount-$BName";
									}
									
									echo "<tr $color2><td colspan=5></td><td colspan=2 align=right bgcolor=#FBFFEC>$txtchannel3</td><td align=right bgcolor=#FBFFEC>".number_format($amt,2)."</td></tr>";
								}
								//****************หารวมเงินใบเสร็จ
								$qrysumtotal=pg_query("select a.\"receiptID\",sum(\"netAmt\"),sum(\"vatAmt\"),sum(\"debtAmt\")
								from thcap_v_receipt_otherpay a 
								left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and a.\"receiptID\" = '$old_receiptID'	group by a.\"receiptID\"");
								list($receipt2,$sumnetamt,$sumvatamt,$sumall_amt)=pg_fetch_array($qrysumtotal);
								
								if($typepay==$paytype_a || $typepay==$paytype_b){
									$sumnet=$sumnet;
									$sumvat=$sumvat;
									$sum_amt=$sum_amt;
								}else{
									$sumnet=$sumnetamt;
									$sumvat=$sumvatamt;
									$sum_amt=$sumall_amt;
								}
								//***********จบหาเงินรวมใบเสร็จ
								echo "<tr $color>
								<td colspan=5 align=right><b>รวมเงินใบเสร็จนี้</b></td>
								<td align=right><b>".number_format($sumnet,2)."</b></td>
								<td align=right><b>".number_format($sumvat,2)."</b></td>
								<td align=right><b>".number_format($sum_amt,2)."</b></td>
								</tr>";
							}
							if($sum_all>0){
								//แสดงช่องทางการชำระทั้งหมด
								$qryredstar = pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\" = '$old_receiptID' order by \"ChannelAmt\" DESC");
								$sumamt=0;
								while($resstar=pg_fetch_array($qryredstar)){
									$chan=$resstar["byChannel"];
									$amt=$resstar["ChannelAmt"];														
									
									if($chan=="999"){
										$txtchannel3="ช่องทาง : ภาษีหัก ณ ที่จ่าย";
									}else{
										//นำไปค้นหาในตาราง BankInt
										$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$chan'");
										$ressearch=pg_fetch_array($qrysearch);
										list($BAccount,$BName)=$ressearch;
										$txtchannel3="ช่องทาง : $BAccount-$BName";
									}
									
									echo "<tr $color><td colspan=5></td><td colspan=2 align=right bgcolor=#FBFFEC>$txtchannel3</td><td align=right bgcolor=#FBFFEC>".number_format($amt,2)."</td></tr>";
								}
								//****************หารวมเงินใบเสร็จ
								$qrysumtotal=pg_query("select a.\"receiptID\",sum(\"netAmt\"),sum(\"vatAmt\"),sum(\"debtAmt\")
								from thcap_v_receipt_otherpay a 
								left join thcap_temp_receipt_details b on a.\"receiptID\"=b.\"receiptID\" 
								where EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and a.\"receiptID\" = '$old_receiptID'	group by a.\"receiptID\"");
								list($receipt2,$sumnetamt,$sumvatamt,$sumall_amt)=pg_fetch_array($qrysumtotal);
								
								if($typepay==$paytype_a || $typepay==$paytype_b){
									$sumnet=$sumnet;
									$sumvat=$sumvat;
									$sum_amt=$sum_amt;
								}else{
									$sumnet=$sumnetamt;
									$sumvat=$sumvatamt;
									$sum_amt=$sumall_amt;
								}
								//***********จบหาเงินรวมใบเสร็จ
								echo "<tr $color>
								<td colspan=5 align=right><b>รวมเงินใบเสร็จนี้</b></td>
								<td align=right bgcolor=#FBFFEC><b>".number_format($sumnet,2)."</b></td>
								<td align=right bgcolor=#FBFFEC><b>".number_format($sumvat,2)."</b></td>
								<td align=right bgcolor=#FBFFEC><b>".number_format($sum_amt,2)."</b></td>
								</tr>";
								
								echo "<tr>
									<td class=\"sum\" align=\"center\"><a href=\"thcap_receipt_day_pdf.php?receiveDate=$receiveDate&chkanalyze=$chkanalyze&typepay=$typepay\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
									<td colspan=4 class=\"sum\" align=right><b>ยอดรวมต่อวัน</b></td>
									<td align=right class=\"sum\"><b>".number_format($sum_allnet,2)."</b></td>
									<td align=right class=\"sum\"><b>".number_format($sum_allvat,2)."</b></td>
									<td align=right class=\"sum\"><b>".number_format($sum_all,2)."</b></td>
									</tr>";
							}	
							echo "<tr>
							<td colspan=5 class=\"sumall\" align=right><b>ยอดรวมทั้งเดือน</b></td>
							<td align=right class=\"sumall\"><b>".number_format($sum_alltotalnet,2)."</b></td>
							<td align=right class=\"sumall\"><b>".number_format($sum_alltotalvat,2)."</b></td>
							<td align=right class=\"sumall\"><b>".number_format($sum_alltotal,2)."</b></td>
							</tr>";
						}
						
						//หาสรุปของแต่ละช่องทางการจ่าย
											
						if($typepay2==""){ //กรณีเลือกแสดงทั้งหมด
							$qryamt=pg_query("select x.\"byChannel\",sum(x.\"debtAmt\"),(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel a
							where a.\"byChannel\"='999' and a.\"receiptID\" in (select \"receiptID\" from thcap_v_receipt_otherpay where \"byChannel\"=x.\"byChannel\" and EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' group by \"receiptID\")) 
							FROM thcap_v_receipt_otherpay x
							where EXTRACT(MONTH FROM x.\"receiveDate\")='$month' and EXTRACT(YEAR FROM x.\"receiveDate\")='$year' group by  x.\"byChannel\"");							
						}else{
							$qryamt=pg_query("select x.\"byChannel\",sum(x.\"debtAmt\"),(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel a
							where a.\"byChannel\"='999' and a.\"receiptID\" in (select \"receiptID\" from thcap_v_receipt_otherpay where \"byChannel\"=x.\"byChannel\" and EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and \"typePayID\"='$typepay' group by \"receiptID\")) 
							FROM thcap_v_receipt_otherpay x
							where EXTRACT(MONTH FROM x.\"receiveDate\")='$month' and EXTRACT(YEAR FROM x.\"receiveDate\")='$year' and x.\"typePayID\"='$typepay' group by  x.\"byChannel\"");							
							$numchkamt=pg_num_rows($qryamt);
							
							if($numchkamt==0){ //แสดงว่าอาจเลือกแสดง type เป็น ชำระเงินต้น หรือ ดอกเบี้ย ทำให้ไม่มีในตาราง otherpay
								$type_a=pg_getprincipletype($typepay2);//หารหัสผ่อนชำระตามสัญญากู้-คืนเงินต้น เช่น 1001
								$type_b=pg_getinteresttype($typepay2);//หารหัสผ่อนชำระตามสัญญากู้-ดอกเบี้ย เช่น 1002
								
								if($type_b==$typepay2){ //กรณีเป็นดอกเบี้ย
									$qryamt=pg_query("select \"byChannel\",sum(\"receiveInterest\") ,
									(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel x
									where x.\"byChannel\"='999' and x.\"receiptID\" IN (select x.\"receiptID\" from thcap_temp_int_201201 x
									inner join \"thcap_temp_receipt_channel\" y on x.\"receiptID\"=y.\"receiptID\"
									where EXTRACT(MONTH FROM x.\"receiveDate\")='$month' and EXTRACT(YEAR FROM x.\"receiveDate\")='$year' and \"byChannel\" <> '999' 
									and account.\"thcap_mg_getInterestType\"(x.\"contractID\")='$type_b'))
						
									FROM thcap_temp_int_201201 a
									inner join \"thcap_temp_receipt_channel\" b on a.\"receiptID\"=b.\"receiptID\"
									where EXTRACT(MONTH FROM a.\"receiveDate\")='$month' and EXTRACT(YEAR FROM a.\"receiveDate\")='$year' and \"byChannel\" <> '999' 
									and account.\"thcap_mg_getInterestType\"(a.\"contractID\")='$type_b'
									group by  \"byChannel\"");																
								}else if($type_a==$typepay2){ //กรณีเป็นเงินต้น
									$qryamt=pg_query("select \"byChannel\",sum(\"receivePriciple\"),
									(select sum(\"ChannelAmt\") from thcap_temp_receipt_channel x
									where x.\"byChannel\"='999' and x.\"receiptID\" IN (select x.\"receiptID\" from thcap_temp_int_201201 x
									inner join \"thcap_temp_receipt_channel\" y on x.\"receiptID\"=y.\"receiptID\"
									where EXTRACT(MONTH FROM x.\"receiveDate\")='$month' and EXTRACT(YEAR FROM x.\"receiveDate\")='$year' and \"byChannel\" <> '999' 
									and account.\"thcap_mg_getInterestType\"(x.\"contractID\")='$type_a'))
									
									FROM thcap_temp_int_201201 a
									inner join \"thcap_temp_receipt_channel\" b on a.\"receiptID\"=b.\"receiptID\"
									where EXTRACT(MONTH FROM a.\"receiveDate\")='$month' and EXTRACT(YEAR FROM a.\"receiveDate\")='$year' and \"byChannel\" <> '999' 
									and account.\"thcap_mg_getPrincipleType\"(a.\"contractID\")='$type_a'
									group by  \"byChannel\"");															
								}
							}
						}
							
						while($resamt=pg_fetch_array($qryamt)){
						list($allchannel,$allamt,$taxamt)=$resamt;
						$allamt-=$taxamt;
						if($taxamt>0){
							$texttaxamt="<b>ภาษีหัก ณ ที่จ่าย </b>".number_format($taxamt,2)." บาท";
						}else{
							$texttaxamt="";
						}
						//นำ channel ที่ได้ไปค้นว่าเป็นการจ่ายแบบไหน
							$qrysearchbnk=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"::text='$allchannel'");
								$ressearchbnk=pg_fetch_array($qrysearchbnk);
								list($BAccount2,$BName2)=$ressearchbnk;
								if($BAccount2 == "" and $BName2==""){
									$txtchannel2="ไม่ระบุ";
								}else{
									$txtchannel2="$BAccount2-$BName2";
								}
								echo "<tr bgcolor=\"#E9F8FE\">
									<td colspan=\"9\">รับเงินจาก <b>$txtchannel2</b>  รวม  ".number_format($allamt,2)." บาท $texttaxamt</td>
									</tr>
								";
							$allamtchannel=$allamtchannel+$allamt;
							$alltaxamt+=$taxamt;
							
						}
						$allchantax=$allamtchannel+$alltaxamt;
						if($alltaxamt>0){
							$texttallaxamt="รวมภาษีหัก ณ ที่จ่าย <font color=\"red\">".number_format($alltaxamt,2)."</font> บาท รวมทั้งหมด <font color=red>".number_format($allchantax,2)."</font> บาท";
						}else{
							$texttallaxamt="";
						}
						echo "<tr bgcolor=\"#E9F8FE\">
							<td colspan=\"9\"><span style=\"background-color:yellow;font-weight:bold;\">รวมทุกช่องทาง <font color=red>".number_format($allamtchannel,2)."</font> บาท $texttallaxamt </font></span></td>
							</tr>
						";
						?>
						
						</table>
						<?php
						}
						?>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>
</body>
</html>