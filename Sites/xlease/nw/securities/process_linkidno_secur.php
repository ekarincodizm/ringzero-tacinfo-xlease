<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$securID = $_POST['securID'];
$IDNO1 = trim($_POST['IDNO1']);
$note = trim($_POST['note']);
$typechk = $_POST['typecon'];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$tc_clTypeRef = $_POST['time'];
$tc_clValue = $_POST['money'];
$tc_clIntPenalty = $_POST['interest'];
$tc_clStartDate = $_POST['datein'];
$tc_clClosedDate = $_POST['dateout'];


$note = checknull($note);
$status = 0;

pg_query("BEGIN");
for($i=0;$i<sizeof($securID);$i++){

	list($id,$secur)=explode("#",$securID[$i]);
	
	$sqlselect = pg_query("SELECT MAX(\"tc_clSerial\") as serial FROM thcap_contract_collateral");
	$queryselect = pg_fetch_array($sqlselect);
	$pkid = $queryselect['serial'];
	$pkidnew = $pkid + 1;
	
	if($typechk[$i] == '1'){
	
	
	
		$sql = "INSERT INTO thcap_contract_collateral(\"tc_clSerial\",\"securID\", \"contractID\", note,\"tc_clType\") VALUES ('$pkidnew','$id', '$IDNO1', $note,'$typechk[$i]')";
		$query = pg_query($sql);
		if($query){}else{ $status++;}
		
			$sql1 = "INSERT INTO thcap_contract_collateral_regdetails(\"tc_clSerial\", reg_iduser, reg_stamp, appvreg_status)VALUES ('$pkidnew', '$id_user', '$datenow', '0')";
			$query1 = pg_query($sql1);
			if($query1){}else{ $status++;}
		
		
	}else if($typechk[$i] == '2'){

		$tc_clTypeRef1 = checknull($tc_clTypeRef[$i]);
		$tc_clValue1 = checknull($tc_clValue[$i]);
		$tc_clIntPenalty1 = checknull($tc_clIntPenalty[$i]);
		$tc_clStartDate1 = checknull($tc_clStartDate[$i]);
		$tc_clClosedDate1 = checknull($tc_clClosedDate[$i]);


			$sql = "INSERT INTO thcap_contract_collateral(\"tc_clSerial\",\"securID\", \"contractID\", note,\"tc_clType\",\"tc_clTypeRef\",\"tc_clValue\",\"tc_clIntPenalty\",\"tc_clStartDate\",\"tc_clClosedDate\") 
					VALUES ('$pkidnew','$id', '$IDNO1', $note,'$typechk[$i]',$tc_clTypeRef1, $tc_clValue1, $tc_clIntPenalty1,$tc_clStartDate1,$tc_clClosedDate1)";
			$query = pg_query($sql);
			if($query){}else{ $status++;}
			
				$sql1 = "INSERT INTO thcap_contract_collateral_regdetails(\"tc_clSerial\", reg_iduser, reg_stamp, appvreg_status)VALUES ('$pkidnew', '$id_user', '$datenow', '0')";
				$query1 = pg_query($sql1);
				if($query1){}else{ $status++;}
	}else{	
		$status++;
	}
}
if($status == 0){

	pg_query("COMMIT");
	echo "<center><h1>success</h1></center>";
	echo "<meta http-equiv=\"refresh\" content=\"3; URL=frm_linkcontract_sec.php\">";
}else{

	pg_query("ROLLBACK");
	echo "<center><h1>error</h1></center>";
	//echo "<meta http-equiv=\"refresh\" content=\"10; URL=process_linkidno_secur.php\">";
	echo $sql;
}

?>