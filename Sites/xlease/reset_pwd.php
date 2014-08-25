<?php
session_start();
include("../config/config.php");
$i_id=pg_escape_string($_POST["h_id"]);
$seed = $_SESSION["session_company_seed"];
$i_pwd = md5(md5($_POST['update_pwd']).$seed);

$str_update="UPDATE  fuser SET password='$i_pwd'  WHERE id_user='$i_id' ";
 if($result=pg_query($str_update))
 {
  $status ="Update password ข้อมูลแล้ว";
 }
 else
 {
  $status ="error Update  fuser ".$str_update;
 }
?> 
<center>
<?php
echo "<br>".$status."<br>"."<br>";
echo "<button onclick=\"window.close();\">CLOSE</button>";
?>
</center>