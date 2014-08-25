<?php
/*
	ไฟล์นี้เป็น Process สำหรับ generate ดอกเบี้ยของสัญญาทั้งหมดจากที่ gen ไว้ล่าสุดจนถึง FinalDate
*/



/*
	Include Settings & Functions
*/
include("../../config/config.php");
include("../../core/core_thcap.php");



// $focusDate - วันที่สนใจ ให้เท่ากับ วันที่คิดดอกเบี้ยวันแรก
$focusDate = $focusIntStartDate;


while()



$query_selectMGCurSetting =	"SELECT 
								*
							FROM 
								account.\"thcap_mg_contract_current\"
							WHERE 
								\"contractID\"='".$contractID."' AND
								\"effectiveDate\" <= '".$focusDate."' AND
								\"appvID\" IS NOT NULL
							ORDER BY
								\"rev\" ASC

							
";
)

$query_insertMGInt = 	"INSERT INTO account.thcap_mg_interest(
							\"contractID\",
							\"intGenStamp\",
							\"intGenJobID\",
							\"intSerial\",
							\"intStartDate\", 
							\"intEndDate\",
							\"intCurRate\",
							\"intMaxRate\",
							\"intAccRate\",
							\"intCurPrinciple\", 
							\"intAmtPerDay\",
							\"intAmtByCurRate\",
							\"intAmtByMaxRate\",
							\"intAmtByAccRate\", 
							\"intAmtPerDayRounded\",
							\"intMethod\")
						VALUES (
							$contractID,
							?,
							?,
							$focusIntSerial,
							$focusIntStartDate, 
							?,
							?,
							NULL,
							NULL,
							?, 
							?,
							?,
							NULL,
							NULL, 
							?,
							?);
";

while($focusIntStartDate != $currentDate)
{
$IntOneDate = thcap_cal_intCalEffFromDate($conCurPrinciple, -1, $intCurRate, $focusIntStartDate, $focusIntStartDate, 1);
$focusIntStartDate = date('Y-m-d',strtotime("$focusIntStartDate +1 day"));
echo "$IntOneDate </br>";
}

?>