<?php
session_start();
include("../config/config.php");

$ssdate = $_REQUEST["signDate"]; //คิดถึงวันที่
$idno = $_REQUEST["idno"];
$nowdate = Date('Y-m-d'); //วันที่ปัจจุบัน
$tmoney = $_REQUEST["tmoney"]; //ค่าทนาย


//อันเก่า
/*$qry_fp=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno';");
if($res_fp=pg_fetch_array($qry_fp)){
	$P_MONTH = $res_fp["P_MONTH"];
	$P_VAT = $res_fp["P_VAT"];
    $sum_mv = $P_MONTH+$P_VAT;
}

$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"R_Receipt\" is null and \"IDNO\"='$idno';");
while($resvc=pg_fetch_array($qry_vcus)) {
    $p += 1;
    $DueDate = $resvc["DueDate"];
    $DueNo = $resvc["DueNo"];

    $s_amt=pg_query("select \"CalAmtDelay\"('$signDate','$DueDate',$sum_mv)");
    $res_amt=pg_fetch_result($s_amt,0);
    
    if($p == 1){
        $start_due = $DueDate;
        $st_dueno = $DueNo;
    }
    
    list($n_year,$n_month,$n_day) = split('-',$DueDate);
    list($b_year,$b_month,$b_day) = split('-',$signDate);
    list($a_year,$a_month,$a_day) = split('-',$nowdate);

    $date_1 = mktime(0, 0, 0, $n_month, $n_day, $n_year);
    $date_2 = mktime(0, 0, 0, $b_month, $b_day, $b_year);
    $date_3 = mktime(0, 0, 0, $a_month, $a_day, $a_year);
    
    if($date_1 < $date_2){
            $nub += 1;
            $st_dueno_last = $DueNo;
            $sum_amt += $res_amt;
    }
    
    if($date_1 < $date_3){
            $nubs += 1;
            $st_dueno_lasts = $DueNo;
            $sum_amts += $res_amt;
    }
    
}
$sum_amt = round($tmoney+$sum_amt,2);
*/
$qry_FpFa1=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
$res_FpFa1=pg_fetch_array($qry_FpFa1);
$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];

$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];

$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
while($resbf=pg_fetch_array($qry_before)){
    $sumamt2+=$resbf["CalAmtDelay"];
}//จบ หารายการที่ชำระแล้ว

$qry_amt=@pg_query("select * ,'$ssdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$ssdate') "); //รายการที่คำนวณ
while($res_amt=@pg_fetch_array($qry_amt)){
    $s_amt=pg_query("select \"CalAmtDelay\"('$ssdate','$res_amt[DueDate]',$s_payment_all)"); 
    $res_s=pg_fetch_result($s_amt,0);
	$sumamt2+=$res_s;
}

$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' AND \"Cancel\"='FALSE' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $otherpay_amt = $re_mny["sum_money_otherpay"];
}
$sum_amt=round(($sumamt2-$otherpay_amt) + $tmoney) ;
echo $sum_amt;
?>