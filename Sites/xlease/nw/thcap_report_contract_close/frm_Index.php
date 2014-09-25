<?php
include("../../config/config.php");

$nowDate = nowDate(); // ดึงข้อมูลวันจาก server

$focusDate = pg_escape_string($_POST["focusDate"]); // ประจำวันวันที่เลือก
$btn_report = pg_escape_string($_POST["btn_report"]); // กดปุ่มแสดงรายงาน
$conTypeSelect = pg_escape_string($_POST["conTypeSelect"]); // ประเภทสัญญา
$selectTime = pg_escape_string($_POST["selectTime"]); // radio วันที่ปิดสัญญา
$yy = pg_escape_string($_POST["yy"]); // ปีของปี
$mm = pg_escape_string($_POST["mm"]); // เดือน
$ym = pg_escape_string($_POST["ym"]); // ปีของเดือน

if($focusDate == ""){$focusDate = $nowDate;} // ถ้ายังไม่มีค่าประจำวันที่เลือก ให้ใช้วันที่ปัจจุบัน
if($selectTime == ""){$selectTime = "a";} // ถ้ายังไม่มีค่า ให้ default เป็น ทั้งหมด

// ประเภทสัญญาที่เลือก
$myWhere = "and (";
$qry_typeContract = pg_query("select \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
while($loop_typeContract = pg_fetch_array($qry_typeContract))
{
	$typecontract = $loop_typeContract["typecontract"];
	
	$looptypecontract[$typecontract] = pg_escape_string($_POST["$typecontract"]);
	
	if($looptypecontract[$typecontract] == "on")
	{
		$get_contype .= "&$typecontract=on";
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานสัญญาที่ปิดบัญชีแล้ว</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		var selectAll = 'Y'; // เริ่มแรก ประเภทสินเชื่อ จะให้ติ๊กเลือกทั้งหมด
		
		$(document).ready(function(){
			$("#focusDate").datepicker({
				showOn: 'button',
				buttonImage: '../thcap/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
		});
		
		function showReport()
		{
			var get_contype = '<?php echo $get_contype; ?>'; // ประเภทสินเชื่อที่จะให้แสดง
			
			$('#panel').empty();
			$('#panel').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังค้นหา...">');
			$("#panel").load("report_gui.php?conTypeSelect="+'<?php echo $conTypeSelect; ?>'+"&selectTime="+'<?php echo $selectTime; ?>'+"&yy="+'<?php echo $yy; ?>'+"&mm="+'<?php echo $mm; ?>'+"&ym="+'<?php echo $ym; ?>'+"&focusDate="+'<?php echo $focusDate; ?>'+get_contype);
		}
		
		function selectOrClearAllType() // ประเภทสินเชื่อ : ติ๊กถูกทั้งหมด หรือ เอาเครื่องหมายถูกออกทั้งหมด
		{
			if(selectAll == 'N')
			{
				selectAll = 'Y';
				
				var ele_contype = $("input[id=con_type]");
				
				//ติ้ก ถูกทั้งหมด
				for (i=0; i< ele_contype.length; i++)
				{
					$(ele_contype[i]).attr ( "checked" ,"checked" );
				}
			}
			else
			{
				selectAll = 'N';
				
				var ele_contype = $("input[id=con_type]");
				
				//เอาติ้ก ถูก ออก ทั้งหมด
				for (i=0; i< ele_contype.length; i++)
				{
					$(ele_contype[i]).removeAttr('checked');
				}
			}
		}
	</script>
	
</head>
<body>

<center>
<h1>(THCAP) รายงานสัญญาที่ปิดบัญชีแล้ว</h1>
</center>

<form name="frm1" method="post" action="frm_Index.php">
	<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<fieldset><legend><B>เงื่อนไข</B></legend>
					<center>						
						<table>
							<tr>
								<td align="right"><span style="cursor:pointer;" onClick="selectOrClearAllType();"><font color="#0000CC"><u><B>ประเภทสินเชื่อ</B></u></font> : </span></td>
								<td align="left">
									<?php
									$qry_typeContract = pg_query("select  \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
									while($loop_typeContract = pg_fetch_array($qry_typeContract))
									{
										$typecontract = $loop_typeContract["typecontract"];
										
										if($btn_report == "yes")
										{
											if($looptypecontract[$typecontract] == "on")
											{
												echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"con_type\" checked> $typecontract &nbsp;&nbsp;&nbsp;";
												$typeForSave .= "<input type=\"hidden\" name=\"$typecontract\" value=\"on\">";
											}
											else
											{
												echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"con_type\" > $typecontract &nbsp;&nbsp;&nbsp;";
												$typeForSave .= "<input type=\"hidden\" name=\"$typecontract\" value=\"\">";
											}
										}
										else
										{
											echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"con_type\" checked> $typecontract &nbsp;&nbsp;&nbsp;";
										}
									}
									?>
								</td>
							</tr>
							<tr>
								<td align="right">วันที่ปิด : </td>
								<td>
									<input type="radio" name="selectTime" value="a" <?php if($selectTime == "a"){echo "checked";} ?>>ทั้งหมด
								</td>
							</tr>
							<tr>
								<td align="right"></td>
								<td>
									<input type="radio" name="selectTime" value="y" <?php if($selectTime == "y"){echo "checked";} ?>>
									ประจำปี :
									<select name="yy">
										<?php
										for($i=date('Y'); $i>=2010; $i--)
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
								<td align="right"></td>
								<td>
									<input type="radio" name="selectTime" value="m" <?php if($selectTime == "m"){echo "checked";} ?>>
									ประจำเดือน :
									เดือน
									<select name="mm">
										<?php
										for($i=1; $i<=12; $i++)
										{
											if(strlen($i) < 2){$i = "0".$i;}
											
											if($i == "$mm")
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
									ปี
									<select name="ym">
										<?php
										for($i=date('Y'); $i>=2010; $i--)
										{
											if($i == "$ym")
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
								<td align="right"></td>
								<td>
									<input type="radio" name="selectTime" value="d" <?php if($selectTime == "d"){echo "checked";} ?>>
									ประจำวัน :  <input type="textbox" name="focusDate" id="focusDate" size="15" value="<?php echo $focusDate; ?>" style="text-align:center;">
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