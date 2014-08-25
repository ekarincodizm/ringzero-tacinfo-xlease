<?php
session_start();
$id_user = $_SESSION["av_iduser"];
include("../../../config/config.php");

$BID = $_POST['Bankname'];
$BDATE = $_POST['Bankdate'];
$revID = $_POST['revTranID'];
$hh	= $_POST["hh"];
$mm = $_POST["mm"];
$branch = $_POST["bran"];
$money = $_POST["money"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$datelog=$datenow;
$status = 0;
$o = 1;

PG_QUERY("BEGIN");



	$qry_sel = pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranStatus\" in('4') AND \"appvXID\" is not null AND \"bankRevAccID\" = '$BID' AND Date(\"bankRevStamp\") = '$BDATE'");
			while($re_sel = pg_fetch_array($qry_sel)){
					$revTranID = $re_sel['revTranID'];
					$bankRevAccID = $re_sel['bankRevAccID'];
					$bankRevBranch = $re_sel['bankRevBranch'];
					$bankRevStamp = $re_sel['bankRevStamp'];
					$bankRevAmt = $re_sel['bankRevAmt'];
					$doerID = $re_sel['doerID'];
					$doerStamp = $re_sel['doerStamp'];
					$appvXID = $re_sel['appvXID'];
					$appvXStamp = $re_sel['appvXStamp'];
					$appvXRemask = $re_sel['appvXRemask'];				
				
					$qry_edittime = pg_query("select MAX(\"edittime\") as edittime from finance.\"thcap_receive_transfer_noapptemp\" where \"revTranID\" = '$revTranID' ");
						list($maxedit) = pg_fetch_array($qry_edittime);
						if($maxedit == ""){ $maxedit = '0';}else{ $maxedit++;}
					$qry_in = pg_query("INSERT INTO finance.thcap_receive_transfer_noapptemp(
							\"revTranID\", \"bankRevAccID\", \"bankRevBranch\", \"bankRevStamp\", 
							\"bankRevAmt\", \"doerID\", \"doerStamp\", \"appvXID\", \"appvXStamp\", 
							\"appvXRemask\", edittime, \"statusEnd\")
					VALUES ('$revTranID', '$bankRevAccID', '$bankRevBranch', '$bankRevStamp', 
							'$bankRevAmt', '$doerID', '$doerStamp','$appvXID', '$appvXStamp', 
							'$appvXRemask', '$maxedit', '1')");
					if($qry_in){}else{ $status++ ;}	
												
			}

if($revID != ""){	
		
	for($i = 0;$i<sizeof($revID);$i++){
		if($revID[$i] != ""){
			
			$datein = $BDATE." ".$hh[$i].":".$mm[$i].":"."00";			
			$qry_up = pg_query("UPDATE finance.thcap_receive_transfer SET \"bankRevBranch\"='$branch[$i]', \"bankRevStamp\"='$datein', \"bankRevAmt\"='$money[$i]' WHERE \"revTranID\" = '$revID[$i]' ");
			if($qry_up){}else{ $status++; echo $error_mes = "การแก้ไขข้อมูลผิดพลาด\n"; break;}
			$qry_up = pg_query("UPDATE finance.thcap_receive_transfer_action SET \"doerID\" = '$id_user', \"doerStamp\" = '$datenow' WHERE \"revTranID\" = '$revID[$i]' ");
			if($qry_up){}else{ $status++; echo $error_mes = "การแก้ไขข้อมูลผิดพลาด\n"; break;}
					
				if($o == sizeof($revID)){
					$revlist = $revlist."'".$revID[$i]."'";
				}else{			
					$revlist = $revlist."'".$revID[$i]."'".",";		
				}$o++;	
				
			//หาข้อมูลเพื่อนำมาเก็บใน log
			$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revID[$i]'");
			if($resdata=pg_fetch_array($qrydata)){
				$BAccount=$resdata["BAccount"];
				$remark=$resdata["appvXRemask"];
			}
			//LOG
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
			\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
			VALUES ('แก้ไขรายการเงินโอน','$revID[$i]','$id_user', '$datelog','$BAccount',
			'$branch[$i]','$money[$i]','$datein','$remark')")); else $status++;
			//LOG---	
		}		
	}
	$qry_checkdel = pg_query("select \"revTranID\" from finance.\"thcap_receive_transfer\" where \"revTranStatus\" = '4'  AND \"bankRevAccID\" = '$BID' AND DATE(\"bankRevStamp\") = '$BDATE' AND \"revTranID\" NOT IN ($revlist)");
	$row_checkdel = pg_num_rows($qry_checkdel);
	if($row_checkdel > 0){
		while($re_del = pg_fetch_array($qry_checkdel)){
			$revTranIDdel =  $re_del["revTranID"];
			
			//หาข้อมูลเพื่อนำมาเก็บใน log
			$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranIDdel'");
			if($resdata=pg_fetch_array($qrydata)){
				$Bank=$resdata["BAccount"];
				$bankBranch=$resdata["bankRevBranch"];
				$bankAmt=$resdata["bankRevAmt"];
				$bankStamp=$resdata["bankRevStamp"];
				$remark2=$resdata["appvXRemask"];
			}
			//LOG
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
			\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
			VALUES ('ลบรายการเงินโอน','$revTranIDdel','$id_user', '$datelog','$Bank',
			'$bankBranch','$bankAmt','$bankStamp','$remark2')")); else $status++;
			//LOG---	
			
			$qry_del1 = pg_query("DELETE FROM finance.thcap_receive_transfer_action WHERE \"revTranID\" = '$revTranIDdel' ");
			if($qry_del1){}else{ $status++; echo $error_mes = "การลบข้อมูลผิดพลาด\n"; break;}
			$qry_del2 = pg_query("DELETE FROM finance.thcap_receive_transfer WHERE \"revTranID\" = '$revTranIDdel' ");
			if($qry_del2){}else{ $status++; echo $error_mes = "การลบข้อมูลผิดพลาด\n"; break;}
			$qry_delstate = pg_query("UPDATE finance.thcap_receive_transfer_noapptemp SET \"statusEnd\" = '0' where \"revTranID\" = '$revTranIDdel' ");
			if($qry_delstate){}else{ $status++ ;}	
		}	
	}
	
}else{
	$qry_checkdel = pg_query("select \"revTranID\" from finance.\"thcap_receive_transfer\" where \"revTranStatus\" = '4'  AND \"bankRevAccID\" = '$BID' AND DATE(\"bankRevStamp\") = '$BDATE'");
	$row_checkdel = pg_num_rows($qry_checkdel);
	if($row_checkdel > 0){
		while($re_del = pg_fetch_array($qry_checkdel)){
			$revTranIDdel =  $re_del["revTranID"];
			//หาข้อมูลเพื่อนำมาเก็บใน log
			$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranIDdel'");
			if($resdata=pg_fetch_array($qrydata)){
				$Bank=$resdata["BAccount"];
				$bankBranch=$resdata["bankRevBranch"];
				$bankAmt=$resdata["bankRevAmt"];
				$bankStamp=$resdata["bankRevStamp"];
				$remark2=$resdata["appvXRemask"];
			}
			//LOG
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
			\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
			VALUES ('ลบรายการเงินโอน','$revTranIDdel','$id_user', '$datelog','$Bank',
			'$bankBranch','$bankAmt','$bankStamp','$remark2')")); else $status++;
			//LOG---	
			$qry_del1 = pg_query("DELETE FROM finance.thcap_receive_transfer_action WHERE \"revTranID\" = '$revTranIDdel' ");
			if($qry_del1){}else{ $status++; echo $error_mes = "การลบข้อมูลผิดพลาด\n"; break;}
			$qry_del2 = pg_query("DELETE FROM finance.thcap_receive_transfer WHERE \"revTranID\" = '$revTranIDdel' ");
			if($qry_del2){}else{ $status++; echo $error_mes = "การลบข้อมูลผิดพลาด\n"; break;}
			$qry_delstate = pg_query("UPDATE finance.thcap_receive_transfer_noapptemp SET \"statusEnd\" = '0' where \"revTranID\" = '$revTranIDdel' ");
			if($qry_delstate){}else{ $status++ ;}	
		}	
	}
}
			$qry_sel = pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranStatus\" in('0','4') AND \"appvXID\" is not null AND \"bankRevAccID\" = '$BID' AND Date(\"bankRevStamp\") = '$BDATE'");
			while($re_sel = pg_fetch_array($qry_sel)){
					$revTranID = $re_sel['revTranID'];
					$qry_upstate = pg_query("UPDATE finance.thcap_receive_transfer SET \"revTranStatus\"= '9' WHERE \"revTranID\"= '$revTranID'");
					if($qry_upstate){}else{ $status++ ;}
					
					$qry_upaction = pg_query("UPDATE finance.thcap_receive_transfer_action SET \"appvXID\"=null, \"appvXStamp\"=null,\"appvXRemask\"=null, \"appvXStatus\"=null WHERE \"revTranID\"='$revTranID'");
					if($qry_upaction){}else{ $status++ ;}		
			}
			
	
	

	
	if($status == 0){
		pg_query("COMMIT");	
		echo "<script type='text/javascript'>alert('Save Successful')</script>";
		echo "<script type='text/javascript'>				
				window.opener.location.href = window.opener.location;				
				self.close();
		  </script>";
		exit();
		
	}else{
		
		pg_query("ROLLBACK");
		//echo "<meta http-equiv=\"refresh\" content=\"5; URL=frm_edit_edit_notapp.php?BID=$BID&date=$BDATE\">";
		echo "<p><center>Error !!<P> $error_mes</center>";
		exit();
	}	

?>
