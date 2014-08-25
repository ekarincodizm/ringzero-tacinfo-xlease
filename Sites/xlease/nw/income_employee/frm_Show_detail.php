<?php
include("../../config/config.php");
include("../function/nameMonth.php");
$id_user=$_GET['inc_userid']; //รหัสพนักงานที่ต้องการแสดง
$month=$_GET['month']; //เดือนที่ต้องการแสด
$year=$_GET['year']; //ปีที่ต้องการแสดง

//หาชื่อเดือน
$monthtxt=nameMonthTH($month);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แสดงรายละเอียดรายได้พิเศษ </title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<div align="center"><h2>แสดงรายละเอียดรายได้พิเศษ ประจำเดือน <?php echo $monthtxt; ?> ปี <?php echo $year;?></h2></div>
<fieldset>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th>รหัสผู้ใช้งาน</th>
		<th>ชื่อผู้ใช้งาน</th>
		<th>คำนำหน้า-ชื่อ-นามสกุล (ชื่อเล่น)</th>
		<th>วันที่ที่ได้รับ</th>
		<th>ประเภทการได้รับ </th>
		<th>เลขอ้างอิง </th>
		<th>จำนวนรายได้พิเศษ </th>
	</tr>
	<?php
		$query=pg_query("select inc_userid,username,fullname,nickname,inc_date,inc_money,inctype_name,inc_typeref from ta_user_incentive a
		left join \"Vfuser\" b on a.inc_userid=b.id_user
		left join ta_user_incentive_type c on a.inc_type=c.inctype_serial
		where EXTRACT(YEAR FROM \"inc_date\")='$year' and EXTRACT(MONTH FROM \"inc_date\")='$month' and inc_userid='$id_user'
		order by inc_date");	
		$numrows=pg_num_rows($query);
		$summoney=0;
		while($result=pg_fetch_array($query)){
			$inc_userid=$result["inc_userid"]; //รหัสผู้ใช้งาน
			$username=$result["username"]; //user
			$fullname=$result["fullname"];	//ชื่อ
			$nickname =$result["nickname"]; //ชื่อเล่น
			if($nickname!=""){
				$fullname="$fullname ($nickname)";
			}
			$inc_date =$result["inc_date"]; //วันที่ที่ได้รับ 
			$inctype_name =$result["inctype_name"]; //ประเภทการได้รับ 
			$inc_typeref =$result["inc_typeref"]; //เลขอ้างอิง 
			$inc_money =$result["inc_money"]; //จำนวนรายได้พิเศษ
			$money=number_format($inc_money,2);
			$summoney+=$inc_money;
		
			if($i%2==0){
				echo "<tr class=\"odd\" align=center >";
			}else{
				echo "<tr class=\"even\" align=center >";
			}
			echo "<td height=25>$inc_userid</td>";
			echo "<td align=left>$username</td>";
			echo "<td align=left>$fullname</td>";
			echo "<td>$inc_date</td>";
			echo "<td align=left>$inctype_name</td>";
			echo "<td>$inc_typeref</td>";
			echo "<td align=right>$money</td>";
			echo "</tr>";
			
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center>ไม่พบข้อมูล</td></tr>";
		}else{
			echo "<tr bgcolor=#FFCCCC><td colspan=6 align=right><b>รวม</b></td><td align=right><b>".number_format($summoney,2)."</b></td></tr>";			
		}
	?>
</table>
</fieldset> 
<div style="padding-top:20px;text-align:center;"><input type="button" value=" ปิด " onclick="window.close();"></div>
</body>
</html>

			
						