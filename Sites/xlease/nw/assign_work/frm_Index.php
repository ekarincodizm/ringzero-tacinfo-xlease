<?php
session_start();
include("../../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$dateTime = nowDateTime();
$date = substr($dateTime,0,10);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) สั่งงานตรวจสอบ-วางบิลเก็บช็ค</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>

<script language=javascript>
$(document).ready(function(){
	var nubrecChq = 1;
	$("#deadline").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#date"+nubrecChq).datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
	});
	$("#AddField").click(function() {
		
		nubrecChq = nubrecChq+1;
		
			console.log(nubrecChq);
			var newrevChqDiv = $(document.createElement('div')).attr("id", 'revChqDiv' + nubrecChq);
			table = '<div style="margin-top:20px;">'
			+ '<table width="800px" align="center" cellspacing="10" style="border-style:groove;border-color:yellow;" bgcolor="#F2F5A9">'
			+ '	<tr bgcolor="#79BCFF">'
			+ '		<td align="center" colspan="3"><input type="checkbox" name="recChq'+ nubrecChq +'" style="float:left;"/><b>รับเช็ค</b></td>'
			+' </tr>'
			+' <tr>'
			+ '		<td>เช็ค : <input type="text" name="chequAmt'+nubrecChq+'" size="5" onkeypress="check_num(event);" onblur="check_num(event);" /> ฉบับ</td>'
			+ '		<td>ลงวันที่ : <input type="text" name="date'+ nubrecChq +'" id="date' +nubrecChq+ '" size="10" readonly /></td>'
			+ '		<td>เลขที่ : <input type="text" name="chqNo'+ nubrecChq +'" size="10"/></td>'
			+ '	</tr>'
			+ '	<tr>'
			+ '		<td colspan="2">เช็คธนาคาร : <select id="chqBank' +nubrecChq+ '" name="chqBank' +nubrecChq+ '">'
														<?php
															$qry_fp=pg_query("select * from \"BankProfile\"");
																while($res_fp=pg_fetch_array($qry_fp)){
																	$bankName=$res_fp["bankName"];
														?>
			+ '													<option value="<?php echo $bankName;?>"><?php echo $bankName; ?></option>'
														<?php
															}
														?>
			+ '								</select>'
			+ '		</td>'
			+ '		<td>จำนวนเงิน : <input type="text" name="cashAmt'+ nubrecChq +'" size="15" value="0.00" style="text-align:right;" onKeyUp="dokeyup(this,event);" onChange="dokeyup(this,event);" /> บาท</td>'
			+ '	</tr>'
			+ '	<tr bgcolor="#79BCFF">'
			+ '		<td align="center" colspan="3">เอกสารรับกลับ</td>'
			+ '	</tr>'
			+ '	<tr>'
			+ '		<td colspan="3">เอกสารระบุ :<input type="text" name="docreturn'+ nubrecChq +'" size="50"/></td>'
			+ '	</tr>'
			+ '</table>'
			+ '</div>'
			newrevChqDiv.html(table);
			newrevChqDiv.appendTo("#revChqGroup1");
			document.getElementById("row").value = nubrecChq;
			
			$("#date"+nubrecChq).datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			});
			
    });
	$("#removeField").click(function(){
		if(nubrecChq>1){
			$("#revChqDiv" + nubrecChq).remove();
			nubrecChq-=1;
			console.log(nubrecChq);
			updateSummary();
			document.getElementById("row").value = nubrecChq;
		}else{
			return false;
		}
    });
});
//ค้นหาลูกค้า/ลูกหนี้
function KeyData(t){
	if(t==1){
		var text = "customer";
	}else{
		var text = "debtor";
	}
	$("#"+text).autocomplete({
		source: "cus_data.php",
        minLength:1
	});
}
//ค้นหาเลขทีสัญญา
function Keycon(){

	$("#contractID").autocomplete({
		source: "s_idall.php",
        minLength:1
	});
}
	
