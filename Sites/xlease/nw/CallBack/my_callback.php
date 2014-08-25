<?php
include("../../config/config.php");

$id_user=$_SESSION["av_iduser"]; // รหัสของ user ที่กำลังใช้งานอยู่

$query_group = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
while($result_group = pg_fetch_array($query_group))
{
	$user_group = $result_group["user_group"]; // รหัสกลุ่มของผู้ใช้ที่กำลังใช้งานอยู่
}

$show = $_POST["show"];
if($show == ""){$show = $_GET["show"];}

if($show == "")
{
	$query = pg_query("select * from public.\"VCallback\" where \"CallBackStatus\" IN ('1','3') and (\"Want_id_user\" = '$id_user' or \"Want_dep_id\" = '$user_group') order by \"doerStamp\" ");
}
elseif($show == "1")
{
	$query = pg_query("select * from public.\"VCallback\" where \"CallBackStatus\" IN ('1','3') and (\"Want_id_user\" = '$id_user' or \"Want_dep_id\" = '$user_group') order by \"doerStamp\" ");
}
elseif($show == "2")
{
	$query = pg_query("select * from public.\"VCallback\" where \"CallBackStatus\" IN ('1','3') order by \"doerStamp\" ");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>ลูกค้าที่รอการติดต่อกลับ</title>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังค้นหาข้อมูล...');
		$("#panel").load("list_nt.php");
		$("#btn1").attr('disabled',false);
		
    });	
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>
</head>
<body>
<div  align="center"><h2>ลูกค้าที่รอการติดต่อกลับ</h2></div>
<div id="panel" style="padding-top: 10px;">

<form name="frm2" method="post" action="my_callback.php">
<center>
<select name="show">
<option value="1" <?php if($show=="1"){echo "selected";} ?>>เฉพาะของฉัน/แผนก</option>
<option value="2" <?php if($show=="2"){echo "selected";} ?>>แสดงทั้งหมด</option>
</select>
<input type="submit" value="ค้นหา">
</center>
</form>

<br>
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th height="30">รายการที่</th>
		<th>เรื่อง</th>
		<th>ชื่อลูกค้า</th>
		<th>เบอร์ติดต่อกลับ</th>
		<th>วันเวลาที่บันทึก</th>
		<th>บุคคลที่ต้องการจะติดต่อ</th>
		<th>วันเวลาที่สะดวกให้ติดต่อกลับ</th>
		<th>รายละเอียด</th>
		<th>บันทึกการติดต่อกลับ</th>
	</tr>
	<?php 
	
	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query))
	{
		$CallBackID=$result["CallBackID"]; // รหัสการติดต่อ
		$CusName=$result["CusName"]; // ชื่อลูกค้า
		$CusPhone=$result["CusPhone"]; // เบอร์ติดต่อกลับไปหาลูกค้า
		$CallTitle=$result["CallTitle"]; // ชื่อเรื่องที่จะติดต่อ
		$doerStamp=$result["doerStamp"]; // เวลาที่ลูกค้าติดต่อเข้ามา
		$Want_dep_id=$result["Want_dep_id"]; // แผนกที่ต้องการจะติดต่อ
		$Want_id_user=$result["Want_name_user"]; // พนักงานที่ต้องการจะติดต่อ
		$TimeCallBack=$result["TimeCallBack"]; // พนักงานที่ต้องการจะติดต่อ
		
		$CallBackStatus=$result["CallBackStatus"]; // สถานะการจบงาน
		if($CallBackStatus=="3"){ //แสดงว่ายังมีการสนทนาค้างอยู่ ดังนั้นให้ไปเอาเวลาติดต่อกลับที่ตารางอื่น
			$qrydetail=pg_query("select \"TimeCallBack\" from \"VCallback_detail\" where \"CallBackID\"='$CallBackID' order by \"callback_Stamp\" DESC limit 1");
			list($TimeCallBack)=pg_fetch_array($qrydetail);
			if($TimeCallBack == "") // ถ้าวันเวลาที่สะดวกให้ติดต่อกลับเป็นค่าว่าง
			{
				$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
				$chk_date = 0; // ใช้ในการตรวจสอบเงื่อนไขในการกำหนดสี
			}
			else
			{
				$chk_date = 1; // ใช้ในการตรวจสอบเงื่อนไขในการกำหนดสี
			}
		}else{
			if($TimeCallBack == "") // ถ้าวันเวลาที่สะดวกให้ติดต่อกลับเป็นค่าว่าง
			{
				$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
				$chk_date = 0; // ใช้ในการตรวจสอบเงื่อนไขในการกำหนดสี
			}
			else
			{
				$chk_date = 1; // ใช้ในการตรวจสอบเงื่อนไขในการกำหนดสี
			}
		}
		
		
		
		if($Want_dep_id != "") // ถ้ารหัสแผนกไม่ว่าง
		{
			$query_dep = pg_query("select * from public.\"department\" where \"dep_id\" = '$Want_dep_id' ");
			while($result_dep = pg_fetch_array($query_dep))
			{
				$went = "แผนก : ".$result_dep["dep_name"];
			}
		}
		elseif($Want_id_user != "") // ถ้ารหัสพนักงานไม่ว่าง
		{
			
				$went = $Want_id_user;
		}
		
		if($chk_date == 1)
		{
			if(date($TimeCallBack) <= date('Y-m-d H:m:s'))
			{
				echo "<tr style=\"font-size:11px;\" bgcolor=\"#FFCCCC\">";
			}
			else
			{
				if($i%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
			}
		}
		else
		{
			echo "<tr style=\"font-size:11px;\" bgcolor=\"#FFCCCC\">";
		}
		echo "<td align=center height=25>$i</td>";
		echo "<td>$CallTitle</td>";
		echo "<td>$CusName</td>";
		echo "<td align=center>$CusPhone</td>";
		echo "<td align=center>$doerStamp</td>";
		echo "<td>$went</td>";
		echo "<td align=center>$TimeCallBack</td>";
		echo "<td align=center><a onclick=\"javascript:popU('detail_final_callback.php?CallBackID=$CallBackID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
		echo "<td align=center><a href=\"frm_callback.php?CallBackID=$CallBackID&show=$show\"><font color=\"#0000FF\"><u>ติดต่อกลับ</u></font></a></td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=9><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</div>
</body>
</html>