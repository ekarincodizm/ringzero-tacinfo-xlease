<?php
include("../../config/config.php");

$tempID = $_GET["tempID"]; // รหัส temp

/*
$corpID = $_GET["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
$receiptNumber = $_GET["receiptNumber"]; // เลขที่ใบเสร็จ
$doerID = $_GET["doerID"]; // ผู้ทำรายการ
$doerStamp = $_GET["doerStamp"]; // วันเวลาที่ทำรายการ
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตรวจสอบสินทรัพย์สำหรับเช่า-ขาย</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <!--<link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>-->
	
	<script language="javascript" type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">
	<script language="javascript" type="text/javascript" src="js/jquery.coolfieldset.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.coolfieldset.css" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	
<script type="text/javascript">

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	if (!newWindow.opener) newWindow.opener = self;
}
</script>

<script language="javascript">
$(document).ready(function(){
	$("#show").hide();
	$("#chksell1").click(function(){
		$("#show").show();
	});
	$("#chksell2").click(function(){
		$("#show").hide();
	});
	$("#dateContact").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#submitbutton").click(function(){
		$("#submitbutton").attr('disabled', true);
		//ตรวจสอบว่ามีการ key ข้อมูลหรือไม่
		if (document.getElementById("chksell1").checked==false && document.getElementById("chksell2").checked==false){
			alert('กรุณาระบุว่าบิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?');
			$("#submitbutton").attr('disabled', false);
			return false;
        }
		if (document.getElementById("chksell1").checked==true){
			if(document.getElementById("rule1").checked==false &&
			document.getElementById("rule2").checked==false && 
			document.getElementById("rule3").checked==false &&
			document.getElementById("rule4").checked==false &&
			document.getElementById("rule5").checked==false){	
				alert('กรุณาระบุว่าในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใด');
				$("#submitbutton").attr('disabled', false);
				return false;
			}
         }
		 if($("#cusContact").val()==""){
			alert('กรุณาระบุผู้ที่ติดต่อในการสอบถามข้อมูล');
			$('#cusContact').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusPost").val()==""){
			alert('กรุณาระบุตำแหน่งข้อผู้ที่ติดต่อ');
			$('#cusPost').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusTel").val()==""){
			alert('กรุณาระบุเบอร์ของผู้ที่ติดต่อ');
			$('#cusTel').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#dateContact").val()==""){
			alert('กรุณาระบุวันที่ติดต่อ');
			$('#dateContact').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#note").val()==""){
			alert('กรุณาระบุเหตุผล');
			$('#note').focus();
			$("#submitbutton").attr('disabled', false);
			return false;
		 }
		
		var chksel;
		if(document.getElementById("chksell1").checked==true){
			chksel=$("#chksell1").val();
		}else{
			chksel=$("#chksell2").val();
		}
		
		if(document.getElementById("rule1").checked==true){
			rule=$("#rule1").val();
		}else if(document.getElementById("rule2").checked==true){
			rule=$("#rule2").val();
		}else if(document.getElementById("rule3").checked==true){
			rule=$("#rule3").val();
		}else if(document.getElementById("rule4").checked==true){
			rule=$("#rule4").val();
		}else if(document.getElementById("rule5").checked==true){
			rule=$("#rule5").val();
		}else{
			rule="null";
		}
		
		var hour = $('#hour option:selected').attr('value');
		var min = $('#minute option:selected').attr('value');
		
		
	});
	$("#dontsubmitbutton").click(function(){
		$("#dontsubmitbutton").attr('disabled', true);
		//ตรวจสอบว่ามีการ key ข้อมูลหรือไม่
		if (document.getElementById("chksell1").checked==false && document.getElementById("chksell2").checked==false){
			alert('กรุณาระบุว่าบิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?');
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
        }
		if (document.getElementById("chksell1").checked==true){
			if(document.getElementById("rule1").checked==false &&
			document.getElementById("rule2").checked==false && 
			document.getElementById("rule3").checked==false &&
			document.getElementById("rule4").checked==false &&
			document.getElementById("rule5").checked==false){	
				alert('กรุณาระบุว่าในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใด');
				$("#dontsubmitbutton").attr('disabled', false);
				return false;
			}
         }
		 if($("#cusContact").val()==""){
			alert('กรุณาระบุผู้ที่ติดต่อในการสอบถามข้อมูล');
			$('#cusContact').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusPost").val()==""){
			alert('กรุณาระบุตำแหน่งข้อผู้ที่ติดต่อ');
			$('#cusPost').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#cusTel").val()==""){
			alert('กรุณาระบุเบอร์ของผู้ที่ติดต่อ');
			$('#cusTel').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#dateContact").val()==""){
			alert('กรุณาระบุวันที่ติดต่อ');
			$('#dateContact').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		 if($("#note").val()==""){
			alert('กรุณาระบุหมายเหตุ');
			$('#note').focus();
			$("#dontsubmitbutton").attr('disabled', false);
			return false;
		 }
		var chksel;
		if(document.getElementById("chksell1").checked==true){
			chksel=$("#chksell1").val();
		}else{
			chksel=$("#chksell2").val();
		}
		
		if(document.getElementById("rule1").checked==true){
			rule=$("#rule1").val();
		}else if(document.getElementById("rule2").checked==true){
			rule=$("#rule2").val();
		}else if(document.getElementById("rule3").checked==true){
			rule=$("#rule3").val();
		}else if(document.getElementById("rule4").checked==true){
			rule=$("#rule4").val();
		}else if(document.getElementById("rule5").checked==true){
			rule=$("#rule5").val();
		}else{
			rule="null";
		}
		
		var hour = $('#hour option:selected').attr('value');
		var min = $('#minute option:selected').attr('value');
		
		
	});
	
});
</script>

<!---- หน้าต่าง Popup รูปภาพ ---->

<!-- Add jQuery library -->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

<script type="text/javascript">
	$(document).ready(function() {
	
		$('.fancyboxa').fancybox({
			minWidth: 1000,
			maxWidth: 1000
					
		});
		$('.fancyboxb').fancybox({	
			minWidth: 1000,
			maxWidth: 1000
		  });
		
		$(".pdforpic").fancybox({
		   minWidth: 500,
		   maxWidth: 800,
		   'height' : '600',
		   'autoScale' : true,
		   'transitionIn' : 'none',
		   'transitionOut' : 'none',
		   'type' : 'iframe'
		});

	});
</script>
<!---- จบหน้าต่าง Popup รูปภาพ ---->

</head>
<body>

<?php
//$qry_asset_biz = pg_query("select * from \"thcap_asset_biz_temp\" where \"corpID\" = '$corpID' and \"receiptNumber\" = '$receiptNumber' and \"doerID\" = '$doerID' and \"doerStamp\" = '$doerStamp' and \"Approved\" is null ");
$qry_asset_biz = pg_query("select * from \"thcap_asset_biz_temp\" where \"tempID\" = '$tempID' and \"Approved\" is null ");
while($res_asset_biz = pg_fetch_array($qry_asset_biz))
{
	$compID = $res_asset_biz["compID"]; // ID บริษัท (ผู้ซื้อ)
	$corpID = $res_asset_biz["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
	$PurchaseOrder = $res_asset_biz["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
	$receiptNumber = $res_asset_biz["receiptNumber"]; // เลขที่ใบเสร็จ
	$buyDate = $res_asset_biz["buyDate"]; // วันที่ซื้อ
	$payDate = $res_asset_biz["payDate"]; // วันที่จ่ายเงิน
	$vatStatus = $res_asset_biz["vatStatus"]; // ประเภท vat
	$beforeVat = $res_asset_biz["beforeVat"]; // ราคาก่อน vat
	$mainVAT_value = $res_asset_biz["VAT_value"]; // ยอด vat
	$afterVat = $res_asset_biz["afterVat"]; // ราคาหลังรวม vat
	$doerID = $res_asset_biz["doerID"]; // ผู้ทำรายการ
	$doerStamp = $res_asset_biz["doerStamp"]; // วันเวลาที่ทำรายการ
	$pathFileReceipt = $res_asset_biz["pathFileReceipt"]; // ไฟล์ใบเสร็จ
	$pathFilePO = $res_asset_biz["pathFilePO"]; // ไฟล์ใบสั่งซื้อ
	
	if($vatStatus == 1)
	{
		$vatStatusTXT = "VAT แยก";
	}
	elseif($vatStatus == 2)
	{
		$vatStatusTXT = "VAT รวม";
	}
	else
	{
		$vatStatusTXT = "ไม่ระบุ";
	}
}

// หาชื่อบริษัท (ผู้ซื้อ)
$qry_nameCom = pg_query("select * from public.\"thcap_company\" where \"compID\" = '$compID' ");
while($result_name = pg_fetch_array($qry_nameCom))
{
	$compThaiName = $result_name["compThaiName"]; // ชื่อของ บริษัท (ผู้ซื้อ)
}

// หาชื่อบริษัท (ผู้ซื้อ)
$qry_nameCorp = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
while($result_name = pg_fetch_array($qry_nameCorp))
{
	$fullnameCorp = $result_name["full_name"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
}
?>

<center>
<h1>(THCAP) ตรวจสอบสินทรัพย์สำหรับเช่า-ขาย</h1>
<form name="frm1" method="post" action="process_appvAssets.php" enctype="multipart/form-data"><!--process_addAssets.php-->
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ผู้ซื้อ :</td>
						<td><?php echo $compThaiName; ?></td>
						<td width="20"></td>
						<td align="right">ผู้ขาย :</td><td><?php echo $fullnameCorp; ?></td>
						<td width="20"></td>
						<td align="right">ประเภท VAT :</td><td><?php echo $vatStatusTXT; ?></td>
						<td width="20"></td>
						<td colspan="2"><font id="noMoney" color="#FF0000" size="4" style="visibility:hidden;">ใบสินค้านี้ไม่มีการระบุราคา</font></td>
					</tr>
					<tr>
						<td align="right">เลขที่ใบสั่งซื้อ<span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span> :</td><td><?php echo $PurchaseOrder; ?></td>
						<td width="20"></td>
						<td align="right">เลขที่ใบเสร็จ<span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span> :</td><td><?php echo $receiptNumber; ?></td>
						<td width="20"></td>
						<td align="right">วันที่ซื้อ :</td><td><?php echo $buyDate; ?></td>
						<td width="20"></td>
						<td align="right">วันที่จ่ายเงิน :</td><td><?php echo $payDate; ?></td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
					
				<table id="tableDetail" align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th>NO.</th>
						<th>ชื่อยี่ห้อ</th>
						<th>ประเภทสินทรัพย์</th>
						<th>ชื่อรุ่น</th>
						<th>รหัสสินค้า<br>(Serial Number)</th>
						<th>รหัสสินค้ารอง<br>(Secondary Serial Number)</th>
						<th>ต้นทุน/ชิ้น</th>
						<th>ภาษีมูลค่าเพิ่ม</th>
						<th>สถานะสินค้า</th>
						<th>คำอธิบาย</th>
					</tr>
					<?php
						$i = 0;
						$haveMoney = 0;
						$haveVat = 0;
						//$qry_asset_biz_detail_temp = pg_query("select * from \"thcap_asset_biz_detail_temp\" where \"receiptNumber\" = '$receiptNumber' and \"doerID\" = '$doerID' and \"doerStamp\" = '$doerStamp' and \"Approved\" is null ");
						$qry_asset_biz_detail_temp = pg_query("select a.*,b.\"model_name\",c.\"brand_name\" from \"thcap_asset_biz_detail_temp\" a
															left join \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
															left join \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
															where a.\"tempAssetID\" = '$tempID' 
															and a.\"doerID\" = '$doerID' 
															and a.\"doerStamp\" = '$doerStamp' 
															and a.\"Approved\" is null ");
						while($res_asset_biz_detail_temp = pg_fetch_array($qry_asset_biz_detail_temp))
						{
							$i++;
							$brand = $res_asset_biz_detail_temp["brand_name"]; // ชื่อยี่ห้อ
							$astypeID = $res_asset_biz_detail_temp["astypeID"]; // ประเภทสินทรัพย์
							$model = $res_asset_biz_detail_temp["model_name"]; // ชื่อรุ่น
							$explanation = $res_asset_biz_detail_temp["explanation"]; // คำอธิบาย
							$pricePerUnit = $res_asset_biz_detail_temp["pricePerUnit"]; // ต้นทุนราคารวมภาษี/ชิ้น
							$productCode = $res_asset_biz_detail_temp["productCode"]; // รหัสสินค้า
							$secondaryID = $res_asset_biz_detail_temp["secondaryID"]; // รหัสสินค้ารอง
							$VAT_value = $res_asset_biz_detail_temp["VAT_value"]; // ยอด VAT
							$ProductStatusID = $res_asset_biz_detail_temp["ProductStatusID"]; // สถานะสินค้า
							
							if($pricePerUnit != ""){$haveMoney++;}
							if($VAT_value != ""){$haveVat++;}
							
							// หาชื่อประเภทสินทรัพย์
							$qry_astype = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeID\" = '$astypeID' ");
							while($result_astype = pg_fetch_array($qry_astype))
							{
								$astypeName = $result_astype["astypeName"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
							}
							
							// หาชื่อสถานะสินค้า
							if($ProductStatusID != "") // ถ้าไม่ว่าง
							{
								$qry_pStatus = pg_query("select * from public.\"ProductStatus\" where \"ProductStatusID\" = '$ProductStatusID' ");
								$row_pStatus = pg_num_rows($qry_pStatus);
								if($row_pStatus > 0) // ถ้ามีข้อมูล
								{
									while($result_pStatus = pg_fetch_array($qry_pStatus))
									{
										$ProductStatusName = $result_pStatus["ProductStatusName"]; // ชื่อของ สถานะสินค้า
									}
								}
								else // ถ้าไม่มีข้อมูล
								{
									$ProductStatusName = "";
								}
							}
							else // ถ้าไม่ได้ระบุไว้
							{
								$ProductStatusName = "";
							}
					?>
							<tr bgcolor="#E8E8E8">
								<td align="center"><?php echo $i; ?></td>
								<td><input type="text" name="brand1" id="brand1" size="20" value="<?php echo $brand; ?>" readonly></td>
								<td>
									<input type="text" size="20" value="<?php echo $astypeName; ?>" readonly>
								</td>
								<td><input type="text" name="model1" id="model1" size="20" value="<?php echo $model; ?>" readonly></td>
								<td><input type="text" name="codeProduct1" id="codeProduct1" size="20" value="<?php echo $productCode; ?>" readonly></td>
								<td><input type="text" size="20" value="<?php echo $secondaryID; ?>" readonly></td>
								<td><input type="text" name="costPerPiece1" style="text-align:right;" size="15" value="<?php if($pricePerUnit != ""){echo number_format($pricePerUnit,2);} ?>" readonly></td>
								<td><input type="text" size="15" style="text-align:right;" value="<?php if($VAT_value != ""){echo number_format($VAT_value,2);} ?>" readonly></td>
								<td><input type="text" size="20" value="<?php echo $ProductStatusName; ?>" readonly></td>
								<td><textarea name="explanation1" readonly><?php echo $explanation; ?></textarea></td>
							</tr>
					<?php
						}
					?>
				</table>
				
				<table width="100%">
					<tr bgcolor="#CCCC99">
						<td width="100%" align="right">
							<b>ราคาก่อน VAT : <?php if($beforeVat != ""){echo number_format($beforeVat,2);} ?></b>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>ยอด VAT : <?php if($mainVAT_value != ""){echo number_format($mainVAT_value,2);} ?></b>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>ราคารวม VAT : <?php if($afterVat != ""){echo number_format($afterVat,2);} ?></b>
							&nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			<fieldset><legend><B>ไฟล์ Upload</B></legend>
			<center>
				<table>
					<tr>
						<td align="right">ใบสั่งซื้อ (Purchase Order) : </td>
						<?php
						if($pathFilePO == "")
						{
							echo "<td align=\"left\">ไม่มีไฟล์<td/>";
						}
						else
						{
						?>
							<td><a class="pdforpic" href="../upload/asset_bill/<?php echo $pathFilePO; ?>" data-fancybox-group="gallery" title="<?php echo "$pathFilePO";?>"><u> แสดงใบสั่งซื้อ </u></a></td>
						<?php
						}
						?>
					</tr>
					<tr>
						<td align="right">ใบเสร็จ  (Receipt) : </td>
						<?php
						if($pathFileReceipt == "")
						{
							echo "<td align=\"left\">ไม่มีไฟล์<td/>";
						}
						else
						{
						?>
							<td><a class="pdforpic" href="../upload/asset_bill/<?php echo $pathFileReceipt; ?>" data-fancybox-group="gallery" title="<?php echo "$pathFileReceipt";?>"><u> แสดงใบเสร็จ </u></a></td>
						<?php
						}
						?>
					</tr>
				</table>
			<center>
			</fieldset>
			
			<fieldset><legend><B>ทำรายการอนุมัติ</B></legend>
				<div align="center" style="padding:10px;"><font size="2" color="red"><b>ข้าพเจ้าได้ทำการตรวจสอบรายละเอียดตามข้อมูลด้านบนอย่างละเอียดโดยตัวข้าพเจ้าเองแล้ว และยินดีรับผิดชอบในความเสียหายทุกประการ</b></font></div>
				<table width="80%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#FFE4B5">
					<tr><td bgcolor="#FFE4B5">&nbsp;</td></tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td>
							<div style="padding-left:10px"><b>บิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?</b><font color="red"><b>*</b></font></div>
							<div style="padding-left:20px"><input type="radio" name="chksell" id="chksell1" value="1">ตรวจสอบแล้วมีการซื้อขายจริง</div>
							<div style="padding-left:20px"><input type="radio" name="chksell" id="chksell2" value="2">ตรวจสอบแล้วไม่มีการซื้อขายจริง</div>
							<div><hr></div>
						</td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5" id="show">
						<td>
							<div style="padding-left:10px"><b>ในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใดหรือไม่?</b><font color="red"><b>*</b></font></div>
							<div style="padding-left:20px"><input type="radio" name="rule" id="rule1" value="1">ไม่ผิดข้อกำหนด (ซื้อขายจริง ได้รับสินค้าครบถ้วนแล้ว)</div>
							<div style="padding-left:20px"><input type="radio" name="rule" id="rule2" value="2">ซื้อขายจริง แต่ยังไม่ได้รับสินค้า</div>
							<div style="padding-left:20px"><input type="radio" name="rule" id="rule3" value="3">มีการคืนสินค้าบางส่วนหรือทั้งหมด</div>
							<div style="padding-left:20px"><input type="radio" name="rule" id="rule4" value="4">ยกเลิกการซื้อแล้วเนื่องจากมีปัญหาสินค้า</div>
							<div style="padding-left:20px"><input type="radio" name="rule" id="rule5" value="5">ซื้อขายจริง แต่ยังได้รับสินค้าไม่ครบ</div>
							<div><hr></div>
						</td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td><div style="padding:5px;"><b>ผู้ที่ติดต่อในการสอบถามข้อมูล</b> : <input type="text" name="cusContact" id="cusContact" size="40"><font color="red"><b>*</b></font><div></td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td><div style="padding:5px;"><b>ตำแหน่งของผู้ที่ติดต่อ</b> : <input type="text" name="cusPost" id="cusPost" size="30"><font color="red"><b>*</b></font></div></td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td><div style="padding:5px;"><b>เบอร์ของผู้ที่ติดต่อ</b> : <input type="text" name="cusTel" id="cusTel" size="30"><font color="red"><b>*</b></font></div></td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td><div style="padding:5px;"><b>วันและเวลาที่ติดต่อโดยประมาณ</b> : <input type="text" name="dateContact" id="dateContact" size="10">
						เวลา
						<select id="hour">
						<?php 
						for($p=0;$p<=23;$p++){
							if($p<10){
								$p="0".$p;
							}
							echo "<option value=$p>$p</option>";
						}
						?>
						</select>:
						<select id="minute">
						<?php 
						for($pp=0;$pp<=59;$pp++){
							if($pp<10){
								$pp="0".$pp;
							}
							echo "<option value=$pp>$pp</option>";
						}
						?>
						</select>
						(ชั่วโมง:นาที)
						<font color="red"><b>*</b></font></div></td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td valign="top"><div style="padding-left:5px;"><b>:::หมายเหตุ:::</b><font color="red"><b>*</b></font></div></td>
					</tr>
					<tr align="left" bgcolor="#FFEFD5">
						<td valign="top"><div style="padding-left:5px;"><textarea name="note" id="note" cols="40" rows="3"></textarea></div></td>
					</tr>
					<tr align="center">
						<td colspan=3>
						<input type="hidden" name="method" value="approve">
						<input type="hidden" name="tempID" id="tempID" value="<?php echo $tempID;?>">
						<input name="submitbutton" type="submit"  value="อนุมัติ" >
						<input type="submit" id="dontsubmitbutton" value="ไม่อนุมัติ"/></td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
</form>
</center>
</body>

<script>
var haveMoney = '<?php echo $haveMoney; ?>';
var haveVat = '<?php echo $haveVat; ?>';

var beforeVat = '<?php echo $beforeVat; ?>';
var mainVAT_value = '<?php echo $mainVAT_value; ?>';
var afterVat = '<?php echo $afterVat; ?>';

if(haveMoney == 0 && haveVat == 0 && beforeVat == '' && mainVAT_value == '' && afterVat == '')
{
	document.getElementById("noMoney").style.visibility = 'visible';
}
</script>

</html>