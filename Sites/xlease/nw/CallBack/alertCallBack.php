<?php
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$iduser = pg_escape_string($_GET["idpopup"]);

// หากลุ่มของผู้ใช้งานในขณะนั้น
$query_group = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$iduser' ");
while($result_group = pg_fetch_array($query_group))
{
	$user_group = $result_group["user_group"]; // กลุ่มของ user
}

$qryalert=pg_query("select \"callback_Stamp\" from public.\"callback\" where \"CallBackStatus\" IN('1','3') and (\"Want_dep_id\" = '$user_group' or \"Want_id_user\" = '$iduser')
and (\"callback_Stamp\" is null or \"callback_Stamp\"<'$nowdate') order by \"callback_Stamp\"");
$p=0;
$pp=0; //เอาไว้ตรวจสอบว่าติดต่อกลับทันทีหรือไม่
while($resalert=pg_fetch_array($qryalert)){
	$callback_Stamp=$resalert["callback_Stamp"];
	
	if($callback_Stamp==""){
		$p++;
		$pp++; //แสดงว่ามีการติดต่อกลับทันที
	}else if($callback_Stamp < $nowdate){
		$p++;
		$nowtime_last=$callback_Stamp;
	}else{
		$p=0;
	}
}

//$qry=pg_query("select * from public.\"VCallback\" where \"CallBackStatus\" IN('1','3') and (\"Want_dep_id\" = '$user_group' or \"Want_id_user\" = '$iduser') ");
//$numrow=pg_num_rows($qry);
//ณ เวลา $nowtime

echo "<br>";
echo "<center>";
echo "<h2><font color=\"#FF0000\">มีลูกค้ารอการติดต่อกลับจากคุณอยู่จำนวน $p คน </font></h2>";
?>

<input type="button" value="CLOSE" onclick="javascript:window.close();">
</center>