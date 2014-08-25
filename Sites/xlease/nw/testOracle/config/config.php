<?php
session_start();
@ini_set('display_errors', '1');
/*

$conn = oci_connect('oae_farm', 'oae_farm', '172.16.2.241:1522/oae');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
*/


print(date("1 F d, Y"));
//$c=OCILogon("oae_farm", "oae_farm", "172.16.2.241/OAE");

//$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.2.241)(PORT = 1522)))(CONNECT_DATA=(SID=oae)))"; 
//$c1 = ocilogon("oae_farm","oae_farm",$db);

$objConnect = oci_connect('oae', 'oae', '172.16.2.241:1522/oae');

if($objConnect) {
echo "Oracle Server Connected";}

else {
echo "Can not connect to Oracle Server"; }
/*

if ($c=OCILogon("oae_farm", "oae_farm", "172.16.2.241:1522/oae")) {
  echo "Successfully connected to Oracle.\n";
  OCILogoff($c);
} else {
  $err = OCIError();
  echo "Oracle Connect Error " . $err[text];
}*/
?>
