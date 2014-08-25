<?php
include("../../config/config.php");
include("../../GenCusID.php");

$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server 
$click = $_POST["click"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ผูกสัญญาเงินกู้ชั่วคราว</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act_index.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tableSort.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#main").autocomplete({
		source: "listcus_main.php",
		minLength:1
	});
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function sort_table(tbid,col){
	$('#'+tbid).sortTable({
		onCol: col,
		keepRelationships: true
	}); 
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage;
	
	var type = $('#contype').val();
	var creditType = document.getElementById('type_'+type).value;
	
	if($('#chkbx_new_cus').is(':checked')==true)
	{
		var fname = $('#new_cus_fname').val();
		var lname = $('#new_cus_lname').val();
		var id_card = $('#new_cus_idcard').val();
		var pname = $('#new_cus_pname').val();
		
		if(pname=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุ คำนำหน้าชื่อลูกค้า";
		}
		if(fname=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุ ชื่อลูกค้า";
		}
		if(lname=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุ นามสกุลลูกค้า";
		}
		if(id_card=='')
		{
			theMessage = theMessage + "\n -->  กรุณาระบุ บัตรประชาชน";
		}
		else if(check_card()==false)
		{
			theMessage = theMessage + "\n -->  หมายเลขบัตรประชาชนไม่ถูกต้อง";
		}
		if (document.frm1.contype.value=="") {
		theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสินเชื่อ";
		}
		
		if (document.frm1.conCompany.value=="") {
		theMessage = theMessage + "\n -->  กรุณาเลือก บริษัท";
		}
		
		if(creditType == 'HIRE_PURCHASE' || creditType == 'LEASING')
		{
			if(document.getElementById('selectSubtype').value == '')
			{
				theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสัญญาย่อย";
			}
		}
		
		if (theMessage == noErrors)
		{
			return true;
		}
		else
		{
			alert(theMessage);
			return false;
		}
	}
	else
	{
		if (document.frm1.main.value=="") {
		theMessage = theMessage + "\n -->  กรุณาระบุ ผู้กู้หลัก";
		}
		
		if (document.frm1.contype.value=="") {
		theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสินเชื่อ";
		}
		
		if (document.frm1.conCompany.value=="") {
		theMessage = theMessage + "\n -->  กรุณาเลือก บริษัท";
		}
		
		if(creditType == 'HIRE_PURCHASE' || creditType == 'LEASING')
		{
			if(document.getElementById('selectSubtype').value == '')
			{
				theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสัญญาย่อย";
			}
		}
		
		if (theMessage == noErrors){
			return true;
		}
		else
		{
			// If errors were found, show alert message
			alert(theMessage);
			return false;
		}
	}
}
function show_add_new_cus(){
	if($('#chkbx_new_cus').is(':checked')==true)
	{
		$('.add_new_cus').show();
		$('#main').val('');
		$('#old_cus').hide();
		$('#cus_type').val('new');
	}
	else
	{
		$('.add_new_cus').find('input[type="text"]').val('');
		$('.add_new_cus').hide();
		$('#old_cus').show();
		$('#cus_type').val('old');
	}
}
function check_card(){
	var data = $('#new_cus_idcard').val();
	if($.isNumeric(data)==true)
	{
		if(data.length==13)
		{
			var digit = data.split('');
			var i = 0;
			var m = 13;
			var sum = 0;
			while(i<12)
			{	
				var s = digit[i]*m;
				sum = sum+s;
				i++;
				m--;
				
			}
			var chk_digit = 11-(sum%11);
			var digitchk = String(chk_digit);
			var digitchk_last = digitchk.substring(digitchk.length-1);
			if(digit[12]==digitchk_last)
			{
				return true;
			}
			else
			{
				alert('เลขบัตรประชาชนไม่ถูกตามหลักการตรวจสอบ เลขบัตรประชาชนไทย !');
				return false;
			}
		}
		else
		{
			alert('เลขบัตรประชาชนไม่ครบ 13 หลัก!');
			return false;
		}
	}
	else
	{
			alert('เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลักเท่านั้น!');
			return false;
	}
}
function show_by_type(){
	var type = $('#contype').val();
	var creditType = document.getElementById('type_'+type).value;
	
	if(type!='')
	{
		if(type!='BH')
		{
			$('#new_cus').hide();
			$('#new_cus_note').hide();
			$('#old_cus').show();
		}
		else
		{
			$('#old_cus').show();
			$('#new_cus').show();
			$('#new_cus_note').show();
		}
		
		document.getElementById('plaseSelectSubtype').selected = true;
		
		if(creditType == 'HIRE_PURCHASE' || creditType == 'LEASING')
		{
			$('#subtype').show();
		}
		else
		{
			$('#subtype').hide();
		}
	}
	else
	{
		$('#new_cus').hide();
		$('#new_cus_note').hide();
		$('#old_cus').hide();
		$('#subtype').hide();
		document.getElementById('plaseSelectSubtype').selected = true;
	}
}
</script>
	
