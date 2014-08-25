<?php
include("../config/config.php");
$cmd = pg_escape_string($_REQUEST['cmd']);

if($cmd == "load134"){
    $id = pg_escape_string($_GET['id']);
    echo '&nbsp;<b>จำนวนงวด</b>&nbsp;<input type="text" name="txtkr'.$id.'" id="txtkr'.$id.'" size="5" value="1" onkeyup="javascript:check100('.$id.')">';
}elseif($cmd == "load134amt"){
    $idno = pg_escape_string($_GET['idno']);
    $qry_vcus=pg_query("select amt from corporate.\"VCorpContact\" WHERE \"IDNO\" = '$idno' ");
    if($res_vcus=pg_fetch_array($qry_vcus)){
        echo $amt = $res_vcus["amt"];
    }else{
        echo 0;
    }
}
?>