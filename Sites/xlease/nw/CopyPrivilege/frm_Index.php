<?php
include("../../config/config.php");

$user_origin = $_POST["id_user"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>คัดลอกสิทธิ์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#id_user").autocomplete({
        source: "s_userid.php",
        minLength:1
    });
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>  
</head>
<body>
<form method="post" name="form1" action="frm_Index.php">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center;padding-bottom: 10px;"><h2>เลือกพนักงานที่จะเป็นต้นฉบับสิทธิ์</h2></div>
			<fieldset><legend><B>ค้นหาข้อมูล</B></legend>
				<div class="ui-widget" align="center">
					<div style="margin:0;height:30px;">
						<b>ค้นหาจาก รหัสพนัีกงาน, username หรือ ชื่อ-สกุลของพนักงาน, ชื่อเล่น</b>&nbsp;
						<input id="id_user" name="id_user" size="60" />&nbsp;
						<input type="submit" id="btn1" value="เลือก"/>
					</div>
				</div>
			 </fieldset>
        </td>
    </tr>
</table>
</form>

<!--แสดงรายละเอียดพนักงานที่จะเป็นต้นฉบับสิทธิ์-->
<?php
$qryname=pg_query("select * from \"Vfuser\" where \"id_user\" = '$user_origin' ");
while($resqryname=pg_fetch_array($qryname))
{
	$fullname=$resqryname["fullname"];
}
if($user_origin != "")
{
?>
<br>
<form method="post" name="form2" action="frm_SelectCopy.php">
<table width="900" border="0" cellspacing="1" cellpadding="1" align="center" style="background-color:#EEEDCC">
    <tr bgcolor="#FFFFFF">
        <td><center><h3>รายชื่อโปรแกรมทั้งหมดของ <?php echo "$fullname(รหัสพนักงาน : $user_origin)"; ?></h3></center></td>
    </tr>
	<tr bgcolor="#FFFFFF" align=right><td><input type="submit" value="ยืนยัน"></td></tr>
	<tr style="background-color:#D0DCA0" height="25" align="center">
		<th>รายชื่อโปรแกรม</th>
	</tr>
	<?php
		$qry=pg_query("select \"f_menu\".\"name_menu\" from public.\"f_usermenu\" , public.\"f_menu\" 
						where \"f_usermenu\".\"id_menu\" = \"f_menu\".\"id_menu\" and \"f_usermenu\".\"id_user\" = '$user_origin' order by \"f_menu\".\"name_menu\" ");
		$numrow=pg_num_rows($qry);
		if($numrow==0){
			echo "<tr><td colspan=1 height=50 align=center><b>ไม่พบรายการ</b></td></tr>";
		}else{
			$i=1;
			while($resqry=pg_fetch_array($qry)){
				$name_menu=$resqry["name_menu"];
				
				echo "<tr height=25 bgcolor=#F4FED6>";
				echo "<td align=center>$name_menu</td>";
				echo "</tr>";
				$i++;
			}
		}
		echo "<input type=\"hidden\" name=\"user_origin\" value=\"$user_origin\">";
	?>
</table>
</form>
<?php
}
?>
</body>
</html>