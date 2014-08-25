<?php
session_start();
include("../../config/config.php");
$proxy_usersend = $_SESSION["av_iduser"];
$proxy_datesend = Date('Y-m-d');

$IDNO = $_POST["IDNO"];
$NTID = $_POST["NTID"];
$authorize_user = $_POST["authorize_user"];
$authorize_user = substr($authorize_user,0,3);

$seize_user = $_POST["seize_user"];
$seize_user = substr($seize_user,0,3);

$witness_user1 = $_POST["witness_user1"];
$witness_user1 = substr($witness_user1,0,3);

$witness_user2 = $_POST["witness_user2"];
$witness_user2 = substr($witness_user2,0,3);

$organizeID = $_POST["organize"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>        

</head>
<body>
<?php
pg_query("BEGIN WORK");
$status = 0;

// ตรวจสอบข้อมูลที่เลือกว่ามีในฐานข้อมูลหรือไม่ 
$query_authorize = pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$authorize_user' ");
$num_authorize=pg_num_rows($query_authorize);

$query_seize = pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$seize_user' ");
$num_seize=pg_num_rows($query_seize);

$query_witness1 = pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$witness_user1' ");
$num_witness1=pg_num_rows($query_witness1);

$query_witness2 = pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$witness_user2' ");
$num_witness2=pg_num_rows($query_witness2);

if($num_authorize == 0 || $num_seize == 0 || $num_witness1 == 0 || $num_witness2 == 0){
	$status = 1;
}else{
	$result2="update  \"nw_seize_car\" set 	\"seize_user\" = '$seize_user',
											\"status_approve\" = '3',
											\"authorize_user\" ='$authorize_user',
											\"witness_user1\" = '$witness_user1',
											\"witness_user2\" = '$witness_user2',
											\"organizeID\" = '$organizeID',
											\"proxy_usersend\" = '$proxy_usersend',
											\"proxy_datesend\" = '$proxy_datesend'
											where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID'";
	if($result=pg_query($result2)){
			
	}else{
		$status += 1;
	}
	
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div><br>";
	?>
	<form method="post" name="form1" action="pdf_send_seize.php" target="_blank">
		<input type="hidden" name="IDNO" value="<?php echo $IDNO?>">
		<input type="hidden" name="NTID" value="<?php echo $NTID?>">
		<center><input type="submit" value="พิมพ์หนังสือมอบอำนาจ"><input type="button" value="     ปิดหน้านี้     " onclick="javascript:RefreshMe();"></center>
	</form>
	
	<?php
	
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
	echo "<meta http-equiv='refresh' content='2; URL=frm_send_seize.php?idno=$IDNO&ntid=$NTID'>";
}		  
?>
</body>
</html>