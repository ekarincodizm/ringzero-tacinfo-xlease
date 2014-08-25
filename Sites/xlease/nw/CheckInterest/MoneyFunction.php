<?php
//include("../../config/config.php");

function SearchMoney($test)
{
$idno = $test;

if(empty($_POST["signDate"])){
    $ssdate = nowDate();
}else{
    $ssdate=$_POST["signDate"];
}

$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];

/*
$qry_VCusPayment_last=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$idno') order by \"DueDate\" desc LIMIT(1)");
$res_VCusPayment_last=pg_fetch_array($qry_VCusPayment_last);
$ldate=$res_VCusPayment_last["DueDate"];
*/

/*
$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
$res_FpFa1=pg_fetch_array($qry_FpFa1);
    $s_payment_nonvat = $res_FpFa1["P_MONTH"];
    $s_payment_vat = $res_FpFa1["P_VAT"];
    $s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
    $f_date = $res_FpFa1["P_STDATE"];
    $fullname = trim($res_FpFa1["A_FIRNAME"])." ".trim($res_FpFa1["A_NAME"])." ".trim($res_FpFa1["A_SIRNAME"]);
    $s_fp_ptotal = $res_FpFa1["P_TOTAL"];
    $s_LAWERFEE = $res_FpFa1["P_LAWERFEE"];
    $s_ACCLOSE = $res_FpFa1["P_ACCLOSE"];
    $s_StopVat = $res_FpFa1["P_StopVat"];
	$repo = $res_FpFa1["repo"];
	$stopVatDate = $res_FpFa1['P_StopVatDate']; if($stopVatDate=="") $stopVatDate="ไม่ได้ระบุ";
	
    $_SESSION["ses_scusid"] = trim($res_FpFa1["CusID"]);


    $qry_thaidate=pg_query("select conversiondatetothaitext('$f_date')");
    $f_dateth=pg_fetch_result($qry_thaidate,0);
*/

$qry_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$idno'");
$res_VContact=pg_fetch_array($qry_VContact);
    $s_year=$res_VContact["C_YEAR"];
    $s_expdate = $res_VContact["C_TAX_ExpDate"]; 
    $s_ccolor = $res_VContact["C_COLOR"];
    $s_ccarname = $res_VContact["C_CARNAME"];
    $s_dp_balance = $res_VContact["dp_balance"];
    $s_radioid = $res_VContact["RadioID"];

    if($res_VContact["C_REGIS"]==""){
        $regis=$res_VContact["car_regis"];
    }else{
        $regis=$res_VContact["C_REGIS"];
        $r_number=$res_VContact["C_CARNUM"];
    }

/*    
$_SESSION["ses_h_start"]=$stdate;
$_SESSION["ses_payment_nonvat"]=$s_payment_nonvat;
$_SESSION["ses_payment_vat"]=$s_payment_vat;
$_SESSION["ses_payment_all"]=$s_payment_all;
$_SESSION["ses_start_date"]=$f_date;
$_SESSION["ses_start_dateth"]=$f_dateth;
$_SESSION["ses_last_date"]=$ldate;
$_SESSION["ses_a_fullname"]=$fullname;
$_SESSION["ses_regis"]=$regis;
$_SESSION["ses_r_number"]=$r_number;
$_SESSION["ses_date"]=$ssdate;
$_SESSION["ses_year"]=$s_year;
$_SESSION["ses_expdate"]=$s_expdate;
$_SESSION["ses_ccolor"]=$s_ccolor;
$_SESSION["ses_ccarname"]=$s_ccarname;
$_SESSION["ses_radioid"]=$s_radioid;
$_SESSION["ses_fp_ptotal"]=$s_fp_ptotal;
$_SESSION["ses_LAWERFEE"]=$s_LAWERFEE;
$_SESSION["ses_ACCLOSE"]=$s_ACCLOSE;
$_SESSION["ses_StopVat"]=$s_StopVat;
$_SESSION["ses_dp_balance"]=$s_dp_balance;
*/

