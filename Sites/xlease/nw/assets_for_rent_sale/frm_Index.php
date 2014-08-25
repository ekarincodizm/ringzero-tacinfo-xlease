<?php
include("../../config/config.php");

$autoappv = $_GET["autoappv"]; //หากมีค่าเป็น 't' ให้อนุมัติรายการนี้อัตโนมัติโดยระบบ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ซื้อสินทรัพย์สำหรับเช่า-ขาย</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){

	document.frm1.updatelistbox.style.visibility = 'hidden';
	document.frm1.updatelistProductStatus.style.visibility = 'hidden';
	document.getElementById("showVat").style.visibility = 'hidden';
	document.getElementById("subVat").style.visibility = 'hidden';

	$(document).ready(function(){
		$("#seller").autocomplete({
			source: "s_corp.php",
			minLength:1
		});
	});
	
    $("#datepicker_buy").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
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

	if (document.frm1.seller.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ ผู้ขาย";
	}
	
	if (document.frm1.datepicker_buy.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ วันที่ซื้อ";
	}
	
	/*if (document.frm1.datepicker_pay.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ วันที่จ่ายเงิน";
	}*/
	
	if (document.frm1.receiptNumber.value=="" && document.frm1.PurchaseOrder.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่ใบสั่งซื้อ หรือ เลขที่ใบเสร็จ อย่างน้อย 1 อย่าง";
	}
	
	if(document.getElementById("noMoney").checked == false){
		if(document.getElementById("howVat1").checked == false && document.getElementById("howVat2").checked == false){
			theMessage = theMessage + "\n -->  กรุณาเลือก ประเภท VAT";
		}
	}
	
	if(document.getElementById("duplicatePO").value == '1'){
		theMessage = theMessage + "\n -->  ผู้ซื้อรายนี้เคยมีเลขที่ใบสั่งซื้อนี้แล้ว";
	}
	
	if(document.getElementById("duplicateReceipt").value == '1'){
		theMessage = theMessage + "\n -->  ผู้ขายรายนี้เคยมีเลขที่ใบเสร็จนี้แล้ว";
	}
	
	if (document.getElementById("filePO").value != "" && document.frm1.PurchaseOrder.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่ใบสั่งซื้อ";
	}
	
	if (document.getElementById("fileReceipt").value != "" && document.frm1.receiptNumber.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่ใบเสร็จ";
	}
	
	for(var i = 1; i <= counter; i++)
	{
		if (document.getElementById("brand"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ ยี่ห้อ " + i;
		}
		
		if (document.getElementById("assets"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสินทรัพย์ " + i;
		}
		
		if (document.getElementById("model"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ ชื่อรุ่น " + i;
		}
		
		if(document.getElementById("noMoney").checked == false){
			if (document.getElementById("costPerPiece"+i).value==""){
				theMessage = theMessage + "\n -->  กรุณาระบุ ต้นทุน/ชิ้น " + i;
			}
		}
		
		if (document.getElementById("productStatus"+i).value==""){
			theMessage = theMessage + "\n -->  กรุณาเลือก สถานะสินค้า " + i;
		}
		
		if (document.getElementById("amount"+i).value < 1){
			theMessage = theMessage + "\n -->  จำนวนชิ้นรายการ  " + i +" ต้องมากกว่า 0";
		}
		
		if(document.getElementById("noMoney").checked == false){
			if (document.getElementById("howVat1").checked == true && document.getElementById("vatValue"+i).value==""){
				theMessage = theMessage + "\n -->  กรุณาระบุ ภาษีมูลค่าเพิ่ม " + i;
			}
		}
	}
	
	if(document.getElementById("noMoney").checked == false){
		if (document.frm1.beforeVat.value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ ราคาก่อน VAT";
		}
	}
	
	if(document.getElementById("noMoney").checked == false){
		if(document.getElementById("howVat2").checked == true)
		{
			if (document.frm1.myVat.value==""){
				theMessage = theMessage + "\n -->  กรุณาระบุ ยอด VAT";
			}
		}
	}
	
	if(document.getElementById("noMoney").checked == false){
		if (document.frm1.afterVat.value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ ราคาหลังรวม VAT";
		}
	}
	
	if (document.getElementById("fileReceipt").value == "" && document.frm1.receiptNumber.value !=""){
		theMessage = theMessage + "\n -->  กรุณาแนบไฟล์ ใบเสร็จ";
	}
	
	if (document.getElementById("filePO").value == "" && document.frm1.PurchaseOrder.value!=""){
		theMessage = theMessage + "\n --> กรุณาแนบไฟล์ ใบสั่งซื้อ";
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

function refreshListBox() // refresh ประเภทสินทรัพย์ ทั้งหมด
{  
	var dataAssetsList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataAssetsList  
		  url: "dataForAssetsList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1
		  async: false  
	}).responseText;
	
	for(var a = 1; a <= counter; a++)
	{
		$("select#assets"+a).html(dataAssetsList); // นำค่า dataAssetsList มาแสดงใน listbox ที่ชื่อ assets..
	}
}

function refreshMyListBox(myBox) // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
{  
	var dataAssetsList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataAssetsList  
		  url: "dataForAssetsList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1
		  async: false  
	}).responseText;
	
	$("select#assets"+myBox).html(dataAssetsList); // นำค่า dataAssetsList มาแสดงใน listbox ที่ชื่อ assets..
}

