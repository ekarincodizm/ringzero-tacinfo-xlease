<?php 
session_start();
include("../config/config.php");
include("../nw/function/checknull.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$status = 0;
$chkstate = $_POST["chkstate"];
$appshk = $_POST["chkapp"];

pg_query("BEGIN");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div>
<?php
//ส่งมาเพื่อเข้ากระบวนการอนุมัติ
if($chkstate == "allowcan"){

$fp_appid = $_POST['idapp']; //รับไอดีที่ถูกส่งมาอนุมัติ

	//ตรวจสอบว่ารายการทั้งหมดยังไม่ถูกอนุมัติไปก่อนหน้านี้
	for($i=0;$i<sizeof($fp_appid);$i++){		
			$chk_app = pg_query("	
									SELECT * 
									FROM 	\"Fp_cancel_approve\"
									WHERE 	\"fp_appID\" = '$fp_appid[$i]' AND 
											\"appstatus\" = '0' 
								");
			$row_chkapp = pg_num_rows($chk_app);
			if($row_chkapp == 0){ $status++;}
	}
		//หากยังไม่มีการอนุมัติรายการใดๆเลย
		if($status == 0){
			//กรณีอนุมัติ	
			if($appshk == "approve"){		
				for($i=0;$i<sizeof($fp_appid);$i++){			
					$selectdata = pg_query("	
												SELECT 	reason,\"IDNO\" 
												FROM 	\"Fp_cancel_approve\" 
												WHERE 	\"fp_appID\" = '$fp_appid[$i]' 
										  ");
					$resultdata = pg_fetch_array($selectdata);
					
					$reason = $resultdata['reason'];
					$h_id = $resultdata['IDNO'];
					
					$qry_stdate=pg_query("	
											SELECT	\"IDNO\",\"P_STDATE\" 
											FROM 	\"Fp\" 
											WHERE 	\"IDNO\"='$h_id' 
										");
					$res_stdate=pg_fetch_array($qry_stdate);
					$stdate=$res_stdate["P_STDATE"];


					$up_del="		UPDATE 	\"Fp\" 
									SET 	\"PayType\"='CC',
											\"P_ACCLOSE\"=TRUE,
											\"P_StopVat\"=TRUE,
											\"P_CLDATE\"='$stdate',
											\"P_StopVatDate\"='$stdate',
											\"ComeFrom\"='$reason'
									WHERE	\"IDNO\"='$h_id'   
							"; 
						  if($result=pg_query($up_del)){}else{$status++;}
						  
					$up_del_app="	UPDATE 	\"Fp_cancel_approve\" 
									SET 	appuser='$user_id', 
											appdate='$add_date', 
											appstatus='1' 
									WHERE 	\"fp_appID\" = '$fp_appid[$i]' 
								";
					if($resultapp=pg_query($up_del_app)){}else{$status++;}
				}
			//กรณีไม่อนุมัติ	
			}else if($appshk == "notapprove"){
				$appreason = checknull($_POST['reasonnotapp']);		
				for($i=0;$i<sizeof($fp_appid);$i++){					
					$up_del_app="	UPDATE 	\"Fp_cancel_approve\" 
									SET 	appuser='$user_id', 
											appdate='$add_date', 
											appstatus='2',
											app_reason = $appreason 
									WHERE 	\"fp_appID\" = '$fp_appid[$i]'
								";
					if($resultapp=pg_query($up_del_app)){}else{$status++;}
				} 	
			}
		//หากมีการอนุมัติไปก่อนแล้ว	
		}else{
			$alerttxt = 'ขออภัย มีการอนุมัติรายการไปก่อนหน้านี้แล้ว';
		}
//ส่งมาเข้ากระบวนการรออนุมัติ
}else if($chkstate == "waitapp"){
		$h_id=$_POST["h_idno"]; //เลขที่สัญญา
		$s_text=$_POST["text"]; //เหตุผลที่ขอยกเลิก
		//ตรวจสอบว่ารายการทั้งหมดยังไม่ถูกนำเข้าไปรออนุมัติก่อนหน้านี้		
				$chk_app = pg_query("	
										SELECT * 
										FROM 	\"Fp_cancel_approve\" 
										WHERE 	\"IDNO\" = '$h_id' AND 
												\"appstatus\" = '0'
									");
				$row_chkapp = pg_num_rows($chk_app);
				if($row_chkapp > 0){ $status++;}
		
		//หากยังไม่มีการยกเลิกก่อนหน้านี้
		IF($status == 0){
				$up_del_app="	INSERT INTO \"Fp_cancel_approve\"(
																	\"IDNO\", 
																	reason, 
																	id_user, 
																	cancel_date,
																	appstatus
																 )
														 VALUES (	'$h_id', 
																	'$s_text', 
																	'$user_id', 
																	'$add_date',
																	'0'
																)"; 
			  if($resultapp=pg_query($up_del_app)){}else{$status++;}
			  
		//หากมีการขอยกเลิกไปก่อนแล้ว	
		}else{
			$alerttxt = 'ขออภัย มีการขอยกเลิกเลขที่สัญญานี้ไปก่อนหน้านี้แล้ว';
		}
}
	

	
		 
if($status == 0 && $chkstate == "waitapp"){

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ยกเลิกสัญญาเช่าซื้อ', '$add_date')");
	//ACTIONLOG---
	
		pg_query("COMMIT");	
			$statuspro ="ขอยกเลิก เลขที่สัญญา ".$h_id." รอการอนุมัติ";
			echo "<center><h1> $statuspro </h1></center>";
			echo "<meta http-equiv=\"refresh\" content=\"3;URL=cc_idno.php\">";
  
}else if($status == 0 && $chkstate == "allowcan"){

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติยกเลิกสัญญาเช่าซื้อ', '$add_date')");
	//ACTIONLOG---
	
		pg_query("COMMIT");
				if($appshk == "approve"){	
					$statuspro ="อนมุติ การขอยกเลิกเลขที่สัญญา ";
				}else if($appshk == "notapprove"){
					$statuspro ="ไม่อนุมัติ การขอยกเลิกเลขที่สัญญา ";
				}
					echo "<center><h1> $statuspro </h1></center>";
					echo "<meta http-equiv=\"refresh\" content=\"2;URL=cc_approve.php\">";
  
}else{

		pg_query("ROLLBACK");

			echo "<center><h1> $alerttxt <p>(ระบบจะนำคุณกลับไปภายใน 5 วินาที) </h1></center>";
			IF($chkstate == "allowcan"){
				echo "<meta http-equiv=\"refresh\" content=\"5;URL=cc_approve.php\">";
			}else{
				echo "<meta http-equiv=\"refresh\" content=\"5;URL=cc_idno.php\">";
			}	
}

 
 
?>
</div>
</body>
</html>
