<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");
$main = $_GET["main"]; // ผู้กู้หลัก
?>
	
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
var billLine = 0;

function billNewRow() // เพิ่มบิล
{
	var main;
	main = '<?php echo $main; ?>';
	//if(parseInt(billLine) < 5)
	//{
		billLine++;
		
		var theTable = document.getElementById("tableBillFA");
		var newRow = theTable.insertRow(theTable.rows.length)
		newRow.id = newRow.billLine
		var newCell
		
		//*** Column 1 ***//
		newCell = newRow.insertCell(0);
		newCell.id = newCell.billLine;
		newCell.align="right";
		newCell.width="25%";
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "บิล <font color=\"#FF0000\"><b> * </b></font> :";

		//*** Column 2 ***//
		newCell = newRow.insertCell(1);
		newCell.id = newCell.billLine;
		newCell.setAttribute("className", "css-name");		
		newCell.innerHTML = "<input type=\"hidden\" name=\"chkbill[]\" id=\"chkbill"+billLine+"\" value=\"469\"><input NAME=\"selectBillFA[]\" ID=\"selectBillFA"+billLine+"\" onfocus=\"javascript : chkamtbill("+billLine+")\" onkeypress=\"javascript : chkamtbill("+billLine+")\" type=\"text\" size=\"100\">";
		//onkeypess=\"javascript : autocom("+billLine+");\"
		$("#selectBillFA"+billLine).autocomplete({
			source: "listBillFA.php?main="+main,
			minLength:1
		});
	//}
}	

//function สำหรับคำนวณเงินในบิลที่เลือก
function chkamtbill(bill){
	var sum;
	var a = $("#selectBillFA"+bill).val();
	var t = a.split("#");  //แยกข้อความ
	
	if(isNaN(t[4]) || t[4] == ""){
		t[4] = 0;
	}
	
	//นำจำนวนเงินที่ได้ไปใส่ใน array
	$("#chkbill"+bill).val(t[4]);  
	
	//วน LOOP บวกค่าใน array
	var elem=$('input[name="chkbill[]"]');
	sum=0;
	for( i=0; i<elem.length; i++ ){ 
		sum=parseFloat(sum)+parseFloat($(elem[i]).val());
	}
	$("#showsumAmtBill").text(parseFloat(sum).toFixed(2)); //แสดงผลรวมออกทางหน้าจอ
}

function billRemoveRow() // ลดบิล
{
	if(parseInt(billLine) > 1)
	{
		$("#chkbill"+billLine).val('');
		document.getElementById("selectBillFA"+billLine+"").value=="";
		theTable = document.getElementById("tableBillFA");				
		theTableBody = theTable.tBodies[0];
		theTableBody.deleteRow(billLine);
		billLine--;
		delAmtBill(billLine);
	}		
}

//function สำหรับลบจำนวนเงินในบิลที่เลือก
function delAmtBill(bill){
	var elem=$('input[name="chkbill[]"]');
	sum=0;
	for( i=0; i<elem.length; i++ ){ 
		sum=parseFloat(sum)+parseFloat($(elem[i]).val());
	}
	$("#showsumAmtBill").text(parseFloat(sum).toFixed(2)); //แสดงผลรวมออกทางหน้าจอ
}
</script>

<table align="center">
	<tr>
		<td align="center" width="25%">
			ปุ่มเพิ่ม /ลบ บิล <input type="button" value=" เพิ่ม " onclick="javascript : billNewRow();"><input type="button" value=" ลบ " onClick="javascript : billRemoveRow();">
			<font color="red">( จำนวนเงินรวมในบิล <span id="showsumAmtBill">0.00</span> บาท )</font>
		</td>
	</tr>
	<tr>
		<td align="center" width="45%">
			<table cellspacing="2" cellpadding="2" style="margin-top:1px" align="left" id="tableBillFA">
				<tr id="tableBillFA"></tr>
			</table>
		</td>
	</tr>
</table>