//===================== หาการโอนสิทธิ์ =====================//
$search_under_idno = $idno; //ค้นหาว่า โอนต่อให้ IDNO อื่นๆ หรือไม่
do{
    $qry_underlv=pg_query("select \"P_TransferIDNO\",\"asset_id\",\"asset_type\" from \"Fp\" WHERE \"IDNO\"='$search_under_idno'");
    if($res_underlv=pg_fetch_array($qry_underlv)){
        $P_TransferIDNO=$res_underlv["P_TransferIDNO"];
		$asset_id = $res_underlv['asset_id'];
		$asset_type = $res_underlv['asset_type'];
        if(!empty($P_TransferIDNO)){
            $list_idno[]=$P_TransferIDNO;
            $search_under_idno = $P_TransferIDNO;
        }else{
            $search_under_idno = "";
        }
    }
}while(!empty($search_under_idno)); //จบ ค้นหาว่า โอนต่อให้ IDNO อื่นๆ หรือไม่

$list_idno = @array_reverse($list_idno);// สลับค่า array หน้าไปหลัง / หลังไปหน้า

$list_idno[] = $idno;//ใส่ IDNO หลักที่ค้นหาลงไป (IDNO กลาง)

$search_top_idno = $idno; //ค้นหาว่า ได้โอนมาจาก IDNO อื่นๆ หรือไม่
do{
    $qry_toplv=pg_query("select \"IDNO\",\"asset_id\",\"asset_type\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top_idno'");
    if($res_toplv=pg_fetch_array($qry_toplv)){
        $list_idno[]=$res_toplv["IDNO"];
        $search_top_idno=$res_toplv["IDNO"];
		$asset_id = $res_toplv['asset_id'];
		$asset_type = $res_toplv['asset_type'];
    }else{
        $search_top_idno = "";
    }
}while(!empty($search_top_idno)); //จบ ค้นหาว่า ได้โอนมาจาก IDNO อื่นๆ หรือไม่

$list_idno = @array_reverse($list_idno);// สลับค่า array หน้าไปหลัง / หลังไปหน้า
$_SESSION["ses_list_idno"]=$list_idno;
//===================== จบ หาการโอนสิทธิ์ =====================//

//หาเลขเครื่อง
/*
if($asset_type == 1){
    $qry_2=pg_query("select * from \"Fc\" WHERE (\"CarID\"='$asset_id');");
    if($res_2=pg_fetch_array($qry_2)){    
        $C_MARNUM = $res_2['C_MARNUM'];
    }
}else{
    $qry_2=pg_query("select * from \"FGas\" WHERE (\"GasID\"='$asset_id');");
    if($res_2=pg_fetch_array($qry_2)){  
        $C_MARNUM = $res_2['marnum'];
    }
}
*/

		//หาค่าภาษี
		$query_tax=pg_query("select b.\"C_TAX_MON\",\"P_BEGIN\" from \"Fp\" a
		left join \"VCarregistemp\" b on a.\"IDNO\" = b.\"IDNO\"
		where a.\"IDNO\" = '$idno'");
		if($res_tax=pg_fetch_array($query_tax)){
			$C_TAX_MON = $res_tax["C_TAX_MON"];
			$P_BEGIN = $res_tax['P_BEGIN'];
			
		}
		
		

$sum_outstanding1 = 0;
$qry_inf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $sum_outstanding1 = $res_inf["sum_outstanding"];
}

$sum_outstanding2 = 0;
$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' AND \"IDNO\"='$idno' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding2 = $res_inuf["sum_outstanding"];
}


$qry_amt=pg_query("select \"CusAmt\",\"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDNO\"='$idno' ");
$nub_amt = pg_num_rows($qry_amt);


 //ยอดค้าง (แถบสีชมพู)
while($res_amt=pg_fetch_array($qry_amt)){
    $CusAmt = $res_amt["CusAmt"];
    $TypeDep = $res_amt["TypeDep"];
    
    $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
    if($res_nn=pg_fetch_array($qry_nn)){
        $TName = $res_nn["TName"];
    }
    
    if($CusAmt != 0){

    }
}