</head>
<body>

<center>
<h2>(THCAP) ผูกสัญญาเงินกู้ชั่วคราว</h2>
</center>

<?php
//--- ตรวจสอบก่อนว่ามีลูกค้าคนนั้นหรือไม่
$click = $_POST["click"];
$contype = $_POST["contype"]; // ประเภทสินเชื่อ
$conCompany = $_POST["conCompany"]; // บริษัท
$selectSubtype = $_POST["selectSubtype"]; // ประเภทสัญญาย่อย
	
$cus_type = $_POST['cus_type'];
if($cus_type!="")
{
	if($cus_type=="old")
	{
	
		$term = trim($_POST['main']);
		list($CusID,$nname) = explode('#',$term);
		
		$qryCusID = pg_query("select \"CusID\" from \"VSearchCusCorp\" WHERE \"CusID\" = '$CusID' ");
		$rowCusID = pg_num_rows($qryCusID);
		
		if($click == "yes")
		{
			if($rowCusID == 0)
			{
				echo "<center><font color=\"#FF0000\"><b>ไม่พบชื่อลูกค้าในระบบ กรุณาทำรายการใหม่!!</b></font></center><br>";
			}
			else
			{
				// เปลี่ยน เครื่องหมาย # เป็นตัวหนังสือตามการกำหนดก่อนส่งข้อมูลแบบ GET ไป เนื่องจากการส่งข้อมูลแบบ GET จะมีปัญญากับ เครื่องหมาย #
				$term = str_replace("#","ThaiaceReplaceSharp",$term);
				?>
					<meta http-equiv='refresh' content='0; URL=chkTypeForSent.php?contype=<?php echo $contype; ?>&conCompany=<?php echo $conCompany; ?>&main=<?php echo $term; ?>&selectSubtype=<?php echo $selectSubtype; ?>'>
				<?php
			}
		}
	}
	else
	{
		$fname = $_POST['new_cus_fname'];
		$lname = $_POST['new_cus_lname'];
		$id_card = $_POST['new_cus_idcard'];
		$pname = $_POST['new_cus_pname'];
		
		$cusID = GenCus();
		$date = date("Y-m-d H:i:s");
		$doerID = $_SESSION['av_iduser'];
		$statusapp = 1;
		$edittime = 0;
		$n_card = "ประชาชน";
		$n_san = "ไทย";
		$n_state = 0;
		
		$qr = pg_query("select * from \"Customer_Temp\" where \"N_IDCARD\"='$id_card'");
		if($qr)
		{
			$row = pg_num_rows($qr);
			if($row==0)
			{
				$status = 0;
				
				pg_query("BEGIN");
		
				$qr = pg_query("insert into \"Customer_Temp\"(\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"N_SAN\",\"N_CARD\",\"N_IDCARD\",\"N_STATE\") values('$cusID','$doerID','$date','$doerID','$date','$statusapp','$edittime','$pname','$fname','$lname','$n_san','$n_card','$id_card','$n_state')");
				if(!$qr)
				{
					$status++;
				}
				
				$qr1 = pg_query("insert into \"Fa1\"(\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"Approved\") values('$cusID','$pname','$fname','$lname',true)");
				if(!$qr1)
				{
					$status++;
				}
				
				$qr2 = pg_query("insert into \"Fn\"(\"CusID\",\"N_STATE\",\"N_SAN\",\"N_CARD\",\"N_IDCARD\") values('$cusID','$n_state','$n_san','บัตรประชาชน','$id_card')");
				if(!$qr2)
				{
					$status++;
				}
				if($status==0)
				{
					pg_query("COMMIT");
					$cusID = $cusID."ThaiaceReplaceSharp".$pname.$fname." ".$lname;
					?>
                        <meta http-equiv='refresh' content='0; URL=chkTypeForSent.php?custype=<?php echo $cus_type; ?>&contype=<?php echo $contype; ?>&conCompany=<?php echo $conCompany; ?>&main=<?php echo $cusID; ?>&selectSubtype=<?php echo $selectSubtype; ?>'>
                    <?php
				}
				else
				{
					pg_query("ROLLBACK");
					echo "<center><font color=\"#FF0000\"><b>ไม่สามารถบันทึกข้อมูลได้</b></font></center><br>";
				}
			}
			else
			{
				echo "<center><font color=\"#FF0000\"><b>ลูกค้าคนนี้มีในระบบแล้ว</b></font></center><br>";
			}
		}
		else
		{
			echo "<center><font color=\"#FF0000\"><b>ไม่สามารถติดต่อกับฐานข้อมูลได้</b></font></center><br>";
		}
	}
}
?>

