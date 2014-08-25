<?php
include("../../config/config.php"); 
?>
<?php
$idno= pg_escape_string($_GET["idno"]);
$address ="";
$qry_address=pg_query("select \"addEach\" from \"Fp_Fa1\" where \"IDNO\"='$idno' and \"CusState\"='0' order by \"edittime\" DESC limit(1)");
list($res_assetsaddress)=pg_fetch_array($qry_address);
$address = $res_assetsaddress;
echo $address;
?>

