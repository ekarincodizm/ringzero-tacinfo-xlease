<?php
session_start();
include("../../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$id_user=$_SESSION["av_iduser"];
$appvmenu = $_GET['appvmenu'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตั้งค่าเอกสารสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<?php if($appvmenu=="true") { ?>
<script language="JavaScript">
window.onbeforeunload = WindowCloseHanlder;
function WindowCloseHanlder()
{    
     opener.location.reload(true);
}
</script>
<?php } ?>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
</head>
<body>
	<div class="header" align="center">
		<h1>(THCAP) ตั้งค่าเอกสารสัญญา</h1>
	</div>
	<?php if($appvmenu=="true") { ?>
	<div class="header" align="left">
		<input type="button" name="close" value="      ปิด      " onclick="refres();" />
	</div>
	<?php } ?>
	<?php
		// หาประเภทของสัญญา
		$qry_contype = pg_query("select \"conType\" from thcap_contract_type "); 
		$num_row_contype = pg_num_rows($qry_contype);
		
			while($res_name = pg_fetch_array($qry_contype)){
				$conTypeName = $res_name[conType];
				
				echo "<div class=\"wrapper\" style=\"margin-top:20px;\">";
					echo "<table width=\"50%\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" align=\"center\" bgcolor=\"#F0F0F0\">";
						echo "<tr style=\"font-weight:bold;\" valign=\"middle\" align=\"center\" bgcolor=\"#F2F5A9\">";
								echo "<td colspan=\"4\">";
									echo "<h2>สัญญาประเภท : $conTypeName</h2>";
								echo "</td>";
								echo "<td>";
									echo "<input type=\"button\" value=\"เพิ่ม\" onclick=\"javascript:popU('frm_insert.php?conType=$conTypeName','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=380');\">";
								echo "</td>";
						echo "</tr>";
						echo "<tr style=\"font-weight:bold;\" valign=\"middle\" bgcolor=\"#79BCFF\" align=\"center\">";
							echo "<td>ลำดับที่</td>";
							echo "<td>ชื่อเอกสาร</td>";
							echo "<td>สถานะการใช้งาน</td>";
							echo "<td>อันดับเอกสาร</td>";
							echo "<td>ทำรายการ</td>";
						echo "</tr>";
					
				
				// Retriev เอกสารที่ใช้แต่ละประเภทสัญญา
				$qry_doc = pg_query("select * from thcap_contract_doc_config where \"doc_conTypeName\" = '$conTypeName' and \"doc_ConfigID\" not in (select \"doc_ConfigID\" from thcap_contract_doc_config_temp where doc_status_appv='2') order by \"doc_Ranking\" ASC "); 
				$num_row = pg_num_rows($qry_doc);
				$nub = 0;
				while($res_doc = pg_fetch_array($qry_doc)){
				$nub++;
					$doc_ConfigID = $res_doc["doc_ConfigID"];
					$doc_docName = $res_doc["doc_docName"];
					$doc_statusDoc = $res_doc["doc_statusDoc"];
					$doc_Ranking = $res_doc['doc_Ranking'];
					// ตรวจสอบสถานะการใช้เอกสาร
					if($doc_statusDoc==0){
						$text_status = "ไม่ใช้งาน";
						$colorF="#FF000";
					} else if($doc_statusDoc==1) {
						$text_status = "ใช้งาน-ใช้งานเสมอ";
						$colorF="#00FF00";
					} else {
						$text_status = "ใช้งาน-ไม่จำเป็นต้องใช้เสมอ";
						$colorF="#FFA500";
					}
					// สลับสีแถว
					if($nub%2==0){
						echo "<tr class=\"odd\" align=center>";
					} else {
						echo "<tr class=\"even\" align=center>";
					}
							echo "<td width=\"5%\">$nub</td>";
							echo "<td width=\"10%\">$doc_docName</td>";
							echo "<td width=\"10%\"><font color=\"$colorF\">$text_status</font></td>";
							echo "<td width=\"10%\">$doc_Ranking</td>";
							echo "<td width=\"5%\"><input type=\"button\" value=\"แก้ไข\" onclick=\"javascript:popU('frm_edit.php?conType=$conTypeName&docName=$doc_docName&docStatus=$doc_statusDoc&configID=$doc_ConfigID&doc_Ranking=$doc_Ranking','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=380,height=380');\"></td>";
						echo "</tr>";
				} // end while
						
					$qry_appv = pg_query("select * from thcap_contract_doc_config_temp where \"doc_conTypeName\" = '$conTypeName' and doc_status_appv = '2' ");
				$nub_appv = pg_num_rows($qry_appv);
				
				while($res_appv = pg_fetch_array($qry_appv)){
					$nub++;
					$appv_doc_ConfigID = $res_appv["doc_ConfigID"];
					$appv_doc_docName = $res_appv["doc_docName"];
					$appv_doc_statusDoc = $res_appv["doc_statusDoc"];
					$appv_doc_status_appv = $res_appv["doc_status_appv"];
					$appv_doc_count_edit = $res_appv["doc_count_edit"];
					$doc_Ranking = $res_appv['doc_Ranking'];
					// ตรวจสอบสถานะการใช้เอกสาร
					if($appv_doc_statusDoc==0){
						$text_status = "ไม่ใช้งาน";
						$colorF="#FF000";
					} else if($appv_doc_statusDoc==1){
						$text_status = "ใช้งาน-ใช้งานเสมอ";
						$colorF="#00FF00";
					} else {
						$text_status = "ใช้งาน-ไม่จำเป็นต้องใช้เสมอ";
						$colorF="#FFA500";
					}
					
					//สถานะรออนุมัติ 
					if($appv_doc_status_appv==2){ 
						if($appv_doc_count_edit>0){
							$textAppv = "รออนุมัติแก้ไข";
						}else {
							$textAppv = "รออนุมัติเพิ่มใหม่";
						}
						
					}
					
					// สลับสีแถว
					if($nub%2==0){
						echo "<tr class=\"odd\" align=center>";
					} else {
						echo "<tr class=\"even\" align=center>";
					}
							echo "<td width=\"5%\">$nub</td>";
							echo "<td width=\"10%\">$appv_doc_docName</td>";
							echo "<td width=\"10%\"><font color=\"$colorF\">$text_status</font></td>";
							echo "<td width=\"10%\">$doc_Ranking</td>";
							echo "<td width=\"5%\">$textAppv</td>";
						echo "</tr>";
				} //end whil query รายการรออนุมัติ
				
				if($num_row == 0 && $nub_appv==0){
				echo "<tr><td colspan=5 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
						echo "<tr><td colspan=5 bgcolor=\"#79BCFF\"><b> ทั้งหมด $nub รายการ</b></td></tr>";
						
					echo "</table>";
				echo "</div>";
				
			} // end while 
	?>
</body>
</html>