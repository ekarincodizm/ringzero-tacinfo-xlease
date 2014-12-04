<?php
	function get_text_assettype_from_assettypeID($assetTypeID)
	{
		$Sql = 	"
					SELECT  
							\"astypeName\"
					FROM 
						thcap_asset_biz_astype
					where 
						\"astypeID\" = ".$assetTypeID."	
				";
				
		$Result = pg_query($Sql);
		$Data = pg_fetch_array($Result);
		return($Data['astypeName']);			
	}
?>