<?php
session_start();
include("../config/config.php");
$officeid=$_SESSION["av_officeid"];
$v_txt=$_GET["cid"];
$dat=date("Y/m/d");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

echo $idno=$_GET["fidno"];
echo $cus_sn=substr($v_txt,0,6);

pg_query("BEGIN WORK");
$status = 0;

//ค้นหาว่าผู้ค้ำคนต่อไปคือคนที่เท่าไหร่
$add_ccus=pg_query("select count(*) AS c_cc from \"ContactCus\" WHERE \"IDNO\"='$idno' ");
$resccus=pg_fetch_array($add_ccus);

$cs_cc=$resccus["c_cc"];

//เพิ่มผู้ค้ำในตาราง ContactCus
$in_cus="insert into \"ContactCus\" (\"CusID\",\"CusState\",\"IDNO\") values ('$cus_sn','$cs_cc','$idno')";
if($result=pg_query($in_cus))
{
	$statuc ="OK at Fn".$in_cus;
}
else
{
	$statuc ="error insert  Fn Re".$in_cus;
	$status++;
}	

//นำข้อมูลในตาราง Fa1 มา insert ในตาราง Fp_Fa1
$insfpfa1="INSERT INTO \"Fp_Fa1\" (\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", 
\"A_AUM\", \"A_PRO\", \"A_POST\", \"CusState\",\"addUser\",\"addStamp\")
  
SELECT a.\"IDNO\",b.\"CusID\",c.\"A_NO\",c.\"A_SUBNO\",c.\"A_SOI\",c.\"A_RD\",c.\"A_TUM\",
c.\"A_AUM\",c.\"A_PRO\",c.\"A_POST\",b.\"CusState\",'$add_user','$add_date' FROM \"Fp\" a
LEFT JOIN \"ContactCus\" b on a.\"IDNO\"=b.\"IDNO\"
LEFT JOIN \"Fa1\" c on b.\"CusID\"=c.\"CusID\"
where b.\"IDNO\"='$idno' and b.\"CusState\"='$cs_cc'";

if($resinsfpfa1=pg_query($insfpfa1)){
}else{
	$status++;
}

if($status==0){
	pg_query("COMMIT");
	echo "<br>";
	echo "    บันทึกข้อมูลเรียบร้อยแ้ล้ว ";
	echo "<br>";
}
else{
	pg_query("ROLLBACK");
} 
echo "<br>".$statuc;
echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_edit.php?idnog=$idno\">"; 


?>