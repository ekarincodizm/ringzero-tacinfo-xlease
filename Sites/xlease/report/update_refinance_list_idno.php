<?php
include("../config/config.php");
$term = $_GET['term'];
$cmd = $_REQUEST['cmd'];

if($cmd == 1){
    $numrows = 0;
    $qry_name=pg_query("select * from \"UNContact\" WHERE \"IDNO\" LIKE '%$term%'");
    while($res_name=pg_fetch_array($qry_name)){
        $numrows++;
        $IDNO=trim($res_name["IDNO"]);
        $full_name=trim($res_name["full_name"]);
        
        $dt['value'] = $IDNO."#".$full_name;
        $dt['label'] = "{$IDNO}, {$full_name}";
        $matches[] = $dt;
    }
}elseif($cmd == 2){
    
    $numrows = 0;
    
    $qry_name1=pg_query("select \"C_CARNUM\" from \"UNContact\" WHERE \"IDNO\"='$_SESSION[session_xxx2_search]' ");
    if($res_name1=pg_fetch_array($qry_name1)){
        $C_CARNUM = $res_name1['C_CARNUM'];
        
        $qry_name=pg_query("select * from \"UNContact\" WHERE \"C_CARNUM\"='$C_CARNUM' AND \"IDNO\" LIKE '%$term%'");
        while($res_name=pg_fetch_array($qry_name)){
            $IDNO=trim($res_name["IDNO"]);
            $full_name=trim($res_name["full_name"]);
            
            if($IDNO == $_SESSION['session_xxx2_search']){ continue; } //ถ้าเป็น IDNO เดียวกับตัวหลัก ไม่ต้องแสดงในลิสต์
            
            $dt['value'] = $IDNO."#".$full_name;
            $dt['label'] = "{$IDNO}, {$full_name}";
            $matches[] = $dt;
            $numrows++;
        }
    }
}elseif($cmd == 3){
    $idno = $_POST['idno'];
    $_SESSION['session_xxx2_search'] = "$idno";
    exit;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
