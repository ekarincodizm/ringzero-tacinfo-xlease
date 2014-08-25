<?php
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"];
$KeyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$ChequeNo = $_POST["ChequeNo"];
$BankID = $_POST["BankID"];
$BankBranch = $_POST["BankBranch"];
$DateReceive = $_POST["DateReceive"];
$DateOnChq = $_POST["DateOnChq"];
$DateEntBank = $_POST["DateEntBank"];
$BAccount = $_POST["BAccount"];
$Amount = $_POST["Amount"];
$RecNo = $_POST["RecNo"];
$status3 = $_POST["status"];

/*echo $ChequeNo ;
echo "<br>";
echo $BankID ;
echo "<br>";
echo $BankBranch ;
echo "<br>";
echo $DateReceive ;
echo "<br>";
echo $DateOnChq ;
echo "<br>";
echo $DateEntBank ;
echo "<br>";
echo $BAccount ;
echo "<br>";
echo $Amount ;
echo "<br>";
echo $RecNo ;
echo "<br>";
echo $status3 ;
echo "<br>";*/

pg_query("BEGIN WORK");
$status = 0;

$in_sql="insert into public.\"DTACCheque\" (\"D_ChequeNo\",\"D_BankID\",\"D_BankBranch\",\"D_DateReceive\",\"D_DateOnChq\",\"D_DateEntBank\",\"BAccount\",\"D_Amount\",\"D_RecNo\",\"status\",\"doerID\",\"doerStamp\") values ('$ChequeNo','$BankID','$BankBranch','$DateReceive','$DateOnChq','$DateEntBank','$BAccount','$Amount','$RecNo','$status3','$id_user','$KeyDate')";
if($result=pg_query($in_sql)){
}else{
	$status++;
	}
	
if($status == 0){
	pg_query("COMMIT");
	echo "<center><h2>การเพิ่มเสร็จสมบูรณ์</h2></center>";
	echo "<form method=\"post\" name=\"form1\" action=\"frm_addTacCheque.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\"></center>";
	echo "</form>";
}else{
	pg_query("ROLLBACK");
	echo "<center><h2>ไม่สามารถเพิ่มได้ กรุณาลองใหม่อีกครั้ง!</h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_addTacCheque.php\">";
		echo "<input type=\"hidden\" name=\"ChequeNo2\" value=\"$ChequeNo\">";
		echo "<input type=\"hidden\" name=\"BankID2\" value=\"$BankID\">";
		echo "<input type=\"hidden\" name=\"BankBranch2\" value=\"$BankBranch\">";
		echo "<input type=\"hidden\" name=\"DateReceive2\" value=\"$DateReceive\">";
		echo "<input type=\"hidden\" name=\"DateOnChq2\" value=\"$DateOnChq\">";
		echo "<input type=\"hidden\" name=\"DateEntBank2\" value=\"$DateEntBank\">";
		echo "<input type=\"hidden\" name=\"BAccount2\" value=\"$BAccount\">";
		echo "<input type=\"hidden\" name=\"Amount2\" value=\"$Amount\">";
		echo "<input type=\"hidden\" name=\"RecNo2\" value=\"$RecNo\">";
		echo "<input type=\"hidden\" name=\"status2\" value=\"$status3\">";
	echo "<center><input type=\"submit\" value=\"ย้อนกลับ\"></center>";
	echo "</form>";
}
?>