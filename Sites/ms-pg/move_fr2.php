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
CONVERT(varchar, ISNULL(o_date,' '), 111) AS o_date, 
CONVERT(varchar, ISNULL(o_prndate,' '), 111) AS o_prndate 
FROM [vfvat-fotherpay] WHERE idno LIKE '11_-%' OR idno LIKE '51_-%' ORDER BY idno,o_date ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    
    $ADD_PAYMENT_NO = "";
    $IDNO = $objSelect['idno'];
    $PAYMENT_NO = $objSelect['payment_no'];
    $RefReceiptNO = $objSelect['refreceiptno'];
    $O_DATE = $objSelect['o_date'];
    $O_MONEY = $objSelect['o_money'];
    $O_BANK = $objSelect['o_bank'];
    $O_PRNDATE = $objSelect['o_prndate'];
    $PayType = $objSelect['paytype'];
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
    
    if($O_DATE!="1900/01/01" && $O_PRNDATE=="1900/01/01"){
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\",\"R_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$RefReceiptNO','$O_DATE','$O_MONEY','$O_BANK',DEFAULT,'$PayType','$O_Description')";
    }elseif($O_DATE=="1900/01/01" && $O_PRNDATE!="1900/01/01"){
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\",\"R_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$RefReceiptNO',DEFAULT,'$O_MONEY','$O_BANK','$O_PRNDATE','$PayType','$O_Description')";
    }elseif($O_DATE=="1900/01/01" && $O_PRNDATE=="1900/01/01"){
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\",\"R_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$RefReceiptNO',DEFAULT,'$O_MONEY','$O_BANK',DEFAULT,'$PayType','$O_Description')";
    }else{
        $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\",\"R_memo\") VALUES ('$IDNO','$ADD_PAYMENT_NO','$RefReceiptNO','$O_DATE','$O_MONEY','$O_BANK','$O_PRNDATE','$PayType','$O_Description')";
    }
    
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        echo "ERROR | $i | $IDNO | $ADD_PAYMENT_NO | $RefReceiptNO | $O_DATE | $O_MONEY | $O_BANK | $O_PRNDATE | $PayType | $O_Description<br>";
        $status_false += 1;
    }
    
}


$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>