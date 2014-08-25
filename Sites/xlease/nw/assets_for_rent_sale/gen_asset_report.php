<?php
include("../../config/config.php");

$type = pg_escape_string($_POST['type']);
$order = pg_escape_string($_POST['order']);

function chk_null($data,$type="text")
{
	$re_data = "";
	if($data=="")
	{
		$re_data = "<ไม่มีข้อมูล>";
	}
	else
	{
		if($type=="money")
		{
			$re_data = number_format($data,2,".",",");
		}
		else
		{
			$re_data = $data;
		}
	}
	
	return $re_data;
}

function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$type,$order){   
	global $e_page;
	global $querystr;
	$urlfile="report_asset.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
	$per_page=10;
	$num_per_page=floor($chk_page/$per_page);
	$total_end_p=($num_per_page+1)*$per_page;
	$total_start_p=$total_end_p-$per_page;
	$pPrev=$chk_page-1;
	$pPrev=($pPrev>=0)?$pPrev:0;
	$pNext=$chk_page+1;
	$pNext=($pNext>=$total_p)?$total_p-1:$pNext;		
	$lt_page=$total_p-4;
	if($chk_page>0){  
		echo "<a  href=\"$urlfile?s_page=$pPrev&type=$type&order=$order\" class=\"naviPN\">ก่อนหน้า</a>";
	}
	for($i=$total_start_p;$i<$total_end_p;$i++){  
		$nClass=($chk_page==$i)?"class=\"selectPage\"":"";
		if($e_page*$i<$total){
		echo "<a href=\"$urlfile?s_page=$i&type=$type&order=$order\" $nClass  >".intval($i+1)."</a> ";   
		}
	}		
	if($chk_page<$total_p-1){
		echo "<a href=\"$urlfile?s_page=$pNext&type=$type&order=$order\"  class='naviPN'>ถัดไป</a>";
	}
}

