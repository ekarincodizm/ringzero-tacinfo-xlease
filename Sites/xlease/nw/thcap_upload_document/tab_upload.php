<?php
require_once("../../config/config.php");
?>
<fieldset>
<legend><b>รายการที่ยัง upload เอกสารไม่ครบ</b></legend>
<?php
	$qry_con=pg_query("select distinct a.* from thcap_contract as a inner join thcap_contract_doc_config as b on a.\"conType\" = b.\"doc_conTypeName\" 
							where (\"doc_statusDoc\"='1') and
							(a.\"contractID\" not in (select \"contractID\" from thcap_upload_document) 
							or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  b.\"doc_docName\"  not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = a.\"contractID\"))  
							or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  (b.\"doc_docName\" not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = a.\"contractID\" and \"Approved\" in ('1','2') )) )) 
							order by a.\"contractID\" 
			");
	$row2 = pg_num_rows($qry_con);
	echo " <div style='overflow-x:hidden;overflow-y:hidden;width:250px;>";
	echo "
		<div class=\"tab_menu_contrainer\">
			<div class=\"menu_box\">
				<div class=\"tab_box\">
					<div class=\"slide_tab\">
						<div class=\"tab\"><a href=\"javascript:list_tab_menu('ALL');\"></a></div>";
						echo"<div class=\"tab\"><a id=\"ALL\" href=\"javascript:list_tab_menu('ALL');\">ทั้งหมด<font color=red>($row2)</font></a></div>";
						$qr = pg_query("select \"conType\" as \"conType\" from thcap_contract_type ");
						if($qr)
						{
							$row = pg_num_rows($qr);
							if($row!=0)
							{
								while($rs=pg_fetch_array($qr))
								{
									$tabID = $rs['conType'];
									$tab_name = $rs['conType'];
									
									
									$qrnum=pg_query("select distinct a.* from thcap_contract as a inner join thcap_contract_doc_config as b on a.\"conType\" = b.\"doc_conTypeName\" 
													where (\"doc_statusDoc\"='1') and a.\"conType\"='$tabID' and
													(a.\"contractID\" not in (select \"contractID\" from thcap_upload_document) 
													or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  b.\"doc_docName\"  not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = a.\"contractID\"))  
													or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  (b.\"doc_docName\" not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = a.\"contractID\" and \"Approved\" in ('1','2') )) )) 
													order by a.\"contractID\"");	
									$row2 = pg_num_rows($qrnum);
	
									echo "
										<div class=\"tab\"><a id=\"$tabID\" href=\"javascript:list_tab_menu('$tabID');\">$tab_name <font color=red>($row2)</font></a></div>
									";
								}
							}
						}
				echo "
					</div>
				</div>
			</div>
		</div></div>
		<div class=\"list_tab_menu\"></div>
	";
?>
</fieldset>