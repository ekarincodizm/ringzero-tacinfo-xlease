<?php
//ไฟล์นี้ เงินโอนที่ออกผิดเลขที่สัญญา
session_start();
include("../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate=date("Y-m-d");
$cid = pg_escape_string($_GET["cid"]);
$rid = pg_escape_string($_GET["rid"]);
$user_id = $_SESSION["av_iduser"];
$memo = pg_escape_string($_GET["memo"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">

<div style="float:left"><input name="button" type="button" onclick="window.location='approve_rec.php'" value=" ย้อนกลับ " /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>อนุมัติยกเลิกใบเสร็จ</B></legend>

<div align="center">

<?php
pg_query("BEGIN WORK");
$status=0;

$qry_while = pg_query("SELECT \"c_memo\" FROM \"CancelReceipt\" WHERE \"c_receipt\"='$cid' ORDER BY \"c_receipt\" ASC ");
if($res_while = pg_fetch_array($qry_while)){
    $arr_memo = explode("#",$res_while['c_memo']);
    $main_cid = $arr_memo[1];
}

$qry_while = pg_query("SELECT * FROM \"CancelReceipt\" WHERE \"c_memo\" LIKE '@#$main_cid%' ORDER BY \"c_receipt\" ASC ");
while($res_while = pg_fetch_array($qry_while)){
    $cid = $res_while['c_receipt'];
    $rid = $res_while['ref_receipt'];

	$type=substr($rid,2,1);

	if($type=="R"){
		
		//ตรวจสอบงวดสุดท้าย แล้วแก้ไข Fp กลับคืนค่าเดิม
		$qry_last=pg_query("select \"IDNO\",\"R_DueNo\",\"R_memo\" from \"Fr\" WHERE \"R_Receipt\"='$rid' ORDER BY \"R_DueNo\" DESC ");
		if( $res_last=pg_fetch_array($qry_last) ){
			$last_IDNO = $res_last["IDNO"];
			$last_R_DueNo = $res_last["R_DueNo"];
			$last_R_memo = $res_last["R_memo"];
		}
		
		$qry_fr=pg_query("select * from \"Fr\" WHERE \"R_Receipt\" = '$rid' LIMIT(1)");
		if($res_fr=pg_fetch_array($qry_fr)){
			$idno = $res_fr["IDNO"];
			$money = $res_fr["R_Money"];
			$bank = $res_fr["R_Bank"];
			$prndate = $res_fr["R_Prndate"];
			$paytype = $res_fr["PayType"];
			$memo = $res_fr["R_memo"];
			$k_date = $res_fr["R_Date"];
			
			if($last_R_DueNo < 99 AND $last_R_DueNo != 0){
				$qry_cc1=pg_query("select \"VatValue\" from \"FVat\" WHERE \"IDNO\"='$last_IDNO' AND \"V_DueNo\"='$last_R_DueNo' ");
				if($res_cc1=pg_fetch_array($qry_cc1)){
					$vat = $res_cc1['VatValue'];
				}
			}elseif($last_R_DueNo == 99 OR $last_R_DueNo == 0){
				$qry_cc1=pg_query("select \"V_memo\",\"VatValue\" from \"FVat\" WHERE \"IDNO\"='$last_IDNO' AND \"V_DueNo\"='$last_R_DueNo' ");
				if($res_cc1=pg_fetch_array($qry_cc1)){
					$V_memo = $res_cc1['V_memo'];
					if($V_memo == "$rid"){
						$vat = $res_cc1['VatValue'];
					}else{
						$vat = 0;
					}
				}
			}

			$money = $money+$vat;
		}
		
		$qry_total=pg_query("select \"P_TOTAL\" from \"Fp\" WHERE \"IDNO\"='$last_IDNO' ");
		if( $res_total=pg_fetch_array($qry_total) ){
			$P_TOTAL = $res_total["P_TOTAL"];
			$P_SL = $res_total["P_SLBAK"];
		}
		
		if($last_R_DueNo == $P_TOTAL){
			$result=pg_query("UPDATE \"Fp\" SET \"P_ACCLOSE\"='FALSE',\"P_CLDATE\"=DEFAULT,\"P_SLBAK\"='0' WHERE \"IDNO\"='$last_IDNO';");
			if(!$result){
				$status++;
			}
			
			$money = $money-$P_SL;
		}
		
		$result=pg_query("UPDATE \"Fr\" SET \"Cancel\"='TRUE' WHERE \"R_Receipt\"='$rid'");
		if(!$result){
			$status++;
		}
		
		// ========== Edit ========== //
		if($last_R_memo == "TR-ACC"){
			$qry_dttp=pg_query("select \"PostID\" from \"DetailTranpay\" WHERE \"ReceiptNo\"='$rid' ");
			if( $res_dttp=pg_fetch_array($qry_dttp) ){
				$PostID = $res_dttp["PostID"];
			}

			$result1=pg_query("UPDATE \"DetailTranpay\" SET \"Cancel\"='TRUE' WHERE \"PostID\"='$PostID' ");
			if(!$result1){
				$status++;
			}
			
			$result=pg_query("UPDATE \"TranPay\" SET \"ref1\"=DEFAULT,\"ref2\"=DEFAULT,\"ref_name\"=DEFAULT,\"post_on_date\"=DEFAULT,\"post_to_idno\"=DEFAULT,\"post_by\"=DEFAULT,\"post_on_asa_sys\"='FALSE' WHERE \"PostID\"='$PostID' ");
			if(!$result){
				$status++;
			}
		}

	}
	elseif($type=="N" || $type=="K"){
		$qry_oth=pg_query("select * from \"FOtherpay\" WHERE \"O_RECEIPT\" = '$rid' LIMIT(1) ");
		if($res_oth=pg_fetch_array($qry_oth)){
			$idno = $res_oth["IDNO"];
			$money = $res_oth["O_MONEY"];
			$bank = $res_oth["O_BANK"];
			$prndate = $res_oth["O_PRNDATE"];
			$paytype = $res_oth["PayType"];
			$memo = $res_oth["O_memo"];
			$k_date = $res_oth["O_DATE"];
			$last_O_memo=$res_oth["O_memo"];
		}
		
		$result=pg_query("UPDATE \"FOtherpay\" SET \"Cancel\"='TRUE' WHERE \"O_RECEIPT\"='$rid'");
		if(!$result){
			$status++;
		}

		// ========== Edit ========== //
		if($last_O_memo == "TR-ACC"){
			$qry_dttp=pg_query("select \"PostID\" from \"DetailTranpay\" WHERE \"ReceiptNo\"='$rid' ");
			if( $res_dttp=pg_fetch_array($qry_dttp) ){
				$PostID = $res_dttp["PostID"];
			}
			
			$result1=pg_query("UPDATE \"DetailTranpay\" SET \"Cancel\"='TRUE' WHERE \"PostID\"='$PostID' ");
			if(!$result1){
				$status++;
			}
			
			$result=pg_query("UPDATE \"TranPay\" SET \"ref1\"=DEFAULT,\"ref2\"=DEFAULT,\"ref_name\"=DEFAULT,\"post_on_date\"=DEFAULT,\"post_to_idno\"=DEFAULT,\"post_by\"=DEFAULT,\"post_on_asa_sys\"='FALSE' WHERE \"PostID\"='$PostID' ");
			if(!$result){
				$status++;
			}
		}
		
	}
	elseif($type=="V"){
		$qry_vat=pg_query("select * from \"FVat\" WHERE \"V_Receipt\" = '$rid' LIMIT(1) ");
		if($res_vat=pg_fetch_array($qry_vat)){
			$idno = $res_vat["IDNO"];
			$money = $res_vat["VatValue"];
			$bank = "";
			$prndate = $res_vat["V_PrnDate"];
			$paytype = "";
			$memo = $res_vat["V_memo"];
			$k_date = $res_vat["V_Date"];
		}

		$result=pg_query("UPDATE \"FVat\" SET \"Cancel\"='TRUE' WHERE \"V_Receipt\"='$rid'");
		if(!$result){
			$status++;
		}
	}


	$result=pg_query("UPDATE \"CancelReceipt\" SET \"admin_approve\"='TRUE',\"approveuser\"='$user_id' WHERE \"c_receipt\"='$cid' ");
	if(!$result){
		$status++;
	}

}// จบ while main

if($status == 0){	
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติยกเลิกใบเสร็จ', '$datelog')");
		//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้";
}
?>

</div>

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>