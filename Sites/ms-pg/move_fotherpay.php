<?php
set_time_limit(0);
ini_set("memory_limit","984M"); 
include("config/config.php");

$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0];

echo "<a href=\"error_fotherpay.txt\" target=\"_blank\">List Error</a><hr>";

$fp=fopen("error_fotherpay.txt", "w");
fputs($fp, "IDNO | O_DATE | O_RECEIPT | O_MONEY | O_STATE | O_BANK | O_PRNDATE | PayType | O_Description\n");

$objQuery = mssql_query("SELECT *, 
CONVERT(varchar, ISNULL(O_DATE,' '), 111) AS O_DATE, 
CONVERT(varchar, ISNULL(O_PRNDATE,' '), 111) AS O_PRNDATE 
FROM [vfotherpaynovat] WHERE IDNO LIKE '11_-%' OR IDNO LIKE '51_-%' AND (O_STATE='1' OR O_STATE='2' OR O_STATE='3' OR O_STATE='4' OR O_STATE='6' OR O_STATE='7' OR O_STATE='8') ORDER BY IDNO ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $IDNO = $objSelect['IDNO'];
    $O_DATE = $objSelect['O_DATE'];
    $O_RECEIPT = $objSelect['O_RECEIPT'];
    $O_MONEY = $objSelect['O_MONEY'];
    $O_STATE = $objSelect['O_STATE'];
    $O_Description = $objSelect['O_Description']; $O_Description = iconv('windows-874','UTF-8',$O_Description);
    $O_BANK = $objSelect['O_BANK'];
    $O_PRNDATE = $objSelect['O_PRNDATE'];
    $PayType = $objSelect['PayType'];
    
    if($O_STATE == 1){
        $O_STATE = "100";
    }elseif($O_STATE == 2){
        $O_STATE = "101";
    }elseif($O_STATE == 3){
        $O_STATE = "136";
    }elseif($O_STATE == 4){
        $O_STATE = "115";
    }elseif($O_STATE == 6){
        $O_STATE = "138";
    }elseif($O_STATE == 7){
        $O_STATE = "139";
    }elseif($O_STATE == 8){
        $O_STATE = "102";
    }else{
        continue;
    }
    
    $i+=1;
    $CIDNO+=1;
    
    if($O_DATE!="1900/01/01" && $O_PRNDATE=="1900/01/01"){
        $sql="INSERT INTO \"FOtherpay\" (\"IDNO\",\"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"O_BANK\",\"O_PRNDATE\",\"PayType\",\"O_memo\") VALUES ('$IDNO','$O_DATE','$O_RECEIPT','$O_MONEY','$O_STATE','$O_BANK',DEFAULT,'$PayType','$O_Description')";
    }elseif($O_DATE=="1900/01/01" && $O_PRNDATE!="1900/01/01"){
        $sql="INSERT INTO \"FOtherpay\" (\"IDNO\",\"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"O_BANK\",\"O_PRNDATE\",\"PayType\",\"O_memo\") VALUES ('$IDNO',DEFAULT,'$O_RECEIPT','$O_MONEY','$O_STATE','$O_BANK','$O_PRNDATE','$PayType','$O_Description')";
    }elseif($O_DATE=="1900/01/01" && $O_PRNDATE=="1900/01/01"){
        $sql="INSERT INTO \"FOtherpay\" (\"IDNO\",\"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"O_BANK\",\"O_PRNDATE\",\"PayType\",\"O_memo\") VALUES ('$IDNO',DEFAULT,'$O_RECEIPT','$O_MONEY','$O_STATE','$O_BANK',DEFAULT,'$PayType','$O_Description')";
    }else{
        $sql="INSERT INTO \"FOtherpay\" (\"IDNO\",\"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"O_Type\",\"O_BANK\",\"O_PRNDATE\",\"PayType\",\"O_memo\") VALUES ('$IDNO','$O_DATE','$O_RECEIPT','$O_MONEY','$O_STATE','$O_BANK','$O_PRNDATE','$PayType','$O_Description')";
    }
    
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        if(empty($IDNO)) $IDNO = "Invalid_IDNO";
        if(empty($O_DATE)) $O_DATE = "Invalid_O_DATE";
        if(empty($O_RECEIPT)) $O_RECEIPT = "Invalid_O_RECEIPT";
        if(empty($O_MONEY)) $O_MONEY = "Invalid_O_MONEY";
        if(empty($O_STATE)) $O_STATE = "Invalid_O_STATE";
        if(empty($O_BANK)) $O_BANK = "Invalid_O_BANK";
        if(empty($O_PRNDATE)) $O_PRNDATE = "Invalid_O_PRNDATE";
        if(empty($PayType)) $PayType = "Invalid_PayType";
        
        fputs($fp, "$IDNO | $O_DATE | $O_RECEIPT | $O_MONEY | $O_STATE | $O_BANK | $O_PRNDATE | $PayType | $O_Description\n");
        echo "ERROR | $i | $IDNO | $O_DATE | $O_RECEIPT | $O_MONEY | $O_STATE | $O_BANK | $O_PRNDATE | $PayType | $O_Description<br>";
        $status_false += 1;
    }

}

fclose($fp);

$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>
