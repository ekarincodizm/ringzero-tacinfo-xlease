<?php
set_time_limit(0);
ini_set("memory_limit","984M"); 
include("config/config.php");

$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0];

//echo "<a href=\"error_freceiptno.txt\" target=\"_blank\">List Error</a><hr>";

//$fp=fopen("error_freceiptno.txt", "w");
//fputs($fp, "RDate | R | N | V | H | K\n");

$objQuery = mssql_query("SELECT *, 
CONVERT(varchar, ISNULL(RDate,' '), 111) AS RDate 
FROM [FReceiptNO] ORDER BY RDate ASC",$conn);
while( $objSelect = mssql_fetch_array($objQuery) ){
    $RDate = $objSelect['RDate'];
    $R = $objSelect['R'];
    $N = $objSelect['N'];
    $V = $objSelect['V'];
    $H = $objSelect['H'];
    $K = $objSelect['K'];
    
    $i+=1;
    $CIDNO+=1;
    
    $sql="INSERT INTO \"FReceiptNO\" (\"Rec_date\",\"R\",\"N\",\"V\",\"K\",\"P\",\"C\") VALUES ('$RDate','$R','$N','$V','$K','0','0')";
    if(pg_query($sql)){
        $status_true += 1;
    }else{
        /*
        if(empty($RDate)) $RDate = "Invalid_RDate";
        
        fputs($fp, "$RDate | $R | $N | $V | $H | $K\n");
        */
        echo "ERROR | $i | $RDate | $R | $N | $V | $K<br>";
        $status_false += 1;
    }
    
}

//fclose($fp);

$end=microtime();
$end=explode(" ",$end);
$end=$end[1]+$end[0];

echo "<hr>Total : $CIDNO //True : $status_true // False : $status_false // Time : ".($end-$start)." sec";
?>
