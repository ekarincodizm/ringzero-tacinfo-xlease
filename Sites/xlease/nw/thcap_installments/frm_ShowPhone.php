<?php
include("../../config/config.php");
$CusID = $_GET["CusID"];

// ตรวจสอบว่าเป็นลูกค้านิติบุคคลหรือไม่
$qry_chkCorp = pg_query("select * from \"th_corp\" where \"corpID\"::text = '$CusID' ");
$row_chkCorp = pg_num_rows($qry_chkCorp);
if($row_chkCorp > 0){$iCorp = "yes";}else{$iCorp = "no";}

if($iCorp == "yes")
{ // ถ้าเป็นลูกค้านิติบุคคล
	$l = 3; // วน 3 รอบ รอบแรกเบอร์บ้าน รอบ 2 เบอร์มือถือ รอบ 3 เบอร์โทรสาร
}
else
{
	$l = 2; // วน 2 รอบ รอบแรกเบอร์บ้าน รอบ 2 เบอร์มือถือ
}

//หาชื่อลูกค้า
$qryname=pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\"='$CusID'");
list($cusname)=pg_fetch_array($qryname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>:: เบอร์โทรติดต่อ ::</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<?php
for($j=1;$j<=$l;$j++){ // ถ้าเป็นบุคคลธรรมดา วน 2 รอบ นิติบุคคล วน 3 รอบ รอบแรกเบอร์บ้าน รอบ 2 เบอร์มือถือ รอบ 3 เบอร์โทรสาร
	if($j==2){
		$type=1;
		$img="<img src=\"images/tel.gif\" width=30 height=27>";
		$txthead="เบอร์บ้าน";
		$colortable="#F0FFF0";
		$colorth="#838B83";
		$colortr1="#F5FFFA";
		$colortr2="#E0EEE0";	
	}
	elseif($j==3){
		$type=3;
		$img="<img src=\"images/fax.gif\" width=35 height=35";
		$txthead="เบอร์โทรสาร";
		$colortable="#F0FFF0";
		$colorth="#838B83";
		$colortr1="#F5FFFA";
		$colortr2="#E0EEE0";	
	}else{
		$type=2;
		$img="<img src=\"images/mobile.gif\" width=25 height=25>";
		$txthead="เบอร์มือถือ";
		$colortable="#FFF0F5";
		$colorth="#8B8386";
		$colortr1="#CDC1C5";
		$colortr2="#EEE0E5";		
	}

	echo "<div align=center><h2>$txthead $img</h2></div>";
	echo "<table width=\"80%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"$colortable\"  align=\"center\">";
	echo "
		<tr><td colspan=4 bgcolor=#FFFFFF><b>$cusname</b></td></tr>
		<tr bgcolor=\"$colorth\" style=\"color:#FFFFFF;\">
			<th>ที่</th>
			<th>เบอร์โทร</th>
			<th>ผู้ที่ทำหน้าที่เปลี่ยน</th>
			<th>วันเวลาที่เพิ่มเบอร์</th>
		</tr>

	";
	//ดึงเบอร์ทั้งหมดในตาราง "ta_phonenumber"
	$qryphone=pg_query("select phonenum,\"fullname\",\"doerStamp\" from \"ta_phonenumber\" a
	left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
	where \"CusID\"='$CusID' and phonetype='$type' order by \"doerStamp\" DESC");
	$numrows=pg_num_rows($qryphone);

	if($numrows>0){
		$i=0;
		while($resnum=pg_fetch_array($qryphone)){
			list($phonenum,$user,$doertime)=$resnum;
			
			$i++;
			if($i%2==0){
				echo "<tr align=center bgcolor=$colortr1>";
			}else{
				echo "<tr align=center bgcolor=$colortr2>";
			}
			echo "
				<td>$i</td>
				<td>$phonenum</td>
				<td align=left>$user</td>
				<td>$doertime</td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan=4 align=center bgcolor=\"$colortr2\">--ยังไม่มีข้อมูล--</td></tr>";
	}
	echo "</table>";
}
echo "<div align=center style=\"padding-top:20px;\"><input type=\"button\" value=\"ปิดหน้าต่าง\" onclick=\"window.close();\"></div>";
?>

</body>
</html>