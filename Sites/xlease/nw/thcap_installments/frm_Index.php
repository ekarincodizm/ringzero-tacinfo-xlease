<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["idno"]);
if($contractID == ""){
	$contractID = pg_escape_string($_POST["idno_text"]);
}


$sqlchkcon = pg_query("select \"contractID\",\"conType\" from thcap_mg_contract where \"contractID\" = '$contractID'");
if(pg_num_rows($sqlchkcon) == 0){
	$sqlchkcon = pg_query("select \"contractID\",\"conType\" from \"thcap_lease_contract\" where \"contractID\"='$contractID'");
	if(pg_num_rows($sqlchkcon)==0)
	{
		$sqlchkcon = pg_query("select \"contractID\" from thcap_contract where \"contractID\" = '$contractID'");
		if(pg_num_rows($sqlchkcon) > 0 ){	
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=../fapn_statement/frm_Index.php?idno=$contractID\">";
			exit();
		}
	}
}

$remainqry = pg_fetch_array($sqlchkcon);

$nowday = nowDate(); // วันที่ปัจจุบัน
$signDatepg=pg_escape_string($_POST["signDate"]);
if(empty($signDatepg)){
    $ssdate = nowDate();
}else{
    $ssdate=$signDatepg;
}

$id_user = $_SESSION["av_iduser"]; // id ของ user ที่กำลังใช้งานอยู่ในขณะนั้น
$add_date = nowDateTime();

// หาสิทธิ์ของ user
$searchLevel = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$id_user' ");
while($leveluser = pg_fetch_array($searchLevel))
{
	$level_user = $leveluser["emplevel"];
}
$CC = pg_escape_string($_POST["criteria"]); // Criteria ที่ใช้ค้นหาข้อมูล

$Qry_user = pg_query("select id_user from thcap_favorite where id_user = '$id_user'");
$check_user = pg_num_rows($Qry_user);
if($check_user>0){
	$favorite = "update";
} else {
	$favorite = "insert";
}

