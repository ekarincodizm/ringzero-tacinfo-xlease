<?php
include("../../../config/config.php");

$contractID = $_GET["contractID"];

$qrydata=pg_query("SELECT * FROM \"thcap_NT1_temp\" 
WHERE \"NT_1_Status\"='1' and \"contractID\"='$contractID' order by \"CusState\"='0'");
if($resdata=pg_fetch_array($qrydata)){
	$NT_tempID=$resdata['NT_tempID']; //รหัส auto_id
	$NT_1_guaranID=$resdata['NT_1_guaranID']; //ประเภทสินทรัพย์ที่จำนอง
	$NT_1_Date=$resdata['NT_1_Date'];//วันที่ทำสัญญาจำนอง
	$NT_1_Lawyer_Name=$resdata['NT_1_Lawyer_Name'];//ทนายความผู้รับมอบอำนาจ
	$NT_1_startDue=$resdata['NT_1_startDue']; //งวดที่เริ่มค้าง
	$NT_1_endDue=$resdata['NT_1_endDue'];//งวดสุดท้ายที่ค้าง
	$NT_1_Duenext=$resdata['NT_1_Duenext'];//งาดที่ค้างในอนาคต
	$NT_1_Paynext=number_format($resdata['NT_1_Paynext'],2);//ค่างวดในอนาคต
	$NT_1_Paytagnext=number_format($resdata['NT_1_Paytagnext'],2);//ค่าติดตามทวงถามอนาคต
	$NT_1_contact=$resdata['NT_1_contact'];//รายละเอียดการติดต่อ
	$NT_1_bank=$resdata['NT_1_bank'];// บัญชีธนาคาร
	$NT_1_Result=$resdata['NT_1_Result']; //หมายเหตุ
	
	//หาวันที่ครบกำหนดชำระงวดถัดไป
	$qrydatenext=pg_query("select \"ptDate\" from account.\"thcap_loan_payTerm_left\" where \"contractID\"='$contractID' and \"ptNum\"='$NT_1_Duenext'");
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
    <title>พิมพ์ NT สัญญา <?php echo $contractID;?></title>
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
function popWindow(wName){
	features = 'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800';
	pop = window.open('',wName,features);
	if(pop.focus){ pop.focus(); }
	return true;
}
</script>   
</head>
<body>
<div align="center"><h2>พิมพ์ NT สัญญา</h2><b>สัญญาเลขที่ <span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></b></div>
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			
			<!--ผู้ที่เกี่ยวข้องในสัญญา-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>ผู้ที่เกี่ยวข้องในสัญญา</b></legend>
				<table width="500" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#CDB7B5">
				<?php
				$qrycus=pg_query("SELECT
									a.\"NTID1\",
									b.\"FullName\",
									a.\"CusState\"
								FROM
									\"thcap_NT1\" a
								LEFT JOIN
									\"thcap_ContactCus\" b ON a.\"CusID\" = b.\"CusID\" AND a.\"contractID\" = b.\"contractID\"
								WHERE
									a.\"contractID\" = 'PL-BK01-5700002'
								ORDER BY
									a.\"CusState\", a.\"NTID1\" ");
				$numcus=pg_num_rows($qrycus);
				$i=1;
				while($rescus=pg_fetch_array($qrycus)){
					$NTID1=$rescus['NTID1'];
					$cusname=$rescus['FullName'];
					$CusState=$rescus['CusState'];
					$print="";
					
					if($CusState == "0"){$relation = "ผู้กู้หลัก";}
					elseif($CusState == "1"){$relation = "ผู้กู้ร่วม";}
					elseif($CusState == "2"){$relation = "ผู้กู้ค้ำประกัน";}
					else{$relation = "";}
					
					//กรณีให้มีปุ่ม "พิมพ์" แค่ปุ่มเดียว
					// if($i==1){ 
						// $print="<td rowspan=\"$numcus\" align=\"center\"><button style=\"width:150px;height:50px;\" onclick=\"javascript:popU('pdf_nt1_loan.php?contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">พิมพ์ <img src=\"../images/icon_pdf.gif\" width=\"16\" height=\"16\"></button></td>";
					// }
					/*echo "<tr height=\"25\" bgcolor=\"#FFE4E1\"><td align=\"left\">&nbsp;<b>$NTID1</b> $cusname ($relation)</td><td align=\"center\"><button onclick=\"javascript:popU('pdf_nt1_loan.php?NTID1=$NTID1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">พิมพ์ <img src=\"../images/icon_pdf.gif\" width=\"16\" height=\"16\"></button></td></tr>";*/
					?>
					<!--ส่งค่าแบบ POST-->
					<form name ="my" action="pdf_nt1_loan.php" method="post" target="Details" onSubmit="return popWindow(this.target)">
						<input type="hidden" name="NTID1" id="NTID1" value="<?php echo $NTID1; ?>">
						<input name="print" type="submit" value="พิมพ์" hidden />
						<tr height="25" bgcolor="#FFE4E1">
							<td align="left">&nbsp;<b><?php echo $NTID1; ?></b><?php echo $cusname ."(".$relation.")";?></td>
							<td align="center">
								<button onclick="document.forms['my'].print.click();" style="cursor: pointer;">พิมพ์ <img src="../images/icon_pdf.gif" width="16" height="16"></button>
							</td>
						</tr>
					</form >
					<?php $i++;
				}
				?>
				</table>
			</fieldset>
			</div>
			
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
					
					$typemin=pg_getminpaytype($contractID); //ชื่อประเภทของค่างวด
					
					if($paytype==$typemin){
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
			<div style="padding-top:5px;"><b>รายละเีอียดการติดต่อ : </b>
			<?php
			$detailcontact=$NT_1_contact;
			echo "<input type=\"text\" name=\"detailcontact\" id=\"detailcontact\" value=\"$detailcontact\" size=\"100\" readonly>";
			?> 
			</div>
			<div><b>หมายเหตุ : </b></div>
			<div><textarea name="result" id="result" cols="40" rows="4" readonly><?php echo $NT_1_Result?></textarea></div>
			<div style="padding:15px;text-align:center;"><hr></div>
		</td>
	</tr>
</table>
</html>