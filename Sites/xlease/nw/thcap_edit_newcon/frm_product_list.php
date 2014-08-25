<?php
include("../../config/config.php");
$contract = $_GET["contract"]; //เลขที่สัญญา
$readonly = $_GET["readonly"]; //ดูข้อมูลเท่านั้นหรือไม่

function chkTableAstype($astypeIDTemp)
{
	$qry_TableAstype = pg_query("select * from information_schema.tables where table_name = 'thcap_asset_biz_detail_$astypeIDTemp'");
	$row_TableAstype = pg_num_rows($qry_TableAstype);
	if($row_TableAstype > 0){return "t";}
	else{return "f";}
}

//หารายการสินค้าที่อยุ่กับสัญญานี้
$qry_asset = pg_query("SELECT c.\"assetID\", a.\"assetDetailID\",b.\"astypeID\", b.\"VAT_value\", c.\"receiptNumber\" , c.\"PurchaseOrder\", d.\"brand_name\", e.\"model_name\", b.\"productCode\", b.\"pricePerUnit\",a.\"assetAddress\"
								FROM 
									\"thcap_contract_asset\" a, \"thcap_asset_biz_detail\" b, \"thcap_asset_biz\" c, \"thcap_asset_biz_model\" e, \"thcap_asset_biz_brand\" d
								WHERE a.\"assetDetailID\" = b.\"assetDetailID\" and b.\"assetID\" = c.\"assetID\" and b.\"model\" = e.\"modelID\" and   b.\"brand\" = d.\"brandID\" and
									a.\"contractID\" = '$contract'
									order by c.\"receiptNumber\", c.\"PurchaseOrder\" ");	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่รายละเอียดสัญญา BH</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
			<link type="text/css" rel="stylesheet" href="act.css"></link>
				<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
					<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
						<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<center>
