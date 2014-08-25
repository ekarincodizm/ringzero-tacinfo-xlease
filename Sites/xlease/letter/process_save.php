<?php
session_start();
include("../config/config.php");
$resid=$_SESSION["av_iduser"];
$fname=pg_escape_string($_POST["f_name"]);
$idno=pg_escape_string($_POST["f_idno"]);

$datenow=date("Y-m-d");

/*if($_POST["type_add"]==1)
{
 $dtl_add=pg_escape_string($_POST["f_fn_add"]);
}
else
{
  $dtl_add=pg_escape_string($_POST["f_ads"]);

}
*/

$dtl_add=pg_escape_string($_POST["f_ads"]);
// $dtl_add;
//echo pg_escape_string($_POST["f_types"]);

$gen_ltr=pg_query("select letter.gen_cusletid('$idno')"); //gen letter
$res_genltr=pg_fetch_result($gen_ltr,0);

//echo "<br>"."gen id=".$res_genltr;

$ins_send_ads="insert into letter.send_address 	
               (\"CusLetID\",\"IDNO\",record_date,\"name\",active,userid,dtl_ads)
               values
			   ('$res_genltr','$idno','$datenow','$fname',TRUE,'$resid','$dtl_add')";
 
 if($result=pg_query($ins_send_ads))
 {
  $status ="OK".$in_sql;
 }
 else
 {
  $status ="error insert Re".$in_sql;
 }
 //echo $status;

 echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_add_let.php?IDNO=$idno&CID=$res_genltr\">"."<br>";   

?>