function refreshListProductStatus() // refresh สถานะสินค้า ทั้งหมด
{  
	var dataProductStatusList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataProductStatusList  
		  url: "dataForProductStatusList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1
		  async: false  
	}).responseText;
	
	for(var bList = 1; bList <= counter; bList++)
	{
		$("select#productStatus"+bList).html(dataProductStatusList); // นำค่า dataProductStatusList มาแสดงใน listbox ที่ชื่อ productStatus..
	}
}

function refreshMyListProductStatus(myList) // refresh สถานะสินค้า เฉพาะของตัวเอง
{  
	var dataProductStatusList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataProductStatusList  
		  url: "dataForProductStatusList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1
		  async: false  
	}).responseText;
	
	$("select#productStatus"+myList).html(dataProductStatusList); // นำค่า dataProductStatusList มาแสดงใน listbox ที่ชื่อ productStatus..
}

function splitVat()
{
	for(var i = 1; i <= counter; i++)
	{
		document.getElementById("vatValue"+i).value='';
		document.getElementById("vatValue"+i).readOnly=false;
		document.getElementById("vatValue"+i).style.backgroundColor='#FFFFFF';
		document.getElementById("myVat").value='';
		document.getElementById("myVat").readOnly=true;
		document.getElementById("myVat").style.backgroundColor='#DDDDDD';
		document.getElementById("showVat").style.visibility = 'hidden';
		document.getElementById("subVat").style.visibility = 'visible';
	}
}

function mixVat()
{
	for(var i = 1; i <= counter; i++)
	{
		document.getElementById("vatValue"+i).value='';
		document.getElementById("vatValue"+i).readOnly=true;
		document.getElementById("vatValue"+i).style.backgroundColor='#DDDDDD';
		document.getElementById("myVat").value='';
		document.getElementById("myVat").readOnly=false;
		document.getElementById("myVat").style.backgroundColor='#FFFFFF';
		document.getElementById("showVat").style.visibility = 'visible';
		document.getElementById("subVat").style.visibility = 'hidden';
	}
}

