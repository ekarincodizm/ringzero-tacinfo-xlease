<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติเพิ่มรหัสสินทรัพย์</title>	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>	
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
</script>

<body>
<form name="frm" method="POST">
	<table width="100%">
		<tr>
			<td align="center"><h1><b>(THCAP) อนุมัติเพิ่มรหัสสินทรัพย์</b></h1></td>
		</tr>
		<tr>
			<td align="center">
				
				<table id="tableDetail" align="center" width="80%" frame="box" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#79BCFF">
							<th>NO.</th>
							<th>เลขที่ใบเสร็จ</th>
							<th>ชื่อยี่ห้อ</th>
							<th>ประเภทสินทรัพย์</th>
							<th>ชื่อรุ่น</th>
							<th>รหัสสินค้า</th>
							<th>รหัสสินค้ารอง</th>
							<th>ต้นทุน/ชิ้น</th>
							<th>ภาษีมูลค่าเพิ่ม</th>
							<th>สถานะสินค้า</th>
							<th>ผู้ขอเพิ่ม</th>
							<th>วันเวลาที่ข้อเพิ่ม</th>
							<th>เลือก</th>
						</tr>
						<?php
							$i = 0;
							$qry_asset_biz_detail = pg_query("select 
																b.\"brand\",
																b.\"astypeID\",
																b.\"model\",
																b.\"explanation\",
																a.\"pricePerUnit\" as \"pricePerUnitwait\",
																a.\"productCode\" as \"productCodewait\",
																a.\"secondaryID\" as \"secondaryIDwait\",
																a.\"VAT_value\" as \"VAT_valuewait\",
																b.\"ProductStatusID\",
																b.\"assetDetailID\",
																a.\"serialID\",
																a.\"add_user\",
																a.\"add_date\",
																b.\"productCode\" as \"productCodesame\",
																b.\"secondaryID\" as \"secondaryIDsame\",
																b.\"pricePerUnit\" as \"pricePerUnitsame\" ,
																b.\"VAT_value\" as \"VAT_valuesame\",
																b.\"receiptNumber\",
																d.\"model_name\",
																e.\"brand_name\"		
														from \"thcap_asset_biz_serial_temp\" a
														left join \"thcap_asset_biz_detail\" b on a.\"assetDetailID\" = b.\"assetDetailID\"
														left join \"thcap_asset_biz_model\" d ON b.\"model\" = d.\"modelID\"
														left join \"thcap_asset_biz_brand\" e ON b.\"brand\" = e.\"brandID\"		
														where a.\"app_status\" = '0' ");
							$row_asset = pg_num_rows($qry_asset_biz_detail);
							if($row_asset > 0){		
							while($res_asset_biz_detail = pg_fetch_array($qry_asset_biz_detail))
							{
								$i++;
								$brand = $res_asset_biz_detail["brand_name"]; // ชื่อยี่ห้อ
								$astypeID = $res_asset_biz_detail["astypeID"]; // ประเภทสินทรัพย์
								$model = $res_asset_biz_detail["model_name"]; // ชื่อรุ่น
								$explanation = $res_asset_biz_detail["explanation"]; // คำอธิบาย
								$pricePerUnit = $res_asset_biz_detail["pricePerUnitwait"]; // ต้นทุนราคารวมภาษี/ชิ้นรออนุมัติ
								$productCode = $res_asset_biz_detail["productCodewait"]; // รหัสสินค้ารออนุมัติ
								$secondaryID = $res_asset_biz_detail["secondaryIDwait"]; // รหัสสินค้ารองรออนุมัติ
								$VAT_value = $res_asset_biz_detail["VAT_valuewait"]; // ยอด VAT
								$ProductStatusID = $res_asset_biz_detail["ProductStatusID"]; // สถานะสินค้า
								$assetDetailID = $res_asset_biz_detail["assetDetailID"]; // รหัสสินค้า
								$serialID = $res_asset_biz_detail["serialID"];
								$add_user = $res_asset_biz_detail["add_user"];
								$add_date = $res_asset_biz_detail["add_date"];
								$productCodesame = $res_asset_biz_detail["productCodesame"]; // รหัสสินค้า
								$secondaryIDsame = $res_asset_biz_detail["secondaryIDsame"]; // รหัสสินค้ารอง
								$pricePerUnitsame = $res_asset_biz_detail["pricePerUnitsame"]; // ต้นทุนราคารวมภาษี/ชิ้น
								$receiptNumber = $res_asset_biz_detail["receiptNumber"]; // เลขที่ใบเสร็จ
								
								// หาชื่อประเภทสินทรัพย์
								$qry_astype = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeID\" = '$astypeID' ");
								while($result_astype = pg_fetch_array($qry_astype))
								{
									$astypeName = $result_astype["astypeName"]; 
								}
								
								//หาชื่อผู้ขอเพิ่ม
								$qry_adduser = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$add_user'");
								list($fullname) = pg_fetch_array($qry_adduser);
								
								
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
								
								if($productCode != ""){ $bgcolor1 = "#FFFFFF"; }else{ $productCode = $productCodesame;}
								if($secondaryID != ""){ $bgcolor2 = "#FFFFFF"; }else{ $secondaryID = $secondaryIDsame;}
								if($pricePerUnit != ""){ $bgcolor3 = "#FFFFFF"; }else{ 
											if($pricePerUnitsame != ""){
												$pricePerUnit = number_format($pricePerUnitsame,2);
											}
								}
								if($VAT_value != ""){ $bgcolor4 = "#FFFFFF"; }else{ 
											if($VAT_valuesame != ""){
												$VAT_value = number_format($VAT_valuesame,2);
											}
								}
						?>
						
								<tr bgcolor="#E8E8E8">
									<td width="" align="center" ><?php echo $i; ?></td>
									<td width="" align="center" ><?php echo $receiptNumber; ?></td>
									<td width=""><?php echo $brand; ?></td> <!-- ชื่อยี่ห้อ -->
									<td width=""><?php echo $astypeName; ?></td> <!-- ประเภท -->
									<td width=""><?php echo $model; ?></td> <!-- รุ่น -->
									<td width="" bgcolor="<?php echo $bgcolor1; ?>"><?php echo $productCode; ?></td>
									<td width="" bgcolor="<?php echo $bgcolor2; ?>"><?php echo $secondaryID; ?></td>	
									<td width="10%" bgcolor="<?php echo $bgcolor3; ?>" align="right"><?php echo $pricePerUnit; ?></td> <!-- ต้นทุน/ชิ้น -->
									<td width="10%" bgcolor="<?php echo $bgcolor4; ?>" align="right"><?php echo $VAT_value; ?></td><!-- ภาษี -->
									<td width="10%"><?php echo $ProductStatusName; ?></td><!-- สถานะ -->
									<td><?php echo $fullname ?></td>
									<td align="center"><?php echo $add_date ?></td>
									<td align="center"><input type="checkbox" name="chkapp[]" id="chkapp<?php echo $i ?>" value="<?php echo $serialID ?>"></td>
								</tr>
						<?php	
								unset($bgcolor1);
								unset($bgcolor2);
								unset($bgcolor3);
								unset($bgcolor4);
							}
						?>
						<tr>
							<td align="right" colspan="13"><input type="button" value="อนุมัติ" onclick="app(this.form,'app');"><input type="button" value="ไม่อนุมัติ" onclick="app(this.form,'notapp');"></td>
						</tr>
						<?php }else{ 
							echo "<tr><td align=\"center\" colspan=\"13\"><h1>-----  ไม่มีรายการรออนุมัติ -----</h1> </td></tr>";
						} ?>
					</table>
					<input type="hidden" name="chkchoise" id="chkchoise" value="<?php echo $i ?>" >
					<input type="hidden" name="state" id="state" >
					
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
function app(frm,app)
{
document.getElementById("state").value = app;
var con = $("#chkchoise").val();
var numchk;
numchk = 0;
	for(var num = 1;num<=con;num++){	
		if(document.getElementById("chkapp"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(app == 'app'){ var txt = 'ยืนยัน การอนุมัติ'; }else{ var txt = 'ยืนยัน ปฎิเสธการอนุมัติ';  }
		if(confirm(txt)==true){
			frm.action="process_app.php";
			frm.submit();
			document.myform.submit.disabled='true';
			return true;
		}else{ 
			return false;
		}
	}	
}
</script>

</body>
</html>