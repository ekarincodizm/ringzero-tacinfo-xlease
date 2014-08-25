<?php
include("../../config/config.php");

$nowDate = nowDate(); //ดึงข้อมูลวันจาก server

$rowCustomer = pg_escape_string($_POST["rowCustomer"]); // จำนวนลูกค้า
$annuities = pg_escape_string($_POST["annuities"]); // ติดตามค่างวด
$focusDate = pg_escape_string($_POST["focusDate"]); // วันที่เลือก
$charges = pg_escape_string($_POST["charges"]); // ติดตามค่าใช้จ่ายอื่นๆ
$note = pg_escape_string($_POST["note"]); // หมายเหตุ
$btn_report = pg_escape_string($_POST["btn_report"]); // ถ้ามีการคลิกค้นหา จะเป็น yes

$noteSent = str_replace("\r\n","codeEnter",$note);
$noteSent = str_replace(" ","codeSpace",$noteSent);

if($focusDate == ""){$focusDate = $nowDate;} // ถ้ายังไม่มีค่าวันที่เลือก ให้ใช้วันที่ปัจจุบัน
if($rowCustomer == ""){$rowCustomer = 1;} // เริ่มต้นต้องมีลูกค้าอย่างน้อย 1 คน

if($annuities == "on"){$annuities_check = "checked";}else{$annuities_check = "";}
if($charges == "on"){$charges_check = "checked";}else{$charges_check = "";}

$textCusArray = pg_escape_string($_POST["CustomerName1"]);

$CusID1 = split("#", $textCusArray);
$CusID_array = $CusID1[0];

if($rowCustomer > 1)
{
	for($i=2;$i<=$rowCustomer;$i++)
	{
		$textCusArray .= "nextCus".pg_escape_string($_POST["CustomerName$i"]);
		
		$CusID_temp = split("#", pg_escape_string($_POST["CustomerName$i"]));
		$CusID_array .= ",".$CusID_temp[0];
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานติดตามชำระลูกค้ารายบุคคล(อู่)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		var rowCustomer = <?php echo $rowCustomer; ?>;
	
		var nubCustomer = 1;
		
		$(document).ready(function(){
			$("#focusDate").datepicker({
				showOn: 'button',
				buttonImage: '../thcap/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
			
			$("#CustomerName1").autocomplete({
				source: "s_cus.php",
				minLength:1
			});
		
			$('#addCustomer').click(function(){
				nubCustomer++;
				if(nubCustomer == 1)
				{
					document.getElementById("tableCustomer").style.visibility = 'visible';
					document.getElementById("rowCustomer").value = nubCustomer;
				}
				else if(nubCustomer > 1)
				{
					console.log(nubCustomer);
					var newCustomerDiv = $(document.createElement('div')).attr("id", 'CustomerDiv' + nubCustomer);
					table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
					+ '	<tr bgcolor="#E8E8E8">'
					+ '		<td align="right">ลูกค้า คนที่ '+ nubCustomer +' :</td>'
					+ '		<td><input type="text" name="CustomerName'+ nubCustomer +'" id="CustomerName'+ nubCustomer +'" size="70"></td>'
					+ '	</tr>'
					+ '	</table>'
					
					newCustomerDiv.html(table);

					newCustomerDiv.appendTo("#CustomerGroup");
						
					document.getElementById("rowCustomer").value = nubCustomer;
					
					$("#CustomerName" + nubCustomer).autocomplete({
						source: "s_cus.php",
						minLength:1
					});
				}
			});
			
			$("#removeCustomer").click(function(){
				if(nubCustomer==1){
					alert("ห้ามลบ !!!");
					return false;
				}
				$("#CustomerDiv" + nubCustomer).remove();
				nubCustomer--;
				console.log(nubCustomer);
				
				document.getElementById("rowCustomer").value = nubCustomer;
			});
		});
		
		function addCustomer()
		{
			nubCustomer++;
			if(nubCustomer == 1)
			{
				document.getElementById("tableCustomer").style.visibility = 'visible';
				document.getElementById("rowCustomer").value = nubCustomer;
			}
			else if(nubCustomer > 1)
			{
				console.log(nubCustomer);
				var newCustomerDiv = $(document.createElement('div')).attr("id", 'CustomerDiv' + nubCustomer);
				table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
				+ '	<tr bgcolor="#E8E8E8">'
				+ '		<td align="right">ลูกค้า คนที่ '+ nubCustomer +' :</td>'
				+ '		<td><input type="text" name="CustomerName'+ nubCustomer +'" id="CustomerName'+ nubCustomer +'" size="70"></td>'
				+ '	</tr>'
				+ '	</table>'
				
				newCustomerDiv.html(table);

				newCustomerDiv.appendTo("#CustomerGroup");
					
				document.getElementById("rowCustomer").value = nubCustomer;
				
				$("#CustomerName" + nubCustomer).autocomplete({
					source: "s_cus.php",
					minLength:1
				});
			}
		}
		
		function showReport()
		{
			$('#panel').empty();
			$('#panel').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังค้นหา...">');
			$("#panel").load("report_gui.php?CusID_array="+'<?php echo $CusID_array; ?>'+"&focusDate="+'<?php echo $focusDate; ?>'+"&annuities="+'<?php echo $annuities_check; ?>'+"&charges="+'<?php echo $charges_check; ?>'+"&note="+'<?php echo $noteSent; ?>');
		}
	</script>
	
</head>
<body>

<center>
<h1>รายงานติดตามชำระลูกค้ารายบุคคล(อู่)</h1>
</center>

<form name="frm1" method="post" action="frm_Index.php">
	<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<fieldset><legend><B>เลือกการติดตาม</B></legend>
					<center>
						<input type="button" value="+ เพิ่มลูกค้า" id="addCustomer"> <input type="button" value="- ลบลูกค้า" id="removeCustomer">
						<table id="tableCustomer" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
							<tr align="center" bgcolor="#E8E8E8">
								<td align="right">ลูกค้า คนที่ 1 :</td>
								<td><input type="text" name="CustomerName1" id="CustomerName1" size="70"></td>
							</tr>
						</table>
						<div id="CustomerGroup">
							<div id='CustomerDiv'>
							</div>
						</div>
						<input type="hidden" name="rowCustomer" id="rowCustomer" value="1">
						
						<table>
							<tr>
								<td align="right"><input type="checkbox" name="annuities" id="annuities" <?php echo $annuities_check; ?>></td>
								<td align="left">
									ติดตามค่างวด
									: ถึงวันที่
									<input type="textbox" name="focusDate" id="focusDate" size="15" value="<?php echo $focusDate; ?>" style="text-align:center;">
								</td>
							</tr>
							<tr>
								<td align="right"><input type="checkbox" name="charges" id="charges" <?php echo $charges_check; ?>></td>
								<td align="left">ติดตามค่าใช้จ่ายอื่นๆ</td>
							</tr>
							<tr>
								<td align="right">หมายเหตุ</td>
								<td align="left"><textarea name="note" id="note" cols="50" rows="5"><?php echo $note; ?></textarea></td>
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

<script>
	var textCusArray = '<?php echo $textCusArray; ?>';
	var valueCusArray = textCusArray.split('nextCus');
	
	document.getElementById("CustomerName1").value = '<?php echo pg_escape_string($_POST["CustomerName1"]); ?>';

	if(rowCustomer > 1)
	{
		for(var i = 2; i <= rowCustomer; i++)
		{
			addCustomer();
			document.getElementById("CustomerName"+i).value = valueCusArray[i-1];
		}
	}
</script>

</html>