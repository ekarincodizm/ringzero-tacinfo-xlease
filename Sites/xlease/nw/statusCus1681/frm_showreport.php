<?php
include("../../config/config.php");
$date = $_GET['date'];
$status=$_GET[status];

if(empty($date)){
    exit;
}
echo "
<table width=\"800\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\" bgcolor=\"#F0F0F0\">
<tr style=\"font-weight:bold;\" valign=\"middle\" bgcolor=\"#79BCFF\" align=\"center\">
	<td>ลำดับที่</td>
    <td>ชื่อ-นามสกุลพนักงาน</td>
    <td>รวมจำนวนเงินที่รับ (บาท)</td>
    <td>พิมพ์</td>
</tr>
";

if($status==1){
	$qry_name=pg_query("select distinct(\"makerID\") as id_user from \"tacReceiveTemp\" where \"tacTempDate\"='$date'");
}else{
	$startDate=$date." 00:00:00";
	$endDate=$date." 23:59:59";
	$qry_name=pg_query("select distinct(\"makerID\") as id_user from \"tacReceiveTemp\" where \"makerStamp\" between '$startDate' and '$endDate'");
}
$num_row = pg_num_rows($qry_name);
$i=1;
while($res_name2=pg_fetch_array($qry_name)){
	$id_user = $res_name2["id_user"];
    $query_name=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$id_user'");
	if($resname=pg_fetch_array($query_name)){
		$fullname=$id_user."-".$resname["fullname"];
	}
	
	if($status==1){
		$querysum=pg_query("select sum(\"tacMoney\") as summoney from \"tacReceiveTemp\" where \"makerID\"='$id_user' and \"tacTempDate\"='$date'");
	}else{
		$querysum=pg_query("select sum(\"tacMoney\") as summoney from \"tacReceiveTemp\" where \"makerID\"='$id_user' and (\"makerStamp\" between '$startDate' and '$endDate')");
	}
	if($ressum=pg_fetch_array($querysum)){
		$summoney=number_format($ressum["summoney"],2);
	}
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
	echo "<td align=center>$i</td>";
    echo "<td>$fullname</td>";
    echo "<td align=right>$summoney</td>";
	echo "<td align=center><a href=\"pdf_report.php?id_user=$id_user&date=$date&status=$status\" target=\"_blank\"><img src=\"images/printer.gif\" width=18 height=18 border=0></a></td>";
	echo "</tr>";
	$i++;
}
if($num_row==0){
	echo "<tr><td height=50 align=center colspan=4>ไม่พบข้อมูล</td></tr>";
}