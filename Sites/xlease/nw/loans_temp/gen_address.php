<?php
include("../../config/config.php");

$cusid = $_POST['cusid'];

$qr = pg_query("select * from \"thcap_contract_asset_address\" where \"customerID\"='$cusid'");
if($qr)
{
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		while($rs = pg_fetch_array($qr))
		{
			$asset_addressID = $rs['asset_addressID'];
			$Room = $rs['Room'];
			$Floor = $rs['Floor'];
			$HomeNumber = $rs['HomeNumber'];
			$Building = $rs['Building'];
			$Moo = $rs['Moo'];
			$Village = $rs['Village'];
			$Soi = $rs['Soi'];
			$Road = $rs['Road'];
			$Tambon = $rs['Tambon'];
			$District = $rs['District'];
			$Province = $rs['Province'];
			$Zipcode = $rs['Zipcode'];
			
			$full_addr = "";
			
			if($HomeNumber!="" && $HomeNumber!="-" && $HomeNumber!="--" && $HomeNumber!=" " )
			{
				$full_addr.="บ้านเลขที่ ".$HomeNumber;
			}							
			if($Moo!="" && $Moo!="-" && $Moo!="--" && $Moo!=" " )
			{							
				$full_addr.="  หมู่ ".$Moo;
			}
			if($Building!="" && $Building!="-" && $Building!="--" && $Building!=" " )
			{
				$full_addr.="  อาคาร".$Building;
			}
			if($Floor!="" && $Floor!="-" && $Floor!="--" && $Floor!=" " )
			{
				$full_addr.="  ชั้น ".$Floor;
			}
			if($Room!="" && $Room!="-" && $Room!="--" && $Room!=" " )
			{
				$full_addr.="  ห้อง ".$Room;
			}							
			if($Village!="" && $Village!="-" && $Village!="--" && $Village!=" " )
			{
				$full_addr.="  หมู่บ้าน".$Village;
			}
			if($Soi!="" && $Soi!="-" && $Soi!="--" && $Soi!=" " )
			{
				$full_addr.="  ซอย".$Soi;
			}
			if($Road!="" && $Road!="-" && $Road!="--" && $Road!=" " )
			{
				$full_addr.="  ถนน".$Road;
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
				if($Tambon!="" && $Tambon!="-" && $Tambon!="--" && $Tambon!=" "){ $full_addr.="  แขวง".$Tambon;}
				if($District!="" && $District!="-" && $District!="--" && $District!=" "){ $full_addr.="  เขต".$District; }
				$full_addr.= "  ".$txtpro;
			}else{
				if($Tambon!="" && $Tambon!="-" && $Tambon!="--" && $Tambon!=" "){ $full_addr.="  ตำบล".$Tambon;}
				if($District!="" && $District!="-" && $District!="--" && $District!=" "){ $full_addr.="  อำเภอ".$District; }
				$full_addr.="  จังหวัด".$txtpro;
			}			
			
			if($Zipcode!="")
			{
				$full_addr.=" ".$Zipcode;
			}else{
				$full_addr.=" ".$zip;	
			}
			if($full_addr!="")
			{
				echo "
					<div class=\"list_pick_addr\">
						<span class=\"span_list_addr\">$full_addr</span>
						<input type=\"hidden\" name=\"h_addr_id[]\" value=\"0,$asset_addressID\" />
						<div class=\"div_pick_box\">
							<input type=\"button\" name=\"btn_pick_list_addr[]\" onclick=\"pick_this_addr('$asset_addressID','$full_addr');\" value=\"เลือก\" />
							<input type=\"button\" name=\"btn_delete_addr[]\" onclick=\"delete_this_addr('$asset_addressID','$full_addr')\" value=\"ลบ\" />
						</div>
					</div>
				";
			}
		}
	}
	else
	{
		echo "
			<div class=\"list_pick_addr\">
				<span class=\"span_list_addr\">ไม่มีข้อมูล</span>
				<input type=\"hidden\" name=\"h_addr_id[]\" value=\"\" />
				<div class=\"div_pick_box\"></div>
			</div>
		";
	}
}
?>