function calculateBeforeVat() // รวมราคาก่อน vat
{
	if(document.getElementById("noMoney").checked == true)
	{
		document.getElementById("beforeVat").value = '';
	}
	else
	{
		var sumBeforeVat = 0;
		for(var i = 1; i <= counter; i++)
		{
			if(document.getElementById("sumCost"+i).value != '')
			{
				sumBeforeVat += parseFloat(document.getElementById("sumCost"+i).value);
			}
		}
		
		document.getElementById("beforeVat").value = sumBeforeVat.toFixed(2);
		
		calculateAfterVat(); // ราคาหลังรวม vat
	}
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
	
	if(document.getElementById("howVat2").checked == true)
	{
		document.getElementById("myVat").value = '';
	}
	else
	{
		document.getElementById("myVat").value = sumVat.toFixed(2);
	}
	
	calculateAfterVat(); // ราคาหลังรวม vat
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

function chkHaveCode(temp) // ระบุรหัสสินค้า
{
	if(document.getElementById("haveCode"+temp).checked == true)
	{
		document.getElementById("codeProduct"+temp).value='';
		document.getElementById("codeProduct"+temp).readOnly=false;
		document.getElementById("codeProduct"+temp).style.backgroundColor='#FFFFFF';
		
		document.getElementById("amount"+temp).value='1';
		document.getElementById("amount"+temp).readOnly=true;
		document.getElementById("amount"+temp).style.backgroundColor='#DDDDDD';
	}
	else
	{
		document.getElementById("codeProduct"+temp).value='';
		document.getElementById("codeProduct"+temp).readOnly=true;
		document.getElementById("codeProduct"+temp).style.backgroundColor='#DDDDDD';
		
		document.getElementById("amount"+temp).value='1';
		document.getElementById("amount"+temp).readOnly=false;
		document.getElementById("amount"+temp).style.backgroundColor='#FFFFFF';
	}
}

function sumCost(temp) // ต้นทุนรวม
{
	if(document.getElementById("noMoney").checked == true)
	{
		document.getElementById("sumCost"+temp).value = '';
	}
	else
	{
		document.getElementById("sumCost"+temp).value = document.getElementById("amount"+temp).value * document.getElementById("costPerPiece"+temp).value;
	}
}

/*function checkUniPO()
{
	$.post("chkUniPO.php",{ // ตรวจสอบว่า ผู้ซื้อ กับ เลขที่ใบสั่งซื้อ
		buyer : document.frm1.buyer.value,
		PurchaseOrder : document.frm1.PurchaseOrder.value
	},
	function(data){
		if(data=='duplicatePO'){
			document.getElementById("PurchaseOrder").style.backgroundColor ="#FFAAAA";
			document.getElementById("buyer").style.backgroundColor ="#FFAAAA";
			document.getElementById("duplicatePO").value='1';
		}else if(data == 'NOduplicate'){
			document.getElementById("PurchaseOrder").style.backgroundColor = "#FFFFFF";
			document.getElementById("buyer").style.backgroundColor ="#FFFFFF";
			document.getElementById("duplicatePO").value='0';
		}
	});
}*/ //Comment ไว้เพื่อให้สามารถคีย์เลขที่ใบสั่งซื้อซ้ำได้

function checkUniReceipt()
{
	$.post("chkUniRecript.php",{ // ตรวจสอบว่า ผู้ซื้อ กับ เลขที่ใบสั่งซื้อ
		seller : document.frm1.seller.value,
		receiptNumber : document.frm1.receiptNumber.value
	},
	function(data){
		if(data=='duplicateReceipt'){
			document.getElementById("receiptNumber").style.backgroundColor ="#FFAAAA";
			document.getElementById("seller").style.backgroundColor ="#FFAAAA";
			document.getElementById("duplicateReceipt").value='1';
		}else if(data == 'NOduplicate'){
			document.getElementById("receiptNumber").style.backgroundColor = "#FFFFFF";
			document.getElementById("seller").style.backgroundColor ="#FFFFFF";
			document.getElementById("duplicateReceipt").value='0';
		}
	});
}
function list_model(order_number){
	var brand_name = $('#brand'+order_number).val();
	if(brand_name!='')
	{	
		$.post('list_model.php',{brand_name:brand_name},function(data){
			$('#model'+order_number).html(data);
		});		
		
		$.post('list_astype.php',{brand_name:brand_name},function(data){
			if(data != ""){
				$('#assets'+order_number).html(data);
			}
		});
	}
	else
	{	
		$('#model'+order_number).html('<option value="">--------- เลือกรุ่น ---------</option>');
	}
}

function noPrice()
{
	if(document.getElementById("noMoney").checked == true)
	{
		for(var i = 1; i <= counter; i++)
		{
			document.getElementById("vatValue"+i).value='';
			document.getElementById("vatValue"+i).readOnly=true;
			document.getElementById("vatValue"+i).style.backgroundColor='#DDDDDD';
			document.getElementById("costPerPiece"+i).value='';
			document.getElementById("costPerPiece"+i).readOnly=true;
			document.getElementById("costPerPiece"+i).style.backgroundColor='#DDDDDD';
			document.getElementById("sumCost"+i).value='';
		}
		document.getElementById("howVat1").checked = false;
		document.getElementById("howVat2").checked = false;
		document.getElementById("howVat1").disabled = true;
		document.getElementById("howVat2").disabled = true;
		document.getElementById("showVat").style.visibility = 'hidden';
		document.getElementById("subVat").style.visibility = 'hidden';
		document.getElementById("priceText").style.visibility = 'hidden';
		document.getElementById("typeVAT").style.visibility = 'hidden';
		document.getElementById("myVat").value='';
		document.getElementById("myVat").readOnly=true;
		document.getElementById("myVat").style.backgroundColor='#DDDDDD';
		document.getElementById("beforeVat").value='';
		document.getElementById("afterVat").value='';
		document.getElementById("star_beforeVat").style.visibility = 'hidden';
		document.getElementById("star_afterVat").style.visibility = 'hidden';
	}
	else
	{
		for(var i = 1; i <= counter; i++)
		{
			document.getElementById("costPerPiece"+i).value='';
			document.getElementById("costPerPiece"+i).readOnly=false;
			document.getElementById("costPerPiece"+i).style.backgroundColor='#FFFFFF';
		}
		document.getElementById("howVat1").disabled = false;
		document.getElementById("howVat2").disabled = false;
		document.getElementById("priceText").style.visibility = 'visible';
		document.getElementById("typeVAT").style.visibility = 'visible';
		document.getElementById("star_beforeVat").style.visibility = 'visible';
		document.getElementById("star_afterVat").style.visibility = 'visible';
	}
}
</script>

</head>
<body>
<center>
<h1>(THCAP) ซื้อสินทรัพย์สำหรับเช่า-ขาย</h1>
<form name="frm1" method="post" action="process_addAssets.php" enctype="multipart/form-data">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ผู้ซื้อ :</td>
						<td>
							<select name="buyer" id="buyer" onChange="checkUniPO();">
								<?php
									$qry_sale = pg_query("select * from \"thcap_company\" ");
									while($res_sale = pg_fetch_array($qry_sale))
									{
										$compID = $res_sale["compID"]; // รหัสบริษัท
										$compThaiName = $res_sale["compThaiName"]; // ชื่อบริษัทภาษาไทย
								?>
										<option value="<?php echo $compID; ?>" <?php if($compID == "THCAP"){echo "selected";} ?>><?php echo $compThaiName; ?></option>
								<?php
									}
								?>
							</select>
						</td>
						<td width="20"></td>
						<td align="right">ผู้ขาย :</td><td><input type="text" name="seller" id="seller" size="35" onKeyUp="checkUniReceipt();" onblur="checkUniReceipt();"><font color="#FF0000"><b> * </b></font></td>
						<td width="20"></td>
						<td align="right">ประเภท VAT <font name="typeVAT" id="typeVAT" color="#FF0000"><b> * </b></font> :</td>
						<td>
							<input type="radio" name="howVat" id="howVat1" value="splitVat" onchange="splitVat()">VAT แยก
							&nbsp;&nbsp;
							<input type="radio" name="howVat" id="howVat2" value="mixVat" onchange="mixVat()">VAT รวม
						</td>
						<td width="20"></td>
						<td align="right"><input type="checkbox" name="noMoney" id="noMoney" onchange="noPrice();"></td><td>ไม่ระบุราคา</td>
					</tr>
					<tr>
						<td align="right">เลขที่ใบสั่งซื้อ<span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span> :</td><td><input type="text" name="PurchaseOrder" id="PurchaseOrder" size="25" onKeyUp="checkUniPO();" onblur="checkUniPO();"></td>
						<td width="20"></td>
						<td align="right">เลขที่ใบเสร็จ<span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span> :</td><td><input type="text" name="receiptNumber" id="receiptNumber" size="25" onKeyUp="checkUniReceipt();" onblur="checkUniReceipt();"></td>
						<td width="20"></td>
						<td align="right">วันที่ซื้อ <font color="#FF0000"><b> * </b></font> :</td><td><input type="text" name="datepicker_buy" id="datepicker_buy" size="15" style="text-align:center;" value="<?php echo nowDate(); ?>"></td>
						<td width="20"></td>
						<td align="right">วันที่จ่ายเงิน :</td><td><input type="text" name="datepicker_pay" id="datepicker_pay" size="15" style="text-align:center;" value="<?php echo nowDate(); ?>"></td>
					</tr>
				</table>
				<input type="hidden" name="duplicatePO" id="duplicatePO" value="0">
				<input type="hidden" name="duplicateReceipt" id="duplicateReceipt" value="0">
			</center>
			</fieldset>
			<div style="padding:10px 10px; text-align:left; display:block; color:#ff0000;">ในกรณีที่สินค้าเป็นรถจักรยานยนต์ ให้ <b>"รหัสสินค้า(Serial Number)"</b> ใส่เป็นเลขเครื่อง และ <b>"รหัสสินค้ารอง(Secondary Serial Number)"</b> ใส่เป็นรหัสรุ่นรถ</div>
			<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
				
				<input type="button" value="+ เพิ่ม" id="addButton"> <input type="button" value="- ลบ" id="removeButton">
					
				<table id="tableDetail" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th>NO.</th>
						<th>ชื่อยี่ห้อ<font color="#FF0000"><b> * </b></font></th>
						<th>ประเภทสินทรัพย์<font color="#FF0000"><b> * </b></font> <a onclick="javascript:popU('frm_addAssetsType.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')" style="cursor:pointer;"><font color="#0000FF"><u> เพิ่ม</u></font></a></th>
						<th>ชื่อรุ่น<font color="#FF0000"><b> * </b></font></th>
						<th>รหัสสินค้า<br>(Serial Number)</th>
						<th>รหัสสินค้ารอง<br>(Secondary Serial Number)</th>
						<th>จำนวนชิ้น<font color="#FF0000"><b> * </b></font></th>
						<th>ต้นทุน/ชิ้น<font color="#FF0000" name="priceText" id="priceText"><b> * </b></font></th>
						<th>ต้นทุนรวม</th>
						<th>ภาษีมูลค่าเพิ่ม<font color="#FF0000" name="subVat" id="subVat"><b> * </b></font></th>
						<th>สถานะสินค้า<font color="#FF0000"><b> * </b></font> <a onclick="javascript:popU('frm_addProductStatus.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')" style="cursor:pointer;"><font color="#0000FF"><u> เพิ่ม</u></font></a></th>
						<th>คำอธิบาย</th>
					</tr>
					<tr bgcolor="#E8E8E8">
						<td align="center">1</td>
						<td>
                        <select name="brand1" id="brand1" onchange="list_model('1');">
                        	<option value="">--------- เลือกยี่ห้อ ---------</option>
                            <?php
							$qr = pg_query("select * from \"thcap_asset_biz_brand\" order by \"brand_name\" asc");
							if($qr)
							{
								$row = pg_num_rows($qr);
								if($row!=0){
									while($rs = pg_fetch_array($qr))
									{
										echo "
											<option value=\"".$rs['brandID']."\">".$rs['brand_name']."</option>
										";
									}
								}
							}
							?>
                        </select>
                        </td>
						<td>
							<select name="assets1" id="assets1">
								<option value="">-เลือกประเภทสินทรัพย์-</option>
								<?php
								$qry_assetsType = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeStatus\" = '1' order by \"astypeName\" ");
								while($res_assetsType=pg_fetch_array($qry_assetsType))
								{
									$astypeID = trim($res_assetsType["astypeID"]);
									$astypeName = trim($res_assetsType["astypeName"]);
								?>
									<option value="<?php echo $astypeID; ?>"><?php echo $astypeName; ?></option>
								<?php
								}
								?>
							</select>
						</td>
						<td>
                        	<select name="model1" id="model1">
                            	<option value="">--------- เลือกรุ่น ---------</option>
                            </select>
                        </td>
						<td valign="top">
							<input type="checkbox" name="haveCode1" id="haveCode1" onclick="chkHaveCode(1); sumCost(1); calculateBeforeVat();">ระบุรหัสสินค้า
							<br>
							<input type="text" name="codeProduct1" id="codeProduct1" size="17" readonly style="background:#DDDDDD;">
						</td>
						<td><input type="text" name="codeSecondary1" size="13"></td>
						<td><input type="text" name="amount1" id="amount1" size="5" style="text-align:right;" value="1" onKeyUp="sumCost(1); calculateBeforeVat();" onblur="sumCost(1); calculateBeforeVat();"></td>
						<td><input type="text" name="costPerPiece1" id="costPerPiece1" size="12" onKeyUp="sumCost(1); calculateBeforeVat();" onblur="sumCost(1); calculateBeforeVat();" style="text-align:right;"></td>
						<td><input type="text" name="sumCost1" id="sumCost1" size="15" readonly style="background:#DDDDDD; text-align:right;"></td>
						<td><input type="text" name="vatValue1" id="vatValue1" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()"></td>
						<td>
							<select name="productStatus1" id="productStatus1">
								<option value="">-เลือกสถานะสินค้า-</option>
								<?php
								$qry_productStatus = pg_query("select * from public.\"ProductStatus\" where \"UseStatus\" = '1' order by \"ProductStatusName\" ");
								while($res_productStatus = pg_fetch_array($qry_productStatus))
								{
									$ProductStatusID = trim($res_productStatus["ProductStatusID"]);
									$ProductStatusName = trim($res_productStatus["ProductStatusName"]);
								?>
									<option value="<?php echo $ProductStatusID; ?>"><?php echo $ProductStatusName; ?></option>
								<?php
								}
								?>
							</select>
						</td>
						<td><textarea name="explanation1"></textarea></td>
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
						<b>ราคาก่อน VAT : </b><input type="text" name="beforeVat" id="beforeVat" readonly style="background:#DDDDDD; text-align:right;"><font id="star_beforeVat" color="#FF0000"><b> * </b></font>
						&nbsp;&nbsp;&nbsp;
						<b>ยอด VAT : </b><input type="text" name="myVat" id="myVat" readonly style="background:#DDDDDD; text-align:right;" onKeyUp="calculateAfterVat()" onblur="calculateAfterVat()"><font color="#FF0000" name="showVat" id="showVat"><b> * </b></font>
						&nbsp;&nbsp;&nbsp;
						<b>ราคารวม VAT : </b><input type="text" name="afterVat" id="afterVat" readonly style="background:#DDDDDD; text-align:right;"><font id="star_afterVat" color="#FF0000"><b> * </b></font>
						&nbsp;&nbsp;
					</td>
				</tr>
			</table>
			
			<fieldset><legend><B>Upload</B></legend>
			<center>
				<table>
					<tr>
						<td align="right">ใบสั่งซื้อ (Purchase Order) : </td>
						<td align="left"><input type="file" name="filePO[]" id="filePO" size="70"></td>
						<td align="left"><input type="button" name="removePO" id="removePO" value="ลบ" onclick="javascript : document.getElementById('filePO').value = '';"></td>
					</tr>
					<tr>
						<td align="right">ใบเสร็จ  (Receipt) : </td>
						<td align="left"><input type="file" name="fileReceipt[]" id="fileReceipt" size="70"></td>
						<td align="left"><input type="button" name="removeReceipt" id="removeReceipt" value="ลบ" onclick="javascript : document.getElementById('fileReceipt').value = '';"></td>
					</tr>
				</table>
			</center>
			</fieldset>
			<?php IF($autoappv == 't'){ echo "<input type=\"hidden\" name=\"autoappv\" value=\"$autoappv\">"; } ?>
			<br><br>
			<input type="submit" value="บันทึก" onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="เริ่มใหม่ทั้งหมด" onclick="window.location='frm_Index.php'">
			<br>
			<input type="button" name="updatelistbox" id="updatelistbox" value="click" onclick="refreshListBox()">
			<input type="button" name="updatelistProductStatus" id="updatelistProductStatus" value="click" onclick="refreshListProductStatus()">
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
			var brandID = $('#brand' + (counter-1)).val();
			var astype = $('#assets'+ (counter-1)).val();
			var modelID = $('#model'+ (counter-1)).val();
			if(document.getElementById("noMoney").checked == true) // ถ้าไม่ระบุราคา
			{
				console.log(counter);
				var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
				table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
				+ '	<tr bgcolor="#E8E8E8">'
				+ '		<td align="center" width="25">'+ counter +'</td>'
				+ '		<td>'
				+ '			<select name="brand'+ counter +'" id="brand'+ counter +'"  onchange="list_model('+ counter +');">'
				+ '				<option value="">--------- เลือกยี่ห้อ ---------</option>'
								<?php
									$qr = pg_query("select * from \"thcap_asset_biz_brand\" order by \"brand_name\" asc");
									if($qr)
									{
										$row = pg_num_rows($qr);
										if($row!=0){
											while($rs = pg_fetch_array($qr))
											{
											?>
				+ '								<option value="<?php echo $rs['brandID']; ?>"><?php echo $rs['brand_name']; ?></option>'
											<?php
											}
										}
									}
								?>
				+ '			</select>'
				+ '		</td>'
				+ '		<td>'
				+ '			<select name="assets'+ counter +'" id="assets'+ counter +'"><option value="">-เลือกประเภทสินทรัพย์-</option>'
							<?php
							$qry_assetsType = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeStatus\" = '1' order by \"astypeName\" ");
							while($res_assetsType=pg_fetch_array($qry_assetsType))
							{
								$astypeID = trim($res_assetsType["astypeID"]);
								$astypeName = trim($res_assetsType["astypeName"]);
							?>
				+ '				<option value="<?php echo $astypeID; ?>"><?php echo $astypeName; ?></option>'
							<?php
							}
							?>
				+ '			</select>'
				+ '		</td>'
				+ '		<td>'
				+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
				+ '		</td>'
				+ '		<td valign="top">'
				+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();">ระบุรหัสสินค้า'
				+ '			<br>'
				+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" size="17" readonly style="background:#DDDDDD;" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="codeSecondary'+ counter +'" size="13" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" readonly size="12" style="background:#DDDDDD; text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<select name="productStatus'+ counter +'" id="productStatus'+ counter +'"><option value="">-เลือกสถานะสินค้า-</option>'
								<?php
								$qry_productStatus = pg_query("select * from public.\"ProductStatus\" where \"UseStatus\" = '1' order by \"ProductStatusName\" ");
								while($res_productStatus = pg_fetch_array($qry_productStatus))
								{
									$ProductStatusID = trim($res_productStatus["ProductStatusID"]);
									$ProductStatusName = trim($res_productStatus["ProductStatusName"]);
								?>
				+ '					<option value="<?php echo $ProductStatusID; ?>"><?php echo $ProductStatusName; ?></option>'
								<?php
								}
								?>
				+ '			</select>'
				+ '		</td>'
				+ '		<td>'
				+ '			<textarea name="explanation'+ counter +'"></textarea>'
				+ '		</td>'
				+ '	</tr>'
				+ '	</table>'
				
				newTextBoxDiv.html(table);

				newTextBoxDiv.appendTo("#TextBoxesGroup1");
					
				document.getElementById("rowDetail").value = counter;
				
				refreshMyListBox(counter); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
				refreshMyListProductStatus(counter); // refresh สถานะสินค้า เฉพาะของตัวเอง
				
			
				
			}
			else // ถ้าระบุราคา
			{
				if(document.getElementById("howVat1").checked == true) // ถ้า แยก VAT
				{
					console.log(counter);
					var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
					table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
					+ '	<tr bgcolor="#E8E8E8">'
					+ '		<td align="center" width="25">'+ counter +'</td>'
					+ '		<td>'
					+ '			<select name="brand'+ counter +'" id="brand'+ counter +'" onChange="list_model('+ counter +');">'
					+ '				<option value="">--------- เลือกยี่ห้อ ---------</option>'
									<?php
										$qr = pg_query("select * from \"thcap_asset_biz_brand\" order by \"brand_name\" asc");
										if($qr)
										{
											$row = pg_num_rows($qr);
											if($row!=0){
												while($rs = pg_fetch_array($qr))
												{
												?>
					+ '								<option value="<?php echo $rs['brandID']; ?>"><?php echo $rs['brand_name']; ?></option>'
												<?php
												}
											}
										}
									?>
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="assets'+ counter +'" id="assets'+ counter +'"><option value="">-เลือกประเภทสินทรัพย์-</option>'
								<?php
								$qry_assetsType = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeStatus\" = '1' order by \"astypeName\" ");
								while($res_assetsType=pg_fetch_array($qry_assetsType))
								{
									$astypeID = trim($res_assetsType["astypeID"]);
									$astypeName = trim($res_assetsType["astypeName"]);
								?>
					+ '				<option value="<?php echo $astypeID; ?>"><?php echo $astypeName; ?></option>'
								<?php
								}
								?>
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
					+ '		</td>'
					+ '		<td valign="top">'
					+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();">ระบุรหัสสินค้า'
					+ '			<br>'
					+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" size="17" readonly style="background:#DDDDDD;" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="codeSecondary'+ counter +'" size="13" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" size="12" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" size="15" onKeyUp="calculateMyVat()" style="text-align:right;" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="productStatus'+ counter +'" id="productStatus'+ counter +'"><option value="">-เลือกสถานะสินค้า-</option>'
									<?php
									$qry_productStatus = pg_query("select * from public.\"ProductStatus\" where \"UseStatus\" = '1' order by \"ProductStatusName\" ");
									while($res_productStatus = pg_fetch_array($qry_productStatus))
									{
										$ProductStatusID = trim($res_productStatus["ProductStatusID"]);
										$ProductStatusName = trim($res_productStatus["ProductStatusName"]);
									?>
					+ '					<option value="<?php echo $ProductStatusID; ?>"><?php echo $ProductStatusName; ?></option>'
									<?php
									}
									?>
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<textarea name="explanation'+ counter +'"></textarea>'
					+ '		</td>'
					+ '	</tr>'
					+ '	</table>'
					
					newTextBoxDiv.html(table);

					newTextBoxDiv.appendTo("#TextBoxesGroup1");
						
					document.getElementById("rowDetail").value = counter;
					
					refreshMyListBox(counter); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
					refreshMyListProductStatus(counter); // refresh สถานะสินค้า เฉพาะของตัวเอง
				}
				else // ถ้า รวม VAT
				{
					console.log(counter);
					var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
					table = '<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">'
					+ '	<tr bgcolor="#E8E8E8">'
					+ '		<td align="center" width="25">'+ counter +'</td>'
					+ '		<td>'
					+ '			<select name="brand'+ counter +'" id="brand'+ counter +'"  onchange="list_model('+ counter +');">'
					+ '				<option value="">--------- เลือกยี่ห้อ ---------</option>'
									<?php
										$qr = pg_query("select * from \"thcap_asset_biz_brand\" order by \"brand_name\" asc");
										if($qr)
										{
											$row = pg_num_rows($qr);
											if($row!=0){
												while($rs = pg_fetch_array($qr))
												{
												?>
					+ '								<option value="<?php echo $rs['brandID']; ?>"><?php echo $rs['brand_name']; ?></option>'
												<?php
												}
											}
										}
									?>
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="assets'+ counter +'" id="assets'+ counter +'"><option value="">-เลือกประเภทสินทรัพย์-</option>'
								<?php
								$qry_assetsType = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeStatus\" = '1' order by \"astypeName\" ");
								while($res_assetsType=pg_fetch_array($qry_assetsType))
								{
									$astypeID = trim($res_assetsType["astypeID"]);
									$astypeName = trim($res_assetsType["astypeName"]);
								?>
					+ '				<option value="<?php echo $astypeID; ?>"><?php echo $astypeName; ?></option>'
								<?php
								}
								?>
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option>'
					+ '			</select>'
					+ '		</td>'
					+ '		<td valign="top">'
					+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();">ระบุรหัสสินค้า'
					+ '			<br>'
					+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" size="17" readonly style="background:#DDDDDD;" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="codeSecondary'+ counter +'" size="13" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" size="12" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="productStatus'+ counter +'" id="productStatus'+ counter +'"><option value="">-เลือกสถานะสินค้า-</option>'
									<?php
									$qry_productStatus = pg_query("select * from public.\"ProductStatus\" where \"UseStatus\" = '1' order by \"ProductStatusName\" ");
									while($res_productStatus = pg_fetch_array($qry_productStatus))
									{
										$ProductStatusID = trim($res_productStatus["ProductStatusID"]);
										$ProductStatusName = trim($res_productStatus["ProductStatusName"]);
									?>
					+ '					<option value="<?php echo $ProductStatusID; ?>"><?php echo $ProductStatusName; ?></option>'
									<?php
									}
									?>
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<textarea name="explanation'+ counter +'"></textarea>'
					+ '		</td>'
					+ '	</tr>'
					+ '	</table>'
					
					newTextBoxDiv.html(table);

					newTextBoxDiv.appendTo("#TextBoxesGroup1");
						
					document.getElementById("rowDetail").value = counter;
					
					refreshMyListBox(counter); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
					refreshMyListProductStatus(counter); // refresh สถานะสินค้า เฉพาะของตัวเอง
				}
			}
				$('#brand' + counter).val(brandID);
				$('#assets'+ counter).val(astype);
				$.post('list_model.php',{brand_name:brandID,selectModel:modelID},function(data){
				if(data != ""){
					$('#model'+counter).html(data);
				}
				});
		}
    }
	);

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