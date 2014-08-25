<?php
session_start();
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$id_user = $_SESSION["av_iduser"];
$page=$_REQUEST["page"]; //เมนูที่เรียกใช้หน้านี้นอกจากหน้าตนเอง
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$qry_username=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
while($res_username=pg_fetch_array($qry_username))
{
	$username = trim($res_username["username"]);
}

pg_query("BEGIN WORK");
$status = 0;
			
$contractID = $_POST["ConID3"];
$chk = $_POST["chk"];
$txtRemark = $_POST["txtRemark"];
if($chk[0] == ""){$status++;}else{

	for($i=0;$i<sizeof($chk);$i++)
	{
		$debtIDchk = split(" ",$chk[$i]); // [0]=จำนวนหนี้  /[1]=รหัสหนี้
		IF($debtID == ""){
			$debtID = "$debtIDchk[1]";
		}else{
			$debtID = "$debtID,$debtIDchk[1]";
		}	
	}	


	$qry_check = "SELECT thcap_process_except_debt('{".$debtID."}','$txtRemark','$id_user','')";
	if($resultD=pg_query($qry_check)){}else{$status++;}
}
// QUERY เก่า ==============================================================================================================================================================================-
//-------------------------------- INSERT ข้อมูลลงใน thcap_temp_except_debt ดังนี้ (จำนวน ROW ที่ insert = รายการหนี้ที่ติ๊กจะชำระ)
	// for($i=0;$i<sizeof($chk);$i++)
	// {
		// $debtIDchk = split(" ",$chk[$i]); // [0]=จำนวนหนี้  /[1]=รหัสหนี้
		// $debtID = "$debtIDchk[1]";
		
		// $qry_check=pg_query("select * from public.\"thcap_temp_except_debt\" where \"debtID\"='$debtID' ");
		// $numrows = pg_num_rows($qry_check);
		// if($numrows == "1")
		// {
			// $result = pg_fetch_array($qry_check);
			// $appstatue = $result['Approve'];
			// $doerUser = $result['doerUser'];
			// $doerStamp = $result['doerStamp'];
			// $remark = $result['remark'];
			
			// if($appstatue == "f"){
			
				// $newtxtRemark = $txtRemark."\n\n".$doerUser." (".$doerStamp.") > ไม่อนุมัติ \n--------------------------------------------------------------------------------------\n".$remark."\n\n";
				
				
				// $qry_in="update \"thcap_temp_except_debt\" 
				// SET\"doerUser\" = '$username',\"doerStamp\" = '$logs_any_time',\"remark\" = '$newtxtRemark',\"appvUser\"=null,\"appvStamp\"=null, \"Approve\"=null
				// where  \"debtID\"='$debtID'";
				// if($resultD=pg_query($qry_in)){
				// }else{
					// $status++;
				// }
			// }else{ 
				// $check_except = $check_except." ".$debtID; // รหัสหนี้ที่เคยร้องขอการยกเว้นไปแล้ว
				// $status++;
			// }	
		// }
		// else
		// {
			// $qry_in="insert into public.\"thcap_temp_except_debt\" (\"debtID\",\"doerUser\",\"doerStamp\",\"remark\") values ('$debtID','$username','$logs_any_time','$txtRemark') ";
			// if($resultD=pg_query($qry_in)){
			// }else{
				// $status++;
			// }
		// }
	// }
//-------------------------------- end INSERT ข้อมูลลงใน thcap_temp_except_debt ดังนี้ (จำนวน ROW ที่ insert = รายการหนี้ที่ติ๊กจะชำระ)
// QUERY เก่า ==============================================================================================================================================================================-   

if($status == 0){
	
	pg_query("COMMIT"); // เอาจริง
	
	echo "<br><center><h3>บันทึกสมบูรณ์</h3></center>";
	if($page=='thcap_installments' || $page=='fapn_statement'){
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
			opener.location.reload(true);
			self.close();
		</script>";
	}else{
		echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	}
	
}else{
	pg_query("ROLLBACK");
	echo "<br><center><h2>บันทึกผิดพลาด!!</h2></center>";
	if($page=='thcap_installments' || $page=='fapn_statement'){
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
			opener.location.reload(true);
			self.close();
		</script>";
	}else{
		echo "<meta http-equiv='refresh' content='6; URL=Payments_history.php?ConID=$contractID'>";
	}
}
?>