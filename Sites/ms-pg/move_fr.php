<?php
set_time_limit(0);
ini_set("memory_limit","984M"); 
include("config/config.php");

$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0];

echo "<a href=\"error_fr.txt\" target=\"_blank\">List Error</a><hr>";

$fp=fopen("error_fr.txt", "w");
fputs($fp, "IDNO | PAYMENT_NO | RefReceiptNO | R_DATE | R_MONEY | R_BANK | R_PRNDATE | PayType\n");

$objQuery = mssql_query("SELECT *, 
CONVERT(varchar, ISNULL(R_DATE,' '), 111) AS R_DATE, 
CONVERT(varchar, ISNULL(R_PRNDATE,' '), 111) AS R_PRNDATE 
FROM [vfvat-fr] WHERE IDNO LIKE '11_-%' OR IDNO LIKE '51_-%' ORDER BY IDNO,PAYMENT_NO ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $IDNO = $objSelect['IDNO'];
    $PAYMENT_NO = $objSelect['PAYMENT_NO'];
    $RefReceiptNO = $objSelect['RefReceiptNO'];
    $R_DATE = $objSelect['R_DATE'];
    $R_MONEY = $objSelect['R_MONEY'];
    $R_BANK = $objSelect['R_BANK'];
    $R_PRNDATE = $objSelect['R_PRNDATE'];
    $PayType = $objSelect['PayType'];
    $i+=1;
    
    $CIDNO+=1;
    
    if($R_DATE!="1900/01/01" && $R_PRNDATE=="1900/01/01"){
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','$PAYMENT_NO','$RefReceiptNO','$R_DATE','$R_MONEY','$R_BANK',DEFAULT,'$PayType')";
    }elseif($R_DATE=="1900/01/01" && $R_PRNDATE!="1900/01/01"){
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','$PAYMENT_NO','$RefReceiptNO',DEFAULT,'$R_MONEY','$R_BANK','$R_PRNDATE','$PayType')";
    }elseif($R_DATE=="1900/01/01" && $R_PRNDATE=="1900/01/01"){
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','$PAYMENT_NO','$RefReceiptNO',DEFAULT,'$R_MONEY','$R_BANK',DEFAULT,'$PayType')";
    }else{
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','$PAYMENT_NO','$RefReceiptNO','$R_DATE','$R_MONEY','$R_BANK','$R_PRNDATE','$PayType')";
    }
    
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        if(empty($IDNO)) $IDNO = "Invalid_IDNO";
        if(empty($PAYMENT_NO)) $PAYMENT_NO = "Invalid_PAYMENT_NO";
        if(empty($RefReceiptNO)) $RefReceiptNO = "Invalid_RefReceiptNO";
        if(empty($R_DATE)) $R_DATE = "Invalid_R_DATE";
        if(empty($R_MONEY)) $R_MONEY = "Invalid_R_MONEY";
        if(empty($R_BANK)) $R_BANK = "Invalid_R_BANK";
        if(empty($R_PRNDATE)) $R_PRNDATE = "Invalid_R_PRNDATE";
        if(empty($PayType)) $PayType = "Invalid_PayType";
        
        fputs($fp, "$IDNO | $PAYMENT_NO | $RefReceiptNO | $R_DATE | $R_MONEY | $R_BANK | $R_PRNDATE | $PayType\n");
        echo "ERROR | $i | $IDNO | $PAYMENT_NO | $RefReceiptNO | $R_DATE | $R_MONEY | $R_BANK | $R_PRNDATE | $PayType<br>";
        $status_false += 1;
    }

}

fclose($fp);

$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>
