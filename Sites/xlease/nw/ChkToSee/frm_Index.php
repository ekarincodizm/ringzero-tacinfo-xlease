<?php
include("../../config/config.php");

$sid = $_POST["sid"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ตรวจสอบการดูตารางผ่อนชำระ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
$(document).ready(function(){
    $("#sid").autocomplete({
        source: "s_ID.php",
        minLength:1
    });
});
</script>

<style type="text/css">
.odd{
    background-color:#FFFFFF;
    font-size:12px
}
.even{
    background-color:#F0F0F0;
    font-size:12px
}
</style>

</head>

<body>
<br>

<fieldset>
	<legend><B>ค้นหา</B></legend>
	<center>
	<form method="post" action="frm_Index.php">
	<b>IDNO,ชื่อ/สกุล,ทะเบียน,Ref1,Ref2 : </b><input type="text" name="sid" id="sid" value="<?php echo $sid; ?>"> <input type="submit" value="ค้นหา">
	</center>
</fieldset>

<?php
if($sid != "")
{
	$qry_see = pg_query("select a.\"time_open\" , a.\"time_close\" , (a.\"time_close\" - a.\"time_open\") as \"time\" , a.\"ref_id\" , b.\"fullname\", b.\"nickname\"
						from \"LogsAnyFunction\" a , \"Vfuser\" b
						where a.\"user_id\" = b.\"id_user\" and \"id_menu\" = 'P05' and a.\"ref_id\" = '$sid'
						order by a.\"time_open\" DESC ");
	$numrows = pg_num_rows($qry_see);
?>
<br>
<center>
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
	<tr bgcolor="#097AB0" style="color:#FFFFFF">
		<th>เวลาที่เข้าดู</th>
		<th>เวลาที่ดูเสร็จ</th>
		<th>รวมเวลาที่ใช้ดู</th>
		<th>เลขที่สัญญา</th>
		<th>ผู้ใช้งานที่เข้าดู</th>
	</tr>
<?php
	if($numrows > 0)
	{
		$i = 1;
		
		while($res_name = pg_fetch_array($qry_see))
		{
			$time_open = $res_name["time_open"];
			$time_close = $res_name["time_close"];
			$time = $res_name["time"];
			$ref_id = $res_name["ref_id"];
			$fullname = $res_name["fullname"];
			$nickname = $res_name["nickname"];
			
			if($nickname == ""){ $nickname = "-"; }
			
			
			if($i%2==0)
			{
				echo "<tr class=\"odd\" align=\"center\">";
			}
			else
			{
				echo "<tr class=\"even\" align=\"center\">";
			}
			
			echo "<td>$time_open</td>";
			echo "<td>$time_close</td>";
			echo "<td>$time</td>";
			echo "
			<td align=\"center\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$ref_id','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\">
			<u>".$ref_id."</u></td>";
			echo "<td align=\"left\">$fullname ($nickname)</td>";
			echo "</tr>";
			
			$i++;
		}
	}
	else
	{
		echo "<tr class=\"even\" align=\"center\">";
		echo "<td colspan=\"5\">ไม่พบข้อมูล</td>";
		echo "</tr>";
	}
?>
</table>
</center>
<?php
}
?>

</body>
</html>