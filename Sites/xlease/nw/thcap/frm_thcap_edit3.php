<?php 
session_start();  
include("../../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$id = pg_escape_string($_GET["id"]);
if($id=="")
{
	$id = pg_escape_string($_POST["tpID2"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แก้ไขความสัมพันธ์ทางบัญชี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language=javascript>
	$(document).ready(function(){
		$("#tpBasis").autocomplete({
			source: "s_account.php",
			minLength:1
		});
		
		$("#tpAccrual").autocomplete({
			source: "s_account.php",
			minLength:1
		});
		
		$("#tpAmortize").autocomplete({
			source: "s_account.php",
			minLength:1
		});
	});

	function validate()
	{
		var theMessage = "Please complete the following: \n-----------------------------------\n";
		var noErrors = theMessage

		if(document.frm_1.tpCompanyID.value==""){
			theMessage = theMessage + "\n -->  กรุณากรอก รหัสประเภทบริษัท";
		}

		if (theMessage == noErrors) {
			return true;
		}else{
			alert(theMessage);
			return false;
		}
	}
</script>
</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
        <td align="center" valign="top" style="background-repeat:repeat-y">

			<!-- <div class="header"><h1>(THCAP) จัดการประเภทค่าใช้จ่าย</h1></div>  -->

			<div class="wrapper">
				<!-- <div align="right"><a href="frm_thcap_show.php"><img src="images/full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div> -->
				<form id="frm_3" name="frm_3" method="post" action="process_thcap_edit_acc.php">
					<fieldset><legend><B>แก้ไขความสัมพันธ์ทางบัญชี</B></legend>
						<?php
						$qry_name=pg_query("SELECT * FROM account.v_thcap_typepay_acc_detials where \"tpID\" = '$id' ");
						$rows = pg_num_rows($qry_name);
						while($res_name=pg_fetch_array($qry_name))
						{
							$tpID = $res_name["tpID"];
							$tpBasis = $res_name["tpBasis"];  //Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Basis
							$accBookID_tpBasis = $res_name["accBookID_tpBasis"];  
							$tpBasis_txt = $res_name["accBookName_tpBasis"];  //Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Basis
							
							$tpAccrual = $res_name["tpAccrual"];  // Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Accural
							$accBookID_tpAccrual = $res_name["accBookID_tpAccrual"];  
							$tpAccrual_txt = $res_name["accBookName_tpAccrual"];  
							
							$tpAmortize = $res_name["tpAmortize"];  // กรณีที่รายได้นั้นมีการทยอยรับรู้
							$accBookID_tpAmortize = $res_name["accBookID_tpAmortize"];  
							$tpAmortize_txt = $res_name["accBookName_tpAmortize"];  
							
							if($tpBasis != ""){$tpBasis_fullText = "$tpBasis#$accBookID_tpBasis#$tpBasis_txt";}
							if($tpAccrual != ""){$tpAccrual_fullText = "$tpAccrual#$accBookID_tpAccrual#$tpAccrual_txt";}
							if($tpAmortize != ""){$tpAmortize_fullText = "$tpAmortize#$accBookID_tpAmortize#$tpAmortize_txt";}
						}
						
						// ตรวจสอบว่าอยู่ระหว่างรออนุมัติหรือไม่
						$qry_chk_row = pg_query("select * from account.\"thcap_typePay_acc_temp\" where \"tpID\" = '$tpID' and (\"appvStatus1\" = '9' or \"appvStatus2\" = '9') and \"appvStatus1\" <> '0' and \"appvStatus2\" <> '0' ");
						$chk_row = pg_num_rows($qry_chk_row);
						?>
						<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
							<tr align="left">
								<td width="30%"><b>รหัสประเภทค่าใช้จ่าย <font color="red">*</font></b></td>
								<td width="70%" class="text_gray"><input type="text" name="tpID" size="28" value="<?php echo $tpID; ?>" readonly></td>
							</tr>
							<tr align="left">
								<td><b>บัญชีพื้นฐาน </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpBasis" name="tpBasis" size="60" value="<?php echo $tpBasis_fullText; ?>"></td>
							</tr>
							<tr align="left">
								<td><b>บัญชีคงค้าง </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAccrual" name="tpAccrual" size="60" value="<?php echo $tpAccrual_fullText; ?>"></td>
							</tr>
							<tr align="left">
								<td><b>บัญชีทยอยรับรู้ </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAmortize" name="tpAmortize" size="60" value="<?php echo $tpAmortize_fullText; ?>"></td>
							</tr>
							<!-- จบเพิ่มเติม By Narm-->
						</table>
					</fieldset>
					<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
					   <tr align="center">
						  <td><br><input type="submit" name="submit" value="บันทึก" onclick="return validate()" <?php if($chk_row > 0){echo "disabled title=\"อยู่ระหว่างรออนุมัติ\" ";} ?>></td>
						  <td><input type="button" name="back" value="กลับ" onClick="window.location='frm_thcap_show.php'"></td>
					   </tr>
					</table>
				</form>
			</div>
        </td>
    </tr>
</table>

</body>
</html>