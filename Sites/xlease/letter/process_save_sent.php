<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("../config/config.php");

$resid=$_SESSION["av_iduser"];
$lid=pg_escape_string($_POST["let_id"]);
$datenow=date("Y-m-d");
$mtype=pg_escape_string($_POST["m_types"]);

$fs_cid=pg_escape_string($_POST["f_cid"]);
$fs_idno=pg_escape_string($_POST["f_idno"]);

if(empty($_POST["typeletter"]))
{
  echo "คุณไม่ได้เลือกจดหมาย เราจะนำท่านกลับไปเลือกจดหมาย";
  echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_add_let.php?IDNO=$fs_idno&CID=$fs_cid\">"."<br>";   
  
}
else
{

for($i=0;$i<count($_POST["typeletter"]);$i++)
{

   
    
	$type_let=pg_escape_string($_POST["typeletter"][$i]);
	if($i<count($_POST["typeletter"])-1)
	{
	 $comma=",";
	}
	else
	{
	 $comma="";
	}
	$tmp_type=$tmp_type.$type_let.$comma;
	
}
echo $sum_type=$tmp_type;


$gen_sendid=pg_query("select letter.gen_sendid('$datenow')");
$res_genid=pg_fetch_result($gen_sendid,0);

$ins_send_dtl="insert into letter.send_detail 	
               (\"send_date\",\"sendID\",\"CusLetID\",\"detail\",\"userid\")
               values
			   ('$datenow','$res_genid','$fs_cid','$sum_type','$resid')";
 
 if($result=pg_query($ins_send_dtl))
 {
  $status ="OK".$ins_send_dtl;
  $sta="บันทึกส่งจดหมายเรียบร้อยแล้ว รอสักครู่จะนำท่านไปรายการพิมพ์จดหมาย";
  ?>
  
  <script language="javascript">
    window.open("print_letter.php?cus_lid=<?php echo $res_genid; ?>");
  </script>
  
  <?php
 }
 else
 {
  $status ="error insert Re".$ins_send_dtl;
  $sta="เกิดข้อผิดพลาด".$status;
 }
 echo $sta;
  echo "<meta http-equiv=\"refresh\" content=\"3;URL=frm_letter.php\">"."<br>";
 //echo "<meta http-equiv=\"refresh\" content=\"2;URL=print_letter.php?cus_lid=$res_genid\" base target=\"_blank\">"."<br>";   
}
?>