<?php
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"];
$KeyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$datepicker = $_POST["datepicker"];
$lawMaxInterest = $_POST["lawMaxInterest"];
$lawMaxMonthTerm = $_POST["lawMaxMonthTerm"];
$lawVATRate = $_POST["lawVATRate"];
$lawSBTRate = $_POST["lawSBTRate"];
$lawLTRate = $_POST["lawLTRate"];
$comPenaltyC = $_POST["comPenaltyC"];
$comMaxInterest = $_POST["comMaxInterest"];
$comMaxMonthTerm = $_POST["comMaxMonthTerm"];
$comPenaltyD = $_POST["comPenaltyD"];
$comLawyerFee = $_POST["comLawyerFee"];
$comCloseAccFee = $_POST["comCloseAccFee"];
$comPenaltyF = $_POST["comPenaltyF"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body style="background-color:#ffffff; margin-top:0px;">

	<?php
	pg_query("BEGIN WORK");
	$status = 0;
 
		$in_sql="insert into public.\"thcap_mg_setting\" (\"mgsActiveDate\",\"lawMaxInterest\",\"lawMaxMonthTerm\",\"lawVATRate\",\"lawSBTRate\",\"lawLTRate\",\"comPenaltyC\",\"comMaxInterest\",\"comMaxMonthTerm\",\"comPenaltyD\",\"comLawyerFee\",\"comCloseAccFee\",\"comPenaltyF\",\"doerID\",\"doerStamp\") values ('$datepicker','$lawMaxInterest','$lawMaxMonthTerm','$lawVATRate','$lawSBTRate','$lawLTRate','$comPenaltyC','$comMaxInterest','$comMaxMonthTerm','$comPenaltyD','$comLawyerFee','$comCloseAccFee','$comPenaltyF','$id_user','$KeyDate')";
		if($result=pg_query($in_sql)){		
		}else{
			$status += 1;
			//$error=$in_sql;
		}

	if($status == 0){
		pg_query("COMMIT");
		echo "<div style=\"padding-top:50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	}else{
		pg_query("ROLLBACK");
		echo "<br><h2><b><center>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></h2></center><br>";
		//echo "$in_sql<br>";
		//echo "<meta http-equiv='refresh' content='5; URL=frm_editCredit.php?creditID=$creditID'>";
		echo "<form method=\"post\" name=\"form2\" action=\"frm_Add.php\">";
		echo "<input type=\"hidden\" name=\"lawMaxInterest2\" value=\"$lawMaxInterest\">";
		echo "<input type=\"hidden\" name=\"lawMaxMonthTerm2\" value=\"$lawMaxMonthTerm\">";
		echo "<input type=\"hidden\" name=\"lawVATRate2\" value=\"$lawVATRate\">";
		echo "<input type=\"hidden\" name=\"lawSBTRate2\" value=\"$lawSBTRate\">";
		echo "<input type=\"hidden\" name=\"lawLTRate2\" value=\"$lawLTRate\">";
		echo "<input type=\"hidden\" name=\"comPenaltyC2\" value=\"$comPenaltyC\">";
		echo "<input type=\"hidden\" name=\"comMaxInterest2\" value=\"$comMaxInterest\">";
		echo "<input type=\"hidden\" name=\"comMaxMonthTerm2\" value=\"$comMaxMonthTerm\">";
		echo "<input type=\"hidden\" name=\"comPenaltyD2\" value=\"$comPenaltyD\">";
		echo "<input type=\"hidden\" name=\"comLawyerFee2\" value=\"$comLawyerFee\">";
		echo "<input type=\"hidden\" name=\"comCloseAccFee2\" value=\"$comCloseAccFee\">";
		echo "<input type=\"hidden\" name=\"comPenaltyF2\" value=\"$comPenaltyF\">";
		echo "<input type=\"hidden\" name=\"datepicker2\" value=\"$datepicker\">";
		echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
	}		
	
?>



</body>
</html>