$qry_fr=pg_query("select * from \"nw_seize_car\" a 
left join \"NTHead\" b on a.\"IDNO\" = b.\"IDNO\" and a.\"NTID\" = b.\"NTID\"
where b.\"cancel\" = 'FALSE' and b.\"CusState\" = '0' and a.\"status_approve\" = '3' and b.\"IDNO\" ='$idno'"); 
$num_fr=pg_num_rows($qry_fr);

/*
if($s_LAWERFEE == 't' || $s_ACCLOSE == 't' || $s_StopVat == 't' || $repo == 't' || $num_fr > 0){

?>
    
<?php 
if($s_LAWERFEE == 't'){
	//ตรวจสอบว่ามีการออก NT หรือยังจากตาราง nw_statusNT
	$query_notice=pg_query("select * from \"nw_statusNT\" a
			left join \"NTHead\" b on a.\"NTID\" = b.\"NTID\" 
			where a.\"IDNO\"='$idno' and b.\"CusState\"='0' and b.cancel='FALSE'");
	$res_notice=pg_fetch_array($query_notice);
	$statusNT=$res_notice["statusNT"];
}

?>
    
<?php
}
*/

$count_idno = count($list_idno);
for($b=0; $b<$count_idno; $b++){ // วนลูป IDNO ทั้งหมด
    $b_plus=$b+1;
    $qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
    $res_VCusPayment=pg_fetch_array($qry_VCusPayment);
    $stdate=$res_VCusPayment["DueDate"];

    $qry_VCusPayment_last=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$list_idno[$b]') order by \"DueDate\" desc LIMIT(1)");
    $res_VCusPayment_last=pg_fetch_array($qry_VCusPayment_last);
    $ldate=$res_VCusPayment_last["DueDate"];

    $qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$list_idno[$b]'");
    $res_FpFa1=pg_fetch_array($qry_FpFa1);
        $s_payment_nonvat = $res_FpFa1["P_MONTH"];
        $s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
        $s_fp_ptotal = $res_FpFa1["P_TOTAL"];

    $money_all_in_vat = $s_payment_all*$s_fp_ptotal;
    $money_all_no_vat = $s_payment_nonvat*$s_fp_ptotal;


    $qry_fullname=pg_query("select \"full_name\" from \"VContact\" WHERE \"IDNO\"='$list_idno[$b]'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $full_name=$res_fullname["full_name"];
    }
    
    

    
    if(($b_plus) != $count_idno){

    $qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
    while($resbf=pg_fetch_array($qry_before)){

    $tmp_1 = $money_all_in_vat-($resbf["DueNo"]*$s_payment_all);
    $tmp_2 = $money_all_no_vat-($resbf["DueNo"]*$s_payment_nonvat);
}//จบ หารายการที่ชำระแล้ว

    }else{//else แบ่งรายปัจจุบัน

$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
while($resbf=pg_fetch_array($qry_before)){

    $sumamt+=$resbf["CalAmtDelay"];
    $last_DueDate = $resbf["DueDate"];
    $sumamt2+=$resbf["CalAmtDelay"];
}//จบ หารายการที่ชำระแล้ว
    
$qry_amt=@pg_query("select * ,'$ssdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]')  AND (\"DueDate\" BETWEEN '$stdate' AND '$ssdate') "); //รายการที่คำนวณ
while($res_amt=@pg_fetch_array($qry_amt)){
    $s_amt=pg_query("select \"CalAmtDelay\"('$ssdate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);

    $sumamt2+=$res_s;
    $sum=$s_payment_all+$res_s;
    $x_sum=$x_sum+$sum;
    $last_DueDate = $res_amt["DueDate"];
} //จบ รายการที่คำนวณ

//แสดงรายการทั้งหมด ถัดจากวัน DueDate ล่าสุด ที่จ่ายแล้ว หรือ วันถัดจากวัน DueDate ที่คำนวณ
$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($last_DueDate)));// วันถัดจาก Due ล่าสุด

$qry_l=@pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"DueDate\" BETWEEN '$DateUpdate' AND '$ldate') ");
while($resl=@pg_fetch_array($qry_l)){
    $inum+=1;

}

    }//จบ แบ่งรายปัจจุบัน

}//จบ วนลูป IDNO ทั้งหมด

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}

$remoney = $sumamt2-$otherpay_amt;

return $remoney;
}
?>