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
						
						
echo $NT_tempID = $re2['NT_tempID'];

$NT_tempID = checknull($NT_tempID);

$conID = checknull($contractID[$i]);


$sqlin2 = "UPDATE \"thcap_NT1_Approve\"
   SET \"Status_app\"='2', appdate='$nowdate', appuser='$id_user' WHERE \"NT_tempID\" = $NT_tempID";
$queryin2 = pg_query($sqlin2);

if($queryin2){}else{ $status++; }


$sqlin3 = "UPDATE \"thcap_NT1_temp\" SET  \"NT_1_Status\"= '2' WHERE \"NT_tempID\" = $NT_tempID";
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
			echo $sqlin2;
			echo "<p>";				
			echo $sqlin3;	
			
			echo "<input type=\"button\" name=\"back\" value=\" กลับ \" onclick=\"parent.location.href='frm_NT1.php'\">";
			}

?>