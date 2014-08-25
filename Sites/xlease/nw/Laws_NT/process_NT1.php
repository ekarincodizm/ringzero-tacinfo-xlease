<?php
include('../../config/config.php');
include('../function/checknull.php');
$date = nowDate();
$id_user = $_SESSION["av_iduser"];
$contractID = trim($_POST['contractID']);
$NTID = trim($_POST['NTID']);
$name = trim($_POST['name']);
$lawyer = trim($_POST['lawyer']);
$payinday = trim($_POST['payinday']);
$price_fol = trim($_POST['price_fol']);
$price_lawyer = trim($_POST['price_lawyer']);

$NTID = checknull($NTID);
$contractID = checknull($contractID);
$name = checknull($name);
$lawyer = checknull($lawyer);
$payinday = checknull($payinday);
$price_fol = checknull($price_fol);
$price_lawyer = checknull($price_lawyer);
$status=0;

pg_query("BEGIN");

//หาจำนวนเงินที่ค้างชำระ
		$qrybackAmt=pg_query("SELECT \"thcap_backAmt\"($contractID,'$date')");
		list($backAmt)=pg_fetch_array($qrybackAmt);
		
//หาวันที่เริ่มค้างชำระ
		$qrybehindday=pg_query("SELECT behind_day FROM thcap_mg_behindhand where \"contractID\" = $contractID");
		list($behindday)=pg_fetch_array($qrybehindday);
		
$backAmt = checknull($backAmt);
$behindday = checknull($behindday);

$sql = "INSERT INTO \"thcap_NT1_temp\"(
            \"NT_1_ID\", \"NT_1_Header\", \"contractID\", \"NT_1_Date\", 
            \"NT_1_Track\", \"NT_1_Proctor\", \"NT_1_Lawyer_Name\", 
            \"NT_1_Payin\", \"NT_1_behind_in_pay\", \"NT_1_period_behind_pay\", 
            \"NT_1_Status\")
    VALUES ( $NTID, $name, $contractID, '$date', 
            $price_fol, $price_lawyer, $lawyer,
            $payinday, $backAmt, $behindday, 
            '0')";
			
$query = pg_query($sql);

if($query){}else{ $status++;}


	$sent1id = pg_query("select MAX(\"NT_tempID\") as max from \"thcap_NT1_temp\" ");
	list($maxsentid)=pg_fetch_array($sent1id);



$sql1 = "INSERT INTO \"thcap_NT1_Approve\"(
             \"NT_tempID\", \"Type_app\", \"Status_app\",\"insertdate\" ,\"id_user\")
    VALUES ('$maxsentid', '0', '0','$date','$id_user')";
			
$query1 = pg_query($sql1);

if($query1){}else{ $status++; }	




			if($status == 0){
				
				pg_query("COMMIT");
				echo "<center><input type=\"button\" name=\"back\" value=\" ปิด  \" onclick=\" window.close()\"></center>";
				echo "<script type='text/javascript'>alert(' Success ')</script>";
			
			}else{
			
			pg_query("ROLLBACK");
		
			echo "<script type='text/javascript'>alert(' error ')</script>";
			echo $sql;
			echo "<p>";				
			echo $sql1;	
			echo "<p>";
			echo "<center><input type=\"button\" name=\"back\" value=\" ปิด  \" onclick=\" window.close()\"></center>";
			}
?>