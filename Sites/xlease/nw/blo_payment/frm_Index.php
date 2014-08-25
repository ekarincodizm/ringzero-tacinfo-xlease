<?php
//เมนู "BLO รับชำระเงิน"

include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(BLO) รับชำระเงิน</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){

	$(document).ready(function(){
		$("#Payer").autocomplete({
			source: "s_cus.php",
			minLength:1
		});
	});
	
	$("#datepicker_pay").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
});

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm1.datepicker_pay.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ วันที่จ่ายเงิน";
	}
	
	if (document.frm1.contractID.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่สัญญา";
	}
	
	if (document.frm1.Payer.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ ผู้ชำระเงิน";
	}
	
	if (document.frm1.address.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ ที่อยู่";
	}
	
	for(var i = 1; i <= counter; i++)
	{
		if (document.getElementById("list"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ รายการ " + i;
		}
		
		if (document.getElementById("amount"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ จำนวนเงิน " + i;
		}
		
		if (document.getElementById("vatValue"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ ภาษีมูลค่าเพิ่ม " + i;
		}
		
		if (document.getElementById("whtValue"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ ภาษีหัก ณ ที่จ่าย " + i;
		}
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	} 
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	if (!newWindow.opener) newWindow.opener = self;
}

function calculateBeforeVat() // รวมราคาก่อน vat
{
	var sumBeforeVat = 0;
	for(var i = 1; i <= counter; i++)
	{
		if(document.getElementById("amount"+i).value != '')
		{
			sumBeforeVat += parseFloat(document.getElementById("amount"+i).value);
		}
	}
	
	document.getElementById("beforeVat").value = sumBeforeVat.toFixed(2);
	
	calculateAfterVat(); // ราคาหลังรวม vat
}

function calculateMyVat() // รวมยอด vat
{
	var sumVat = 0;
	for(var i = 1; i <= counter; i++)
	{
		if(document.getElementById("vatValue"+i).value != '')
		{
			sumVat += parseFloat(document.getElementById("vatValue"+i).value);
		}
	}
	
	document.getElementById("myVat").value = sumVat.toFixed(2);
	
	calculateAfterVat(); // ราคาหลังรวม vat
}

function calculateMyWht() // รวมยอด wht
{
	var sumWht = 0;
	for(var i = 1; i <= counter; i++)
	{
		if(document.getElementById("whtValue"+i).value != '')
		{
			sumWht += parseFloat(document.getElementById("whtValue"+i).value);
		}
	}
	
	document.getElementById("mywht").value = sumWht.toFixed(2);
}

function calculateAfterVat() // ราคาหลังรวม vat
{
	var sumAfterVat;
	var BeforeVat;
	var Vat;
	
	if(document.getElementById("beforeVat").value == '')
	{
		BeforeVat = 0;
	}
	else
	{
		BeforeVat = parseFloat(document.getElementById("beforeVat").value);
	}
	
	if(document.getElementById("myVat").value == '')
	{
		Vat = 0;
	}
	else
	{
		Vat = parseFloat(document.getElementById("myVat").value);
	}
	
	sumAfterVat = BeforeVat + Vat;
	document.getElementById("afterVat").value = sumAfterVat.toFixed(2);
}

function sumCost(temp) // ต้นทุนรวม
{
	document.getElementById("sumCost"+temp).value = parseFloat(document.getElementById("amount"+temp).value) + parseFloat(document.getElementById("vatValue"+temp).value);
}

function chkadd(){
	$.post("address.php",{
			id : document.frm1.Payer.value			
		},
		function(data){		
			$("#address").text(data);
		}
	);
};
</script>

</head>
<body>
<center>
<h1>(BLO) รับชำระเงิน</h1>
<form name="frm1" method="post" action="processAdd.php" enctype="multipart/form-data">
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">วันที่ :</td>
						<td><input type="text" name="datepicker_pay" id="datepicker_pay" size="15" style="text-align:center;" value="<?php echo nowDate(); ?>"></td>
						<td width="20"></td>
						<td align="right">สัญญาเลขที่ :</td><td><input type="textbox" size="35" name="contractID" id="contractID"></td>
						<td width="20"></td>
						<td align="right">ผู้ชำระเงิน :</td><td><input type="text" name="Payer" id="Payer" size="35" onKeyUp="chkadd();" onblur="chkadd();" onFocus="chkadd();"></td>
					</tr>
					<tr>
						<td align="right" colspan="3">ที่อยู่ :</td>
						<td align="left" colspan="5"><textarea name="address" id="address" cols="50" rows="3"></textarea></td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
				
				<input type="button" value="+ เพิ่ม" id="addButton"> <input type="button" value="- ลบ" id="removeButton">
					
				<table id="tableDetail" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th>NO.</th>
						<th>รายการ</th>
						<th>จำนวนเงิน</th>
						<th>ภาษีมูลค่าเพิ่ม</th>
						<th>รวมเงิน</th>
						<th>ภาษีหัก ณ ที่จ่าย</th>
					</tr>
					<tr bgcolor="#E8E8E8">
						<td align="center" width="25">1</td>
						<td>
                        <select name="list1" id="list1">
                        	<option value="">--------- เลือกรายการ ---------</option>
                            <?php
							$qr = pg_query("select * from \"blo_costs\" order by \"costsName\" asc");
							if($qr)
							{
								$row = pg_num_rows($qr);
								if($row!=0){
									while($rs = pg_fetch_array($qr))
									{
										echo "
											<option value=\"".$rs['costsID']."\">".$rs['costsName']."</option>
										";
									}
								}
							}
							?>
                        </select>
                        </td>
						<td><input type="text" name="amount1" id="amount1" size="15" style="text-align:right;" onKeyUp="sumCost(1); calculateBeforeVat();" onblur="sumCost(1); calculateBeforeVat();"></td>
						<td><input type="text" name="vatValue1" id="vatValue1" size="15" value="0" onKeyUp="sumCost(1); calculateMyVat();" onblur="sumCost(1); calculateMyVat();" style="text-align:right;"></td>
						<td><input type="text" name="sumCost1" id="sumCost1" size="15" readonly style="background:#DDDDDD; text-align:right;"></td>
						<td><input type="text" name="whtValue1" id="whtValue1" style="text-align:right;" value="0" size="15" onKeyUp="calculateMyWht()"></td>
					</tr>
				</table>
				<div id="TextBoxesGroup1">
				<div id='TextBoxDiv1'>
				</div>
				</div>
				<input type="hidden" name="rowDetail" id="rowDetail" value="1">
			</center>
			</fieldset>
			
			<table width="100%">
				<tr bgcolor="#CCCC99">
					<td width="100%" align="right">
						<b>ราคาก่อน VAT : </b><input type="text" name="beforeVat" id="beforeVat" readonly style="background:#DDDDDD; text-align:right;">
						&nbsp;&nbsp;
						<b>ยอด VAT : </b><input type="text" name="myVat" id="myVat" readonly style="background:#DDDDDD; text-align:right;" onKeyUp="calculateAfterVat()" onblur="calculateAfterVat()">
						&nbsp;&nbsp;
						<b>ราคารวม VAT : </b><input type="text" name="afterVat" id="afterVat" readonly style="background:#DDDDDD; text-align:right;">
						&nbsp;&nbsp;
						<b>รวมภาษีหัก ณ ที่จ่าย : </b><input type="text" name="mywht" id="mywht" readonly style="background:#DDDDDD; text-align:right;">
						&nbsp;
					</td>
				</tr>
			</table>
			<br><br>
			<input type="submit" value="บันทึก" onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="เริ่มใหม่ทั้งหมด" onclick="window.location='frm_Index.php'">
		</td>
	</tr>
</table>
</form>
</center>
</body>

<script type="text/javascript">
var counter = 1;

$(document).ready(function(){
	$('#addButton').click(function()
	{
		counter++;
		if(counter > 1)
		{
			console.log(counter);
			var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
			table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
			+ '	<tr bgcolor="#E8E8E8">'
			+ '		<td align="center" width="25">'+ counter +'</td>'
			+ '		<td>'
			+ '			<select name="list'+ counter +'" id="list'+ counter +'">'
			+ '				<option value="">--------- เลือกรายการ ---------</option>'
							<?php
								$qr = pg_query("select * from \"blo_costs\" order by \"costsName\" asc");
								if($qr)
								{
									$row = pg_num_rows($qr);
									if($row!=0){
										while($rs = pg_fetch_array($qr))
										{
										?>
			+ '								<option value="<?php echo $rs['costsID']; ?>"><?php echo $rs['costsName']; ?></option>'
										<?php
										}
									}
								}
							?>
			+ '			</select>'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="15" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();">'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" size="15" value="0" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateMyVat();" onblur="sumCost('+ counter +'); calculateMyVat();" />'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
			+ '		</td>'
			+ '		<td>'
			+ '			<input type="text" name="whtValue'+ counter +'" id="whtValue'+ counter +'" size="15" value="0" onKeyUp="calculateMyWht()" style="text-align:right;" />'
			+ '		</td>'
			+ '	</tr>'
			+ '	</table>'
			
			newTextBoxDiv.html(table);

			newTextBoxDiv.appendTo("#TextBoxesGroup1");

			document.getElementById("rowDetail").value = counter;
		}
    });

	$("#removeButton").click(function(){
		if(counter==1){
			//alert("ห้ามลบ !!!");
			return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
		
		calculateMyVat(); // คำนวณใหม่
		calculateBeforeVat(); // คำนวณใหม่
		
        console.log(counter);
        updateSummary();
		
		document.getElementById("rowDetail").value = counter;
    });
});
</script>

</html>