function popU(U,N,T){
	newWindow = window.open(U, N, T);
}
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
};
function validate(){;
		if ($("#contractID").val() == ""){
			alert('กรุณาระบุเลขที่สัญญา');
            $("#contractID").focus();
            return false;
		}else if($("#institution").val() == ""){
            alert('กรุณาระบุหน่วยงาน');
            $("#institution").focus();
            return false;
        } else if ($("#customer").val() == "") {
			 alert('กรุณาระบุลูกค้า');
             $('#customer').focus();
             return false;
		} else if($("#place").val() == ""){
			alert('กรุณาระบุสถานที่');
             $('#place').focus();
             return false;
		} else if($("#phoneNumber").val() == ""){
			alert('กรุณาระบุเบอร์โทรศัพท์/ผู้ติดต่อ');
             $('#phoneNumber').focus();
             return false;
		} else if($("#deadline").val() == ""){
			alert('กรุณาระบุวันกำหนดส่งงาน');
             $('#deadline').focus();
             return false;
		} else if($("#assignName").val() == ""){
			alert('กรุณาระบุผู้สั่งงาน');
             $('#assignName').focus();
             return false;
        } else {
			if(document.getElementById('pay').checked==true){
				if($("#paymentAmt").val() == ""){
					alert('กรุณาระบุจำนวนเงิน');
					$('#paymentAmt').focus();
					return false;
				}else if($("#refvalue").val() == ""){
					alert('กรุณาระบุเลขอ้างอิงหนี้');
					$('#refvalue').focus();
					return false;
				}
			} else {
				
			}
		}
		
}
</script>
<style>
#include
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
#detail
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:60%;
}
#revChqGroup1
{
margin-top:20px;
}
#payment
{
margin-top:20px;
}
#btset
{
margin-top:20px;
}
</style>
</head>
<body>
	<div class="header" align="center">
		<h1>(THCAP) สั่งงานตรวจสอบ-วางบิลเก็บช็ค</h1>
	</div>

	<div id="detail">
		<form id="frmDetail" method="post" action="process_insert.php" onsubmit="return validate();">
			<div>
				<table width="800px"align="center" cellspacing="10" style="border-style:groove;border-color:yellow;" bgcolor="#F2F5A9">
					<tr bgcolor="#79BCFF">
						<td colspan="4" align="center"><b>รายละเอียดงาน</b></td>
					</tr>
					<tr>
						<td align="right">เลขที่สัญญา :<font color="red">*</font></td>
						<td align="left"><input type="text" name="contractID" id="contractID" onkeyup="Keycon();" onblur="Keycon();" size="30"\></td>
					</tr>
					<tr>
						<td align="right">หน่วยงาน :<font color="red">*</font></td>
						<td align="left"><input type="text" name="institution" id="institution"\></td>
					</tr>
					<tr>
						<td align="right">เรื่อง :</td>
						<td align="left" colspan="3">
							<input type="checkbox" name="subject[]" id="subject1" value="1" /> รับเช็ค 
							<input type="checkbox" name="subject[]" id="subject1" value="2"/> เอกสารรับกลับ
							<input type="checkbox" name="subject[]" id="subject1" value="3"/> ตรวจรับสินค้า/บริการ 
						</td>
					</tr>
					<tr>
						<td align="right">ลูกค้า :<font color="red">*</font></td>
						<td align="left"><input type="text" name="customer" id="customer"\ onkeyup="KeyData(1);" onblur="KeyData(1);" size="30"></td>
							<td align="right">ลูกหนี้ :</td>
						<td align="left"><input type="text" name="debtor" id="debtor" onkeyup="KeyData();" onblur="KeyData();" size="30"\></td>
					</tr>
					<tr>
						<td align="right">สถานที่ :<font color="red">*</font></td>
						<td align="left"><textarea name="place" id="place" cols="20" rows="3"></textarea></td>
						<td align="right">เบอร์โทร/ผู้ติดต่อ :<font color="red">*</font></td>
						<td align="left"><input type="text" name="phoneNumber" id="phoneNumber"\></td>
					</tr>
					<tr>
						<td align="right">กำหนดส่งงาน :<font color="red">*</font></td>
						<td align="left"><input type="text" name="deadline" id="deadline" size="10" readonly \></td>
						<td align="right">ผู้สั่งงาน :<font color="red">*</font></td>
						<td align="left"><input type="text" name="assignName" id="assignName" size="25"\></td>
					</tr>
					<tr>
						<td align="right">หมายเหตุ :</td>
						<td align="left" colspan="3"><textarea name="note" id="note" cols="20" rows="3"></textarea></td>
					</tr>
				</table>
			</div>
				
				<div id="revChq">
					<div>
						<table align="center">
							<tr>
								<td><input type="button" value="+" id="AddField"/> <input type="button" value="-" id="removeField"/></td>
							<tr>
						</table>
					</div>
					<div>
						<table width="800px" align="center" cellspacing="10" style="border-style:groove;border-color:yellow;" bgcolor="#F2F5A9">
							<tr bgcolor="#79BCFF">
								<td align="center" colspan="3"><input type="checkbox" name="recChq1" id="select1" style="float:left;"/><b>รับเช็ค</b></td>
							</tr>
							<tr>
								<td>เช็ค : <input type="text" name="chequAmt1" size="5" onkeypress="check_num(event);" onblur="check_num(event);"/> ฉบับ</td>
								<td>ลงวันที่ : <input type="text" name="date1" id="date1" size="10" readonly /></td>
								<td>เลขที่ : <input type="text" name="chqNo1" size="10"/></td>
							</tr>
							<tr>
								<td colspan="2">เช็คธนาคาร : <select id="chqBank1" name="chqBank1">
														<?php
															$qry_fp=pg_query("select * from \"BankProfile\"");
																while($res_fp=pg_fetch_array($qry_fp)){
																	$bankName=$res_fp["bankName"];
																	echo "<option value=\"$bankName\">$bankName</option>";
																}
														?>
															</select>
								</td>
								<td>จำนวนเงิน : <input type="text" name="cashAmt1" size="15" value="0.00" style="text-align:right;" onKeyUp="dokeyup(this,event);" onChange="dokeyup(this,event);" /> บาท</td>
							</tr>
							<tr bgcolor="#79BCFF">
								<td align="center" colspan="3">เอกสารรับกลับ</td>
							</tr>
							<tr>
								<td colspan="3">เอกสารระบุ :<input type="text" name="docreturn1" size="50"/></td>
							</tr>
						</table>
					</div>
					
					<div id="revChqGroup1">
						<div id='revChqDiv1'>
						</div>
					</div>
					
						<input type="hidden" name="row" id="row" value="1">
				</div>
				
				<div id="payment">
						<table width="600" align="center" bgcolor="#F2F5A9" cellspacing="10" style="border-style:groove;border-color:yellow;">
							<tr align="center" bgcolor="#79BCFF">
								<td colspan="3"><b>ทำรายการตั้งหนี้</b></td>
							</tr>
							<tr>
								<td><input type="radio" name="payment" value="N" checked />ไม่มีค่าใช้จ่าย</td>
							</tr>
							<tr>
								<td>
									<input type="radio" name="payment" id="pay" value="Y"/>มีค่าใช่จ่าย   
								</td>
								<td>
									จำนวน<font color="red">*</font> <input type="text" name="paymentAmt" id="paymentAmt" size="15" style="text-align:right;"  value="0.00" onKeyUp="dokeyup(this,event);" onChange="dokeyup(this,event);"/> บาท
								</td>
								<td>
									เลขอ้างอิงหนี้<font color="red">*</font>  <input type="text" name="refvalue" id="refvalue" size="15" style="text-align:right;"/>
								</td>
							</tr>
							<tr>
								<td colspan="3"><b>*หมายเหตุ</b> ค่าใช่จ่ายจะถูกตั้งหนี้ต่อ 1 เรื่อง</td>
							</tr>
						</table>
					</div>
					
					<div id="btset">
						<table align="center" >
							<tr>
								<td>
									<input type="submit" name="submit" id="submit" value="บันทึก"> 
									<input type="reset" name="reset" value="reset">
									<input type="reset" name="reset" value="ปิด" onclick="window.close();">
								</td>
							</tr>
						</table>
					</div>
		</form>
	</div>
</body>
</html>