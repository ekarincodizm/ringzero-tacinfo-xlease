<?php
set_time_limit(0);
ini_set("memory_limit","984M");
include("config/config.php");

$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0]; 

//Connect MSSQL
//$objConnect = mssql_connect("essoft01","sa","") or die("Error Connect to Database");
//$objDB = mssql_select_db("ThaiAce");

//SELECT
/*
$objQuery = mssql_query("SELECT COUNT(IDNO) AS CIDNO FROM [vfvat-fr] WHERE IDNO LIKE '22%' ");
if( $objSelect = mssql_fetch_array($objQuery) ){
    $CIDNO = $objSelect['CIDNO'];
}
*/

$objQuery = mssql_query("SELECT *,
CONVERT(varchar, ISNULL(V_DATE,' '), 111) AS V_DATE, 
CONVERT(varchar, ISNULL(V_PrnDate,' '), 111) AS V_PrnDate 
FROM [vfvat-fr] WHERE IDNO LIKE '11_-%' OR IDNO LIKE '51_-%' ORDER BY IDNO,PAYMENT_NO ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $IDNO = $objSelect['IDNO'];
    $PAYMENT_NO = $objSelect['PAYMENT_NO'];
    $V_VAT_REC_NO = $objSelect['V_VAT_REC_NO'];
    $V_DATE = $objSelect['V_DATE'];
    $VatValue = $objSelect['VatValue'];
    $V_PrnDate = $objSelect['V_PrnDate'];
    $Paid_Status = $objSelect['Paid_Status'];
    $i+=1;
    
    //if(!ereg("[+]",$IDNO)){
    $CIDNO+=1;
    
    if($V_DATE!="1900/01/01" && $V_PrnDate=="1900/01/01"){
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\") VALUES ('$IDNO','$PAYMENT_NO','$V_VAT_REC_NO','$V_DATE','$VatValue',DEFAULT,'$Paid_Status')";
    }elseif($V_DATE=="1900/01/01" && $V_PrnDate!="1900/01/01"){
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\") VALUES ('$IDNO','$PAYMENT_NO','$V_VAT_REC_NO',DEFAULT,'$VatValue','$V_PrnDate','$Paid_Status')";
    }elseif($V_DATE=="1900/01/01" && $V_PrnDate=="1900/01/01"){
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\") VALUES ('$IDNO','$PAYMENT_NO','$V_VAT_REC_NO',DEFAULT,'$VatValue',DEFAULT,'$Paid_Status')";
    }else{
        $sql="INSERT INTO \"FVat\" (\"IDNO\",\"V_DueNo\",\"V_Receipt\",\"V_Date\",\"VatValue\",\"V_PrnDate\",\"Paid_Status\") VALUES ('$IDNO','$PAYMENT_NO','$V_VAT_REC_NO','$V_DATE','$VatValue','$V_PrnDate','$Paid_Status')";
    }
    
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        echo "ERROR | $i | $IDNO | $PAYMENT_NO | $V_VAT_REC_NO | $V_DATE | $VatValue | $V_PrnDate | $Paid_Status<br>";
        $status_false += 1;
    }
    
    //}
}


$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>
