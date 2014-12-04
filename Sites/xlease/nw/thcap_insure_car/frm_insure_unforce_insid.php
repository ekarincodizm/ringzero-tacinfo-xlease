<?php
include("../../config/config.php");

$UnforceID = pg_escape_string($_GET["UnforceID"]);
?> 
<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: '../thcap/images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
function validate(){

    var theMessage = "";
    var noErrors = theMessage;

    if (document.insureforce.insid.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเลขกรมธรรม์";       
    }
	
	if (document.insureforce.datepicker.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนวันที่รับกรมธรรม์";       
    }
	
    if (document.insureforce.netpremium.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเบี้ยสุทธิ";       
    }

    // If no errors, submit the form
    if (theMessage == noErrors) {
        return true;
    } else {
        // If errors were found, show alert message
        alert(theMessage);
        return false;
    }
}
</script>	
<form name="insureforce" method="post" action="frm_insure_unforce_insids.php" onsubmit="return validate(this)">
<table width="690" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td></td>
</tr>
<tr>
	<td align="center" valign="top">
	<div class="wrapper">
	<?php
	$qry_in=pg_query("
						SELECT
							a.*,
							CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" ELSE d.\"frame_no\" END AS \"C_CARNUM\",
							CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" ELSE d.\"regiser_no\" END AS \"C_REGIS\",
							f.\"full_name\"
						FROM
							\"insure\".\"thcap_InsureUnforce\" a
						LEFT JOIN
							\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
						LEFT JOIN
							\"thcap_asset_biz_detail_10\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
						LEFT JOIN
							\"thcap_asset_biz_detail_car\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
						LEFT JOIN
							\"thcap_ContactCus\" e ON a.\"contractID\" = e.\"contractID\" AND e.\"CusState\" = '0'
						LEFT JOIN
							\"VSearchCusCorp\" f ON e.\"CusID\" = f.\"CusID\"
						WHERE
							a.\"UnforceID\" = '$UnforceID'
					");
	if($res_in=pg_fetch_array($qry_in)){
		$UnforceID = $res_in["UnforceID"];
		$contractID = $res_in["contractID"];
		$InsID = $res_in["InsID"];
		$TempInsID = $res_in["TempInsID"];      
		$Company = $res_in["Company"];
		$StartDate = $res_in["StartDate"];
		$EndDate = $res_in["EndDate"];
		$Code = $res_in["Code"];
		$Kind = $res_in["Kind"];
		$Invest = $res_in["Invest"];
		$Premium = $res_in["Premium"]; 
		$Discount = $res_in["Discount"];
		$CollectCus = $res_in["CollectCus"];
		$InsUser = $res_in["InsUser"];
		$Discount = $res_in["Discount"];
		$NetPremium = $res_in["NetPremium"];
		$InsID = $res_in["InsID"];
		$InsDate = $res_in["InsDate"];
		$car_num = $res_in["C_CARNUM"];
		$c_regis = $res_in["C_REGIS"];
		$full_name = $res_in["full_name"]; 
		
		if($car_num == ""){$car_num = "-";}
		if($c_regis == ""){$c_regis = "-";}
		if($full_name == ""){$full_name = "-";}
	}else{
		$error = 1;
	}
	?>
	<fieldset><legend><B>ตรวจรับกรมธรรม์</B></legend>
	<?php
	if($error == 1){
		echo "<br>ไม่พบข้อมูล<br><br>";
	}else{
	?>
		<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="left">
			<tr align="left">
				<td width="20%"><b>รหัสประกัน</b></td>
				<td width="80%" colspan="3" class="text_gray"><?php echo $UnforceID; ?></td>
			</tr>
			<tr align="left">
				<td width="20%"><b>ชื่อ</b></td>
				<td width="80%" colspan="3" class="text_gray"><?php echo $full_name." (".$contractID.")" ?>
					<input type="hidden" name="UnforceID" value="<?php echo "$UnforceID"; ?>"> 
				</td>
			</tr>
			<tr align="left">
			  <td><b>เลขถัง</b></td>
			  <td colspan="3" class="text_gray"><?php echo $car_num; ?></td>
			</tr>
			<tr align="left">
				<td><b>ทะเบียนรถ</b></td>
				<td colspan="3" class="text_gray"><?php echo $c_regis; ?></td>
			</tr>
			<tr align="left">
				<td><b>บริษัทประกัน</b></td>
				<td colspan="3" class="text_gray"><?php echo $Company; ?></td>
			</tr>
			<tr align="left">
				<td><b>รหัสประเภทรถ</b></td>
				<td colspan="3" class="text_gray"><?php echo $Code; ?></td>
			</tr>
			<tr align="left">
				<td><b>ประเภทประกัน</b></td>
				<td colspan="3" class="text_gray"><?php echo $Kind; ?></td>
			</tr>
			<tr align="left">
				<td><b>วันที่เริ่ม</b></td>
				<td colspan="3" class="text_gray"><?php echo $StartDate; ?></td>
			</tr>
			<tr align="left">
				<td><b>วันทีสิ้นสุด</b></td>
				<td colspan="3" class="text_gray"><?php echo $EndDate; ?></td>
			</tr>
			<tr align="left">
				<td><b>ทุนประกัน</b></td>
				<td colspan="3" class="text_gray"><?php echo "$Invest"; ?> บาท.</td>
			</tr>
			<tr align="left">
				<td><b>ค่าเบี้ยประกัน</b></td>
				<td colspan="3" class="text_gray"><?php echo $Premium; ?> บาท.</td>
			</tr>
			<tr align="left">
				<td><b>ส่วนลด</b></td>
				<td colspan="3" class="text_gray"><?php echo $Discount; ?> บาท.</td>
			</tr>
			<tr align="left">
				<td><b>เบี้ยที่เก็บลูกค้า</b></td>
				<td colspan="3" class="text_gray"><?php echo $CollectCus; ?> บาท.</td>
			</tr>
			<tr align="left">
				<td><b>เลขรับแจ้ง</b></td>
				<td colspan="3" class="text_gray"><?php echo $TempInsID; ?></td>
			</tr>
			<tr align="left">
				<td><b>ผู้รับแจ้ง</b></td>
				<td colspan="3" class="text_gray"><?php echo $InsUser; ?></td>
			</tr>
			<tr align="left">
				<td><b>เลขกรมธรรม์</b></td>
				<td colspan="3"><input type="text" id="insid" name="insid" size="50" value="<?php echo $InsID;?>"></td>
			</tr>
			<tr align="left">
				<td><b>วันที่รับกรมธรรม์</b></td>
				<?php
				if($InsDate != ""){
					$insdate = $InsDate;
				}else{
					$insdate = nowDate();//ดึง วันที่จาก server
				}
				?>
				<td colspan="3"><input type="text" id="datepicker" name="datepicker" value="<?php echo $insdate; ?>" size="15"></td>
			</tr>
			<tr align="left">
				<td><b>เบี้ยสุทธิ</b></td>
				<td colspan="3" class="text_gray"><input type="text" id="netpremium" name="netpremium" size="50" value="<?php echo $NetPremium;?>"> บาท.</td>
			</tr>
		</table>
	</fieldset>
	<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
	   <tr align="center">
		  <td><br><input type="submit" name="submit" value="   บันทึก   " style="cursor:pointer;" /></td>
	   </tr>
	</table>
	<?php } ?>
	</div>
	</td>
</tr>
</table>
</form>
