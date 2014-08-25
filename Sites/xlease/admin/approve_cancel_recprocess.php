<?php
//ไฟล์นี้ ยกเลิกทั่วไป
include("../config/config.php");
 
$nowdate=date("Y-m-d");
$cid = pg_escape_string($_GET["cid"]);
$rid = pg_escape_string($_GET["rid"]);
$memo = pg_escape_string($_GET["memo"]);
$statusapp = pg_escape_string($_GET["statusapp"]);
$user_id = $_SESSION["av_iduser"];
if($statusapp==""){
	$cid = $_POST["cid"];
	$rid = $_POST["rid"];
	$memo = $_POST["memo"];
	if(isset($_POST["f_appv"])){
		$statusapp=1;//กดอนุมัติ
	}else if(isset($_POST["f_unappv"])){
		$statusapp=2;//กดไม่อนุมัติ
	}
}
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


//อันดับแรกต้องตรวจสอบก่อนว่าข้อมูลได้ถูกอนุมัติหรือยกเลิกไปก่อนหน้านี้แล้วหรือยัง
$qry_check=pg_query("select * from \"CancelReceipt\" where \"c_receipt\" = '$cid' and \"admin_approve\"='FALSE'");
$numcheck=pg_num_rows($qry_check);
if($numcheck==0){ //แสดงว่ามีการ approve ก่อนหน้านี้แล้ว
	echo "<div style=\"padding:20px;text-align:center;\"><h2>มีการทำรายการก่อนหน้านี้แล้วค่ะ</h2></div>";
}else{
	$qry_cc=pg_query("select \"c_money\",\"return_to\" from \"CancelReceipt\" WHERE \"c_receipt\" = '$cid'");
	if($res_cc=pg_fetch_array($qry_cc)){
		$str_money = $res_cc["c_money"];
		$return_to = $res_cc["return_to"];
	}


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
				// $qry_cc1=pg_query("select \"VatValue\" from \"FVat\" WHERE \"IDNO\"='$last_IDNO' AND \"V_DueNo\"='$last_R_DueNo' ");

				//หาจำนวนเงินที่ต้องการคืน
				$qry_cc1=pg_query("select a.\"IDNO\",sum(\"R_Money\") as \"R_Money\",\"R_Receipt\",sum(\"VatValue\") as \"VatValue\" from \"Fr\" a 
				left join \"FVat\" b on a.\"IDNO\" =b.\"IDNO\" and a.\"R_DueNo\"=b.\"V_DueNo\"
				WHERE \"R_Receipt\"='$rid' 
				GROUP BY a.\"IDNO\",\"R_Receipt\"");
				
				if($res_cc1=pg_fetch_array($qry_cc1)){
					$vat = $res_cc1['VatValue'];
					$money = $res_cc1['R_Money'];
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
			/*
			$qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$idno' LIMIT(1)");
			if($res_cc1=pg_fetch_array($qry_cc1)){
				$vat = $res_cc1['VatValue'];
			}*/
			$money = $money+$vat;
		}
		
		$qry_total=pg_query("select \"P_TOTAL\",\"P_SLBAK\" from \"Fp\" WHERE \"IDNO\"='$last_IDNO' ");
		if( $res_total=pg_fetch_array($qry_total) ){
			$P_TOTAL = $res_total["P_TOTAL"];
				$P_SL = $res_total["P_SLBAK"]; //โค้ดเก่า
			
		}
		if($statusapp==1){
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
		
			if(empty($return_to)){
				$qry_k_no=pg_query("select gen_k_no('$k_date')");
				$res_k_no=pg_fetch_result($qry_k_no,0);
			}

			// ========== Edit 1 ========== //
			if($last_R_memo == "TR-ACC" OR $last_R_memo == "Bill Payment"){
				$qry_dttp=pg_query("select * from \"DetailTranpay\" WHERE \"ReceiptNo\"='$rid' ");
				if( $res_dttp=pg_fetch_array($qry_dttp) ){
					$PostID = $res_dttp["PostID"];
					$DT_IDNO = $res_dttp["IDNO"];
					$DT_Amount = $res_dttp["Amount"];
					$DT_ReceiptNo = $res_dttp["ReceiptNo"];
					$DT_PrnDate = $res_dttp["PrnDate"];
				}
				
				$result1=pg_query("UPDATE \"DetailTranpay\" SET \"Cancel\"='TRUE' WHERE \"ReceiptNo\"='$rid' ");
				if(!$result1){
					$status++;
				}
				
				$result2="insert into \"DetailTranpay\" (\"PostID\",\"IDNO\",\"TypePay\",\"Amount\",\"ReceiptNo\",\"PrnDate\") values ('$PostID','$DT_IDNO','200','$DT_Amount','$res_k_no','$DT_PrnDate')";
				if(!pg_query($result2)){
					$status++;
				}
				
				/*
				$result=pg_query("UPDATE \"TranPay\" SET \"ref1\"=DEFAULT,\"ref2\"=DEFAULT,\"ref_name\"=DEFAULT,\"post_on_date\"=DEFAULT,\"post_to_idno\"=DEFAULT,\"post_by\"=DEFAULT,\"post_on_asa_sys\"='FALSE' WHERE \"PostID\"='$PostID' ");
				if(!$result){
					$status++;
				}
				*/
			}
		} // statusapp

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
			$RefAnyID=$res_oth["RefAnyID"];
			
		}
		if($statusapp==1){
			$result=pg_query("UPDATE \"FOtherpay\" SET \"Cancel\"='TRUE' WHERE \"O_RECEIPT\"='$rid'");
			if(!$result){
				$status++;
			}
		
			/* ยกเลิก Mark ที่บอกว่าจ่ายแล้ว กลับมาเป็นยังไม่จ่าย*/
			//ยกเลิกของ พรบ
			$resultup=pg_query("UPDATE insure.\"InsureForce\" SET \"CusPayReady\"='False' WHERE \"InsFIDNO\"='$RefAnyID'");
			if(!$resultup){
				$status++;
			}
			
			//ยกเลิกของประกันภาคสมัครใจ
			$resultup2=pg_query("UPDATE insure.\"InsureUnforce\" SET \"CusPayReady\"='False' WHERE \"InsUFIDNO\"='$RefAnyID'");
			if(!$resultup2){
				$status++;
			}
			
			//ยกเลิกของคุ้มครองหนี้
			$resultup2=pg_query("UPDATE insure.\"InsureLive\" SET \"CusPayReady\"='False' WHERE \"InsLIDNO\"='$RefAnyID'");
			if(!$resultup2){
				$status++;
			}

			$resultup3=pg_query("UPDATE carregis.\"CarTaxDue\" SET \"cuspaid\"='False' WHERE \"IDCarTax\"='$RefAnyID'");
			if(!$resultup3){
				$status++;
			}

			if(empty($return_to)){
				$qry_k_no=pg_query("select gen_k_no('$k_date')");
				$res_k_no=pg_fetch_result($qry_k_no,0);
			}
		
			// ========== Edit 2 ========== //
			if($last_O_memo == "TR-ACC" OR $last_O_memo == "Bill Payment"){
				$qry_dttp=pg_query("select * from \"DetailTranpay\" WHERE \"ReceiptNo\"='$rid' ");
				if( $res_dttp=pg_fetch_array($qry_dttp) ){
					$PostID = $res_dttp["PostID"];
					$DT_IDNO = $res_dttp["IDNO"];
					$DT_Amount = $res_dttp["Amount"];
					$DT_ReceiptNo = $res_dttp["ReceiptNo"];
					$DT_PrnDate = $res_dttp["PrnDate"];
				}

				$result1=pg_query("UPDATE \"DetailTranpay\" SET \"Cancel\"='TRUE' WHERE \"ReceiptNo\"='$rid' ");
				if(!$result1){
					$status++;
				}
					
				$result2="insert into \"DetailTranpay\" (\"PostID\",\"IDNO\",\"TypePay\",\"Amount\",\"ReceiptNo\",\"PrnDate\") values ('$PostID','$DT_IDNO','200','$DT_Amount','$res_k_no','$DT_PrnDate')";
				if(!pg_query($result2)){
					$status++;
				}
				
				/*
				$result=pg_query("UPDATE \"TranPay\" SET \"ref1\"=DEFAULT,\"ref2\"=DEFAULT,\"ref_name\"=DEFAULT,\"post_on_date\"=DEFAULT,\"post_to_idno\"=DEFAULT,\"post_by\"=DEFAULT,\"post_on_asa_sys\"='FALSE' WHERE \"PostID\"='$PostID' ");
				if(!$result){
					$status++;
				}
				*/
			}
		}//statusapp
		
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
		if($statusapp==1){
			$result=pg_query("UPDATE \"FVat\" SET \"Cancel\"='TRUE' WHERE \"V_Receipt\"='$rid'");
			if(!$result){
				$status++;
			}
		
			if(empty($return_to)){
				$qry_k_no=pg_query("select gen_k_no('$k_date')");
				$res_k_no=pg_fetch_result($qry_k_no,0);
			}
		}//statusapp	
		
	}

	if(!empty($return_to)){//เข้าเงินสด
		if($statusapp==1){
			$result=pg_query("UPDATE \"CancelReceipt\" SET \"admin_approve\"='TRUE',\"approveuser\"='$user_id' WHERE \"c_receipt\"='$cid' ");
			if(!$result){
				$status++;
			}
		}else{
			$result=pg_query("UPDATE \"CancelReceipt\" SET \"admin_approve\"='TRUE',\"approveuser\"='$user_id',\"statusApprove\"='FALSE' WHERE \"c_receipt\"='$cid' ");
			if(!$result){
				$status++;
			}
		}
	}else{//เข้าเงินรับฝาก
		if($statusapp==1){
			$result=pg_query("insert into \"FOtherpay\" (\"IDNO\",\"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"O_BANK\",\"O_PRNDATE\",\"PayType\",\"O_memo\") values ('$idno','$k_date','$res_k_no','$str_money','200','$bank','$prndate','$paytype','$memo')");
			if(!$result){
				$status++;
			}
	 
			$result=pg_query("UPDATE \"CancelReceipt\" SET \"return_to\"='$res_k_no',\"admin_approve\"='TRUE',\"approveuser\"='$user_id' WHERE \"c_receipt\"='$cid' ");
			if(!$result){
				$status++;
			}
		}else{
			$result=pg_query("UPDATE \"CancelReceipt\" SET \"admin_approve\"='TRUE',\"approveuser\"='$user_id',\"statusApprove\"='FALSE' WHERE \"c_receipt\"='$cid' ");
			if(!$result){
				$status++;
			}
		}
		
	}// end check return_to

	if($bank == "CA" AND $paytype == "OC"){
		if($statusapp==1){ //กรณีอนุมัติ
			$result=pg_query("UPDATE \"FCash\" SET \"cancel\"='TRUE' WHERE \"refreceipt\"='$rid';");
			if(!$result){
				$status++;
			}
			
			if(empty($return_to)){
				$qry_fcash=pg_query("select * from \"FCash\" WHERE \"refreceipt\" = '$rid';");
				if($res_fcash=pg_fetch_array($qry_fcash)){
					$fcash_PostID = $res_fcash["PostID"];
					$fcash_CusID = $res_fcash["CusID"];
					$fcash_IDNO = $res_fcash["IDNO"];
				}
				
				$result=pg_query("insert into \"FCash\" (\"PostID\",\"CusID\",\"IDNO\",\"TypePay\",\"AmtPay\",\"refreceipt\") values ('$fcash_PostID','$fcash_CusID','$fcash_IDNO','200','$money','$res_k_no')");
				if(!$result){
					$status++;
				}
			}
		}  
	}
	if($bank == "TC" AND $paytype == "TCQ"){
		if($statusapp==1){
			$result=pg_query("UPDATE \"FTACCheque\" SET \"cancel\"='TRUE' WHERE \"refreceipt\"='$rid';");
			if(!$result){
				$status++;
			}
			
			if(empty($return_to)){
				$qry_FTACCheque=pg_query("select * from \"FTACCheque\" WHERE \"refreceipt\" = '$rid';");
				if($res_FTACCheque=pg_fetch_array($qry_FTACCheque)){
					$FTACCheque_PostID = $res_FTACCheque["PostID"];
					$FTACCheque_IDNO = $res_FTACCheque["COID"];
					$D_ChequeNo = $res_FTACCheque["D_ChequeNo"];
					$D_BankName = $res_FTACCheque["D_BankName"];
					$D_BankBranch = $res_FTACCheque["D_BankBranch"];
					$D_DateEntBank = $res_FTACCheque["D_DateEntBank"];
					$fullname = $res_FTACCheque["fullname"];
					$carregis = $res_FTACCheque["carregis"];
				}
				
				$result=pg_query("insert into \"FTACCheque\" (\"PostID\",\"COID\",\"TypePay\",\"AmtPay\",\"refreceipt\",\"D_ChequeNo\",\"D_BankName\",
				\"D_BankBranch\",\"D_DateEntBank\",\"fullname\",\"carregis\") 
				values ('$FTACCheque_PostID','$FTACCheque_IDNO','200','$money','$res_k_no','$D_ChequeNo','$D_BankName','$D_BankBranch','$D_DateEntBank',
				'$fullname','$carregis')");
				if(!$result){
					$status++;
				}
			}
		}  
	}

	if($bank == "TT" AND $paytype == "TTR"){
		if($statusapp==1){
			$result=pg_query("UPDATE \"FTACTran\" SET \"cancel\"='TRUE' WHERE \"refreceipt\"='$rid';");
			if(!$result){
				$status++;
			}
			
			if(empty($return_to)){
				$qry_FTACTran=pg_query("select * from \"FTACTran\" WHERE \"refreceipt\" = '$rid';");
				if($res_FTACTran=pg_fetch_array($qry_FTACTran)){
					$FTACTran_PostID = $res_FTACTran["PostID"];
					$FTACTran_IDNO = $res_FTACTran["COID"];
					$fullname = $res_FTACTran["fullname"];
					$carregis = $res_FTACTran["carregis"];
					$D_BankName = $res_FTACTran["D_BankName"];
					$D_BankAccount = $res_FTACTran["D_BankAccount"];
					$D_DatetimeEnterBank = $res_FTACTran["D_DatetimeEnterBank"];
				}
				
				$result=pg_query("insert into \"FTACTran\" (\"PostID\",\"COID\",\"TypePay\",\"AmtPay\",\"refreceipt\",\"fullname\",\"carregis\",\"D_BankName\",\"D_BankAccount\",\"D_DatetimeEnterBank\") values ('$FTACTran_PostID','$FTACTran_IDNO','200','$money','$res_k_no','$fullname','$carregis','$D_BankName','$D_BankAccount','$D_DatetimeEnterBank')");
				if(!$result){
					$status++;
				}
			}
		}  
	}

	if($status == 0){
		pg_query("COMMIT");
		echo "บันทึกเรียบร้อยแล้ว";
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกได้";
	}
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