<?php
set_time_limit(0);
require_once("config/config.php");

$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0]; 

//Connect MSSQL
//$objConnect = mssql_connect("essoft01","sa","") or die("Error Connect to Database");
//$objDB = mssql_select_db("ThaiAce");

//SELECT
$objQuery = mssql_query("SELECT *,
CONVERT(varchar, ISNULL(v_date,' '), 111) AS v_date, 
CONVERT(varchar, ISNULL(v_prndate,' '), 111) AS v_prndate 
FROM [vfvat-fotherpay] WHERE idno LIKE '11_-%' OR idno LIKE '51_-%' ORDER BY idno,v_date ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $IDNO = $objSelect['idno'];
    $PAYMENT_NO = $objSelect['payment_no'];
    $V_VAT_REC_NO = $objSelect['v_vat_rec_no'];
    $V_DATE = $objSelect['v_date'];
    $VatValue = $objSelect['vatvalue'];
    $V_PrnDate = $objSelect['v_prndate'];
    $Paid_Status = $objSelect['paid_status'];
    $O_STATE = $objSelect['o_state'];
    $O_Description = $objSelect['o_description']; $O_Description = iconv('windows-874','UTF-8',$O_Description);
        if($O_STATE == 5){
            $ADD_PAYMENT_NO = 0;
            $O_Description = "";
        }else{
            $ADD_PAYMENT_NO = 99;
        }
        
    $i+=1;
    $CIDNO+=1;
    
    if($V_DATE!="1900/01/01" && $V_PrnDate=="1900/01/01"){
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\",\"V_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$V_VAT_REC_NO','$V_DATE','$VatValue',DEFAULT,'$Paid_Status','$O_Description')";
    }elseif($V_DATE=="1900/01/01" && $V_PrnDate!="1900/01/01"){
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\",\"V_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$V_VAT_REC_NO',DEFAULT,'$VatValue','$V_PrnDate','$Paid_Status','$O_Description')";
    }elseif($V_DATE=="1900/01/01" && $V_PrnDate=="1900/01/01"){
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\",\"V_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$V_VAT_REC_NO',DEFAULT,'$VatValue',DEFAULT,'$Paid_Status','$O_Description')";
    }else{
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\",\"V_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$V_VAT_REC_NO','$V_DATE','$VatValue','$V_PrnDate','$Paid_Status','$O_Description')";
    }
    
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        echo "ERROR | $i | $IDNO | $ADD_PAYMENT_NO | $V_VAT_REC_NO | $V_DATE | $VatValue | $V_PrnDate | $Paid_Status | $O_Description<br>";
        $status_false += 1;
    }
    
}


$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>