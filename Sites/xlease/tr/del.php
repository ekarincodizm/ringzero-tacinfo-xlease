<?php
session_start();
$iduser = $_SESSION['uid'];
$g_id=$_SESSION["av_usergroup"];
$c_code=$_SESSION["session_company_code"];
//$c_code="THA";


if(trim($g_id)=="AD")
{
  
$s_did=$_GET["d_id"];

$delFile=unlink("upload/".$c_code."/".$s_did);
              if($delFile)
           {
              echo "File Deleted";
           }
           else
           {
            echo "File can not delete";
           }  
}
else
{

echo "<center>"."คุณไม่มีสิทธิในการลบไฟล์ กรุณาติดต่อผู้ดูแลระบบ "."</center>";

}
	   
?>
<br><br><center><button onClick="window.close();" >CLOSE</button></center></br>