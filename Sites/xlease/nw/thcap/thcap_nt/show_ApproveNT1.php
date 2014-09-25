<?php
include("../../../config/config.php");
include("../../function/checknull.php");
$contractID = pg_escape_string($_GET["contractID"]);

$qrydata=pg_query("SELECT * FROM \"thcap_NT1_temp\" 
WHERE \"NT_1_Status\"='2' and \"contractID\"='$contractID' order by \"CusState\"='0'");
if($resdata=pg_fetch_array($qrydata)){
	$NT_tempID=$resdata['NT_tempID']; //รหัส auto_id
	$NT_1_guaranID=$resdata['NT_1_guaranID']; //ประเภทสินทรัพย์ที่จำนอง
	$NT_1_Date=$resdata['NT_1_Date'];//วันที่ทำสัญญาจำนอง
	$NT_1_Lawyer_Name=$resdata['NT_1_Lawyer_Name'];//ทนายความผู้รับมอบอำนาจ
	$NT_1_withInDay = $resdata['NT_1_withInDay']; // จำนวนวันที่ชำระภายในกี่วัน
	$NT_1_startDue=$resdata['NT_1_startDue']; //งวดที่เริ่มค้าง
	$NT_1_endDue=$resdata['NT_1_endDue'];//งวดสุดท้ายที่ค้าง
	$NT_1_Duenext=$resdata['NT_1_Duenext'];//งาดที่ค้างในอนาคต
	$NT_1_Paynext=number_format($resdata['NT_1_Paynext'],2);//ค่างวดในอนาคต
	$NT_1_Paytagnext=number_format($resdata['NT_1_Paytagnext'],2);//ค่าติดตามทวงถามอนาคต
	$NT_1_contact=$resdata['NT_1_contact'];//รายละเอียดการติดต่อ
	$NT_1_bank=$resdata['NT_1_bank'];// บัญชีธนาคาร
	$NT_1_Result=$resdata['NT_1_Result']; //หมายเหตุ
	
	$NT_1_Duenext_checknull = checknull($NT_1_Duenext);
	
	//หาวันที่ครบกำหนดชำระงวดถัดไป
	$qrydatenext=pg_query("select \"ptDate\" from account.\"thcap_loan_payTerm_left\" where \"contractID\"='$contractID' and \"ptNum\"=$NT_1_Duenext_checknull ");
	list($ptDatenext)=pg_fetch_array($qrydatenext);
	
	if($NT_1_Duenext==""){
		$textnext="-";
	}else{
		$textnext="งวดที่ $NT_1_Duenext จำนวนเงิน $NT_1_Paynext บาท ครบกำหนดวันที่ $ptDatenext";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติ Create NT สัญญา <?php echo $contractID;?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
    
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ')==true){
			return true;}
		else{
			return false;}
	}
	else{
		if(confirm('ยืนยันการไม่อนุมัติ')==true){
			return true;}
		else{
			return false;}
	}
}

</script>   
</head>
<body>
<div align="center"><h2>(THCAP) อนุมัติ Create NT</h2><b>สัญญาเลขที่ <?php echo $contractID;?></b></div>
<form method="post" name="frm1" action="process_nt1_loan.php"> 
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<div style="margin-top:0px;"><?php include('../../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
			
			<!--ข้อมูลเพิ่มเติม-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>ข้อมูลเพิ่มเติม</b></legend>
				<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="25">
					<td width="160">ประเภทสินทรัพย์ที่จำนอง </td>
					<td>
						<input type="text" value="<?php echo $NT_1_guaranID;?>" readonly="true">
					</td>
				</tr>
				<tr height="25">
					<td>วันที่ทำสัญญาจำนอง  </td>
					<td>
						<input type="text" name="startdate" id="startdate" value="<?php echo $NT_1_Date;?>" size="10" readonly>
					</td>
				</tr>
				<tr height="25">
					<td>ทนายความผู้รับมอบอำนาจ  </td>
					<td>
						<input type="text" name="proctor" id="proctor"size="40" value="<?php echo $NT_1_Lawyer_Name;?>" readonly>
					</td>
				</tr>
				<tr height="25">
					<td>จำนวนวันที่ชำระภายใน  </td>
					<td>
						<input type="text" name="withInDay" id="withInDay" size="3" value="<?php echo $NT_1_withInDay;?>" readonly> วัน
					</td>
				</tr>
				</table>
			</fieldset>
			</div>
			
			<!--หนี้ที่ต้องการเรียบเก็บ-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>หนี้ที่ต้องการเรียบเก็บ</b></legend>
				<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">				
				<?php
				//หนี้ที่ต้องการเรียบเก็บเพิ่มเติม
				$qrymore=pg_query("SELECT ta_array_list(\"NT_1_Debtmore\"),ta_array_get(\"NT_1_Debtmore\", ta_array_list(\"NT_1_Debtmore\")) FROM \"thcap_NT1_temp\" where \"NT_tempID\"='$NT_tempID'");
				$i=1;
				while($resmore=pg_fetch_array($qrymore)){
					list($paytype,$payleft)=$resmore;
					
					//หาชื่อหนี้
					$qrynametype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$paytype'");
					list($tpDesc)=pg_fetch_array($qrynametype);
					
					if($i==1){
						$tpDesc="ค่างวด";
						$txtmore="(งวดที่ $NT_1_startDue - งวดที่ $NT_1_endDue)";
					}else{
						$txtmore="";
					}
					
					echo "<tr height=\"25\">
					<td width=\"160\">$tpDesc </td>
					<td><input type=\"text\" value=".number_format($payleft,2)." readonly> $txtmore</td>
					</tr>
					";
					$i++;
				}
				?>	
				</table>
			</fieldset>
			</div>
			
			<!--หนี้ในอนาคต-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>หนี้ในอนาคต</b></legend>
				<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="25">
					<td width="160">ค่างวด </td>
					<td>
					<?php
						echo $textnext;
					?>
					</td>
				</tr>
				<tr height="25">
					<td>ค่าติดตามทวงถาม   </td>
					<td>
					<?php
					echo "<input type=\"text\" value=\"$NT_1_Paytagnext\" readonly>";
					?>
					</td>
				</tr>
				</table>
			</fieldset>
			</div>
			<div style="padding-top:5px;"><b>บัญชีธนาคาร : </b>
			<?php
			echo "<input type=\"text\" size=\"110\" value=\"$NT_1_bank\" readonly>";
			?> 
			</div>
			<div style="padding-top:5px;"><b>รายละเอียดการติดต่อ : </b>
			<?php
			$detailcontact=$NT_1_contact;
			echo "<input type=\"text\" name=\"detailcontact\" id=\"detailcontact\" value=\"$detailcontact\" size=\"100\" readonly>";
			?> 
			</div>
			<div><b>หมายเหตุ : </b></div>
			<div><textarea name="result" id="result" cols="40" rows="4" readonly><?php echo $NT_1_Result?></textarea></div>
			<div style="padding:15px;text-align:center;">
			<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
			<hr>
			<!--input type="button" id="btn1" value="อนุมัติ">
			<input type="button" id="btn2" value="ไม่อนุมัติ" -->
			<input type="submit" name="btn1" value="อนุมัติ" onclick="return confirmappv('1')">
			<input type="submit" name="btn2" value="ไม่อนุมัติ" onclick="return confirmappv('0')">
			<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID;?>">
			<input type="hidden" name="method" id="method" value="approve">			
			</div>
		</td>
	</tr>
</table>
</form>
</html>