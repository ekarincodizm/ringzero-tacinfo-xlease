<?php
session_start();
include("../../config/config.php");
include("../../core/core_thcap_cal.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$month=$_POST["mount"];
$year=$_POST["year"];

if($month=="01" || $month=="03" || $month=="05" || $month=="07" || $month=="08" || $month=="10" || $month=="12"){
	$d="31";
}else if($month=="04" || $month=="06" || $month=="09" || $month=="11"){
	$d="30";
}else{
	if($year%400==0){
		$d="29";
	}else if($year%100==0){
		$d="28";
	}else if($year%4==0){
		$d="29";
	}else{
		$d="28";
	}
}
$date=$year."-".$month."-01";
$date_add=$year."-".$month."-01 ".date('H:i:s');

$month2=$month;
$year2=$year;

if($month2=="12"){
	$year2=$year2+1;
}else{
	$year2=$year2;
}

if($month2 < "10"){
	$mm=str_replace("0","",$month2);
}else{
	$mm=$month2;
}

if($mm=="12"){
	$month2="1";
}else{
	$month2=$month2+1;
					
	if($month2=="10" || $month2=="11" || $month2=="12"){
		$month2=$month2;
	}else{
		$month2="0".$month2;
	}
}
				
$startDate=$year2."-".$month2."-01"; //receiveDate
$startDate_add=$year2."-".$month2."-01 ".date('H:i:s'); //receiveDate
$endDate=$year."-".$month."-".$d;

pg_query("BEGIN WORK");
$status = 0;

//ดึงข้อมูลจากตาราง thcap_mg_contract ขึ้นมาโดยนำมาเฉพาะ conStartDate ไม่เกินเดือนและปีที่เราเลือก
$qrycontract=pg_query("SELECT * FROM thcap_mg_contract where  EXTRACT(MONTH FROM \"conStartDate\")='$month' and EXTRACT(YEAR FROM \"conStartDate\")='$year'");
while($rescon=pg_fetch_array($qrycontract)){
	$contractID1=$rescon["contractID"];
	$lastReceiveDate1=$rescon["conStartDate"];
	$lastPrinciple1=$rescon["conLoanAmt"];
	$interestRate1=$rescon["conLoanIniRate"];
	$lastInterest1="0.00";
	
	//นำ record ที่ได้ไป check ว่ามีในตาราง  thcap_temp_int_201201 หรือไม่ ถ้าไม่มีให้ insert ถ้ามีให้ทำตามเงื่อนไข
	$qrycheck1=pg_query("select * from thcap_temp_int_201201 where \"contractID\"='$contractID1'");
	$numcheck1=pg_num_rows($qrycheck1);
	
	if($numcheck1==0){ //กรณีไม่พบข้อมูลให้ insert โดยนำค่ามาจากตาราง thcap_mg_contract
		//เอาเข้า function เพื่อหาค่าดอกเบี้ยที่ค้างชำระ
		$total_interest1=thcap_cal_intCalEffFromDate($lastPrinciple1, 2, $interestRate1, $lastReceiveDate1, $startDate_add, 0, 0, 0);

		//หาดอกเบี้ยค้างชำระโดย  ดอกเบี้ยค้างชำระ = "lastInterest" (ดอกเบี้ยคงเหลือจากคราวก่อน) + ดอกเบี้ยที่จะต้องเรียกเก็บ
		$y1=$lastInterest1+$total_interest1;

		//หาค่า X โดย  X="receiveAmount" - ดอกเบี้ยค้างชำระ 
		$x1=0-$y1;

		if($x1 < 0){
			$receivePriciple1=0;
			$receiveInterest1=0;

			// เงินต้นคงเหลือหลังหัก คือเงินต้นเดิม เนื่องจากไม่ได้ไปหักเงินต้นเลย
			$LeftPrinciple1 = $lastPrinciple1;

			// ดอกเบี้ยคงเหลือหลังหัก = (ดอกเบี้ยเดิม + ดอกเบี้ยใหม่) - จำนวนเงินที่จ่าย
			$LeftInterest1 = $y1 - 0;

		}

		// ผมรวมคงเหลือ
		$LeftSum1 = $LeftPrinciple1 + $LeftInterest1;
	
		//หลังจากได้ค่าแล้วให้นำไป insert
		
		$ins1="INSERT INTO thcap_temp_int_201201(
				\"contractID\", \"lastReceiveDate\", \"lastPrinciple\", \"lastInterest\", 
				\"receiveDate\", \"receiveAmount\", \"interestRate\", \"receivePriciple\", 
				\"receiveInterest\", \"LeftPrinciple\", \"LeftInterest\", \"LeftSum\", 
				\"genCloseMonth\",\"isReceiveReal\")
				VALUES ('$contractID1', '$lastReceiveDate1', '$lastPrinciple1', '$lastInterest1', 
				'$startDate_add', 0.00, '$interestRate1', '$receivePriciple1', 
				'$receiveInterest1', '$LeftPrinciple1', '$LeftInterest1', '$LeftSum1', 
				'$endDate','0')";
		if($resin1=pg_query($ins1)){
		}else{
			$status++;
		}
		
	}else{
		//ไม่ต้องทำอะไร
	}
}

/*#################หลังจาก insert ข้อมูลในตารางเรียบร้อยแล้่วก็ให้เข้าเงื่อนไขที่เหลือ ######################*/

//ดึงทุกเลขที่สัญญาขึ้นมา
$query = pg_query("select distinct(\"contractID\") from thcap_temp_int_201201 where  EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year'"); 	
while($result = pg_fetch_array($query)){
	$contractID = trim($result["contractID"]);
	
	//หาค่าล่าสุดที่จะนำมาคำนวณ
	$qrydata = pg_query("select * from thcap_temp_int_201201 where  EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year'
		and \"contractID\"='$contractID' order by \"receiveDate\" DESC, \"LeftPrinciple\" ASC limit(1)"); 	
	if($res=pg_fetch_array($qrydata)){
		$receiveDate = trim($res["receiveDate"]);
		$interestRate = trim($res["interestRate"]);
		$LeftPrinciple = trim($res["LeftPrinciple"]); if($LeftPrinciple=="") $LeftPrinciple=0;
		$LeftInterest = trim($res["LeftInterest"]);
	}
	
	// แก้บัคเนื่องจากนำ LeftInterest ไปใช้ในการคำนวณแล้วทำให้ตัวเลขเปลี่ยนไป ทำให้เวลา insert เอาค่าที่ผ่านการคำนวณแล้วไป insert ซึ่งไม่ใช่ค่าจากใบเสร็จเดิมจริงๆ
	$LeftInterestReal = $LeftInterest;
		
	//###########################################คำนวณ###########################################
	//เอาเข้า function เพื่อหาค่าดอกเบี้ยที่ค้างชำระ
	$total_interest=thcap_cal_intCalEffFromDate($LeftPrinciple, 2, $interestRate, $receiveDate, $startDate_add, 0, 0, 0);

	//หาดอกเบี้ยค้างชำระโดย  ดอกเบี้ยค้างชำระ = "lastInterest" (ดอกเบี้ยคงเหลือจากคราวก่อน) + ดอกเบี้ยที่จะต้องเรียกเก็บ
	$y=$LeftInterest+$total_interest;

	//หาค่า X โดย  X="receiveAmount" - ดอกเบี้ยค้างชำระ 
	$x=0-$y;

	if($x < 0){
		$receivePriciple=0;
		$receiveInterest=0;

		// เงินต้นคงเหลือหลังหัก คือเงินต้นเดิม เนื่องจากไม่ได้ไปหักเงินต้นเลย
		$LeftPrinciple = $LeftPrinciple;

		// ดอกเบี้ยคงเหลือหลังหัก = (ดอกเบี้ยเดิม + ดอกเบี้ยใหม่) - จำนวนเงินที่จ่าย
		$LeftInterest = $y - 0;

	}
	// ผมรวมคงเหลือ
	$LeftSum = $LeftPrinciple + $LeftInterest; //ใช้ในกรณีปกติ
	//###########################################จบคำนวณ###########################################		
			
	//เมื่อได้ record ล่าสุดให้นำไป check อีกรอบว่าถูก Gen หรือยังถ้าถูก Gen แล้วให้  update record นั้นใหม่แต่ถ้ายังให้ insert เข้าไปใหม่
	$qrycheck=pg_query("select * from thcap_temp_int_201201 where \"contractID\"='$contractID' and \"genCloseMonth\"='$endDate'");
	$numcheck=pg_num_rows($qrycheck);
		
	if($numcheck==0){ //เกิดขึ้นได้ 2 กรณีคือ 1.มีใบเสร็จแต่ยังไม่ได้ gen 2.ไม่มีใบเสร็จเลย (เป็นการ Gen ของเดือนก่อนหน้า)
		$qrychkbill=pg_query("select * from thcap_temp_int_201201 where \"contractID\"='$contractID' and EXTRACT(MONTH FROM \"receiveDate\")='$month' and EXTRACT(YEAR FROM \"receiveDate\")='$year' and \"genCloseMonth\" is null");
		$numchkbill=pg_num_rows($qrychkbill);
			
		if($numchkbill==0){ //ไม่มีใบเสร็จของเดือนนั้นเลยให้ใช้จากการ Gen เดือนก่อน
			$year3=substr($endDate,0,4);
			$month3=substr($endDate,5,2);
				
				if($month3=="03"){
					if($year3%400==0){
						$d2="29";
					}else if($year3%100==0){
						$d2="28";
					}else if($year3%4==0){
						$d2="29";
					}else{
						$d2="28";
					}
				}else if($month3=="05" || $month3=="07" || $month3=="10" || $month3=="12"){
					$d2="30";
				}else{
					$d2="31";
				}
				
				if($month3=="01"){
					$year3=$year3-1;
				}else{
					$year3=$year3;
				}
				
				if($month3 < "10"){
					$mm2=str_replace("0","",$month3);
				}else{
					$mm2=$month3;
				}
				
				if($mm2=="1"){
					$month3="12";
				}else{
					$month3=$month3-1;
					
					if($month3=="10" || $month3=="11" || $month3=="12"){
						$month3=$month3;
					}else{
						$month3="0".$month3;
					}
				}
				
				$lastDate=$year3."-".$month3."-".$d2;
				
				//ดึงใบเสร็จข้อมูลเดือนก่อนขึ้นมา
				$qrycheck2=pg_query("select * from thcap_temp_int_201201 where \"contractID\"='$contractID' and date(\"receiveDate\")='$date' and \"genCloseMonth\"='$lastDate'");
				$num_check2=pg_num_rows($qrycheck2);
				if($num_check2==0){ //แสดงว่าัยังไม่มีการ Gen ใบเสร็จในเดือนก่อนหน้า
					$txt="ยังไม่มีการ Gen ก่อนหน้า";
					$status++;
				}else{
					if($res2=pg_fetch_array($qrycheck2)){
						$lastReceiveDate2 = trim($res2["receiveDate"]); 
						$interestRate2 = trim($res2["interestRate"]); 
						$lastPrinciple2 = trim($res2["LeftPrinciple"]); if($lastPrinciple2=="") $lastPrinciple2=0;
						$lastInterest2 = trim($res2["LeftInterest"]);
					}
				
					//เอาเข้า function เพื่อหาค่าดอกเบี้ยที่ค้างชำระ
					$total_interest2=thcap_cal_intCalEffFromDate($lastPrinciple2, 2, $interestRate2, $lastReceiveDate2, $startDate_add, 0, 0, 0);

					//หาดอกเบี้ยค้างชำระโดย  ดอกเบี้ยค้างชำระ = "lastInterest" (ดอกเบี้ยคงเหลือจากคราวก่อน) + ดอกเบี้ยที่จะต้องเรียกเก็บ
					$y2=$lastInterest2+$total_interest2;

					//หาค่า X โดย  X="receiveAmount" - ดอกเบี้ยค้างชำระ 
					$x2=0-$y2;

					if($x2 < 0){
						$receivePriciple2=0;
						$receiveInterest2=0;

						// เงินต้นคงเหลือหลังหัก คือเงินต้นเดิม เนื่องจากไม่ได้ไปหักเงินต้นเลย
						$LeftPrinciple2 = $lastPrinciple2;

						// ดอกเบี้ยคงเหลือหลังหัก = (ดอกเบี้ยเดิม + ดอกเบี้ยใหม่) - จำนวนเงินที่จ่าย
						$LeftInterest2 = $y2 - 0;

					}

					// ผมรวมคงเหลือ
					$LeftSum2 = $lastPrinciple2 + $LeftInterest2;
					
					if($lastInterest2==""){
						$lastInterest2="null";
					}else{
						$lastInterest2="'".$lastInterest2."'";
					}
					$ins3="INSERT INTO thcap_temp_int_201201(
							\"contractID\", \"lastReceiveDate\", \"lastPrinciple\", \"lastInterest\", 
							\"receiveDate\", \"receiveAmount\", \"interestRate\", \"receivePriciple\", 
							\"receiveInterest\", \"LeftPrinciple\", \"LeftInterest\", \"LeftSum\", 
							\"genCloseMonth\",\"isReceiveReal\")
							VALUES ('$contractID', '$lastReceiveDate2', '$lastPrinciple2', $lastInterest2, 
							'$startDate_add', 0.00, '$interestRate2', '$receivePriciple2', 
							'$receiveInterest2', '$LeftPrinciple2', '$LeftInterest2', '$LeftSum2', 
							'$endDate','0')";
					if($resin3=pg_query($ins3)){
					}else{
						$status++;
					}	
					
				}
				
		}else{ //พบว่ามีใบเสร็จจัดการตามกรณีปกติ
			$insert="INSERT INTO thcap_temp_int_201201(
				\"contractID\", \"lastReceiveDate\", \"lastPrinciple\", \"lastInterest\", 
				\"receiveDate\", \"receiveAmount\", \"interestRate\", \"receivePriciple\", 
				\"receiveInterest\", \"LeftPrinciple\", \"LeftInterest\", \"LeftSum\", 
				\"genCloseMonth\",\"isReceiveReal\")
				VALUES ('$contractID', '$receiveDate', '$LeftPrinciple', '$LeftInterestReal', 
				'$startDate_add', 0.00, '$interestRate', NULL, 
				NULL, NULL, NULL, NULL, 
				'$endDate','0')";
			if($resin=pg_query($insert)){
			}else{
				$status++;
			}
		}
	}else{ //กรณีมีการ gen ใบเสร็จแล้วให้ update ข้อมูล
		$update="UPDATE thcap_temp_int_201201
			SET
				\"lastReceiveDate\"='$receiveDate',
				\"lastPrinciple\"='$LeftPrinciple',
				\"receivePriciple\"='$receivePriciple',
				\"receiveInterest\"='$receiveInterest',
				\"LeftPrinciple\"='$LeftPrinciple',
				\"LeftInterest\"='$LeftInterest',
				\"interestRate\"='$interestRate',
				\"LeftSum\"='$LeftSum'
			WHERE
				\"contractID\"='$contractID' AND \"genCloseMonth\"='$endDate'";
		if($resup=pg_query($update)){
		}else{
			$status++;
		}	
	}					
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) Process จัดการยอดสรุปสิ้นเดือน', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
    echo "1";
}
else
{
	pg_query("ROLLBACK");
	echo "2";
}		
?>

