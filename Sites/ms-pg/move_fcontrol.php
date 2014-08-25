<?php
set_time_limit(0);
ini_set("memory_limit","984M"); 
include("config/config.php");

$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0];

echo "<a href=\"error_fcontrol.txt\" target=\"_blank\">List Error</a><hr>";

$fp=fopen("error_fcontrol.txt", "w");
fputs($fp, "CDATE | IDNO | CusID | Description\n");

$objQuery = mssql_query("SELECT *, 
CONVERT(varchar, ISNULL(CDATE,' '), 111) AS CDATE 
FROM [FControl] WHERE IDNO LIKE '11_-%' OR IDNO LIKE '51_-%' ORDER BY IDNO ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $IDNO = $objSelect['IDNO'];
    $CDATE = $objSelect['CDATE'];
    $Description = $objSelect['Description']; $Description = iconv('windows-874','UTF-8',$Description);
    $Description = str_replace("'","",$Description);
    //$Description = trim($Description);
    $i+=1;
    $CIDNO+=1;
    
    $result=pg_query("SELECT \"CusID\" FROM \"Fp\" WHERE \"IDNO\"='$IDNO'");
    if($arr = pg_fetch_array($result)){
        $CusID = $arr['CusID'];
    }
    
    if(empty($CusID)){
        $sql="INSERT INTO \"FollowUpCus\" (\"FollowDate\",\"GroupID\",\"userid\",\"IDNO\",\"CusID\",\"FollowDetail\") VALUES ('$CDATE','FLC','001','$IDNO',DEFAULT,'$Description')";
    }else{
        $sql="INSERT INTO \"FollowUpCus\" (\"FollowDate\",\"GroupID\",\"userid\",\"IDNO\",\"CusID\",\"FollowDetail\") VALUES ('$CDATE','FLC','001','$IDNO','$CusID','$Description')";
    }
    
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        if(empty($CDATE)) $CDATE = "Invalid_CDATE";
        if(empty($IDNO)) $IDNO = "Invalid_IDNO";
        if(empty($CusID)) $CusID = "Invalid_CusID";
        if(empty($Description)) $Description = "Invalid_Description";
        
        fputs($fp, "$CDATE | $IDNO | $CusID | $Description\n");
        echo "ERROR | $i | $CDATE | FLC | 001 | $IDNO | $CusID | $Description<br>";
        $status_false += 1;
    }
}

fclose($fp);

$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>
