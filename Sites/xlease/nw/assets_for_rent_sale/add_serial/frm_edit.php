<?php
include("../../../config/config.php");

$bill_id=$_GET["bill_id"];
$assetID_arr = split("#",$bill_id);
$assetID = $assetID_arr[0];
$qry_asset_biz = pg_query("select * from \"thcap_asset_biz\" where \"assetID\" = '$assetID'");
$row_asset_biz = pg_num_rows($qry_asset_biz);
while($res_asset_biz = pg_fetch_array($qry_asset_biz))
{
	$compID = $res_asset_biz["compID"]; // ID บริษัท (ผู้ซื้อ)
	$corpID = $res_asset_biz["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
	$receiptNumbershow = $res_asset_biz["receiptNumber"]; // เลขที่ใบเสร็จ
	$PurchaseOrder = $res_asset_biz["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
	$buyDate = $res_asset_biz["buyDate"]; // วันที่ซื้อ
	$payDate = $res_asset_biz["payDate"]; // วันที่จ่ายเงิน
	$vatStatus = $res_asset_biz["vatStatus"]; // ประเภท vat
	$beforeVat = $res_asset_biz["beforeVat"]; // ราคาก่อน vat
	$mainVAT_value = $res_asset_biz["VAT_value"]; // ยอด vat
	$afterVat = $res_asset_biz["afterVat"]; // ราคาหลังรวม vat
	//$assetID = $res_asset_biz["assetID"]; // ราคาหลังรวม vat
	IF($receiptNumbershow != ""){
		$sendback = $receiptNumbershow;
	}else{
		$sendback = $PurchaseOrder;
	}
	
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
</script>
<form name="frm" action="process_add.php" method="POST">
<table width="80%" align="center">
<?php if($row_asset_biz > 0){ ?>
	<tr>
		<td align="center" colspan="2">
			<fieldset><legend><B>ข้อมูลหลัก</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ผู้ซื้อ :</td>
						<td><?php echo $compThaiName; ?></td>
						<td width="40"></td>
						<td align="right">ผู้ขาย :</td><td><?php echo $fullnameCorp; ?></td>
						<td width="40"></td>
						<td align="right">ประเภท VAT :</td><td><?php echo $vatStatusTXT; ?></td>
					</tr>
					<tr>
						<?php if($receiptNumbershow != ""){ ?>
							<td align="right">เลขที่ใบเสร็จ :</td><td><?php echo $receiptNumbershow; ?></td>
						<?php }else{	?>
							<td align="right">เลขที่ใบสั่งซื้อ :</td><td><?php echo $PurchaseOrder; ?></td>
						<?php } ?>	
						<td width="40"></td>
						<td align="right">วันที่ซื้อ :</td><td><?php echo $buyDate; ?></td>
						<td width="40"></td>
						<td align="right">วันที่จ่ายเงิน :</td><td><?php echo $payDate; ?></td>
					</tr>
				</table>
			</center>
			</fieldset>		
		</td>
	</tr>	
	<tr>
		<td align="center" colspan="2">
				<fieldset><legend><B>รายละเอียด</B></legend>
			<center>
	
				<table id="tableDetail" align="center" width="99%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th>NO.</th>
						<th>ชื่อยี่ห้อ</th>
						<th>ประเภทสินทรัพย์</th>
						<th>ชื่อรุ่น</th>
						<th>รหัสสินค้า</th>
						<th>รหัสสินค้ารอง</th>
						<th>ต้นทุน/ชิ้น</th>
						<th>ภาษีมูลค่าเพิ่ม</th>
						<th>สถานะสินค้า</th>
						<th>คำอธิบาย</th>
					</tr>
					<?php
						$i = 0;
						$qry_asset_biz_detail = pg_query("select a.*,b.\"model_name\",c.\"brand_name\"
															from \"thcap_asset_biz_detail\" a
															left join \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
															left join \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"	
															where a.\"assetID\" = '$assetID' 
															order by a.\"assetDetailID\" ");
						$qry_as_list_rows = pg_num_rows($qry_asset_biz_detail);
						while($res_asset_biz_detail = pg_fetch_array($qry_asset_biz_detail))
						{
							$i++;
							$brand = $res_asset_biz_detail["brand_name"]; // ชื่อยี่ห้อ
							$astypeID = $res_asset_biz_detail["astypeID"]; // ประเภทสินทรัพย์
							$model = $res_asset_biz_detail["model_name"]; // ชื่อรุ่น
							$explanation = $res_asset_biz_detail["explanation"]; // คำอธิบาย
							$pricePerUnit = $res_asset_biz_detail["pricePerUnit"]; // ต้นทุนราคารวมภาษี/ชิ้น
							$productCode = $res_asset_biz_detail["productCode"]; // รหัสสินค้า
							$secondaryID = $res_asset_biz_detail["secondaryID"]; // รหัสสินค้ารอง
							$VAT_value = $res_asset_biz_detail["VAT_value"]; // ยอด VAT
							$ProductStatusID = $res_asset_biz_detail["ProductStatusID"]; // สถานะสินค้า
							$assetDetailID = $res_asset_biz_detail["assetDetailID"]; // รหัสสินค้า
							
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
								<td width="5%" align="center" ><?php echo $i; ?></td>
								<td width="10%"><?php echo $brand; ?></td> <!-- ชื่อยี่ห้อ -->
								<td width="10%"><?php echo $astypeName; ?></td> <!-- ประเภท -->
								<td width="10%"><?php echo $model; ?></td> <!-- รุ่น -->
								<td width=""><!--หากรหัสสินค้าหลักไม่มีค่า-->
								<?php if($productCode == ""){ //ตรวจสอบว่ารออนุมัติอยู่หรือไม่
										$qry_serialtemp = pg_query("SELECT \"productCode\" FROM thcap_asset_biz_serial_temp where \"assetDetailID\" = '$assetDetailID' AND \"app_status\" = '0' AND  \"productCode\" is not null");
										$row_seialtemp = pg_num_rows($qry_serialtemp);
										if($row_seialtemp > 0){
											list($waitserail) = pg_fetch_array($qry_serialtemp); 
											echo "รออนุมัติ: <b>$waitserail</b>";
											echo "<input type=\"hidden\" name=\"codeProduct[]\" value=\"\">";	
										}else{
											echo "<input type=\"text\" name=\"codeProduct[]\" id=\"codeProduct\" size=\"20\" >";
										
										}								
								?>
								<?php }else{ ?><!--หากรหัสสินค้าหลักมีค่า-->
									<?php echo $productCode; ?>
								<?php } ?>
								</td>
								<td width=""><!--หากรหัสสินค้ารองไม่มีค่า-->
								<?php if($secondaryID == ""){ //ตรวจสอบว่ารออนุมัติอยู่หรือไม่
										$qry_serialtemp = pg_query("SELECT \"secondaryID\" FROM thcap_asset_biz_serial_temp where \"assetDetailID\" = '$assetDetailID' AND \"app_status\" = '0' AND  \"secondaryID\" is not null");
										$row_seialtemp = pg_num_rows($qry_serialtemp);
										if($row_seialtemp > 0){
											list($waitserail) = pg_fetch_array($qry_serialtemp); 
											echo "รออนุมัติ: <b>$waitserail</b>	";
											echo "<input type=\"hidden\" name=\"secondaryID[]\" value=\"\">";		
										}else{
											echo "<input type=\"text\" name=\"secondaryID[]\" id=\"secondaryID\" size=\"20\" >";
										
										}								
								?>
								<?php }else{ ?><!--หากรหัสสินค้ารองมีค่า-->
									<?php echo $secondaryID; ?>
								<?php } ?>
								</td>	
								<td width="10%" align="right">
									<?php if($pricePerUnit == ""){ //หากไม่มีราคาสินค้า
											//ตรวจสอบว่ารออนุมัติอยู่หรือไม่
												$qry_serialtemp = pg_query("SELECT \"pricePerUnit\" FROM thcap_asset_biz_serial_temp where \"assetDetailID\" = '$assetDetailID' AND \"app_status\" = '0' AND  \"pricePerUnit\" is not null");
												$row_seialtemp = pg_num_rows($qry_serialtemp);
												if($row_seialtemp > 0){
													list($waitserail) = pg_fetch_array($qry_serialtemp); 
													echo "รออนุมัติ: <b>".number_format($waitserail,2)."</b>	";
													echo "<input type=\"hidden\" name=\"pricePerUnit[]\" value=\"\">";		
												}else{
													echo "<input type=\"text\" name=\"pricePerUnit[]\" id=\"pricePerUnit\" size=\"20\" >";
												
												}								
									?>
									<?php }else{ //--หากมีราคาสินค้า
											echo number_format($pricePerUnit,2);
										  } ?>
								
								
								
								</td> <!-- ต้นทุน/ชิ้น -->								
								<td width="10%" align="right">
									<?php if($VAT_value == ""){ //หากไม่มี vat
											//ตรวจสอบว่าเป็นการซ์้อแบบรวม vat หรือไม่
												if($vatStatus == 1){ //หากเป็นแบบ vat แยกถึงจะใส่ได้

														//ตรวจสอบว่ารออนุมัติอยู่หรือไม่
															$qry_serialtemp = pg_query("SELECT \"VAT_value\" FROM thcap_asset_biz_serial_temp where \"assetDetailID\" = '$assetDetailID' AND \"app_status\" = '0' AND  \"VAT_value\" is not null");
															$row_seialtemp = pg_num_rows($qry_serialtemp);
															if($row_seialtemp > 0){
																list($VAT_valueshow) = pg_fetch_array($qry_serialtemp); 
																echo "รออนุมัติ: <b>".number_format($VAT_valueshow,2)."</b>	";
																echo "<input type=\"hidden\" name=\"VAT_value[]\" value=\"\">";		
															}else{
																echo "<input type=\"text\" name=\"VAT_value[]\" id=\"VAT_value\" size=\"20\" >";
															}
												}			
									?>
									<?php }else{ //--หากมี vat
											echo number_format($VAT_value,2);
										  } ?>							
								</td><!-- ภาษี -->
								<td width="10%"><?php echo $ProductStatusName; ?></td><!-- สถานะ -->
								<td width=""><textarea name="explanation1" readonly style="background-color:#EEEEEE"><?php echo $explanation; ?></textarea></td><!-- คำอธิบาย -->
							</tr>
							<input type="hidden" name="hdid[]" value="<?php echo $assetDetailID; ?>"><!--ส่งรหัสสินค้าไปด้วย-->
					<?php
						
						}
					?>
					<input type="hidden" name="receiprid" value="<?php echo $sendback; ?>"> <!--ส่งรหัสใบเสร็จไปด้วย-->
				</table>
			</fieldset>		
		</td>
	</tr>
	<?php if($qry_as_list_rows != 0){ ?>
	<tr>
		<td align="right">
			
			<input type="submit" value="บันทึก" onclick="if(confirm('ยืนยันการเก็บข้อมูล?')==true){ frm.submit(this);}else{ return false;}" style="width:140px;height:50px;">
		</td>
		<td align="left">
			<input type="button" value="ยกเลิก" onclick="if(confirm('ยกเลิกการเพิ่มรหัสสินทรัพย์?')==true){ window.location='index.php';}else{ return false;}" style="width:140px;height:50px;">
		</td>
	</tr>
	<?php } ?>
<?php }else{ ?>	
	<tr>
		<td align="center">
			<h1> -- ไม่พบข้อมูลที่ท่านต้องการ  กรุณาลองใหม่อีกครั้ง -- </h1>
		</td>
	</tr>
<?php } ?>
</table>
</form>	