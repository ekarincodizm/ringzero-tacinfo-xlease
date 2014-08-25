<?php
session_start();
include("../../config/config.php");
include("../function/emplevel.php");
$id_user=$_SESSION["av_iduser"];
$datepick=$_POST['datepick'];
$bank=$_POST['bank'];

$emplevel=emplevel($id_user);

//ตรวจสอบว่าธนาคารที่เลือกมี "BankInt"."isLoadStatementAble"=1 หรือไม่
$qrybank = pg_query("select \"LoadStatementDate\" from \"BankInt\" where \"BID\"='$bank' and \"isLoadStatementAble\" = '1' and \"LoadStatementDate\" is not null ");

//กรณี "BankInt"."isLoadStatementAble"=1
if(pg_num_rows($qrybank)>0)
{
	// หาวันที่บังคับให้เริ่ม load Statement Bank
	$LoadStatementDate = pg_fetch_result($qrybank,0);
	
	if($emplevel<=1)
	{ //ถ้าระดับพนักงาน <=1 จะสามารถทำรายการได้ทุกกรณี
		echo 1;
	}
	else
	{
		//ตรวจสอบว่าวันที่เลือกน้อยกว่า LoadStatementDate หรือไม่ ถ้าไม่ใช่ให้แจ้งว่าทำรายการไม่ได้
		if($datepick < $LoadStatementDate)
		{ 
			echo 1; //อนุญาตให้ทำรายการได้เฉพาะวันที่น้อยกว่า  LoadStatementDate เท่านั้น
		}
		else
		{
			// หาวันที่สูงสุดที่อนุญาติให้คีย์ข้อมูลเองได้
			$qry_canLastKeyDate = pg_query("select '$LoadStatementDate'::date - 1");
			$canLastKeyDate = pg_fetch_result($qry_canLastKeyDate,0);
			
			echo "2#$LoadStatementDate#$canLastKeyDate"; //ไม่อนุญาตให้ทำรายการถ้าวันที่ตั้งแต่  LoadStatementDate เป็นต้นไป
		}
	}
}
else
{ //กรณี "BankInt"."isLoadStatementAble" ไม่เท่า 1 ให้สามารถบันทึกได้ตามปกติ
	echo 1;
}
?>