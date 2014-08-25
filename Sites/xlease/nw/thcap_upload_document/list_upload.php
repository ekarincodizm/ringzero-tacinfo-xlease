<?php
require_once("../../config/config.php");
$tab_id = pg_escape_string($_GET['tabid']); //id contype ที่ต้องการให้แสดง
$Strsort=pg_escape_string($_GET['sort']);
if($Strsort==""){$Strsort="conDate";}
 $Strorder=pg_escape_string($_GET['order']);
if($Strorder==""){$Strorder="DESC";}

if($Strorder=="DESC"){
	$NewStrorder="ASC";
} else {
	$NewStrorder="DESC";
}

	if($tab_id=='ALL'){
		// หาเลขที่สัญญาที่ยังไม่ได้ upload เอกสารตามประเภทสัญญา
		$qry_con = pg_query(
							"select distinct a.* from thcap_contract as a inner join thcap_contract_doc_config as b on a.\"conType\" = b.\"doc_conTypeName\" 
							where (\"doc_statusDoc\"='1') and
							(a.\"contractID\" not in (select \"contractID\" from thcap_upload_document)   
							or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  (b.\"doc_docName\" not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = a.\"contractID\" and \"Approved\" in ('1','2') )) )) 
							order by a.\"$Strsort\" $NewStrorder "); 
		
	} else {
		// หาเลขที่สัญญาที่ยังไม่ได้ upload เอกสารทุกประเภทสัญญา
		$qry_con = pg_query(
							"select distinct a.* from thcap_contract as a inner join thcap_contract_doc_config as b on a.\"conType\" = b.\"doc_conTypeName\" 
							where (\"doc_statusDoc\"='1') and a.\"conType\"='$tab_id' and
							(a.\"contractID\" not in (select \"contractID\" from thcap_upload_document)  
							or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  (b.\"doc_docName\" not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = a.\"contractID\" and \"Approved\" in ('1','2') )) )) 
							order by a.\"$Strsort\" $NewStrorder "); 
		
	}
		$num_row_con = pg_num_rows($qry_con);
?>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>ลำดับที่</td>
				<td><a href="frm_Index.php?sort=contractID&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>เลขที่สัญญา</u></font></td>
				<td><a href="frm_Index.php?sort=conType&order=<?php echo $NewStrorder;?>&tab_id=<?php echo $tab_id;?>"><font color="black"><u>ประเภทสัญญา</u><font></td>
				<td>ทำรายการ</td>
			</tr>
			<?php
			$nubrow=0;
			while($res_con = pg_fetch_array($qry_con)){
			$nubrow++;
				$contractID = $res_con["contractID"];
				$conTypeName = $res_con["conType"];
				
				if($nubrow%2==0){
				echo "<tr class=\"odd\" align=center  >" ;
				} else {
				echo "<tr class=\"even\" align=center >";
				}
					echo "<td>$nubrow</td>";
					echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
					echo "<td>$conTypeName</td>";
					echo "<td><input type=\"button\" value=\"Upload\" onclick=\"javascript:popU('frm_insert.php?conid=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=480');\"></td>";
				echo "</tr>";
			} //end while
			if($nubrow == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			<tr bgcolor="#6699FF">
				<td colspan="9" align="left"><b>รายการทั้งหมด <?php echo $nubrow;?> รายการ<b></td>
			</tr>
		</table>