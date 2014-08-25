<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");
$main = pg_escape_string($_GET["main"]); // ผู้กู้หลัก
?>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
 
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
var intLine = 0;
var guarantorLine = 0;

function CreateNewRow()
	{
		intLine++;
		
		var theTable = document.getElementById("tableadd");
		var newRow = theTable.insertRow(theTable.rows.length)
		newRow.id = newRow.intLine
		var newCell
		
		//*** Column 1 ***//
		newCell = newRow.insertCell(0);
		newCell.id = newCell.intLine;
		newCell.align="right";
		newCell.width="35%";
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "ระบุผู้ขายฝากร่วม :";

		//*** Column 2 ***//
		newCell = newRow.insertCell(1);
		newCell.id = newCell.intLine;
		newCell.setAttribute("className", "css-name");		
		newCell.innerHTML = "<input NAME=\"join[]\" ID=\"join"+intLine+"\" onkeypess=\"javascript : autocom("+intLine+");\" type=\"text\" size=\"50\" /> หรือ <label><input type=\"checkbox\" name=\"add_join[]\" id=\"add_join"+intLine+"\" value=\"1\" onchange=\"add_new_cus('join"+intLine+"','add_join"+intLine+"');\" />เพิ่มลูกค้าใหม่</label>";
		
		$("#join"+intLine).autocomplete({
			source: "listcus_main.php",
			minLength:1
		});
	}	

function RemoveRow()
	{
		if(parseInt(intLine) > 1)
		{
				document.getElementById("join"+intLine+"").value=="";
				theTable = document.getElementById("tableadd");				
				theTableBody = theTable.tBodies[0];
				theTableBody.deleteRow(intLine);
				intLine--;
		
		}		
	}

function guarantorNewRow() // เพิ่มผู้ค้ำประกัน
{

		guarantorLine++;
		
		var theTable = document.getElementById("tableGuarantor");
		var newRow = theTable.insertRow(theTable.rows.length)
		newRow.id = newRow.guarantorLine
		var newCell
		
		//*** Column 1 ***//
		newCell = newRow.insertCell(0);
		newCell.id = newCell.guarantorLine;
		newCell.align="right";
		newCell.width="35%";
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "ระบุผู้ค้ำประกัน :";

		//*** Column 2 ***//
		newCell = newRow.insertCell(1);
		newCell.id = newCell.guarantorLine;
		newCell.setAttribute("className", "css-name");		
		newCell.innerHTML = "<input NAME=\"guarantor[]\" ID=\"guarantor"+guarantorLine+"\" onkeypess=\"javascript : autocom("+guarantorLine+");\" type=\"text\" size=\"50\" /> หรือ <label><input type=\"checkbox\" name=\"add_guarantor[]\" id=\"add_guarantor"+intLine+"\" value=\"1\" onchange=\"add_new_cus('guarantor"+intLine+"','add_guarantor"+intLine+"');\" />เพิ่มลูกค้าใหม่</label>";
		
		$("#guarantor"+guarantorLine).autocomplete({
			source: "listcus_main.php",
			minLength:1
		});
	
}	

function guarantorRemoveRow() // ลดผู้ค้ำประกัน
{
	if(parseInt(guarantorLine) > 1)
	{
			document.getElementById("guarantor"+guarantorLine+"").value=="";
			theTable = document.getElementById("tableGuarantor");				
			theTableBody = theTable.tBodies[0];
			theTableBody.deleteRow(guarantorLine);
			guarantorLine--;
	}		
}
</script>

<table align="center">
	<tr>
		<td align="center" colspan="2">
			ปุ่มเพิ่ม /ลบ ผู้ขายฝากร่วม <input type="button" value=" เพิ่ม " onclick="javascript : CreateNewRow();"><input type="button" value=" ลบ " onClick="javascript : RemoveRow();">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="850"  cellspacing="2" cellpadding="2" style="margin-top:1px" align="center" bgcolor="#DDFFAA" id="tableadd">
				<tr></tr>
				
			</table>
		</td>
	</tr>

	<tr>
		<td align="center" colspan="2">
			ปุ่มเพิ่ม /ลบ ผู้ค้ำประกัน <input type="button" value=" เพิ่ม " onclick="javascript : guarantorNewRow();"><input type="button" value=" ลบ " onClick="javascript : guarantorRemoveRow();">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="850"  cellspacing="2" cellpadding="2" style="margin-top:1px" align="center" id="tableGuarantor">
				<tr></tr>
			</table>
		</td>
	</tr>
</table>
<input type="text" name="chkContractRef" id="chkContractRef" value="1" readonly>

<script>
	document.getElementById("chkContractRef").style.visibility = 'hidden';
</script>