$fav_column = "TIS_Search";
if($CC==""){
	
	$qry_array = pg_query("select \"TIS_Search\"from thcap_favorite where id_user = '$id_user'");
	$already_fav = pg_num_rows($qry_array);
		if($already_fav>0){
			$res_array = pg_fetch_result($qry_array ,0);
			$qyr_Fav = pg_query("select ta_array1d_popularity('$res_array','2')");
			$res_Fav = pg_fetch_result($qyr_Fav,0);
		switch ($res_Fav){
			case 1:
				$check0 = "checked";
				break;
			case 2:
				$check1 = "checked";
				break;
			case 3:
				$check2 = "checked";
				break;
			default:
				$check0 = "checked";
				break;
			}
		}
	$check0 = "checked";
} else {
	if($CC=="1"){
		$check0 = "checked";
		SetFavoriteToTable($fav_column,$id_user,1);
	} else if($CC=="2") {
		$check1 = "checked";
		SetFavoriteToTable($fav_column,$id_user,2);
	} else {
		$check2 = "checked";
		SetFavoriteToTable($fav_column,$id_user,3);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตารางแสดงการผ่อนชำระ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function SubmitForm(){
	var criteria;
	if(document.getElementById("criteria0").checked){
		criteria = "Default";	
	} else if (document.getElementById("criteria1").checked){
		criteria = "Asset10";
	} else if (document.getElementById("criteria2").checked){
		criteria = "PrimaryCus";
	} 
	return criteria; 
}
function KeyData(){
	var Cr = SubmitForm();
	$("#idno_text").autocomplete({
        //source: "s_idno.php",
		source: "s_idall.php?criteria="+Cr,
        minLength:1
    });
}
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
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function beenpaid(){
	popU('../Payments_Other/frm_Index.php?conid1=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=800');
}
	
function chkdate()
{	
	if(document.getElementById("chk_conFreeDate").value != '')
	{	
		if(document.getElementById("signDate").value > document.getElementById("chk_conFreeDate").value)
		{
			document.getElementById("damage").disabled = true;
			document.getElementById("damage").checked = false;
		}
		else
		{
			document.getElementById("damage").disabled = false;
		}
	}
	else
	{
		document.getElementById("damage").disabled = false;
	}
}
function calc_tax() {
	popU('other_debt_inc_vat.php?contractid=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=700')
}
</script>

<script language="JavaScript">
<!--
function windowOpen() {
var
myWindow=window.open('search2.php','windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
//--></script>
    
</head>
<body onload="chkdate()">

<input type="hidden" name="test" id="test">

<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right">
		<input type="button" class="ui-button" onclick="javascript:popU('frm_Index.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1330,height=760')" style="cursor:pointer" value="เปิด (THCAP)ตารางแสดงการผ่อนชำระ เพิ่ม">
		<input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<?php
include "menu.php"; // tab menubar

if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING"){
	$linkpdf="frm_pdf_notloan.php";
}else{
	$linkpdf="frm_pdf.php";
} 

?>

<fieldset>
	<legend><B>ค้นหา</B></legend>
	<center>
	<div align="center" style="width:850px;" id="divmain">
	<form method="post" name="form1" action="frm_Index.php">
		<div style="float:center; width:850px;">
			<label><b>ค้นหาโดย:</b></label>
			<input type="radio" name="criteria" id="criteria0" value="1" onchange="SubmitForm();"<?php echo $check0; ?>>เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว 
			<input type="radio" name="criteria" id="criteria1" value="2" onchange="SubmitForm();"<?php echo $check1; ?>>เลขทะเบียนรถ, เลขตัวถัง
			<input type="radio" name="criteria" id="criteria2" value="3" onchange="SubmitForm();"<?php echo $check2; ?>>แสดงเฉพาะชื่อผู้กู้หลัก 
		<div>
		<div style="float:center; width:850px;">
			<input type="text" name="idno_text" id="idno_text" value="<?php echo $contractID; ?>" size="70" onkeyup="KeyData();" onblur="KeyData();" />
			<input type="submit" id="btnsearch" value="ค้นหา" />
			<?php 
			//ตรวจสอบว่าี้ผู้ใช้งานสามารถเห็นปุ่มรับชำระเงินได้หรือไม่ 
				$qrychk=pg_query("select \"id_menu\" from \"f_usermenu\" where id_user='$id_user' and id_menu='TM13' and status='TRUE'");
				$numchk=pg_num_rows($qrychk);
				if(($numchk>0)and($contractID!="")) {?>
					<input type="button" value="รับชำระเงิน" onclick="beenpaid();">
				<?php }?>
			<!--<input name="openPopup" type="button" id="openPopup" onClick="Javascript:windowOpen();" value="ค้นหาจากชื่อผู้กู้หลัก/ร่วม" />-->
		</div>
	</form>
		<div style="float:left; width:100%;">
		<form method="post" name="form2" action="<?php echo $linkpdf;?>" target="_blank">
			<input type="hidden" name="idno_text" value="<?php echo $contractID; ?>">
			<?php
				if($contractID != "")
				{
			?>
					<div align="left"><input type="button" id="btnprint" value="พิมพ์ PDF" onclick="javascript:popU('<?php echo $linkpdf;?>?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"><input type="button" value="พิมพ์หน้าจอ" onclick="window.print();"></div>
                   <!--<div id="divinterest"><span id="RateNow"></span></div> <!-- อัตราดอกเบี้ยปัจจุบันของสัญญา -->
					<!--<div align="right"><b><span id="InterestToDate" style="font-size:16px; color:#008800;"></span></b></div> <!-- ดอกเบี้ยถึงวันนี้ -->
			<?php
				}
			?>
		</form>
		</div>
		<div style="clear:both;"></div>
		<div id="panel" align="left" style="margin-top:10px"></div>
	</div>
	</center>
</fieldset>


<?php
if($contractID != "")
{
?>	

<div style="margin-top:0px;"><?php include('../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
<div style="margin-top:0px;"><?php include('contract_note.php'); //หมายเหตุ?></div>
<?php if($remainqry["conType"] == "FA" || $credit_type=="FACTORING"){ ?> <div style="margin-top:0px;"><?php include('../thcap/Data_fa_bill.php'); //ข้อมูล บิล?></div><?php } ?>
<?php if($credit_type=="HIRE_PURCHASE"||$credit_type=="LEASING"){ ?> <div style="margin-top:0px;"><?php include("show_group_product.php"); //รายการสินค้า?></div><?php } ?>
<div style="margin-top:0px;"><?php  include('../thcap/Data_other_debt.php'); //หนี้อื่นๆที่ค้างชำระ ?></div>

<?php if($remainqry["conType"] == "FI"){ ?>
<div style="margin-top:0px;"><?php  include('../thcap/Data_table_payment_FI.php'); //ตารางการชำระค่าเช่า ?></div>
<?php }elseif($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING" || $credit_type=="SALE_ON_CONSIGNMENT"){ ?>
<div style="margin-top:0px;"><?php  include('../thcap/Data_table_paymentlease.php'); //ตารางการชำระค่าเช่า ?></div>
<?php
}else{ ?> <div style="margin-top:0px;"><?php  include('../thcap/Data_table_payment.php'); //ตารางแสดงการจ่าย ?></div> <?php } ?>


<?php
if($level_user <= 3)
{
	if($credit_type!="HIRE_PURCHASE" && $credit_type!="LEASING" && $credit_type!="GUARANTEED_INVESTMENT" && $credit_type!="FACTORING" && $credit_type!="SALE_ON_CONSIGNMENT")
	{
?>
<fieldset>
	<legend><B>ยอดรวมรับชำระ</B></legend>
	<div align="center">
		<div id="panel6" align="center" style="margin-top:10px">
		<?php
			if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
			{
		?>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
				<tr align="center" bgcolor="#79BCFF">
					<th>ยอดรวมรับชำระทั้งสิ้น</th><th>ยอดรวมรับชำระที่เป็นดอกเบี้ย</th><th>ยอดรวมรับชำระที่เป็นเงินต้น</th>
				</tr>
				<tr class="even" align="right">
					<td><?php echo number_format($sumreceiveAmount,2); ?> บาท</td>
					<td><?php echo number_format($sumreceiveInterest,2); ?> บาท</td>
					<td><?php echo number_format($sumreceivePriciple,2); ?> บาท</td>
				</tr>
			</table>
		<?php
			}
		?>
		</div>
	</div>
</fieldset>
<?php
	}
}
if($credit_type!="HIRE_PURCHASE" && $credit_type!="LEASING" && $credit_type!="GUARANTEED_INVESTMENT" && $credit_type!="FACTORING" && $credit_type!="SALE_ON_CONSIGNMENT")
{
?>



<fieldset>
	<legend><B>คำนวนยอดปิดบัญชี-เงินกู้</B></legend>
	
	<div align="center">
		<div id="panel5" align="left" style="margin-top:10px">	
			<?php
			if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
			{
				// ==================================================================================
				// นำข้อมูลต่างๆของสัญญาที่เป็นการตั้งค่าัปัจจุบัน
				// ==================================================================================
				
				$sql_chkdate = pg_query("select \"conLoanAmt\", \"conClosedFee\", \"conFreeDate\" from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
				while($resultchkdate=pg_fetch_array($sql_chkdate))
				{
					$chk_conLoanAmt = $resultchkdate["conLoanAmt"]; // เงินต้นเริ่มแรก
					$chk_conClosedFee = $resultchkdate["conClosedFee"]; // ค่าธรรมเนียมปิดบัญชี
					$chk_conFreeDate = $resultchkdate["conFreeDate"]; // วันที่ยกเว้นค่าธรรมเนียมปิดบัญชี
				}
				
				// ==================================================================================
				// หาเงินต้น ดอกเบี้ยคงเหลือ
				// ==================================================================================
				
				$sql_money_one = pg_query("	SELECT MAX(\"serial\") as \"maxserial\" 
											FROM public.\"thcap_temp_int_201201\" 
											WHERE 
												\"contractID\" = '$contractID' AND
												\"isReceiveReal\" != '0'
				");
				while($resultone=pg_fetch_array($sql_money_one))
				{
					$maxserial = $resultone["maxserial"];
				}
				
				if($maxserial != "")
				{
					$sql_money_two = pg_query("	SELECT \"LeftPrinciple\", \"LeftInterest\"
												FROM
													public.\"thcap_temp_int_201201\" 
												WHERE
													\"serial\" = '$maxserial'
					");
					while($resulttwo=pg_fetch_array($sql_money_two))
					{
						$LastLeftPrinciple = $resulttwo["LeftPrinciple"];
						$Last_LeftInterest = $resulttwo["LeftInterest"];
							
					}	
				} else{
					$sql_money_three = pg_query("select \"conLoanAmt\" from public.\"thcap_contract\" where \"contractID\" = '$contractID' ");
					while($resultthree=pg_fetch_array($sql_money_three))
					{
						$LastLeftPrinciple = $resultthree["conLoanAmt"];
						$Last_LeftInterest = 0;
					}
						
				}
				
				// ==================================================================================
				// รับค่าที่ POST มาต่างๆ
				// ==================================================================================
				
				$chktest = pg_escape_string($_POST["chkcloseaccount"]); // ถ้าเป็น 1 แสดงว่ามีการคลิกปุ่มคำนวน
				$signDate = pg_escape_string($_POST["signDate"]); //วันที่เลือก
				$damage = pg_escape_string($_POST["damage"]); //รวมค่าเสียหายปิดบัญชีก่อนกำหนด ถ้าเลือกจะเป็น on
				$costclose = pg_escape_string($_POST["costclose"]); //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on
				$damageCHK = pg_escape_string($_POST["damage"]); //รวมค่าเสียหายปิดบัญชีก่อนกำหนด ถ้าเลือกจะเป็น on ใช้ในการเช็ค checkbox
				$costcloseCHK = pg_escape_string($_POST["costclose"]); //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on ใช้ในการเช็ค checkbox
				
				if($costclose == "on"){$costclose = 2000;}else{$costclose = 0;}
				
				if($damage == "on")
				{
					$damage = $chk_conLoanAmt * $chk_conClosedFee * (1/100);
				}
				else
				{
					$damage = 0;
				}
				
				if($chktest == "1")
				{
					if($signDate." 23:59:59" >= $maxdate)
					{
						$inter=pg_query("SELECT \"thcap_cal_InterestToDateFromLastPay\"('$contractID','$signDate')");
						$resin=pg_fetch_array($inter);
						list($money_function)=$resin;
						
						// ACTIONLOG
						$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) คิดยอดปิดบัญชี', '$add_date')");
						// ACTIONLOG
					}
				}
				else
				{
					$money_function = 0;
				}
				
				$money_function = $money_function + $Last_LeftInterest;
				
				// ==================================================================================
				// หายอดหนี้ค้างชำระอื่นๆ
				// ==================================================================================
				$sql_other = pg_query("select \"tpID\" from account.\"thcap_typePay\" where \"isSubsti\" <> '1' ");
				while($resultother=pg_fetch_array($sql_other))
				{
					$tpID_other = $resultother["tpID"];
					
					$sql_Sother = pg_query("select sum(\"typePayAmt\") as \"sumone\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$contractID' and \"typePayID\" = '$tpID_other' and \"debtStatus\" = '1' ");
					while($resultSother=pg_fetch_array($sql_Sother))
					{
						 $sumone = $resultSother["sumone"];
						
						$plusone += $sumone; // ยอดหนี้ค้างชำระอื่นๆ
					}
					$sumone = 0;
				}
				
				// ==================================================================================
				// หายอดรับจ่ายแทนค่าประกันภัย-อื่นๆ
				// ==================================================================================
				$sql_other2 = pg_query("select \"tpID\" from account.\"thcap_typePay\" where \"isSubsti\" = '1' ");
				while($resultother2=pg_fetch_array($sql_other2))
				{
					 $tpID_other2 = $resultother2["tpID"];
					
					$sql_Sother2 = pg_query("select sum(\"typePayAmt\") as \"sumone\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$contractID' and \"typePayID\" = '$tpID_other2' and \"debtStatus\" = '1' ");
					while($resultSother2=pg_fetch_array($sql_Sother2))
					{
						$sumone = $resultSother2["sumone"];
						
						$plustwo += $sumone; // ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ
					}
					$sumone = 0;
				}
				
				if($chktest == 1) // ถ้าเป็น 1 แสดงว่ามีการคลิกปุ่มคำนวน
				{
					// หาค่าเบี้ยปรับ ณ วันที่เลือก
					if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING")
					{
						$qr_get_cloce_fine = pg_query("select \"thcap_get_lease_fine\"('$contractID','$signDate')");
						$cloce_fine = pg_fetch_result($qr_get_cloce_fine,0);
					}
					else
					{
						$qr_get_cloce_fine = pg_query("select \"thcap_get_loan_fine\"('$contractID','$signDate')");
						$cloce_fine = pg_fetch_result($qr_get_cloce_fine,0);
					}
					if($cloce_fine == ""){$cloce_fine = "0.00";}
				}
				
				// ==================================================================================
				// ยอดรวมรับชำระ = เงินต้นคงเหลือ + ค่าปิดบัญชี + ค่าเสียหาย + ดอกเบี้ย + ค่าอื่นๆ + รับแทนอื่นๆ + เบี้ยปรับ
				// ==================================================================================
				$sum_close_account = $LastLeftPrinciple + $costclose + $damage + $money_function + $plusone + $plustwo + $cloce_fine; // รวมเงิน
				
				if($chktest == 1) // ถ้าเป็น 1 แสดงว่ามีการคลิกปุ่มคำนวน
				{
					if($signDate." 23:59:59" < $maxdate)
					{
						$lastmoney = "วันที่ปิดบัญชี จะต้องมากกว่าหรือเท่ากับ วันที่จ่ายล่าสุดเท่านั้น";
					}
					else
					{
						$lastmoney = "ยอดปิดบัญชี ณ วันที่ $signDate คือ ".number_format($sum_close_account,2)." บาท";
					}
				}
				
			?>
				<form name="frm_fuc2" method="post" action="frm_Index.php?idno=<?php echo "$contractID"; ?>">
					<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
						<tr bgcolor="#E6FFE6" align="left" valign="top">
							<td align="left" valign="middle" colspan="5">
								<b>คำนวณยอดปิดบัญชี ณ วันที่</b>
								
								<input type="hidden" id="chk_conFreeDate" value="<?php echo $chk_conFreeDate; ?>">
								
								<input type="text" size="12" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo $ssdate; ?>" onchange="chkdate()"/>
								<input type="checkbox" name="damage" id="damage" <?php if($damageCHK == "on"){echo "checked=\"checked\"";} ?> >รวมค่าเสียหายปิดบัญชีก่อนกำหนด
								<input type="checkbox" name="costclose" id="costclose" <?php if($costcloseCHK == "on"){echo "checked=\"checked\"";} ?> >รวมค่าบริการปิดบัญชี
								<input name="btnButton" id="btnButton" type="button" value="คำนวณ" onclick="document.frm_fuc2.submit()" />
								<input type="hidden" name="chkcloseaccount" value="1">
							</td>
						</tr>
						<tr bgcolor="#E6FFE6" align="left" valign="top">
							<td align="left" valign="middle" colspan="5">
								<?php if($chktest == 1)
								{
									echo "<br>";
									echo $lastmoney;
									$Installment = $LastLeftPrinciple + $money_function;
									$otherpay = $damage + $plusone + $plustwo + $costclose + $cloce_fine;
									if($signDate." 23:59:59" >= $maxdate)
									{
										echo "<br><br>";
										echo "-==========  โดยมาจาก ============-";
										echo "<br>";
										echo "<br>";
										echo "___ <b>ค่างวด (  รวม: ".number_format($Installment,2)." )</b>__________________________________";
										echo "<br>";
										echo "•ยอดเงินต้น : ".number_format($LastLeftPrinciple,2)." บาท";
										echo "<br>";
										echo "•ยอดดอกเบี้ย : ".number_format($money_function,2)." บาท";
										echo "<br>";	
										echo "<br>";										
										echo "___ <b>ค่าอื่นๆ (  รวม: ".number_format($otherpay,2)." )</b> ___________________________________";
										echo "<br>";										
										echo "•ยอดเงินเสียหายปิดบัญชีก่อนกำหนด : ".number_format($damage,2)." บาท";
										echo "<br>";
										echo "•ยอดหนี้ค้างชำระอื่นๆ : ".number_format($plusone,2)." บาท";
										echo "<br>";
										echo "•ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ : ".number_format($plustwo,2)." บาท";
										echo "<br>";
										echo "•ยอดเงินบริการปิดบัญชี : ".number_format($costclose,2)." บาท";
										echo "<br>";
										
										if($cloce_fine != "" && $cloce_fine > 0.00)
										{
											echo "•ยอดเบี้ยปรับ : ".number_format($cloce_fine,2)." บาท";
											echo "<br>";
										}
										
										?>
											<br>
											<input type="button" id="btnprint" value="พิมพ์" onclick="javascript:popU('frm_pdf.php?idno=<?php echo "$contractID"; ?>&signDate=<?php echo $signDate; ?>&damage=<?php echo $damage; ?>&costclose=<?php echo $costclose; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
										<?php
									}
								}
								?>
							</td>
						</tr>
					</table>
				</form>
			<?php
			}
			?>
		</div>
	</div>
</fieldset>

<?php
}
	if($credit_type=='GUARANTEED_INVESTMENT')
	{
		include "frm_calclose_guaranteed.php";
	}
	elseif($credit_type == "HIRE_PURCHASE" || $credit_type == "LEASING")
	{
		include "frm_calclose_hirepurchase_leasing.php";
	}
}
?>

        </td>
    </tr>
</table>

</body>

<script>
	// $("#RateNow").html($("#phpNowRate").val()+' %');
	// $("#InterestToDate").html('มีดอกเบี้ยถึงวันนี้: '+$("#InterestNowDay").val()+' บาท');
</script>

</html>