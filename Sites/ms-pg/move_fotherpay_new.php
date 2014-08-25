<?php
set_time_limit(0);
ini_set("memory_limit","984M"); 
include("config/config.php");

$objQuery = mssql_query("SELECT *, 
CONVERT(varchar, ISNULL(O_DATE,' '), 111) AS O_DATE, 
CONVERT(varchar, ISNULL(O_PRNDATE,' '), 111) AS O_PRNDATE 
FROM [FOtherPay] ORDER BY IDNO ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $IDNO = $objSelect['IDNO'];
    $O_DATE = $objSelect['O_DATE']; list($n_year,$n_month,$n_day) = split('/',$O_DATE);
    $O_RECEIPT = $objSelect['O_RECEIPT'];   $O_RECEIPT = iconv('windows-874','UTF-8',$O_RECEIPT);
    $O_MONEY = $objSelect['O_MONEY'];
    $O_STATE = $objSelect['O_STATE'];
    $O_Description = $objSelect['O_Description']; $O_Description = iconv('windows-874','UTF-8',$O_Description);
    $O_BANK = $objSelect['O_BANK'];
    $O_PRNDATE = $objSelect['O_PRNDATE'];
    $PayType = $objSelect['PayType'];

    //เช็คปี 2002 ขึ้นไป
    if($n_year < 2002){
        continue;
    }
    
    if($O_RECEIPT[2] != "R" && $O_RECEIPT[2] != "N" && $O_RECEIPT[2] != "K"){
        continue;
    }elseif($O_RECEIPT[2] == "R"){ // เข้า Fr
    
    //เช็คซ้ำกับในตารางหรือไม่
    $chk_nub_fr = 0;
    $ch2=pg_query("SELECT COUNT(\"R_Receipt\") AS \"corept\" FROM \"Fr\" WHERE (\"R_Receipt\"='$O_RECEIPT')");
    if($arr2 = pg_fetch_array($ch2)){
        $chk_nub_fr = $arr2["corept"];   
    }
    if($chk_nub_fr != 0){
        continue;
    }
    
        if($O_DATE!="1900/01/01" && $O_PRNDATE=="1900/01/01"){
            $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','900','$O_RECEIPT','$O_DATE','$O_MONEY','$O_BANK',DEFAULT,'$PayType')";
        }elseif($O_DATE=="1900/01/01" && $O_PRNDATE!="1900/01/01"){
            $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','900','$O_RECEIPT',DEFAULT,'$O_MONEY','$O_BANK','$O_PRNDATE','$PayType')";
        }elseif($O_DATE=="1900/01/01" && $O_PRNDATE=="1900/01/01"){
            $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','900','$O_RECEIPT',DEFAULT,'$O_MONEY','$O_BANK',DEFAULT,'$PayType')";
        }else{
            $sql="INSERT INTO \"Fr\" (\"IDNO\",\"R_DueNo\",\"R_Receipt\",\"R_Date\",\"R_Money\",\"R_Bank\",\"R_Prndate\",\"PayType\") VALUES ('$IDNO','900','$O_RECEIPT','$O_DATE','$O_MONEY','$O_BANK','$O_PRNDATE','$PayType')";
        }
        
        if(pg_query($sql)){
            $status_true_fr += 1;
        }else{
            $status_false_fr += 1;
        }
        
        $nub_all+=1;

    }elseif($O_RECEIPT[2] == "N" || $O_RECEIPT[2] == "K"){ //เข้า FOtherpay
    
    //เช็คซ้ำกับในตารางหรือไม่
    $chk_nub_fo = 0;
    $ch1=pg_query("SELECT COUNT(\"O_RECEIPT\") AS \"corept\" FROM \"FOtherpay\" WHERE (\"O_RECEIPT\"='$O_RECEIPT')");
    if($arr1 = pg_fetch_array($ch1)){
        $chk_nub_fo = $arr1["corept"];   
    }
    if($chk_nub_fo != 0){
        continue;
    }
    
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
        }elseif($O_STATE == 9){
            if(eregi("ประกัน", $O_Description)){
                $O_STATE = "102";
            }elseif(eregi("พรบ", $O_Description)){
                $O_STATE = "103";
            }elseif(eregi("มิเตอร์", $O_Description)){
                $O_STATE = "105";
            }elseif(eregi("ภาษี", $O_Description)){
                $O_STATE = "101";
            }else{
                $O_STATE = "900";
            }
        }elseif($O_STATE == 10){
            if($O_MONEY >= 0){
                $O_STATE = "200";
            }else{
                $O_STATE = "299";
            }
        }
        
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
            $status_true_fo += 1;
        }else{
            $status_false_fo += 1;
        }
        
        $nub_all+=1;

    }

    //echo "$i | $IDNO | $O_DATE | $O_RECEIPT | $O_MONEY | $O_STATE | $O_BANK | $O_PRNDATE | $PayType | $O_Description<br>";
}

echo "<hr>Total : $nub_all<br />
FOtherpay Insert : $status_true_fo // FOtherpay Insert False : $status_false_fo<br />
Fr Insert : $status_true_fr // Fr Insert False : $status_false_fr";
?>
