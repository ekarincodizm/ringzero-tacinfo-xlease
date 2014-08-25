<?php
include("../../config/config.php");

$CallBackID = $_GET["CallBackID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดการติดต่อกลับ</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>
<br>
<center>
<h2>รายละเอียดการติดต่อกลับ</h2>
<br>
<?php
$query = pg_query("select * from public.\"VCallback\" where \"CallBackID\" = '$CallBackID' "); 
while($result = pg_fetch_array($query))
{
	$CusName=$result["CusName"];
	$CusPhone=$result["CusPhone"];
	$CallTitle=$result["CallTitle"];
	$CallDetial=$result["CallDetial"];
	$doerUser=$result["doerName"];
	$doerStamp=$result["doerStamp"];
	$Want_dep_id=$result["Want_dep_id"];
	$Want_id_user=$result["Want_name_user"];
	$TimeCallBack=$result["TimeCallBack"]; // พนักงานที่ต้องการจะติดต่อ
	$callback_name_user=$result["callback_name_user"]; // รหัสพนักงานที่ติดต่อกลับ
	$callback_Stamp=$result["callback_Stamp"]; // วันเวลาที่ติดต่อกลับ
	$TalkDetail=$result["TalkDetail"]; // รายละเอียดการสนทนาที่ติดต่อกลับ
	$CallBackStatus=$result["CallBackStatus"]; //สถานะการติดต่อกลับ
	$callTypeName=$result["callTypeName"]; //ชื่อประเภทการติดต่อ
}

if($TimeCallBack == "") // ถ้าวันเวลาที่สะดวกให้ติดต่อกลับเป็นค่าว่าง
{
	$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
}

if($Want_dep_id != "")
{
	$query_dep = pg_query("select * from public.\"department\" where \"dep_id\" = '$Want_dep_id' ");
	while($result_dep = pg_fetch_array($query_dep))
	{
		$went = "แผนก : ".$result_dep["dep_name"];
	}
}
elseif($Want_id_user != "")
{
	$went = $Want_id_user;
}
?>
<table>
	<tr>
		<td align="right" width="165"><b>ชื่อลูกค้า :</b></td>
		<td><?php echo $CusName; ?></td>
	</tr>
	<tr>
		<td align="right"><b>บุคคลที่ต้องการจะติดต่อ :</b></td>
		<td><?php echo $went; ?></td>
	</tr>
	<tr>
		<td align="right"><b>พนักงานที่รับเรื่อง :</b></td>
		<td><?php echo $doerUser; ?></td>
	</tr>
	<tr>
		<td align="right"><b>วันเวลาที่รับเรื่อง :</b></td>
		<td><?php echo $doerStamp; ?></td>
	</tr>
	<tr>
		<td align="right"><b>เบอร์ติดต่อกลับ :</b></td>
		<td><?php echo $CusPhone; ?></td>
	</tr>
	<tr>
		<td align="right"><b>ประเภทการติดต่อ :</b></td>
		<td><?php echo $callTypeName; ?></td>
	</tr>
	<?php
	//ค้นหาแหล่งที่ทราบข้อมูล
	$qrychkfrom=pg_query("SELECT \"callFromName\" FROM callback_details_from a
	left join callback_from b on a.\"callFromID\"=b.\"callFromID\" where \"CallBackID\"='$CallBackID'");
	$numchkfrom=pg_num_rows($qrychkfrom);
	?>
	<tr id="statusShow"><td align="right" valign="top"><b>แหล่งที่ทราบข้อมูล :</b></td>
	<td id="showfrom">
	<?php
		$t=1;
		while($reschk=pg_fetch_array($qrychkfrom)){		
			list($callFromName)=$reschk;	
			if($t!=$numchkfrom){
				echo "$callFromName,";
			}else{
				echo "$callFromName";
			}
			$t++;
		}
	?>
	</td></tr>
	<tr>
		<td align="right"><b>เรื่อง :</b></td>
		<td><?php echo $CallTitle; ?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>รายละเอียด :</b></td>
		<td><textarea name="DetailCall" cols="35" rows="4" readonly="true"><?php echo $CallDetial; ?></textarea></td></td>
	</tr>
	<?php
	//แสดงรายละเอียดการสนทนา
	$qrydetail=pg_query("select \"callback_name_user\",\"callback_Stamp\",\"TalkDetail\",\"TimeCallBack\" from \"VCallback_detail\" where \"CallBackID\"='$CallBackID'");
	while($resdetail=pg_fetch_array($qrydetail)){
		list($callback_name_user,$callback_Stamp,$TalkDetail,$TimeCallBack)=$resdetail;
		
		if($TimeCallBack == ""){
			$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
		}
		if($TalkDetail!=""){
	?>
	<tr><td colspan="2"><hr></td><tr>
	<tr>
		<td align="right"><b>วันเวลาที่สะดวกให้ติดต่อกลับ :</b></td>
		<td><?php echo $TimeCallBack; ?></td>
	</tr>
	<tr>
		<td align="right"><b>พนักงานที่ติดต่อกลับ :</b></td>
		<td><?php echo $callback_name_user; ?></td>
	</tr>
	<tr>
		<td align="right"><b>วันเวลาที่ติดต่อกลับ :</b></td>
		<td><?php echo $callback_Stamp; ?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>รายละเอียดที่ติดต่อกลับ :</b></td>
		<td><textarea name="DetailCall" cols="30" rows="4" readonly="true"><?php echo $TalkDetail; ?></textarea></td></td>
	</tr>
	<?php
		}
	}
	
	//ดึงเหตุผลในการปฏิเสธออกมาแสดงกรณีที่สิ้นสุดการติดต่อกับลูกค้าแล้ว
	if($CallBackStatus==2){
		$qryrej=pg_query("select \"callRejName\" from callback_details_reject a
		left join callback_reject b on a.\"callRejID\"=b.\"callRejID\" where \"CallBackID\" = '$CallBackID'");
		$numrej=pg_num_rows($qryrej);
		if($numrej>0){
			echo "<tr><td colspan=2><hr></td></tr>";
			echo "<tr><td align=right><b>สถานะการปิดงาน :</b></td><td><font color=red>ปฏิเสธ</font></td></tr>";
			echo "<tr><td align=right><b>เหตุผลในการปฏิเสธ :</b></td>";
			echo "<td>";
			$t=1;
			while($resrej=pg_fetch_array($qryrej)){
				list($callRejName)=$resrej;
				if($t!=$numrej){
					echo "$callRejName,";
				}else{
					echo "$callRejName";
				}
				$t++;
			}
			echo "</td></tr>";
		}else{
			echo "<tr><td colspan=2><hr></td></tr>";
			echo "<tr><td align=right><b>สถานะการปิดงาน :</b></td><td><font color=red>สำเร็จ</font></td></tr>";
		}
	}
	?>
	
	<tr>
		<td align="center" colspan="2"><br><input type="button" value="close" onclick="window.close();"></td>
	</tr>
</table>
</center>
</body>
</html>