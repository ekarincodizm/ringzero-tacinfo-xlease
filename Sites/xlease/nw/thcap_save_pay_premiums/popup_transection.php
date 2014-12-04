<?php
include("../../config/config.php");

$debtID = pg_escape_string($_GET["debtID"]);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>




<script>
	function check_num(e)
	{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
		var key;
		if(window.event)
		{
			key = window.event.keyCode; // IE
			if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
				&& key != 43 && key != 44 && key != 45 && key != 47)
			{
				// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
			}
			else
			{
				window.event.returnValue = false;
			}
		}
		else
		{
			key = e.which; // Firefox       
			if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
				&& key != 43 && key != 44 && key != 45 && key != 47)
			{
				// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
			}
			else
			{
				key = e.preventDefault();
			}
		}
	}
	
	function validate() 
	{
		var theMessage = "Please complete the following: \n-----------------------------------\n";
		var noErrors = theMessage
		
		if (document.frm1.policyNo.value=="") {
			theMessage = theMessage + "\n ->  กรุณาระบุ กรมธรรม์เลขที่";		
		}
		
		if (document.frm1.insurer_id.value=="") {
			theMessage = theMessage + "\n ->  กรุณาเลือก บริษัทประกันภัย";		
		}
		
		if (document.frm1.payAmt.value=="") {
			theMessage = theMessage + "\n ->  กรุณาระบุ จำนวนเงินที่ชำระนายหน้า";		
		}
		
		// If no errors, submit the form
		if (theMessage == noErrors) {
			return true;
		}
		else
		{
			// If errors were found, show alert message
			alert(theMessage);
			return false;
		}
	}
		
</script>

<form name="frm1" method="post" action="process_save_transection.php">
	<div style="width:550px; height:auto; margin-left:auto; margin-right:auto;">
		<div id="warppage" style="width:550px; height:auto;">
			<div class="style1" align="center" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><b>(THCAP) บันทึกจ่ายเบี้ยประกันภัย</b><hr/></div>
			<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
				<?php
				$qry_fr = pg_query("
									SELECT
										a.\"contractID\",
										b.\"tpDesc\",
										a.\"typePayRefValue\",
										a.\"typePayAmt\"
									FROM
										\"thcap_temp_otherpay_debt\" a,
										account.\"thcap_typePay\" b
									WHERE
										\"debtID\" = '$debtID' AND
										a.\"typePayID\" = b.\"tpID\"
								");
				$nub=pg_num_rows($qry_fr);
				$contractID = pg_fetch_result($qry_fr,0); // เลขที่สัญญา
				$tpDesc = pg_fetch_result($qry_fr,1); // รายละเอียดค่าใช้จ่าย
				$typePayRefValue = pg_fetch_result($qry_fr,2); // ค่าอ้างอิงของค่าใช้จ่ายนั้นๆ
				$typePayAmt = pg_fetch_result($qry_fr,3); // จำนวนเงิน
				
				if($typePayAmt != "")
				{
					$typePayAmt_show = number_format($typePayAmt,2);
				}
				else
				{
					$typePayAmt_show = "";
				}
				?>
				
				<table>
					<tr>
						<td align="right"><b>เลขที่สัญญา : </b></td>
						<td align="left"><?php echo $contractID; ?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>รายละเอียดค่าใช้จ่าย : </b></td>
						<td align="left"><?php echo $tpDesc; ?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>ค่าอ้างอิงของค่าใช้จ่าย : </b></td>
						<td align="left"><?php echo $typePayRefValue; ?></td>
					</tr>
					<tr>
						<td align="right"><b>กรมธรรม์เลขที่ : </b></td>
						<td align="left"><input type="textbox" name="policyNo" /></td>
					</tr>
					<tr>
						<td align="right"><b>บริษัทประกันภัย : </b></td>
						<td align="left">
							<select name="insurer_id">
								<option value="">- - เลือกบริษัทประกันภัย - -</option>
								<?php
								$qry_insurer = pg_query("SELECT
															\"insurer_id\",
															\"insurer_name\"
														FROM
															\"insurer\"
														WHERE
															\"insurer_active\" = TRUE
														ORDER BY
															\"insurer_name\" ");
								while($res_insurer = pg_fetch_array($qry_insurer))
								{
									$insurer_id = $res_insurer["insurer_id"]; // รหัสบริษัทประกันภัย
									$insurer_name = $res_insurer["insurer_name"]; // ชื่อบริษัทประกันภัย
									
									echo "<option value=\"$insurer_id\">$insurer_name</option>";
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><b>ยอดหนี้ : </b></td>
						<td align="left"><?php echo $typePayAmt_show; ?></td>
					</tr>
					<tr>
						<td align="right"><b>จำนวนเงินที่ชำระนายหน้า : </b></td>
						<td align="left"><input type="textbox" name="payAmt" onkeypress="check_num(event);" /></td>
					</tr>
					<tr>
						<td align="right"><b>วันที่ชำระเงินนายหน้า : </b></td>
						<td align="left"><input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15"></td>
					</tr>
					
				</table>
				<br><br>
				<center>
					<input type="hidden" name="debtID" value="<?php echo $debtID; ?>" />
					<input type="submit" value="บันทึก" oNclick="return validate()" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" value="ยกเลิก / ปิด" onclick="window.close();">
				</center>
			</div>
		</div>
	</div>
</form>