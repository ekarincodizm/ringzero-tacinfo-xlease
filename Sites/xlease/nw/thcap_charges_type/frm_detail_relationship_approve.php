<?php 
session_start();  
include("../../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$id = pg_escape_string($_GET["id"]);
$view = pg_escape_string($_GET["view"]);
if($id=="")
{
	$id = pg_escape_string($_POST["tpID2"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติความสัมพันธ์ทางบัญชี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>

<script language=javascript>
function checkWHT(){
	var wht = document.getElementById('ableWHT')
	if(wht.checked==false){
		document.getElementById("curWHTRate").readOnly=true;
	} else {
		document.getElementById("curWHTRate").readOnly=false;
	}
}

function appv(no)
{
	var str_appv;
	if(no=='1'){
		str_appv='อนุมัติ';
	}
	else if(no=='0'){
		str_appv='ไม่อนุมัติ';
	}
	
	if(confirm('คุณต้องการ'+str_appv+'รายการนี้หรือไม่'))
	{
		$.post('process_approve.php',{
            id:'<?php echo $id; ?>',
            stapp:no
		},function(data){
			if(data == 1){
				alert('บันทึกรายการเรียบร้อย');
			}else{
				alert('ผิดผลาด ไม่สามารถบันทึกได้! '+data);
			}
			window.opener.location.reload();
			window.close();	
		});
	}
}
</script>
</head>
<body>

<?php
// รายการใหม่
$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay_temp\" where \"tpAutoID\" = '$id' ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name))
{
	$tpID_temp = $res_name["tpID"];
	$tpDesc_temp = $res_name["tpDesc"]; // ชื่อประเภทค่าใช้จ่าย
	$tpBasis_temp = $res_name["tpBasis"]; // บัญชีพื้นฐาน
	$tpAccrual_temp = $res_name["tpAccrual"]; // บัญชีคงค้าง
	$tpAmortize_temp = $res_name["tpAmortize"]; // บัญชีทยอยรับรู้
	
	$appvID1 = $res_name["appvID1"];
	
	// หา สมุดบัญชีพื้นฐาน
	if($tpBasis_temp != "")
	{
		$qry_accBookName = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpBasis_temp' ");
		$tpBasisID_temp = pg_result($qry_accBookName,0);
		$tpBasisName_temp = pg_result($qry_accBookName,1);
	}
	else
	{
		$tpBasisID_temp = "";
		$tpBasisName_temp = "";
	}
	
	// หา สมุดบัญชีคงค้าง
	if($tpAccrual_temp != "")
	{
		$qry_accBookName = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAccrual_temp' ");
		$tpAccrualID_temp = pg_result($qry_accBookName,0);
		$tpAccrualName_temp = pg_result($qry_accBookName,1);
	}
	else
	{
		$tpAccrualID_temp = "";
		$tpAccrualName_temp = "";
	}
	
	// หา สมุดบัญชีทยอยรับรู้
	if($tpAmortize_temp != "")
	{
		$qry_accBookName = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAmortize_temp' ");
		$tpAmortizeID_temp = pg_result($qry_accBookName,0);
		$tpAmortizeName_temp = pg_result($qry_accBookName,1);
	}
	else
	{
		$tpAmortizeID_temp = "";
		$tpAmortizeName_temp = "";
	}
}

// รายการเดิม
if($view != "v")
{ // ถ้าเป็นการอนุมัติแก้ไขรายการ ให้เปรียบเทียบข้อมูลจากตารางจริง
	$qry_name=pg_query("SELECT * FROM account.v_thcap_typepay_acc_detials where \"tpID\" = '$tpID_temp' ");
	$rows = pg_num_rows($qry_name);
	while($res_name=pg_fetch_array($qry_name))
	{
		$tpID = $res_name["tpID"];
		$tpDesc = $res_name["tpDesc"];
		$tpBasis = $res_name["tpBasis"];  //Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Basis
		$tpBasisID = $res_name["accBookID_tpBasis"];  
		$tpBasisName = $res_name["accBookName_tpBasis"];  //Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Basis
		
		
		
		$tpAccrual = $res_name["tpAccrual"];  // Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Accural
		$tpAccrualID = $res_name["accBookID_tpAccrual"];  
		$tpAccrualName = $res_name["accBookName_tpAccrual"];  
		
		$tpAmortize = $res_name["tpAmortize"];  // กรณีที่รายได้นั้นมีการทยอยรับรู้
		$tpAmortizeID = $res_name["accBookID_tpAmortize"];  
		$tpAmortizeName = $res_name["accBookName_tpAmortize"];
	}
}
elseif($view == "v")
{ // ถ้าเป็นการดูประวัติรายการ ให้เปรียบเทียบจากรายการที่อนุมัติก่อนหน้านั้น 1 รายการ
	$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay_temp\" where \"tpID\" = '$tpID_temp'
						and \"tpAutoID\" = (select max(\"tpAutoID\") from account.\"thcap_typePay_temp\" where \"tpID\" = '$tpID_temp' and \"tpAutoID\" < '$id')
						and \"appvStatus1\" = '1' and \"appvStatus2\" = '1' ");
	$rows = pg_num_rows($qry_name);
	
	if($rows == 0)
	{ // ถ้าไม่พบรายการ ให้ไปดูในตารางจริง
		$qry_name=pg_query("SSELECT * FROM account.v_thcap_typepay_acc_detials where \"tpID\" = '$tpID_temp' ");
		$rows = pg_num_rows($qry_name);
		while($res_name=pg_fetch_array($qry_name))
		{
			$tpID = $res_name["tpID"];
			$tpDesc = $res_name["tpDesc"];
			$tpBasis = $res_name["tpBasis"];  //Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Basis
			$tpBasisID = $res_name["accBookID_tpBasis"];  
			$tpBasisName = $res_name["accBookName_tpBasis"];  //Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Basis
			
			
			
			$tpAccrual = $res_name["tpAccrual"];  // Serial รหัสบัญชีของรายการรับนี้ ที่เป็น Cash Accural
			$tpAccrualID = $res_name["accBookID_tpAccrual"];  
			$tpAccrualName = $res_name["accBookName_tpAccrual"];  
			
			$tpAmortize = $res_name["tpAmortize"];  // กรณีที่รายได้นั้นมีการทยอยรับรู้
			$tpAmortizeID = $res_name["accBookID_tpAmortize"];  
			$tpAmortizeName = $res_name["accBookName_tpAmortize"];
		}
	}
	else
	{
		while($res_name=pg_fetch_array($qry_name))
		{
			$tpID_temp = $res_name["tpID"];
			$tpDesc_temp = $res_name["tpDesc"]; // ชื่อประเภทค่าใช้จ่าย
			$tpBasis_temp = $res_name["tpBasis"]; // บัญชีพื้นฐาน
			$tpAccrual_temp = $res_name["tpAccrual"]; // บัญชีคงค้าง
			$tpAmortize_temp = $res_name["tpAmortize"]; // บัญชีทยอยรับรู้
			
			// หา สมุดบัญชีพื้นฐาน
			if($tpBasis_temp != "")
			{
				$qry_accBookName = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpBasis_temp' ");
				$tpBasisID_temp = pg_result($qry_accBookName,0);
				$tpBasisName_temp = pg_result($qry_accBookName,1);
			}
			else
			{
				$tpBasisID_temp = "";
				$tpBasisName_temp = "";
			}
			
			// หา สมุดบัญชีคงค้าง
			if($tpAccrual_temp != "")
			{
				$qry_accBookName = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAccrual_temp' ");
				$tpAccrualID_temp = pg_result($qry_accBookName,0);
				$tpAccrualName_temp = pg_result($qry_accBookName,1);
			}
			else
			{
				$tpAccrualID_temp = "";
				$tpAccrualName_temp = "";
			}
			
			// หา สมุดบัญชีทยอยรับรู้
			if($tpAmortize_temp != "")
			{
				$qry_accBookName = pg_query("select \"accBookID\", \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAmortize_temp' ");
				$tpAmortizeID_temp = pg_result($qry_accBookName,0);
				$tpAmortizeName_temp = pg_result($qry_accBookName,1);
			}
			else
			{
				$tpAmortizeID_temp = "";
				$tpAmortizeName_temp = "";
			}
		}
	}
}

// ตรวจสอบว่าเป็นการเพิ่มรายการ หรือการแก้ไขรายการ
if($tpID == "")
{
	$addOrEdit = "N"; // เพิ่มรายการใหม่
}
else
{
	$addOrEdit = "U"; // แก้ไขรายการ
}
?>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center" valign="top" style="background-repeat:repeat-y">
			<div class="wrapper">
				<!-- <div align="right"><a href="frm_thcap_show.php"><img src="images/full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div> -->
				<form id="frm_1" name="frm_1" method="post" action="process_thcap_edit.php">
					<fieldset><legend><B>ความสัมพันธ์ทางบัญชี</B></legend>
						<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
							<tr align="left">
								<td width="30%"><b>รหัสประเภทค่าใช้จ่าย </b></td>
								<td width="70%" class="text_gray"><input type="text" name="tpID" size="15" value="<?php echo $tpID_temp; ?>" readonly></td>
							</tr>
							<tr align="left">
								<td><b>ชื่อประเภทค่าใช้จ่าย </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpBasis" name="tpBasis" size="60" value="<?php echo $tpDesc_temp; ?>" readonly <?php if($tpDesc_temp != $tpDesc && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpDesc\" ";} ?>></td>
							</tr>
							<tr align="left">
								<td><b>เลขที่บัญชีพื้นฐาน </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpBasis" name="tpBasis" size="15" value="<?php echo $tpBasisID_temp; ?>" readonly <?php if($tpBasisID_temp != $tpBasisID && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpBasisID\" ";} ?>></td>
							</tr>
							<tr align="left">
								<td><b>ชื่อบัญชีพื้นฐาน </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpBasis" name="tpBasis" size="60" value="<?php echo $tpBasisName_temp; ?>" readonly <?php if($tpBasisName_temp != $tpBasisName && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpBasisName\" ";} ?>></td>
							</tr>
							<tr align="left">
								<td><b>เลขที่บัญชีคงค้าง </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAccrual" name="tpAccrual" size="15" value="<?php echo $tpAccrualID_temp; ?>" readonly <?php if($tpAccrualID_temp != $tpAccrualID && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpAccrualID\" ";} ?>></td>
							</tr>
							<tr align="left">
								<td><b>ชื่อบัญชีคงค้าง </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAccrual" name="tpAccrual" size="60" value="<?php echo $tpAccrualName_temp; ?>" readonly <?php if($tpAccrualName_temp != $tpAccrualName && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpAccrualName\" ";} ?>></td>
							</tr>
							<tr align="left">
								<td><b>เลขที่บัญชีทยอยรับรู้ </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAmortize" name="tpAmortize" size="15" value="<?php echo $tpAmortizeID_temp; ?>" readonly <?php if($tpAmortizeID_temp != $tpAmortizeID && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpAmortizeID\" ";} ?>></td>
							</tr>
							<tr align="left">
								<td><b>ชื่อบัญชีทยอยรับรู้ </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAmortize" name="tpAmortize" size="60" value="<?php echo $tpAmortizeName_temp; ?>" readonly <?php if($tpAmortizeName_temp != $tpAmortizeName && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpAmortizeName\" ";} ?>></td>
							</tr>
						</table>
					</fieldset>
				</form>
			</div>
        </td>
    </tr>
</table>

</body>
</html>