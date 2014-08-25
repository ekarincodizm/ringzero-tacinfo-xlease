<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");


$method = $_POST["method"];

$add_date=nowDateTime();
$user_id = $_SESSION["av_iduser"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<?php
pg_query("BEGIN WORK");
$status = 0;

if($method=="add"){
	$BAccount = checknull($_POST["BAccount"]);
	$BName = checknull($_POST["BName"]);
	$BBranch = checknull($_POST["BBranch"]);
	$BCompany = $_POST["BCompany"];
	list($bcode,$bname)=explode("#",$BCompany);
	$BType = $_POST["BType"];	
	$desc = checknull($_POST["desc"]);
	$BChannel = checknull($_POST["BChannel"]);	
	$isChannel = $_POST["isChannel"];	
	$isTranPay = $_POST["isTranPay"];

	$ins="INSERT INTO \"BankInt_Waitapp\"(
           \"BAccount\", \"BName\", \"BBranch\", \"BCompany\", \"BType\", 
            \"BChannel\", add_user, add_date, \"statusApp\", 
            \"desc\", edittime, \"BActive\",\"isChannel\",\"isTranPay\")
    VALUES ($BAccount, $BName,$BBranch, '$bcode', '$BType', 
            $BChannel, '$user_id', '$add_date', '2', 
            $desc, 0, 1,'$isChannel','$isTranPay')";
	if($result=pg_query($ins)){		
	}else{
		$status += 1;
	}
}else if($method=="approve"){
	$statusapp=$_POST["statusapp"];
	$auto_id=$_POST["auto_id"];
	
	if($statusapp=="0"){ //กรณีไม่อนุมัติ
		//ให้ update ข้อมูลว่าไม่อนุมัติ
		$up="update \"BankInt_Waitapp\" set \"statusApp\"='0' ,app_user='$user_id',app_date='$add_date' where \"auto_id\"='$auto_id'";
		if($result=pg_query($up)){		
		}else{
			$status += 1;
		}
	}else{ //กรณีอนุมัติ
		$ins="INSERT INTO \"BankInt\"(
           \"BAccount\", \"BName\", \"BBranch\", \"BCompany\", \"BType\", 
            \"BChannel\",\"desc\", \"BActive\",\"isChannel\",\"isTranPay\")
		select \"BAccount\", \"BName\", \"BBranch\", \"BCompany\", \"BType\", 
            \"BChannel\",\"desc\", \"BActive\",\"isChannel\",\"isTranPay\" from \"BankInt_Waitapp\" where \"auto_id\"='$auto_id' returning \"BID\"";

		if($result=pg_query($ins)){	
			$resbid=pg_fetch_array($result);
			$BID=$resbid["BID"];
			echo $BID;
		}else{
			$status += 1;
		}
		
		$up="update \"BankInt_Waitapp\" set \"statusApp\"='1' ,app_user='$user_id',app_date='$add_date',\"BID\"='$BID' where \"auto_id\"='$auto_id'";
		if($result=pg_query($up)){		
		}else{
			$status += 1;
		}
	}
	
	
}else if($method=="edit"){
	$BAccount = checknull($_POST["BAccount"]);
	$BName = checknull($_POST["BName"]);
	$BBranch = checknull($_POST["BBranch"]);
	$BCompany = $_POST["BCompany"];
	list($bcode,$bname)=explode("#",$BCompany);
	$BType = $_POST["BType"];	
	$desc = checknull($_POST["desc"]);
	$BChannel = checknull($_POST["BChannel"]);	
	$isChannel = $_POST["isChannel"];	
	$isTranPay = $_POST["isTranPay"];
	$BID = $_POST["BID"];
	
	//หา edittime
	$qrymax=pg_query("select max(\"edittime\") as edittime from \"BankInt_Waitapp\" where \"BID\"='$BID'");
	list($edittime)=pg_fetch_array($qrymax);
	
	if($edittime==""){
		$edittime=1;
	}else{
		$edittime=$edittime+1;
	}

	$ins="INSERT INTO \"BankInt_Waitapp\"(
           \"BAccount\", \"BName\", \"BBranch\", \"BCompany\", \"BType\", 
            \"BChannel\", add_user, add_date, \"statusApp\", 
            \"desc\", edittime, \"BActive\",\"isChannel\",\"isTranPay\",\"BID\")
    VALUES ($BAccount, $BName,$BBranch, '$bcode', '$BType', 
            $BChannel, '$user_id', '$add_date', '2', 
            $desc, '$edittime', 1,'$isChannel','$isTranPay','$BID')";
			
	if($result=pg_query($ins)){		
	}else{
		$status += 1;
	}
}else if($method=="approveedit"){
	$statusapp=$_POST["statusapp"];
	$auto_id=$_POST["auto_id"];
	
	if($statusapp=="0"){ //กรณีไม่อนุมัติ
		//ให้ update ข้อมูลว่าไม่อนุมัติ
		$up="update \"BankInt_Waitapp\" set \"statusApp\"='0' ,app_user='$user_id',app_date='$add_date' where \"auto_id\"='$auto_id'";
		if($result=pg_query($up)){		
		}else{
			$status += 1;
		}
	}else{ //กรณีอนุมัติ
		$up="UPDATE \"BankInt\" SET \"BAccount\"=atable.\"BAccount\", \"BName\"=atable.\"BName\", \"BBranch\"=atable.\"BBranch\", 
		\"BCompany\"=atable.\"BCompany\", \"BType\"=atable.\"BType\", \"BChannel\"=atable.\"BChannel\",\"desc\"=atable.\"desc\", 
		\"BActive\"=atable.\"BActive\",\"isChannel\"=atable.\"isChannel\",\"isTranPay\"=atable.\"isTranPay\"
		FROM (select \"BAccount\", \"BName\", \"BBranch\", \"BCompany\", \"BType\", 
            \"BChannel\",\"desc\", \"BActive\",\"isChannel\",\"isTranPay\",\"BID\" from \"BankInt_Waitapp\" where \"auto_id\"='$auto_id') as atable
		WHERE \"BankInt\".\"BID\"=atable.\"BID\"";

		if($result=pg_query($up)){	
		}else{
			$status += 1;
		}
		
		$up="update \"BankInt_Waitapp\" set \"statusApp\"='1' ,app_user='$user_id',app_date='$add_date' where \"auto_id\"='$auto_id'";
		if($result=pg_query($up)){		
		}else{
			$status += 1;
		}
	}
}	
	

if($status == 0){
	if($method=="add"){
		$txtlog="(ALL) ขอเพิ่มบัญชีธนาคารของบริษัท";
	}else if($method=="approve"){
		$txtlog="(ALL) อนุมัติบัญชีธนาคารของบริษัท";
	}else if($method=="edit"){
		$txtlog="(ALL) ขอแก้ไขบัญชีธนาคารของบริษัท";
	}
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '$txtlog', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	
	echo "<div style=\"padding-top:50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
	
	if($method=="add" || $method=="edit"){
		echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	}else{
		echo "<div style=\"padding-top:20px;text-align:center;\"/><input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\"></div>";
	}
}else{
	pg_query("ROLLBACK");
	echo "<br><h2><b><center>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></h2></center><br>";
	
	if($method=="add"){
		echo "<form method=\"post\" name=\"form2\" action=\"frm_AddAccount.php\">";
		echo "<input type=\"hidden\" name=\"BAccount2\" value=\"$BAccount\">";
		echo "<input type=\"hidden\" name=\"BName2\" value=\"$BName\">";
		echo "<input type=\"hidden\" name=\"BBranch2\" value=\"$BBranch\">";
		echo "<input type=\"hidden\" name=\"BCompany2\" value=\"$BCompany\">";
		echo "<input type=\"hidden\" name=\"BType2\" value=\"$BType\">";
		echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
	}else if($method=="edit"){
		echo "<form method=\"post\" name=\"form2\" action=\"frm_EditAccount.php\">";
		echo "<input type=\"hidden\" name=\"BAccount2\" value=\"$BAccount\">";
		echo "<input type=\"hidden\" name=\"BName2\" value=\"$BName\">";
		echo "<input type=\"hidden\" name=\"BBranch2\" value=\"$BBranch\">";
		echo "<input type=\"hidden\" name=\"BCompany2\" value=\"$BCompany\">";
		echo "<input type=\"hidden\" name=\"BType2\" value=\"$BType\">";
		echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
	}
}		
	
?>



</body>
</html>