<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <?php
 
session_start();
$id_user = $_SESSION["av_iduser"];
include("../../config/config.php");
include('../function/checknull.php');
$nowdate = Date('Y-m-d');
$status = 0;
$contractID = $_POST['chkapp'];

pg_query("BEGIN");
for($i=0;$i<sizeof($contractID);$i++){

	$sql2 = pg_query("SELECT * FROM \"thcap_NT1_temp\" where \"contractID\" = '$contractID[$i]' order by \"NT_tempID\" DESC limit 1");
	$re2 = pg_fetch_array($sql2);
						
						
$NT_tempID = $re2['NT_tempID'];
$NT_1_ID = $re2['NT_1_ID'];
$NT_1_Header = $re2['NT_1_Header'];
$NT_1_Track = $re2['NT_1_Track'];
$NT_1_Proctor = $re2['NT_1_Proctor'];
$NT_1_Lawyer_Name = $re2['NT_1_Lawyer_Name'];
$NT_1_Payin = $re2['NT_1_Payin'];
$NT_1_behind_in_pay = $re2['NT_1_behind_in_pay'];
$NT_1_period_behind_pay = $re2['NT_1_period_behind_pay'];
$NT_1_Date = $re2['NT_1_Date'];



$NT_tempID = checknull($NT_tempID);
$NT_1_ID = checknull($NT_1_ID);
$NT_1_Header = checknull($NT_1_Header);
$NT_1_Track = checknull($NT_1_Track);
$NT_1_Proctor = checknull($NT_1_Proctor);
$NT_1_Lawyer_Name = checknull($NT_1_Lawyer_Name);
$NT_1_Payin = checknull($NT_1_Payin);
$NT_1_behind_in_pay = checknull($NT_1_behind_in_pay);
$NT_1_period_behind_pay = checknull($NT_1_period_behind_pay);
$NT_1_Date = checknull($NT_1_Date);







$conID = checknull($contractID[$i]);

$sqlin1 = "INSERT INTO \"thcap_NT1\"(
            \"NT_1_ID\", \"NT_1_Header\", \"contractID\", \"NT_1_Date\", \"NT_1_Track\", 
            \"NT_1_Proctor\", \"NT_1_Lawyer_Name\", \"NT_1_Payin\", 
            \"NT_1_behind_in_pay\", \"NT_1_period_behind_pay\", \"NT_1_Status\")
    VALUES ($NT_1_ID, $NT_1_Header, $conID, $NT_1_Date , $NT_1_Track, 
            $NT_1_Proctor , $NT_1_Lawyer_Name, $NT_1_Payin, 
            $NT_1_behind_in_pay, $NT_1_period_behind_pay, '1')";
$queryin1 = pg_query($sqlin1);

if($queryin1){}else{ $status++; }

$sqlin2 = "UPDATE \"thcap_NT1_Approve\"
   SET \"Status_app\"='1', appdate='$nowdate', appuser='$id_user' WHERE \"NT_tempID\" = $NT_tempID";
$queryin2 = pg_query($sqlin2);

if($queryin2){}else{ $status++; }


$sqlin3 = "UPDATE \"thcap_NT1_temp\" SET  \"NT_1_Status\"= '1' WHERE \"NT_tempID\" = $NT_tempID";
 $queryin3 = pg_query($sqlin3);

if($queryin3){}else{ $status++; }


}



if($status == 0){
				
				pg_query("COMMIT");
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_NT1.php\">";
				echo "<script type='text/javascript'>alert(' Success ')</script>";
			
			}else{
			
			pg_query("ROLLBACK");
		
			echo "<script type='text/javascript'>alert(' error ')</script>";
			echo $sqlin1;
			echo "<p>";				
			echo $sqlin2;	
			
			echo "<input type=\"button\" name=\"back\" value=\" กลับ \" onclick=\"parent.location.href='frm_NT1.php'\">";
			}

?>