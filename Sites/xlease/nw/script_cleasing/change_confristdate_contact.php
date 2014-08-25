<?php
session_start();
include("../../config/config.php");
$contractid = $_POST["contractid"]; //เลขที่สัญญา
$conFirstDue = $_POST["conFirstDue"]; //วันที่เริ่มชำระ
$start = $_POST["start"];

$status = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>แก้ไขข้อมูลเลขที่สัญญา</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<body>
<center>
<div style="padding-top:50px;"></div>
<?php


		IF($start == 'true'){
				pg_query("BEGIN");

					$qry_sel = pg_query("select * from \"thcap_contract\" where \"contractID\" = '$contractid'");
					$result = pg_fetch_array($qry_sel);
					$term = $result["conTerm"]; // จำนวนงวด
					$conMinPay = $result["conMinPay"]; // จำนวนเงินกู้
					$conDate = $result["conDate"];
					
					//เปลี่ยนวันที่ถึงกำหนดชำระ
					for($i=1; $i<=$term; $i++)
					{
							if($i == 1)
							{
								$nextConDue = $conFirstDue;
							}
							else
							{
								$arrayConDue = explode("-",$nextConDue);						
								$plusConDue = mktime(0,0,0,$arrayConDue[1]+1,$arrayConDue[2],$arrayConDue[0]); // เวลา เดือน วัน ปี
								$nextConDue = date("Y-m-d",$plusConDue); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
							}
							
							$strTerm = "update account.\"thcap_payTerm_temp\" SET \"ptDate\" = '$nextConDue' WHERE \"contractID\" = '$contractid' AND \"ptNum\" = '$i' AND \"Approved\" = 'TRUE'";
							$qryTerm = pg_query($strTerm);
							if($qryTerm){}else{ $status++; echo $strTerm;}	

							$strTerm1 = "update account.\"thcap_payTerm\" SET \"ptDate\" = '$nextConDue' WHERE \"contractID\" = '$contractid' AND \"ptNum\" = '$i'";
							$qryTerm1 = pg_query($strTerm1);
							if($qryTerm1){}else{ $status++; echo $strTerm1;}
							
							//แก้ไขหนี้ทั้งหมดของสัญญา	
							$strdel = "UPDATE \"thcap_temp_otherpay_debt\" SET \"debtDueDate\" = '$nextConDue' WHERE \"contractID\" = '$contractid' AND \"debtIsOther\" = '0' AND \"typePayRefValue\" = '$i'";
							$qrydel = pg_query($strdel);
							if($qrydel){}else{ $status++; echo $strdel;}
							
							$strdel = "UPDATE \"thcap_temp_otherpay_debt\" SET \"debtStatus\" = '0' WHERE \"contractID\" = '$contractid' AND \"debtIsOther\" = '1' AND \"typePayID\" = '7003' ";
							$qrydel = pg_query($strdel);
							if($qrydel){}else{ $status++; echo $strdel;}

							
					}
					
					$str_mg = "UPDATE thcap_mg_contract_current SET \"conEndDate\"='$nextConDue' WHERE \"contractID\"='$contractid'";
					$qry_mg = pg_query($str_mg);
					if($qry_mg){}else{ $status++; echo $str_mg;}
					
					//เปลี่ยนวันที่เริ่มชำระงวดแรกและวันที่สิ้นสุดสัญญา
					$strfdue = "update \"thcap_contract_temp\" SET \"conFirstDue\" = '$conFirstDue',\"conEndDate\"='$nextConDue' WHERE \"contractID\" = '$contractid' AND \"Approved\" = 'TRUE' ";
					$qryfdue = pg_query($strfdue);
					if($qryfdue){}else{ $status++; echo $strfdue;}
					
					//เปลี่ยนวันที่เริ่มชำระงวดแรกและวันที่สิ้นสุดสัญญา
					$strfdue = "update \"thcap_contract\" SET \"conFirstDue\" = '$conFirstDue',\"conEndDate\"='$nextConDue' WHERE \"contractID\" = '$contractid'";
					$qryfdue = pg_query($strfdue);
					if($qryfdue){}else{ $status++; echo $strfdue;}
					
					
						
			
					
					
					
				IF($status == 0){
					pg_query("COMMIT");
					echo "<h1> แก้ไขข้อมูลเรียบร้อย </h1>";
					
				}else{
					pg_query("ROLLBACK");
					echo "<h1> ไม่สามารถแก้ไขข้อมูลได้ </h1>";
						}	
		}else{?>

			<form action="change_confristdate_contact.php" method="POST">
				<input type="hidden" name="start" value="true">
					<h2>Script แก้ไขข้อมูลเลขที่สัญญา </h2>
						<div>เลขที่สัญญา :<input type="text" name="contractid" value=""></div>
						<div>วันที่เริ่มชำระงวดแรก :<input type="text" name="conFirstDue" value=""></div>
						<div style="padding-top:10px;"><input type="submit" value=" เริ่ม " style="width:150px;height:75px;"></div>
			</form>
		<?php } 

	
	
		?>	
</center>	
</body>