<!--<form name="frm1" method="post" action="home_index.php">-->

<form name="frm1" method="post" action="index.php">
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
				<center>
					<table border="0" cellpadding="3" cellspacing="1" width="500">
                    	<tr>
							<td align="right">เลือกบริษัท <font color="#FF0000"><b> * </b></font> : </td>
							<td>
								<select name="conCompany">
									<option value="THCAP">---- THCAP  ----</option>
								</select>
							</td>
						</tr>
                        <tr>
							<td align="right">ประเภทสินเชื่อ <font color="#FF0000"><b> * </b></font> : </td>
							<td>
								<select name="contype" id="contype" onchange="show_by_type();">
									<option value="">เลือกประเภทสินเชื่อ</option>
									<option value="BH">BH</option>
									<option value="CG">CG</option>
									<option value="FA">FA</option>
									<option value="FL">FL</option>
									<option value="FI">FI</option>
									<option value="HP">HP</option>
									<option value="JV">JV</option>
									<option value="LI">LI</option>
									<option value="MG">MG</option>
									<option value="OL">OL</option>
									<option value="PL">PL</option>
									<option value="PN">PN</option>
									<option value="SB">SB</option>
									<option value="SM">SM</option>
									<option value="UF">UF</option>
								</select>
								
								<input type="hidden" name="type_BH" id="type_BH" value="<?php echo pg_creditType(BH); ?>">
								<input type="hidden" name="type_CG" id="type_CG" value="<?php echo pg_creditType(CG); ?>">
								<input type="hidden" name="type_FA" id="type_FA" value="<?php echo pg_creditType(FA); ?>">
								<input type="hidden" name="type_FL" id="type_FL" value="<?php echo pg_creditType(FL); ?>">
								<input type="hidden" name="type_FI" id="type_FI" value="<?php echo pg_creditType(FI); ?>">
								<input type="hidden" name="type_HP" id="type_HP" value="<?php echo pg_creditType(HP); ?>">
								<input type="hidden" name="type_JV" id="type_JV" value="<?php echo pg_creditType(JV); ?>">
								<input type="hidden" name="type_LI" id="type_LI" value="<?php echo pg_creditType(LI); ?>">
								<input type="hidden" name="type_MG" id="type_MG" value="<?php echo pg_creditType(MG); ?>">
								<input type="hidden" name="type_OL" id="type_OL" value="<?php echo pg_creditType(OL); ?>">
								<input type="hidden" name="type_PL" id="type_PL" value="<?php echo pg_creditType(PL); ?>">
								<input type="hidden" name="type_PN" id="type_PN" value="<?php echo pg_creditType(PN); ?>">
								<input type="hidden" name="type_SB" id="type_SB" value="<?php echo pg_creditType(SB); ?>">
								<input type="hidden" name="type_SM" id="type_SM" value="<?php echo pg_creditType(SM); ?>">
								<input type="hidden" name="type_UF" id="type_UF" value="<?php echo pg_creditType(UF); ?>">
							</td>	
						</tr>
						<tr id="subtype">
							<td align="right">ประเภทสัญญาย่อย <font color="#FF0000"><b> * </b></font> : </td>
							<td>
								<select name="selectSubtype" id="selectSubtype">
									<option id="plaseSelectSubtype" value="">เลือกประเภทสัญญาย่อย</option>
									<?php
										$qry_Subtype = pg_query("select * from \"thcap_contract_subtype\" order by \"conSubType_name\" ");
										while($resSubtype = pg_fetch_array($qry_Subtype))
										{
											$SubType_serial = $resSubtype["conSubType_serial"];
											$SubType_name = $resSubtype["conSubType_name"];
											
											echo "<option value=\"$SubType_serial\">$SubType_name</option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr id="old_cus">
							<td align="right">โปรดระบุผู้กู้หลัก <font color="#FF0000"><b> * </b></font> : </td>
							<td><input type="textbox" name="main" id="main" size="50" value="<?php echo $temp; ?>"></td>
						</tr>
                        <tr id="new_cus">
                        	<td align="right">หรือเพิ่มลูกค้าใหม่ <font color="#FF0000"><b> * </b></font> : </td>
                            <td>
                            	<label>
                                	<input type="checkbox" name="chkbx_new_cus" id="chkbx_new_cus" value="1" onchange="show_add_new_cus();" /> ลูกค้าใหม่
                                </label>
                                <input type="hidden" name="cus_type" id="cus_type" value="old" />
                            </td>
                        </tr>
                        <tr id="new_cus_note">
                        	<td></td>
                            <td>
                            	<font color="#FF0000">
                                	หมายเหตุ : การเพิ่มลูกค้าใหม่ในหน้านี้อนุญาติให้เพิ่มเฉพาะลูกค้าบุคคลธรรมดา<br />ที่มีหมายเลขบัตรประชาชนและมีสัญชาติไทยเท่านั้น  ห้ามเพิ่มลูกค้านิติบุคคล<br />หรือลูกค้าประเภทอื่น ๆ ที่นอกเหนือจากนี้โดยเด็ดขาด !!!
                                </font>
                            </td>
                        </tr>
                        <tr class="add_new_cus">
                        	<td align="right">คำนำหน้า <font color="#FF0000"><b> * </b></font> : </td>
                            <td><input type="text" name="new_cus_pname" id="new_cus_pname" size="30" /></td>
                        </tr>
                        <tr class="add_new_cus">
                        	<td align="right">ชื่อ <font color="#FF0000"><b> * </b></font> : </td>
                            <td><input type="text" name="new_cus_fname" id="new_cus_fname" size="30" /></td>
                        </tr>
                        <tr class="add_new_cus">
                        	<td align="right">นามสกุล <font color="#FF0000"><b> * </b></font> : </td>
                            <td><input type="text" name="new_cus_lname" id="new_cus_lname" size="30" /></td>
                        </tr>
                        <tr class="add_new_cus">
                        	<td align="right">เลขบัตรประชาชน <font color="#FF0000"><b> * </b></font> : </td>
                            <td><input type="text" name="new_cus_idcard" id="new_cus_idcard" size="30" /></td>
                        </tr>
						<tr>
							<td align="center" colspan="2"><br><input type="submit" id="btnSave" value="ตกลง" onclick="return validate();"></td>
						</tr>
					</table>
				</center>
			</fieldset>
		</td>
	</tr>
</table>
<br />
<br />
<center>
<fieldset style="width:85%">
<legend><font color="black"><b>สัญญาที่รออนุมัติ</b></font></legend>
<br />
<table id="tb_wait_approve" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
    <tr align="center" bgcolor="#79BCFF">
        <th>รายการที่</th>
        <th>ประเภทการผูกสัญญา</th>
        <th>เลขที่สัญญา</th>
		<th>วันที่ทำสัญญา</th>
        <th class="sortable" onclick="sort_table('tb_wait_approve',4);">ประเภทสินเชื่อ</th>
        <th>วงเงินที่ปล่อย</th>
        <th>จำนวนเงินกู้</th>
        <th>ยอดจัด/ยอดลงทุน</th>
        <th>ผู้ทำรายการ</th>
        <th class="sortable" onclick="sort_table('tb_wait_approve',9);">วันเวลาที่ทำรายการ</th>
        <th>รายละเอียด</th>
    </tr>
	<?php
	$where = "\"conRepeatDueDay\" is not null ";
	
	$query = pg_query("select * from public.\"thcap_contract_temp\" where \"Approved\" is null and \"editNumber\" = '0' order by \"doerStamp\" ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$contractAutoID = $result["autoID"];
		$contractID = $result["contractID"]; // เลขที่สัญญา
		$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
		$conDate = $result["conDate"]; // วันที่ทำสัญญา
		$conLoanAmt = $result["conLoanAmt"]; // จำนวนเงินกู้
		$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
		$doerUser = $result["doerUser"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
		$conFinanceAmount = $result["conFinanceAmount"]; // ยอดจัด/ยอดลงทุน
		
		if($conLoanAmt != ""){$txtconLoanAmt = number_format($conLoanAmt,2);}else{$txtconLoanAmt = "--";}
		if($conCredit != ""){$txtconCredit = number_format($conCredit,2);}else{$txtconCredit = "--";}
		if($conFinanceAmount != ""){$txtconFinanceAmount = number_format($conFinanceAmount,2);}else{$txtconFinanceAmount = "--";}
		
		if($conRepeatDueDay == "")
		{
			$contractType = 1; // ถ้าเท่ากับ 1 แสดงว่ามาจากเมนู (THCAP) ผูกสัญญาวงเงินชั่วคราว
			$contractTypeText = "ผูกสัญญาวงเงิน";
		}
		else
		{
			$contractType = 2; // ถ้าเท่ากับ 2 แสดงว่ามาจากเมนู (THCAP) ผูกสัญญาเงินกู้ชั่วคราว
			$contractTypeText = "ผูกสัญญาเงินกู้";
		}
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerUser' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($i%2==0){
			echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
		}else{
			echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$contractTypeText</td>";
		echo "<td align=\"center\">$contractID</td>";
		echo "<td align=\"center\">$conDate</td>";
		echo "<td align=\"center\">$conType</td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconCredit</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconLoanAmt</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconFinanceAmount</b></font></td>";
		echo "<td align=\"center\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		if($contractType == 1)
		{
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_financial_amount.php?contractAutoID=$contractAutoID&lonly=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		}
		else
		{
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_loan.php?contractAutoID=$contractAutoID&lonly=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		}
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</fieldset>
<div style="margin-top:50px;"></div>
<center>
<?php
include("frm_historyapp_limit.php");
?>
</center>
</center>
<input type="hidden" name="click" value="yes">
</form>

</body>

<script>
	$('#subtype').hide();
</script>

</html>