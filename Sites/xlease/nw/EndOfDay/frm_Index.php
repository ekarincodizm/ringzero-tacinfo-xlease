<?php
include("../../config/config.php");

if(empty($_GET["signDate"])){
    $ssdate = nowDate();
}else{
    $ssdate=$_GET["signDate"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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

<script type="text/javascript">
$(document).ready(function(){	
	$("#signDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
});
</script>
</head>
<body>
<fieldset>
	<legend><B>รัน EndOfDay ประจำวัน ตามวันที่ต้องการ</B></legend>
	<center>
	<div align="center" style="width:850px;">
		<form method="post" name="form1" action="process_endofday.php">
			วันที่ : &nbsp
			<input type="text" size="12" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo $ssdate; ?>" onchange="chkdate()"/> &nbsp
			<input type="submit" id="btnsearch" value="เริ่มประมวลผล">
		</form>
	</div>
	<br>
	</center>
</fieldset>

<fieldset>
	<legend><B>ประวัติการทำรายการ</B></legend>
	<center>
	<div align="center" style="width:850px;">
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
			<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25">
				<th>ครั้งที่</th>
				<th>ผู้ทำรายการ</th>
				<th>วันเวลาที่ทำรายการ</th>
				<th>วันที่เลือก</th>
			</tr>
<?php
			$qry_history = pg_query("select * from public.\"thcap_endofday\" order by \"id\" DESC ");
			$numrows = pg_num_rows($qry_history);
			while($res_history = pg_fetch_array($qry_history))
			{
				$id = trim($res_history["id"]); // ครั้งที่ทำรายการ
				$username = trim($res_history["username"]); // ผู้ทำรายการ
				$doerstamp = trim($res_history["doerstamp"]); // วันที่ทำรายการ
				$selectdate = trim($res_history["selectdate"]); // วันที่เลือก
				
				if($id%2==0)
				{
					echo "<tr class=\"odd\" align=\"center\">";
				}
				else
				{
					echo "<tr class=\"even\" align=\"center\">";
				}
		
				//echo "<tr align=\"center\">";
				echo "<td>$id</td>";
				echo "<td>$username</td>";
				echo "<td>$doerstamp</td>";
				echo "<td>$selectdate</td>";
				echo "</tr>";
			}
?>
		</table>
	</div>
	<br>
	</center>
</fieldset>
</body>
</html>