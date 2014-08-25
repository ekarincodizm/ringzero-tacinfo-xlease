<?php
include("../../config/config.php");

$tempID = $_GET["tempID"]; // ถ้ามีค่า tempID แสดงว่าเป็นการแก้ไข
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แก้ไขสินทรัพย์สำหรับเช่า-ขาย ที่ไม่อนุมัติ</title>
	
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
	
	if (document.getElementById("fileReceipt").value == "" && document.frm1.receiptNumber.value !="" && document.getElementById("reuseReceipt").checked == false){
		theMessage = theMessage + "\n -->  กรุณาแนบไฟล์ ใบเสร็จ";
	}
	
	if (document.getElementById("filePO").value == "" && document.frm1.PurchaseOrder.value!="" && document.getElementById("reusePO").checked == false){
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

function refreshMyListBox(myBox, selectType) // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
{  
	var dataAssetsList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataAssetsList  
		  url: "dataForAssetsList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  data:"selectType="+selectType, // ส่งตัวแปร GET ชื่อ selectType
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

function refreshMyListProductStatus(myList, selectStatus) // refresh สถานะสินค้า เฉพาะของตัวเอง
{  
	var dataProductStatusList = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร dataProductStatusList  
		  url: "dataForProductStatusList.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  data:"selectStatus="+selectStatus, // ส่งตัวแปร GET ชื่อ selectStatus
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
}*/

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
function list_model(order_number, selectModel){
	var brand_name = $('#brand'+order_number).val();
	if(brand_name!='')
	{
		$.post('list_model.php',{brand_name:brand_name, selectModel:selectModel},function(data){
			$('#model'+order_number).html(data);
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

function chkReusePO()
{
	if(document.getElementById("reusePO").checked == true)
	{
		document.getElementById("filePO").value = '';
		document.getElementById("filePO").disabled = true;
	}
	else
	{
		document.getElementById("filePO").disabled = false;
	}
}

function chkReuseReceipt()
{
	if(document.getElementById("reuseReceipt").checked == true)
	{
		document.getElementById("fileReceipt").value = '';
		document.getElementById("fileReceipt").disabled = true;
	}
	else
	{
		document.getElementById("fileReceipt").disabled = false;
	}
}
</script>

</head>
<body>

<?php
if($tempID != "") // ถ้ามีค่า tempID แสดงว่าเป็นการแก้ไข
{
	$qry_asset_biz = pg_query("select * from \"thcap_asset_biz_temp\" where \"tempID\"='$tempID'");
		
	$qry_asset_biz_detail_temp = pg_query("select a.*,b.*,c.\"modelID\",c.\"model_name\",d.\"brandID\",d.\"brand_name\"
										from \"thcap_asset_biz_temp\" a
										left join \"thcap_asset_biz_detail_temp\" b 
										ON b.\"doerID\" = a.\"doerID\" AND b.\"doerStamp\" = a.\"doerStamp\" AND 
										b.\"appvID\" = a.\"appvID\" AND b.\"appvStamp\" = a.\"appvStamp\" AND b.\"Approved\" = a.\"Approved\" 					
										left join \"thcap_asset_biz_model\" c ON b.\"model\" = c.\"modelID\"
										left join \"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
										where a.\"tempID\" = '$tempID'");

	// จำนวนรายการสินทรัพย์
	$rowAssets = pg_num_rows($qry_asset_biz_detail_temp);

	while($res_asset_biz = pg_fetch_array($qry_asset_biz))
	{
		$compIDFromReject = $res_asset_biz["compID"]; // ID บริษัท (ผู้ซื้อ)
		$corpID = $res_asset_biz["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
		$PurchaseOrder = $res_asset_biz["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
		$receiptNumber = $res_asset_biz["receiptNumber"]; // เลขที่ใบเสร็จ
		$buyDate = $res_asset_biz["buyDate"]; // วันที่ซื้อ
		$payDate = $res_asset_biz["payDate"]; // วันที่จ่ายเงิน
		$vatStatus = $res_asset_biz["vatStatus"]; // ประเภท vat
		$beforeVat = $res_asset_biz["beforeVat"]; // ราคาก่อน vat
		$mainVAT_value = $res_asset_biz["VAT_value"]; // ยอด vat
		$afterVat = $res_asset_biz["afterVat"]; // ราคาหลังรวม vat
		$pathFileReceipt = $res_asset_biz["pathFileReceipt"]; // ไฟล์ใบเสร็จ
		$pathFilePO = $res_asset_biz["pathFilePO"]; // ไฟล์ใบสั่งซื้อ
	}
	
	// หาชื่อบริษัท (ผู้ซื้อ)
	$qry_nameCorp = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
	while($result_name = pg_fetch_array($qry_nameCorp))
	{
		$fullnameCorp = $result_name["full_name"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
	}
	
	// รหัสและชื่อบริษัท (ผู้ซื้อ)
	$corpIDForEdit = "$corpID#$fullnameCorp";
	
	$a = 0;
	while($res_asset_biz_detail_temp = pg_fetch_array($qry_asset_biz_detail_temp))
	{
		$a++;
		$brandID[$a] = $res_asset_biz_detail_temp["brandID"]; // รหัสยี่ห้อ
		$brand_name[$a] = $res_asset_biz_detail_temp["brand_name"]; // ชื่อยี่ห้อ
		$astypeIDmain[$a] = $res_asset_biz_detail_temp["astypeID"]; // ประเภทสินทรัพย์
		$modelID[$a] = $res_asset_biz_detail_temp["modelID"]; // รหัสรุ่น
		$model_name[$a] = $res_asset_biz_detail_temp["model_name"]; // ชื่อรุ่น
		$explanation[$a] = $res_asset_biz_detail_temp["explanation"]; // คำอธิบาย
		$pricePerUnit[$a] = $res_asset_biz_detail_temp["pricePerUnit"]; // ต้นทุนราคารวมภาษี/ชิ้น
		$productCode[$a] = $res_asset_biz_detail_temp["productCode"]; // รหัสสินค้า
		$secondaryID[$a] = $res_asset_biz_detail_temp["secondaryID"]; // รหัสสินค้ารอง
		$VAT_value[$a] = $res_asset_biz_detail_temp["VAT_value"]; // ยอด VAT
		$ProductStatusIDmian[$a] = $res_asset_biz_detail_temp["ProductStatusID"]; // สถานะสินค้า
		
		if($pricePerUnit[$a] != "")
		{ // ถ้ามีการระบุราคาสินค้า
			$havePrice = "yes";
		}
		
		// หาชื่อประเภทสินทรัพย์
		$qry_astype = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeID\" = '$astypeIDmain[$a]' ");
		while($result_astype = pg_fetch_array($qry_astype))
		{
			$astypeName[$a] = $result_astype["astypeName"];
		}
		
		echo "<input type=\"hidden\" id=\"brandID$a\" name=\"brandID$a\" value=\"$brandID[$a]\" >"; // รหัสยี่ห้อ
		echo "<input type=\"hidden\" id=\"brand_name$a\" name=\"brand_name$a\" value=\"$brand_name[$a]\" >"; // ชื่อยี่ห้อ
		echo "<input type=\"hidden\" id=\"astypeID$a\" name=\"astypeID$a\" value=\"$astypeIDmain[$a]\" >"; // รหัสประเภทสินทรัพย์
		echo "<input type=\"hidden\" id=\"astypeName$a\" name=\"astypeName$a\" value=\"$astypeName[$a]\" >"; // ชื่อประเภทสินทรัพย์
		echo "<input type=\"hidden\" id=\"productCode$a\" name=\"productCode$a\" value=\"$productCode[$a]\" >"; // รหัสสินค้าหลัก
		echo "<input type=\"hidden\" id=\"secondaryID$a\" name=\"secondaryID$a\" value=\"$secondaryID[$a]\" >"; // รหัสสินค้ารอง
		echo "<input type=\"hidden\" id=\"pricePerUnit$a\" name=\"pricePerUnit$a\" value=\"$pricePerUnit[$a]\" >"; // ต้นทุนต่อชิ้น
		echo "<input type=\"hidden\" id=\"VAT_value$a\" name=\"VAT_value$a\" value=\"$VAT_value[$a]\" >"; // ยอด vat
		echo "<input type=\"hidden\" id=\"ProductStatusID$a\" name=\"ProductStatusID$a\" value=\"$ProductStatusIDmian[$a]\" >"; // สถานะสินค้า
		echo "<input type=\"hidden\" id=\"explanation$a\" name=\"explanation$a\" value=\"$explanation[$a]\" >"; // คำอธิบาย
	}
}
?>

<center>
<h1>(THCAP) แก้ไขสินทรัพย์สำหรับเช่า-ขาย ที่ไม่อนุมัติ</h1>
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
							<select name="buyer" id="buyer">
								<?php
									$qry_sale = pg_query("select * from \"thcap_company\" ");
									while($res_sale = pg_fetch_array($qry_sale))
									{
										$compID = $res_sale["compID"]; // รหัสบริษัท
										$compThaiName = $res_sale["compThaiName"]; // ชื่อบริษัทภาษาไทย
								?>
										<option value="<?php echo $compID; ?>" <?php if($compID == $compIDFromReject){echo "selected";} ?>><?php echo $compThaiName; ?></option>
								<?php
									}
								?>
							</select>
						</td>
						<td width="20"></td>
						<td align="right">ผู้ขาย :</td><td><input type="text" name="seller" id="seller" size="35" onKeyUp="checkUniReceipt();" onblur="checkUniReceipt();" value="<?php echo $corpIDForEdit; ?>"><font color="#FF0000"><b> * </b></font></td>
						<td width="20"></td>
						<td align="right">ประเภท VAT <font name="typeVAT" id="typeVAT" color="#FF0000"><b> * </b></font> :</td>
						<td>
							<input type="radio" name="howVat" id="howVat1" value="splitVat" onchange="splitVat()" <?php if($vatStatus == 1){echo "checked";} ?> >VAT แยก
							&nbsp;&nbsp;
							<input type="radio" name="howVat" id="howVat2" value="mixVat" onchange="mixVat()" <?php if($vatStatus == 2){echo "checked";} ?>>VAT รวม
						</td>
						<td width="20"></td>
						<td align="right"><input type="checkbox" name="noMoney" id="noMoney" onchange="noPrice();" <?php if($havePrice != "yes"){echo "checked";} ?>></td><td>ไม่ระบุราคา</td>
					</tr>
					<tr>
						<td align="right">เลขที่ใบสั่งซื้อ<span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span> :</td><td><input type="text" name="PurchaseOrder" id="PurchaseOrder" size="25" value="<?php echo $PurchaseOrder; ?>"></td>
						<td width="20"></td>
						<td align="right">เลขที่ใบเสร็จ<span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span> :</td><td><input type="text" name="receiptNumber" id="receiptNumber" size="25" onKeyUp="checkUniReceipt();" onblur="checkUniReceipt();" value="<?php echo $receiptNumber; ?>"></td>
						<td width="20"></td>
						<td align="right">วันที่ซื้อ <font color="#FF0000"><b> * </b></font> :</td><td><input type="text" name="datepicker_buy" id="datepicker_buy" size="15" style="text-align:center;" value="<?php echo $buyDate; ?>"></td>
						<td width="20"></td>
						<td align="right">วันที่จ่ายเงิน :</td><td><input type="text" name="datepicker_pay" id="datepicker_pay" size="15" style="text-align:center;" value="<?php echo $payDate; ?>"></td>
					</tr>
				</table>
				<input type="hidden" name="duplicatePO" id="duplicatePO" value="0">
				<input type="hidden" name="duplicateReceipt" id="duplicateReceipt" value="0">
			</center>
			</fieldset>
			<div style="padding:10px 10px; text-align:left; display:block; color:#ff0000;">ในกรณีที่สินค้าเป็นรถจักรยานยนต์ ให้ <b>"รหัสสินค้า(Serial Number)"</b> ใส่เป็นเลขเครื่อง และ <b>"รหัสสินค้ารอง(Secondary Serial Number)"</b> ใส่เป็นรหัสรุ่นรถ</div>
			<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
				
				<input type="button" value="+ เพิ่ม" id="addButton" name="addButton"> <input type="button" value="- ลบ" id="removeButton">
					
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
										if($rs['brandID'] == $brandID[1])
										{
											echo "<option value=\"".$rs['brandID']."\" selected>".$rs['brand_name']."</option>";
										}
										else
										{
											echo "<option value=\"".$rs['brandID']."\">".$rs['brand_name']."</option>";
										}
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
									<option value="<?php echo $astypeID; ?>" <?php if($astypeIDmain[1] == $astypeID){echo "selected";} ?>><?php echo $astypeName; ?></option>
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
							<input type="checkbox" name="haveCode1" id="haveCode1" onclick="chkHaveCode(1); sumCost(1); calculateBeforeVat();" <?php if($productCode[1] != ""){echo "checked";} ?>>ระบุรหัสสินค้า
							<br>
							<input type="text" name="codeProduct1" id="codeProduct1" size="17" <?php if($productCode[1] == ""){echo "readonly style=\"background:#DDDDDD;\" ";} ?> value="<?php echo $productCode[1]; ?>">
						</td>
						<td><input type="text" name="codeSecondary1" size="13" value="<?php echo $secondaryID[1]; ?>"></td>
						<td><input type="text" name="amount1" id="amount1" size="5" style="text-align:right;" value="1" onKeyUp="sumCost(1); calculateBeforeVat();" onblur="sumCost(1); calculateBeforeVat();"></td>
						<td><input type="text" name="costPerPiece1" id="costPerPiece1" size="12" onKeyUp="sumCost(1); calculateBeforeVat();" onblur="sumCost(1); calculateBeforeVat();" style="text-align:right;" value="<?php echo $pricePerUnit[1]; ?>" <?php if($havePrice != "yes"){echo "readonly style=\"background:#DDDDDD;\" ";} ?>></td>
						<td><input type="text" name="sumCost1" id="sumCost1" size="15" readonly style="background:#DDDDDD; text-align:right;"></td>
						<?php
						if($vatStatus == 1)
						{
						?>
							<td><input type="text" name="vatValue1" id="vatValue1" style="text-align:right;" size="15" onKeyUp="calculateMyVat()" value="<?php echo $VAT_value[1]; ?>"></td>
						<?php
						}
						else
						{
						?>
							<td><input type="text" name="vatValue1" id="vatValue1" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" value="<?php echo $VAT_value[1]; ?>"></td>
						<?php
						}
						?>
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
									<option value="<?php echo $ProductStatusID; ?>" <?php if($ProductStatusIDmian[1] == $ProductStatusID){echo "selected";} ?>><?php echo $ProductStatusName; ?></option>
								<?php
								}
								?>
							</select>
						</td>
						<td><textarea name="explanation1"><?php echo $explanation[1]; ?></textarea></td>
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
						<b>ราคาก่อน VAT : </b><input type="text" name="beforeVat" id="beforeVat" readonly style="background:#DDDDDD; text-align:right;" value="<?php echo $beforeVat; ?>" ><font id="star_beforeVat" color="#FF0000"><b> * </b></font>
						&nbsp;&nbsp;&nbsp;
						<b>ยอด VAT : </b><input type="text" name="myVat" id="myVat" readonly style="background:#DDDDDD; text-align:right;" onKeyUp="calculateAfterVat()" onblur="calculateAfterVat()" value="<?php echo $mainVAT_value; ?>" ><font color="#FF0000" name="showVat" id="showVat"><b> * </b></font>
						&nbsp;&nbsp;&nbsp;
						<b>ราคารวม VAT : </b><input type="text" name="afterVat" id="afterVat" readonly style="background:#DDDDDD; text-align:right;" value="<?php echo $afterVat; ?>" ><font id="star_afterVat" color="#FF0000"><b> * </b></font>
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
						<td align="left"><input type="checkbox" name="reusePO" id="reusePO" onclick="chkReusePO();" <?php if($pathFilePO != ""){echo "checked";}else{echo "disabled";} ?>>ใช้ใบสั่งซื้อเดิม</td>
						<input type="hidden" name="oldFilePO" value="<?php echo $pathFilePO; ?>">
						<?php
						if($pathFilePO == "")
						{
							echo "<td align=\"left\"><font color=\"#FF0000\">ไม่มีไฟล์ใบสั่งซื้อเดิมอยู่</font></td>";
						}
						else
						{
						?>
							<td><a style="cursor:pointer;" onclick="popU('../upload/asset_bill/<?php echo $pathFilePO; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" title="<?php echo "$pathFilePO";?>"><font color="#0000FF"><u> แสดงใบสั่งซื้อเดิม </u></font></a></td>
						<?php
						}
						?>
					</tr>
					<tr>
						<td align="right">ใบเสร็จ  (Receipt) : </td>
						<td align="left"><input type="file" name="fileReceipt[]" id="fileReceipt" size="70"></td>
						<td align="left"><input type="button" name="removeReceipt" id="removeReceipt" value="ลบ" onclick="javascript : document.getElementById('fileReceipt').value = '';"></td>
						<td align="left"><input type="checkbox" name="reuseReceipt" id="reuseReceipt" onclick="chkReuseReceipt();" <?php if($pathFileReceipt != ""){echo "checked";}else{echo "disabled";} ?>>ใช้ใบเสร็จเดิม</td>
						<input type="hidden" name="oldFileReceipt" value="<?php echo $pathFileReceipt; ?>">
						<?php
						if($pathFileReceipt == "")
						{
							echo "<td align=\"left\"><font color=\"#FF0000\">ไม่มีไฟล์ใบเสร็จเดิมอยู่</font></td>";
						}
						else
						{
						?>
							<td><a style="cursor:pointer;" onclick="popU('../upload/asset_bill/<?php echo $pathFileReceipt; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"  title="<?php echo "$pathFileReceipt";?>"><font color="#0000FF"><u> แสดงใบเสร็จเดิม </u></font></a></td>
						<?php
						}
						?>
					</tr>
				</table>
			</center>
			</fieldset>
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

function addRow()
{
	counter++;
	
	var brandID = document.getElementById('brandID'+counter).value; // รหัสยี่ห้อ
	var brand_name = document.getElementById('brand_name'+counter).value; // ชื่อยี่ห้อ
	var astypeID = document.getElementById('astypeID'+counter).value; // รหัสประเภทสินทรัพย์
	var astypeName = document.getElementById('astypeName'+counter).value; // ชื่อประเภทสินทรัพย์
	var productCode = document.getElementById('productCode'+counter).value; // รหัสสินค้าหลัก
	var secondaryID = document.getElementById('secondaryID'+counter).value; // รหัสสินค้ารอง
	var pricePerUnit = document.getElementById('pricePerUnit'+counter).value; // ต้นทุนต่อชิ้น
	var VAT_value = document.getElementById('VAT_value'+counter).value; // ยอด vat
	var ProductStatusID = document.getElementById('ProductStatusID'+counter).value; // สถานะสินค้า
	var explanation = document.getElementById('explanation'+counter).value; // คำอธิบาย
	
	if(counter > 1)
	{
		if(document.getElementById("noMoney").checked == true) // ถ้าไม่ระบุราคา
		{
			if(productCode != '')
			{ // ถ้ามีรหัสสินค้า
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
				+ '								<option value="<?php echo $rs["brandID"]; ?>"><?php echo $rs["brand_name"]; ?></option>'
											<?php
											}
										}
									}
								?>
				+ '				<option value="'+brandID+'" selected>'+brand_name+'</option>'
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
				+ '				<option value="'+astypeID+'" selected>'+astypeName+'</option>'
				+ '			</select>'
				+ '		</td>'
				+ '		<td>'
				+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
				+ '		</td>'
				+ '		<td valign="top">'
				+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();" checked>ระบุรหัสสินค้า'
				+ '			<br>'
				+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" value="'+ productCode +'" size="17" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="codeSecondary'+ counter +'" value="'+ secondaryID +'" size="13" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" readonly size="12" style="background:#DDDDDD; text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="'+ pricePerUnit +'" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" value="'+ VAT_value +'" />'
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
				+ '			<textarea name="explanation'+ counter +'">'+ explanation +'</textarea>'
				+ '		</td>'
				+ '	</tr>'
				+ '	</table>'
				
				newTextBoxDiv.html(table);

				newTextBoxDiv.appendTo("#TextBoxesGroup1");
					
				document.getElementById("rowDetail").value = counter;
				
				refreshMyListBox(counter, astypeID); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
				refreshMyListProductStatus(counter, ProductStatusID); // refresh สถานะสินค้า เฉพาะของตัวเอง
			}
			else
			{ // ถ้าไม่มีรหัสสินค้าหลัก
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
				+ '								<option value="<?php echo $rs["brandID"]; ?>"><?php echo $rs["brand_name"]; ?></option>'
											<?php
											}
										}
									}
								?>
				+ '				<option value="'+brandID+'" selected>'+brand_name+'</option>'
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
				+ '				<option value="'+astypeID+'" selected>'+astypeName+'</option>'
				+ '			</select>'
				+ '		</td>'
				+ '		<td>'
				+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
				+ '		</td>'
				+ '		<td valign="top">'
				+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();">ระบุรหัสสินค้า'
				+ '			<br>'
				+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" value="'+ productCode +'" size="17" readonly style="background:#DDDDDD;" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="codeSecondary'+ counter +'" value="'+ secondaryID +'" size="13" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" readonly size="12" style="background:#DDDDDD; text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="'+ pricePerUnit +'" />'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
				+ '		</td>'
				+ '		<td>'
				+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" value="'+ VAT_value +'" />'
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
				+ '			<textarea name="explanation'+ counter +'">'+ explanation +'</textarea>'
				+ '		</td>'
				+ '	</tr>'
				+ '	</table>'
				
				newTextBoxDiv.html(table);

				newTextBoxDiv.appendTo("#TextBoxesGroup1");
					
				document.getElementById("rowDetail").value = counter;
				
				refreshMyListBox(counter, astypeID); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
				refreshMyListProductStatus(counter, ProductStatusID); // refresh สถานะสินค้า เฉพาะของตัวเอง
			}
		}
		else // ถ้าระบุราคา
		{
			if(document.getElementById("howVat1").checked == true) // ถ้า แยก VAT
			{
				if(productCode != '')
				{ // ถ้ามีรหัสสินค้า
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
					+ '				<option value="'+brandID+'" selected>'+brand_name+'</option>'
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
					+ '				<option value="'+astypeID+'" selected>'+astypeName+'</option>'
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
					+ '		</td>'
					+ '		<td valign="top">'
					+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();" checked>ระบุรหัสสินค้า'
					+ '			<br>'
					+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" value="'+ productCode +'" size="17" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="codeSecondary'+ counter +'" value="'+ secondaryID +'" size="13" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" size="12" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="'+ pricePerUnit +'" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" size="15" onKeyUp="calculateMyVat()" style="text-align:right;" value="'+ VAT_value +'" />'
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
					+ '			<textarea name="explanation'+ counter +'">'+ explanation +'</textarea>'
					+ '		</td>'
					+ '	</tr>'
					+ '	</table>'
					
					newTextBoxDiv.html(table);

					newTextBoxDiv.appendTo("#TextBoxesGroup1");
						
					document.getElementById("rowDetail").value = counter;
					
					refreshMyListBox(counter, astypeID); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
					refreshMyListProductStatus(counter, ProductStatusID); // refresh สถานะสินค้า เฉพาะของตัวเอง
				}
				else
				{ // ถ้าไม่มีรหัสสินค้า
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
					+ '				<option value="'+brandID+'" selected>'+brand_name+'</option>'
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
					+ '				<option value="'+astypeID+'" selected>'+astypeName+'</option>'
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
					+ '		</td>'
					+ '		<td valign="top">'
					+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();">ระบุรหัสสินค้า'
					+ '			<br>'
					+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" value="'+ productCode +'" size="17" readonly style="background:#DDDDDD;" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="codeSecondary'+ counter +'" value="'+ secondaryID +'" size="13" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" size="12" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="'+ pricePerUnit +'" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" size="15" onKeyUp="calculateMyVat()" style="text-align:right;" value="'+ VAT_value +'" />'
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
					+ '			<textarea name="explanation'+ counter +'">'+ explanation +'</textarea>'
					+ '		</td>'
					+ '	</tr>'
					+ '	</table>'
					
					newTextBoxDiv.html(table);

					newTextBoxDiv.appendTo("#TextBoxesGroup1");
						
					document.getElementById("rowDetail").value = counter;
					
					refreshMyListBox(counter, astypeID); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
					refreshMyListProductStatus(counter, ProductStatusID); // refresh สถานะสินค้า เฉพาะของตัวเอง
				}
			}
			else // ถ้า รวม VAT
			{
				if(productCode != '')
				{ // ถ้ามีรหัสสินค้า
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
					+ '				<option value="'+brandID+'" selected>'+brand_name+'</option>'
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
					+ '				<option value="'+astypeID+'" selected>'+astypeName+'</option>'
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
					+ '		</td>'
					+ '		<td valign="top">'
					+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();" checked>ระบุรหัสสินค้า'
					+ '			<br>'
					+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" value="'+ productCode +'" size="17" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="codeSecondary'+ counter +'" value="'+ secondaryID +'" size="13" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" size="12" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="'+ pricePerUnit +'" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" value="'+ VAT_value +'" />'
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
					+ '			<textarea name="explanation'+ counter +'">'+ explanation +'</textarea>'
					+ '		</td>'
					+ '	</tr>'
					+ '	</table>'
					
					newTextBoxDiv.html(table);

					newTextBoxDiv.appendTo("#TextBoxesGroup1");
						
					document.getElementById("rowDetail").value = counter;
					
					refreshMyListBox(counter, astypeID); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
					refreshMyListProductStatus(counter, ProductStatusID); // refresh สถานะสินค้า เฉพาะของตัวเอง
				}
				else
				{ // ถ้าไม่มีรหัสสินค้า
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
					+ '				<option value="'+brandID+'" selected>'+brand_name+'</option>'
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
					+ '				<option value="'+astypeID+'" selected>'+astypeName+'</option>'
					+ '			</select>'
					+ '		</td>'
					+ '		<td>'
					+ '			<select name="model'+ counter +'" id="model'+ counter +'" ><option value="">--------- เลือกรุ่น ---------</option></select>'
					+ '		</td>'
					+ '		<td valign="top">'
					+ '			<input type="checkbox" name="haveCode'+ counter +'" id="haveCode'+ counter +'" onclick="chkHaveCode('+ counter +'); sumCost('+ counter +'); calculateBeforeVat();">ระบุรหัสสินค้า'
					+ '			<br>'
					+ '			<input type="text" name="codeProduct'+ counter +'" id="codeProduct'+ counter +'" value="'+ productCode +'" size="17" readonly style="background:#DDDDDD;" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="codeSecondary'+ counter +'" value="'+ secondaryID +'" size="13" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="amount'+ counter +'" id="amount'+ counter +'" size="5" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="1">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="costPerPiece'+ counter +'" id="costPerPiece'+ counter +'" size="12" style="text-align:right;" onKeyUp="sumCost('+ counter +'); calculateBeforeVat();" onblur="sumCost('+ counter +'); calculateBeforeVat();" value="'+ pricePerUnit +'" />'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="sumCost'+ counter +'" id="sumCost'+ counter +'" size="15" readonly style="background:#DDDDDD; text-align:right;">'
					+ '		</td>'
					+ '		<td>'
					+ '			<input type="text" name="vatValue'+ counter +'" id="vatValue'+ counter +'" readonly style="background:#DDDDDD; text-align:right;" size="15" onKeyUp="calculateMyVat()" value="'+ VAT_value +'" />'
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
					+ '			<textarea name="explanation'+ counter +'">'+ explanation +'</textarea>'
					+ '		</td>'
					+ '	</tr>'
					+ '	</table>'
					
					newTextBoxDiv.html(table);

					newTextBoxDiv.appendTo("#TextBoxesGroup1");
						
					document.getElementById("rowDetail").value = counter;
					
					refreshMyListBox(counter, astypeID); // refresh ประเภทสินทรัพย์  เฉพาะของตัวเอง
					refreshMyListProductStatus(counter, ProductStatusID); // refresh สถานะสินค้า เฉพาะของตัวเอง
				}
			}
		}
	}
}
</script>

<script>
	checkUniReceipt();
	sumCost(1);
	chkReusePO();
	chkReuseReceipt();
</script>

<?php
	echo "<script type=\"text/javascript\">";
	echo "list_model(1, $modelID[1]);";
	echo "</script>";
?>

<?php
for($i=2;$i<=$rowAssets;$i++)
{
	echo "<script type=\"text/javascript\">";
	echo "addRow();";
	echo "list_model($i, $modelID[$i]);";
	echo "sumCost($i);";
	echo "</script>";
}

if($havePrice != "yes")
{
	echo "<script type=\"text/javascript\">";
	echo "noPrice();";
	echo "</script>";
}
?>

</html>