<fieldset style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:100%;">
	<legend><font color="blue"><h2>สินค้าที่ผูกกับสัญญา</h2></font></legend>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">	
		<?php if($readonly != 't'){ ?>
			<tr>
				<td align="right">
					<font color="green" size="3px;"><a onclick="javascript:popU('../loans_temp/frm_add_product.php?contract=<?php echo $contract ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=750')" title="เพิ่มสินค้าใหม่" style="cursor:pointer;">
						<img src="images/addproduct.png"   width="25" height="22" /><b><u>เพิ่มสินค้า</u></b>
					</a></font>	
				</td>			
			</tr>
		<?php } ?>	
			<tr>
				<td colspan="2" align="center">
					<table border="0" cellpadding="1" cellspacing="1" width="100%" >
						<tr bgcolor="#CDC8B1">
							<th>ลำดับ</th>
							<th>เลขที่ใบสั่งซื้อ</th>
							<th>เลขที่ใบเสร็จ</th>
							<th>รหัสสินค้า(Serial)</th>
							<th>ยี่ห้อ</th>
							<th>รุ่น</th>
							<th>ราคา</th>
							<th>ราคา vat</th>
							<th>ราคารวม</th>
							<th width="20%">สถานที่ติดตั้งเครื่อง</th>
							<th>รายละเอียด</th>
	<?php IF($readonly != 't'){	echo "<th>เอาออก</th>"; } ?>
						</tr>
			<?php
			$x = 1;
			$y = 0;

			$row_asset = pg_num_rows($qry_asset);
			if($row_asset > 0)
			{	
				$all_ppu = 0;
				$all_vat = "";
				$all_ppu_vat = "";
				while($re_asset = pg_fetch_array($qry_asset))
				{
					$assetID = $re_asset["assetID"];
					$receiptNumber = $re_asset["receiptNumber"]; // เลขที่ใบเสร็จ
					$brand = $re_asset["brand_name"];
					$model = $re_asset["model_name"];
					$pricePerUnit = $re_asset["pricePerUnit"];
					$productCode = $re_asset["productCode"];
					$PurchaseOrder = $re_asset["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
					$assetDetailID = $re_asset["assetDetailID"]; // รหัสสินค้าแต่ละตัว
					$VAT_value = $re_asset["VAT_value"]; //vat
					$ppu_vat = $pricePerUnit+$VAT_value;
					$astypeID = $re_asset["astypeID"]; //ประเภทสินค้า
					
					$assetAddress = $re_asset["assetAddress"];	//สถานที่ติดตั้งเครื่อง
					$address = "";
					if($assetAddress!="")
					{
						$qr_addr = pg_query("select * from \"thcap_contract_asset_address\" where \"asset_addressID\"='$assetAddress'");
						if($qr_addr)
						{
							$row_addr = pg_num_rows($qr_addr);
							if($row_addr!=0)
							{
								while($rs_addr = pg_fetch_array($qr_addr))
								{
									$asset_addressID = $rs_addr['asset_addressID'];
									$Room = $rs_addr['Room'];
									$Floor = $rs_addr['Floor'];
									$HomeNumber = $rs_addr['HomeNumber'];
									$Building = $rs_addr['Building'];
									$Moo = $rs_addr['Moo'];
									$Village = $rs_addr['Village'];
									$Soi = $rs_addr['Soi'];
									$Road = $rs_addr['Road'];
									$Tambon = $rs_addr['Tambon'];
									$District = $rs_addr['District'];
									$Province = $rs_addr['Province'];
									$Zipcode = $rs_addr['Zipcode'];
									
									$full_addr = "";
									
									if($HomeNumber!="" && $HomeNumber!="-" && $HomeNumber!="--" && $HomeNumber!=" " )
									{
										$address.="บ้านเลขที่ ".$HomeNumber;
									}							
									if($Moo!="" && $Moo!="-" && $Moo!="--" && $Moo!=" " )
									{							
										$address.="  หมู่ ".$Moo;
									}
									if($Building!="" && $Building!="-" && $Building!="--" && $Building!=" " )
									{
										$address.="  อาคาร".$Building;
									}
									if($Floor!="" && $Floor!="-" && $Floor!="--" && $Floor!=" " )
									{
										$address.="  ชั้น ".$Floor;
									}
									if($Room!="" && $Room!="-" && $Room!="--" && $Room!=" " )
									{
										$address.="  ห้อง ".$Room;
									}							
									if($Village!="" && $Village!="-" && $Village!="--" && $Village!=" " )
									{
										$address.="  หมู่บ้าน".$Village;
									}
									if($Soi!="" && $Soi!="-" && $Soi!="--" && $Soi!=" " )
									{
										$address.="  ซอย".$Soi;
									}
									if($Road!="" && $Road!="-" && $Road!="--" && $Road!=" " )
									{
										$address.="  ถนน".$Road;
									}
									if($Province != "" && $Province!="-" && $Province!="--" && $Province!=" " )
									{
										$qr_province = pg_query("select \"proName\" from \"nw_province\" where \"proID\"='$Province'");
										if($qr_province)
										{
											$rs_province = pg_fetch_array($qr_province);
											$txtpro = $rs_province['proName'];
										}
										if($txtpro == ""){
											list($txtpro,$zip) = explode(" ",$Province);
										}		
									}
									if($txtpro == 'กรุงเทพ' OR $txtpro == 'กรุงเทพฯ' OR $txtpro == 'กรุงเทพมหานคร' OR $txtpro == 'กทม' OR $txtpro == 'กทม.'){
										if($Tambon!="" && $Tambon!="-" && $Tambon!="--" && $Tambon!=" "){ $address.="  แขวง".$Tambon;}
										if($District!="" && $District!="-" && $District!="--" && $District!=" "){ $address.="  เขต".$District; }
										$address.= "  ".$txtpro;
									}else{
										if($Tambon!="" && $Tambon!="-" && $Tambon!="--" && $Tambon!=" "){ $address.="  ตำบล".$Tambon;}
										if($District!="" && $District!="-" && $District!="--" && $District!=" "){ $address.="  อำเภอ".$District; }
										$address.="  จังหวัด".$txtpro;
									}			
									
									if($Zipcode!="")
									{
										$address.=" ".$Zipcode;
									}else{
										$address.=" ".$zip;	
									}
									if($address=="")
									{
										$address = "<ไม่มีข้อมูล>";
									}
								}
							}
							else
							{
								
							}
						}
					}
					else
					{
						$address = "<ไม่มีข้อมูล>";
					}
					
					$all_ppu = $all_ppu+$pricePerUnit;
					if($VAT_value!="")
					{
						$all_vat = $all_vat+$VAT_value;
						$all_ppu_vat = $all_ppu_vat+$ppu_vat;
					}
					
					$receitppopup = "<a style=\"cursor:pointer;\" onclick=\"javascript:popU('view_product.php?bill_id=$assetID&status=1&notconfig=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=550')\"><u>$receiptNumber</u></a>";						
					$PurchaseOrderpopup = "<a style=\"cursor:pointer;\" onclick=\"javascript:popU('view_product.php?bill_id=$assetID&status=1&notconfig=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=550')\"><u>$PurchaseOrder</u></a>";						
					
					if($VAT_value==""){ $VAT_value = "ไม่มีข้อมูล"; }else{ $VAT_value = number_format($VAT_value,2,'.',','); }
					
					if($pricePerUnit != ""){ $pricePerUnit = number_format($pricePerUnit,2); }
					
					if($y%2==0){
							echo "<tr bgcolor=#EEE8CD onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE8CD';\" align=center>";
					}else{
							echo "<tr bgcolor=#FFF8DC onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF8DC';\" align=center>";
					} 
					echo "
						<td align=\"center\">$x</td>
						<td align=\"center\">$PurchaseOrderpopup</td>
						<td align=\"center\">$receitppopup</td>
						<td align=\"center\">$productCode</td>
						<td align=\"center\">$brand</td>
						<td align=\"center\">$model</td>
						<td align=\"right\">$pricePerUnit</td>
						<td align=\"right\">$VAT_value</td>
						<td align=\"right\">";
					if($VAT_value=="ไม่มีข้อมูล")
					{
						echo "ไม่มีข้อมูล";
					}
					else
					{
						echo number_format($ppu_vat,2,'.',',');
					}
					
					IF($readonly == 't')
					{
						if(chkTableAstype($astypeID) == "t")
						{
							//$linklist = "<a onclick=\"javascript:popU('../thcap/thcap_edit_asset/edit_asset_for_sales_$astypeID.php?assetDetailID=$assetDetailID&readonly=t&realdata=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=350')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a>";
							$linklist = "<a onclick=\"javascript:popU('../thcap/thcap_add_asset/add_asset_for_sales_$astypeID.php?assetdetailID=$assetDetailID&readonly=t&realdata=t&method=edit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=350')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a>";
						}
						else
						{
							$linklist = "-";
						}
					}
					else
					{
						if(chkTableAstype($astypeID) == "t")
						{
							//$linklist = "<a onclick=\"javascript:popU('../thcap/thcap_edit_asset/edit_asset_for_sales_$astypeID.php?assetDetailID=$assetDetailID&appvauto=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=350')\"><img src=\"images/edit_pa1.png\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a>";
							$linklist = "<a onclick=\"javascript:popU('../thcap/thcap_add_asset/add_asset_for_sales_$astypeID.php?assetdetailID=$assetDetailID&appvauto=t&method=edit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=350')\"><img src=\"images/edit_pa1.png\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a>";
						}
						else
						{
							$linklist = "-";
						}
					}
					
					echo "</td>
						<td>$address</td>
						<td align=\"center\">
							$linklist
						</td>";
					IF($readonly != 't'){	
						echo "
							<td align=\"center\">
								<img src=\"images/x.png\" onclick=\"removeitem('$assetID','$assetDetailID')\" style=\"cursor:pointer;\" width=\"19\" height=\"19\" >
							</td>
							
								";	
					}				
						
					echo "</tr>";
					
					$x++;
					$y++;
				}
				if($row_asset!=0)
				{
					if($all_vat==""){$all_vat="ไม่มีข้อมูล"; }else{ $all_vat = number_format($all_vat,2,'.',','); }
					echo "
						<tr bgcolor=\"#CDC9A5\">
							<td colspan=\"6\" align=\"right\"><b>ยอดรวม</b></td>
							<td align=\"right\">".number_format($all_ppu,2,'.',',')."</td>
							<td align=\"right\">".$all_vat."</td>
							<td align=\"right\">";
					if($all_ppu_vat=="")
					{
						echo "ไม่มีข้อมูล";
					}
					else
					{
						echo	number_format($all_ppu_vat,2,'.',',');
					}
					echo "</td><td colspan=\"3\"></td>";
					echo "</tr>";
				}
			}
			else
			{
				echo "
					<tr bgcolor=\"#EEE8CD\">
						<td colspan=\"12\" align=\"center\"><h3> ************************** ไม่มีสินค้าที่เกี่ยวข้อง ************************** <h3></td>
					</tr>
				";
			}

		?>
	</table>
</fieldset>	
</body>
</html>
