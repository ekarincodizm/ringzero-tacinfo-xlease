<?php
	// สำหรับดังรายการที่รออนุมัติ สำหรับสินทรัพย์แต่ละประเภท
	function Get_motorcycle_Wait_Approve_Type_ID_10()
	{   // หาจำนวนรายการสินทรัพย์ที่รออนุมัติสำหรับเข่า-ขาย สำหรับประเภท รถจักรยานยนต์ (10)
		$Sql = " SELECT 
						a.\"assetDetailID\"
				 FROM 
						thcap_asset_biz_detail a 
						LEFT JOIN (	select 
											* 
									from 
											\"thcap_asset_biz_detail_central\" d1
											left join \"thcap_asset_biz_detail_10_temp\" d2
												on 
													d1.\"ascenID\" = d2.\"ascenID\" 
								   ) d 
							ON 
								a.\"assetDetailID\" = d.\"assetDetailID\"
		  		 WHERE 
		  		 		d.\"statusapp\" = '0' AND a.\"astypeID\" = 10 
		  		"; 	
		$Result = pg_query($Sql);
		$Num_Row = pg_num_rows($Result);
		return($Num_Row); // คืนจำนวนรายการสินทรัพย์ี่รออนุมัติ ประเภทรถจักนขานขนต์ (10)			
	} 
	function Get_Car_Wait_Approve($Type_ID)
	{	// หาจำนวนรายการสินทรัพย์ที่รออนุมัติสำหรับเข่า-ขาย สำหรับประเภท รถยนต์ (13,37,50,51,66,69,82,84,85,86,89,90,91,93,94,95,96,97)  
		$Sql = 	"
					SELECT 
							COUNT(*)
					FROM 
							\"thcap_asset_biz_detail_car_temp\",
							\"thcap_asset_biz_detail_central\"
							 
					WHERE 
							(thcap_asset_biz_detail_central.\"ascenID\" = thcap_asset_biz_detail_car_temp.\"ascenID\") AND
							(\"thcap_asset_biz_detail_central\".\"statusapp\" = 0) AND
							car_type = $Type_ID
				";
		$Result = pg_query($Sql);
		$Data = pg_fetch_array($Result);
		$Num_Row = $Data[0];
		return($Num_Row);	
	} 
	function Load_Num_Wait_For_Approve($TypeID="")
	{ // ดึงจำนวนสินทรัพย์ที่รออนุมัติจากทรัพย์สินแต่ละประเภท
		switch($TypeID){
			case 10 :	$Num_Wait =	Get_motorcycle_Wait_Approve_Type_ID_10($TypeID);
						break;
			case 13 :
			case 37 :
			case 50 :
			case 51 :
			case 66 :
			case 69 :
			case 82 :
			case 84 :	
			case 85 :
			case 86 :
			case 89 :
			case 90 :
			case 91 :
			case 93 :
			case 94 :
			case 95 :
			case 96 :
			case 97 :															  
						$Num_Wait = Get_Car_Wait_Approve($TypeID); 
						break;
			default : 	$Num_Wait = 0;
			
		} 
		
		return($Num_Wait);
	}
?>