if($type=="all")
{
	$q = "	select a.*,b.\"pathFileReceipt\",b.\"pathFilePO\"
			from \"vthcap_asset_biz_detail\" a
			left join \"thcap_asset_biz\" b ON a.\"assetID\" = b.\"assetID\"
			order by a.\"".$order."\"";
}
else if($type=="active")
{
	$q = "	select a.*,b.\"pathFileReceipt\",b.\"pathFilePO\"
			from \"vthcap_asset_biz_detail\" a
			left join \"thcap_asset_biz\" b ON a.\"assetID\" = b.\"assetID\"
			where a.\"RealmaterialisticStatus\"='1' 
			order by a.\"".$order."\"";
}
else
{
	$q = "	select a.*,b.\"pathFileReceipt\",b.\"pathFilePO\" 
			from \"vthcap_asset_biz_detail\" a
			left join \"thcap_asset_biz\" b ON a.\"assetID\" = b.\"assetID\"
			where a.\"RealmaterialisticStatus\"<>'1' 
			order by a.\"".$order."\"";
}
$qr = pg_query($q);
$total=pg_num_rows($qr);
$resultRows=pg_num_rows($qr);
$e_page=30; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
if(!isset($_POST['s_page'])){   
	$_POST['s_page']=0;   
}else{   
	$chk_page=$_POST['s_page'];     
	$_POST['s_page']=$_POST['s_page']*$e_page;   
}   
$q.=" LIMIT $e_page offset ".$_POST['s_page'];
$qr=pg_query($q);
if(pg_num_rows($qr)>=1){   
	$plus_p=($chk_page*$e_page)+pg_num_rows($qr);   
}else{   
	$plus_p=($chk_page*$e_page);       
}   
$total_p=ceil($total/$e_page);   
$before_p=($chk_page*$e_page)+1;
if($qr)
{
?>
<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<thead>
        <tr bgcolor="#00baff">
            <th width="5%">ลำดับ</th>
            <th width="10%">ประเภทสินทรัพย์</th>
            <th width="5%">ชื่อยี่ห้อ</th>
            <th width="10%">ชื่อรุ่น</th>
            <th width="7%">รหัสสินค้า <span style="font-size:10px;">(Serial Number)</span></th>
            <th width="7%">รหัสสินค้ารอง <span style="font-size:10px;">(Secondary Serial Number)</span></th>
            <th width="5%">ต้นทุน/ชิ้น</th>
            <th width="10%">ภาษีมูลค่าเพิ่ม</th>
            <th width="10%">สถานะสินค้า</th>
            <th width="10%">การมีอยู่ของสินค้า</th>
            <th width="7%">คำอธิบาย</th>
			<th width="7%">ใบสั่งซื้อที่แนบ</th>
			<th width="7%">ใบเสร็จที่แนบ</th>
			<th width="7%">ยกเลิกใบเสร็จ</th>
        </tr>
    </thead>
    <tbody>
    <?php
	$curpage = $_POST['s_page'];
	if($curpage=="")
	{
		$curpage = 0;
	}
	$number = $curpage+1;
	$i = 0;
	while($rs = pg_fetch_array($qr))
	{
		$assetID = $rs['assetID'];//รหัสสินทรัพย์
		$assetDetailID = $rs['assetDetailID']; //รหัสรายละเอียดสินทรัพย์
		$brand = chk_null($rs['brand']);
		$astypeName = chk_null($rs['astypeName']);	//ประเภทสินทรัพย์
		$model = chk_null($rs['model']);
		$explanation = $rs['explanation'];	//คำอธิบาย
		$pricePerUnit = chk_null($rs['pricePerUnit'],"money");	//ราคาต่อหน่วย
		$productCode = chk_null($rs['productCode']);	//รหัสสินค้าหลัก
		$receiptNumber = chk_null($rs['receiptNumber']);	//เลขที่ใบเสร็จ
		$PurchaseOrder = chk_null($rs['PurchaseOrder']);	//เลขที่ใบสั่งซื้อ
		$secondaryID = chk_null($rs['secondaryID']);	//รหัสสินค้ารอง	
		$VAT_value = chk_null($rs['VAT_value'],"money");	//vat
		$ProductStatusName = chk_null($rs['ProductStatusName']);	//สถานะสินค้า
		$materialisticstatus = chk_null($rs['materialisticstatus']);	//สถานะการใช้งาน
		$RealmaterialisticStatus = chk_null($rs['RealmaterialisticStatus']);	//สถานะการใช้งาน
		$activestatus = chk_null($rs['activestatus']);
		$pathFileReceipt = $rs['pathFileReceipt']; //ไฟล์แนบ ใบเสร็จ
		$pathFilePO = $rs['pathFilePO']; //ไฟล์แนบ 
		$contractID = $rs['contractID']; //เลขที่สัญญาที่ผูกกับสินค้า
		
		//ใบเสร็จ
		if($pathFileReceipt != ""){
			$pathFileReceiptshow = "<a style=\"cursor:pointer;\" onclick=\"popU('../upload/asset_bill/$pathFileReceipt','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"  title=\"$pathFileReceipt\"><font color=\"blue\"><u> แสดงใบเสร็จ </u></font></a>";
		}else{
			$pathFileReceiptshow = "<ไม่มีข้อมูล>";
		}
		//ใบสั่งซื้อ
		if($pathFilePO != ""){
			$pathFilePOshow =  "<a style=\"cursor:pointer;\" onclick=\"popU('../upload/asset_bill/$pathFilePO','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"  title=\"$pathFilePO\"><font color=\"blue\"><u> แสดงใบสั่งซื้อ </u></font></a>";	
		}else{
			$pathFilePOshow = "<ไม่มีข้อมูล>";
		}
		//คำอธิบาย
		if($explanation != ""){
			$explanationshow = "<img style=\"cursor:pointer;\" src=\"../thcap/images/detail.gif\" Title=\"คลิกเพื่อดูคำอธิบาย\" onclick=\"popU('show_detail.php?assetDetailID=$assetDetailID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=350')\" />";
		}else{
			$explanationshow = '<ไม่มีข้อมูล>';
		}
		
		//strip table
		if($type=="all")
		{
			if($RealmaterialisticStatus!='1')
			{
				if($contractID!=""){
					$materialContract = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\"><font color=\"#0000FF\"><u>$contractID</u></font>";
				}else{
					$materialContract = "<ไม่พบเลขที่สัญญา>";
				}
				echo "<tr bgcolor=\"#f5f5f5\">";
			}
			else
			{
				$materialContract = $materialisticstatus;
				if($i%2==0)
				{
					echo "<tr bgcolor=\"#b2e3ff\">";
				}
				else
				{
					echo "<tr bgcolor=\"#94d8ff\">";
				}
			}
		}
		else
		{
			
			if($RealmaterialisticStatus!='1')
			{
				if($contractID!=""){
					$materialContract = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\"><font color=\"#0000FF\"><u>$contractID</u></font>";
				}else{
					$materialContract = "<ไม่พบเลขที่สัญญา>";
				}
				echo "<tr bgcolor=\"#f5f5f5\">";
			}
			else
			{
				$materialContract = $materialisticstatus;
				if($i%2==0)
				{
					echo "<tr bgcolor=\"#b2e3ff\">";
				}
				else
				{
					echo "<tr bgcolor=\"#94d8ff\">";
				}
			}
		}
		
		echo "
			<td align=\"center\">$number</td>
			<td align=\"left\">$astypeName</td>
			<td align=\"left\">$brand</td>
			<td align=\"left\">$model</td>
			<td align=\"left\">$productCode</td>
			<td align=\"left\">$secondaryID</td>
			<td align=\"right\">$pricePerUnit</td>
			<td align=\"right\">$VAT_value</td>
			<td align=\"center\">$ProductStatusName</td>
			<td align=\"center\">$materialContract</td>
			<td align=\"center\">$explanationshow</td>
			<td align=\"center\">$pathFilePOshow</td>
			<td align=\"center\">$pathFileReceiptshow</td>
			<td align=\"center\"><img style=\"cursor:pointer;\" src=\"../thcap/images/del.png\" Title=\"ยกเลิกใบเสร็จ\" onclick=\"popU('../assets_cancel/frm_Index.php?bill_id=$assetID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1280,height=800')\" /></td>
			
		</tr>
		";
		$number++;
		$i++;
	}
	?>
    </tbody>
</table>
<?php
	if($total>0)
	{
		echo "
			<div style=\"padding:15px 10px; margin-bottom:15px;\">
				<div class=\"browse_page\">";
					page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$type,$order);
		echo "	</div>
			</div>
		";
	}
}
?>