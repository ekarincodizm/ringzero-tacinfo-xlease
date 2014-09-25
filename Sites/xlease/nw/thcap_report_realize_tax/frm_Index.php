<?php
include("../../config/config.php");

$btn_report = pg_escape_string($_POST["btn_report"]); // กดปุ่มให้แสดงข้อมูลหรือไม่
$tm = pg_escape_string($_POST["tm"]); // ไตรมาส
$yy = pg_escape_string($_POST["yy"]); // ปี
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการรับรู้รายได้ (ทางภาษี)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function showReport()
		{
			$('#panel').empty();
			$('#panel').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังค้นหา...">');
			$("#panel").load("report_gui.php?tm="+'<?php echo $tm; ?>'+"&yy="+'<?php echo $yy; ?>');
		}
	</script>
	
</head>
<body>

<center>
<h1>(THCAP) รายงานการรับรู้รายได้ (ทางภาษี)</h1>
</center>

<form name="frm1" method="post" action="frm_Index.php">
	<table width="85%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<fieldset><legend><B>เงื่อนไข</B></legend>
					<center>						
						<table>
							<tr>
								<td align="right">ไตรมาส : </td>
								<td align="left">
									<select name="tm">
										<option value="1" <?php if($tm == "1"){echo "selected";} ?> >1</option>
										<option value="2" <?php if($tm == "2"){echo "selected";} ?> >2</option>
										<option value="3" <?php if($tm == "3"){echo "selected";} ?> >3</option>
										<option value="4" <?php if($tm == "4"){echo "selected";} ?> >4</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right">ปี : </td>
								<td align="left">
									<select name="yy">
										<?php
										for($i=date('Y'); $i>=2014; $i--)
										{
											if($i == "$yy")
											{
												echo "<option value=\"$i\" selected>$i</option>";
											}
											else
											{
												echo "<option value=\"$i\">$i</option>";
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input type="submit" value="แสดงรายงาน"/></td>
							</tr>
						</table>
					</center>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<div id="panel" name="panel" ></div>
				<?php
				if($btn_report == "yes")
				{
					echo "<script>";
					echo "showReport();";
					echo "</script>";
				}
				?>
			</td>
		</tr>
	</table>
	<input type="hidden" name="btn_report" value="yes"/>
</form>

</body>

</html>